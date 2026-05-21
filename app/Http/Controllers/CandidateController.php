<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Election;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Position;

class CandidateController extends Controller
{
    // =========================================================================
    // HELPERS
    // =========================================================================

    /**
     * Resolve the current "working" election — active first, then latest.
     * Auto-creates a placeholder if none exist.
     */
    private function resolveElection(): Election
    {
        // Prefer the active election first, then the most recent one
        $election = Election::where('status', 'active')->first()
            ?? Election::latest()->first();

        // Only auto-create if truly none exist at all
        if (! $election) {
            $election = Election::create([
                'title'      => 'UM General Election ' . date('Y'),
                'start_date' => now()->toDateString(),
                'end_date'   => now()->addDays(7)->toDateString(),
                'status'     => 'pending',
            ]);
        }

        return $election;
    }

    /**
     * Abort with a 403 JSON / redirect if role check fails.
     */
    private function requireRole(int ...$roles): void
    {
        if (! in_array(auth()->user()->role_id, $roles, true)) {
            abort(403, 'Unauthorized: You do not have permission to perform this action.');
        }
    }

    /**
     * Write an audit log entry.
     */
    private function audit(string $description): void
    {
        AuditLog::create([
            'user_id'            => auth()->id(),
            'action_description' => $description,
            'ip_address'         => request()->ip(),
            'user_agent'         => request()->userAgent(),
        ]);
    }

    // =========================================================================
    // INDEX — Role-branched dashboard
    // =========================================================================

    public function index()
    {
        $user     = auth()->user();
        $election = $this->resolveElection();
        $electionStatus = $election->status;

        $activeCandidateIds = Candidate::where('is_archived', false)->pluck('id');

        $finalTally          = collect();
        $totalBallots        = 0;
        $maxVotesPerPosition = [];

        if (in_array($electionStatus, ['certified', 'published'], true)) {
            $finalTally = Candidate::where('is_archived', false)
                ->with('position')
                ->withCount('votes')
                ->get()
                ->groupBy('position.position_name');

            $totalBallots = Vote::whereIn('candidate_id', $activeCandidateIds)
                ->distinct('user_id')
                ->count('user_id');

            foreach ($finalTally as $position => $candidates) {
                $maxVotes = $candidates->max('votes_count');
                $maxVotesPerPosition[$position] = $maxVotes > 0 ? $maxVotes : 1;
            }
        }

        // ------------------------------------------------------------------
        // ADMIN
        // ------------------------------------------------------------------
        if ($user->role_id === User::ADMIN) {
            $candidates = Candidate::where('is_archived', false)
                ->with('position')
                ->get()
                ->sortBy('position_id')
                ->groupBy('position.position_name');

            return view('candidates.index', compact(
                'candidates', 'electionStatus', 'finalTally', 'election'
            ));
        }

        // ------------------------------------------------------------------
        // AUDITOR
        // ------------------------------------------------------------------
        if ($user->role_id === User::AUDITOR) {
            $candidates = Candidate::where('is_archived', false)
                ->with('position')
                ->withCount('votes')
                ->get();

            $tally = $candidates->groupBy('position.position_name');

            $totalVoters       = User::where('role_id', User::VOTER)->count();
            $totalVoted        = Vote::whereIn('candidate_id', $activeCandidateIds)
                ->distinct('user_id')->count('user_id');
            $turnoutPercentage = $totalVoters > 0
                ? round(($totalVoted / $totalVoters) * 100, 1)
                : 0;

            $votesPerPosition = collect();
            foreach ($tally as $positionName => $positionCandidates) {
                $votesPerPosition[$positionName] = $positionCandidates->sum('votes_count');
            }

            $recentLogs = AuditLog::with('user')
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get();

            return view('candidates.index', compact(
                'tally', 'totalVoters', 'totalVoted', 'turnoutPercentage',
                'votesPerPosition', 'recentLogs', 'electionStatus',
                'finalTally', 'maxVotesPerPosition', 'election'
            ));
        }

        // ------------------------------------------------------------------
        // VOTER
        // ------------------------------------------------------------------
        // Scope to the CURRENT active election only — never bleed across cycles
        $hasVoted = Vote::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->exists();

        $votedCandidates  = [];
        $groupedCandidates = [];
        $tally            = collect();

        // Past voted (archived) candidates
        $pastVoteIds  = Vote::where('user_id', $user->id)
            ->whereNotIn('candidate_id', $activeCandidateIds)
            ->pluck('candidate_id');
        $pastHistory  = Candidate::whereIn('id', $pastVoteIds)
            ->where('is_archived', true)
            ->with('position')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($hasVoted) {
            $voteIds         = Vote::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->pluck('candidate_id');
            $votedCandidates = Candidate::whereIn('id', $voteIds)
                ->with('position')
                ->get();

            $tally = Candidate::where('is_archived', false)
                ->with('position')
                ->withCount('votes')
                ->get()
                ->groupBy('position.position_name');
        } else {
            $groupedCandidates = Candidate::where('is_archived', false)
                ->with('position')
                ->get()
                ->groupBy('position.position_name');
        }

        return view('candidates.index', compact(
            'groupedCandidates', 'hasVoted', 'votedCandidates',
            'electionStatus', 'tally', 'finalTally', 'totalBallots',
            'maxVotesPerPosition', 'election', 'pastHistory'
        ));
    }

    // =========================================================================
    // CREATE / STORE
    // =========================================================================

    public function create()
    {
        $this->requireRole(User::ADMIN);

        $election = $this->resolveElection();

        if ($election->status !== 'pending') {
            return redirect()->route('candidates.index')
                ->with('error', 'Candidates can only be registered while the election is in PENDING status.');
        }

        // Try positions linked to this election first, then fall back to all positions
        // (handles mismatched election IDs after re-seeding)
        $positions = Position::where('election_id', $election->id)->get();
        if ($positions->isEmpty()) {
            $positions = Position::all();
        }

        if ($positions->isEmpty()) {
            return redirect()->route('candidates.index')
                ->with('error', 'No positions found. Please run: php artisan db:seed --class=PositionSeeder');
        }

        return view('candidates.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $this->requireRole(User::ADMIN);

        $election = $this->resolveElection();

        if ($election->status !== 'pending') {
            return redirect()->route('candidates.index')
                ->with('error', 'Cannot register candidates while election is ' . strtoupper($election->status) . '.');
        }

        // Validate partylist-level fields
        $request->validate([
            'partylist_name' => 'required|string|max:100',
            'college'        => 'required|string|max:50',
            'candidates'     => 'required|array|min:1',
        ], [
            'partylist_name.required' => 'The partylist name is required.',
            'college.required'        => 'Please select a college/department.',
            'candidates.required'     => 'Please fill in at least one candidate.',
        ]);

        $atLeastOne = false;

        DB::transaction(function () use ($request, &$atLeastOne) {
            foreach ($request->candidates as $positionId => $data) {
                $name = trim($data['name'] ?? '');
                if ($name === '') {
                    continue; // Allow blank positions
                }

                // Validate candidate-level fields
                if (strlen($name) > 255) {
                    throw new \InvalidArgumentException("Candidate name is too long for position #{$positionId}.");
                }

                // Check that position exists
                $position = Position::find($positionId);
                if (! $position) {
                    throw new \Exception("Invalid position ID: {$positionId}.");
                }

                Candidate::create([
                    'position_id'          => $positionId,
                    'candidate_name'       => $name,
                    'partylist'            => $request->partylist_name,
                    'college'              => $request->college,
                    'platform_description' => trim($data['platform'] ?? 'No platform provided.'),
                    'is_archived'          => false,
                ]);

                $atLeastOne = true;
            }
        });

        if (! $atLeastOne) {
            return back()
                ->withInput()
                ->with('error', 'Please enter at least one candidate name to register this partylist.');
        }

        $this->audit("Registered partylist '{$request->partylist_name}' ({$request->college}) with new candidates.");

        return redirect()->route('candidates.index')
            ->with('success', "Partylist '{$request->partylist_name}' registered successfully!");
    }

    // =========================================================================
    // EDIT / UPDATE
    // =========================================================================

    public function edit($id)
    {
        $this->requireRole(User::ADMIN);

        $candidate = Candidate::findOrFail($id);
        $positions = Position::all();

        return view('candidates.edit', compact('candidate', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $this->requireRole(User::ADMIN);

        $validated = $request->validate([
            'candidate_name'       => 'required|string|max:255',
            'position_id'          => 'required|exists:positions,id',
            'platform_description' => 'required|string|max:1000',
        ], [
            'candidate_name.required'       => 'Candidate name cannot be empty.',
            'position_id.required'          => 'Please select a valid position.',
            'position_id.exists'            => 'The selected position does not exist.',
            'platform_description.required' => 'Platform description is required.',
        ]);

        $candidate = Candidate::findOrFail($id);
        $candidate->update($validated);

        $this->audit("Updated candidate record: '{$candidate->candidate_name}' (ID #{$id}).");

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate profile updated successfully.');
    }

    // =========================================================================
    // DESTROY (soft-archive)
    // =========================================================================

    public function destroy($id)
    {
        $this->requireRole(User::ADMIN);

        $candidate = Candidate::findOrFail($id);

        $election = $this->resolveElection();
        if ($election->status === 'active') {
            return back()->with('error', 'Cannot remove a candidate while the election is active.');
        }

        $candidate->update(['is_archived' => true]);

        $this->audit("Archived candidate: '{$candidate->candidate_name}' (ID #{$id}).");

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate archived successfully.');
    }

    // =========================================================================
    // VOTE
    // =========================================================================

    public function storeVote(Request $request)
    {
        $user = auth()->user();

        // Only voters can cast ballots
        if ($user->role_id !== User::VOTER) {
            return back()->with('error', 'Only registered students (voters) can cast a ballot.');
        }

        $election = Election::where('status', 'active')->first();

        if (! $election) {
            return back()->with('error', 'The election is not currently active. Please try again later.');
        }

        // Double-vote prevention
        $alreadyVoted = Vote::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', 'You have already submitted your ballot for this election.');
        }

        if (! $request->has('votes') || ! is_array($request->votes)) {
            return back()->with('error', 'No votes were submitted. Please select a candidate for each position.');
        }

        $votes             = $request->votes;
        $activeCandidateIds = Candidate::where('is_archived', false)->pluck('id');

        $expectedPositions = Candidate::where('is_archived', false)
            ->distinct('position_id')
            ->pluck('position_id');

        if (count($votes) !== count($expectedPositions)) {
            return back()->with('error', 'Incomplete ballot — you must vote for every position before submitting.');
        }

        try {
            DB::transaction(function () use ($votes, $user, $election) {
                foreach ($votes as $positionId => $candidateId) {
                    // Tamper protection — verify the candidate belongs to that position
                    $candidate = Candidate::where('id', $candidateId)
                        ->where('position_id', $positionId)
                        ->where('is_archived', false)
                        ->firstOrFail();

                    Vote::create([
                        'user_id'      => $user->id,
                        'candidate_id' => $candidate->id,
                        'election_id'  => $election->id,
                        'position_id'  => $positionId,
                    ]);
                }
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Security alert: An invalid candidate was detected. Your ballot was rejected.');
        } catch (\Exception $e) {
            return back()->with('error', 'A system error occurred while submitting your ballot. Please try again.');
        }

        $this->audit("Student '{$user->name}' successfully submitted their official ballot.");

        return back()->with('voted_success', 'Your official ballot has been securely submitted and sealed. Thank you!');
    }

    // =========================================================================
    // ELECTION TOGGLE
    // =========================================================================

    public function toggleElection(Request $request)
    {
        $this->requireRole(User::ADMIN);

        $request->validate([
            'status' => 'required|in:active,closed,pending',
        ]);

        $election = $this->resolveElection();

        if ($request->status === 'active') {
            $activeCandidatesCount = Candidate::where('is_archived', false)->count();

            if ($activeCandidatesCount === 0) {
                return back()->with('error', 'Cannot start election: No active candidates found. Please register candidates first.');
            }

            $activeCandidateIds  = Candidate::where('is_archived', false)->pluck('id');
            $hasUnarchivedVotes  = Vote::whereIn('candidate_id', $activeCandidateIds)->exists();

            if ($hasUnarchivedVotes || in_array($election->status, ['closed', 'certified', 'published'], true)) {
                return back()->with('error', 'Cannot start a new election: Archive the previous election results first.');
            }
        }

        $election->update(['status' => $request->status]);

        $this->audit("Changed election status to: " . strtoupper($request->status));

        return back()->with('success', 'Election is now ' . strtoupper($request->status) . '.');
    }

    // =========================================================================
    // HISTORY (Voter)
    // =========================================================================

    public function history()
    {
        $this->requireRole(User::VOTER);

        $user = auth()->user();

        $votes = Vote::where('user_id', $user->id)
            ->with(['candidate.position', 'election'])
            ->orderBy('created_at', 'desc')
            ->get();

        $electionHistory = $votes->groupBy(function ($vote) {
            return \Carbon\Carbon::parse($vote->created_at)->format('F d, Y');
        });

        return view('candidates.history', compact('electionHistory'));
    }

    // =========================================================================
    // LEDGER (Auditor)
    // =========================================================================

    public function ledger()
    {
        $this->requireRole(User::AUDITOR);

        $election       = $this->resolveElection();
        $electionStatus = $election->status;

        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $finalTally = Candidate::where('is_archived', false)
            ->with('position')
            ->withCount('votes')
            ->get()
            ->groupBy('position.position_name');

        $activeCandidateIds = Candidate::where('is_archived', false)->pluck('id');
        $totalBallots = Vote::whereIn('candidate_id', $activeCandidateIds)
            ->distinct('user_id')
            ->count('user_id');

        $maxVotesPerPosition = [];
        foreach ($finalTally as $position => $candidates) {
            $maxVotes = $candidates->max('votes_count');
            $maxVotesPerPosition[$position] = $maxVotes > 0 ? $maxVotes : 1;
        }

        return view('candidates.ledger', compact(
            'logs', 'electionStatus', 'finalTally', 'totalBallots', 'maxVotesPerPosition'
        ));
    }

    // =========================================================================
    // ACCESS CONTROL (Admin)
    // =========================================================================

    public function accessControl()
    {
        $this->requireRole(User::ADMIN);

        $auditors = User::where('role_id', User::AUDITOR)->get();

        $election       = $this->resolveElection();
        $electionStatus = $election->status;

        $finalTally = collect();
        if (in_array($electionStatus, ['certified', 'published'], true)) {
            $finalTally = Candidate::where('is_archived', false)
                ->with('position')
                ->withCount('votes')
                ->get()
                ->groupBy('position.position_name');
        }

        return view('candidates.access', compact('auditors', 'electionStatus', 'finalTally'));
    }

    public function storeAuditor(Request $request)
    {
        $this->requireRole(User::ADMIN);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'     => 'Auditor full name is required.',
            'email.required'    => 'A valid email address is required.',
            'email.unique'      => 'This email is already registered in the system.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id'  => User::AUDITOR,
        ]);

        $this->audit("Created Auditor account for '{$validated['name']}' ({$validated['email']}).");

        return back()->with('success', 'Auditor account created successfully.');
    }

    // =========================================================================
    // CERTIFY (Auditor)
    // =========================================================================

    public function certifyResults()
    {
        $this->requireRole(User::AUDITOR);

        $election = $this->resolveElection();

        if ($election->status !== 'closed') {
            return back()->with('error', 'Only a CLOSED election can be certified.');
        }

        $election->update(['status' => 'certified']);

        $this->audit('Certified election results and forwarded to Administration for official publication.');

        return back()->with('success', 'Results certified and sent to Admin!');
    }

    // =========================================================================
    // PUBLISH (Admin)
    // =========================================================================

    public function publishResults()
    {
        $this->requireRole(User::ADMIN);

        $election = $this->resolveElection();

        if ($election->status !== 'certified') {
            return back()->with('error', 'Only a CERTIFIED election can be published.');
        }

        $election->update(['status' => 'published']);

        $this->audit('Published official certified election results to the student portal.');

        return back()->with('success', 'Official results published to all students!');
    }

    // =========================================================================
    // ARCHIVE SYSTEM (Admin)
    // =========================================================================

    public function archiveSystem()
    {
        $this->requireRole(User::ADMIN);

        $election = $this->resolveElection();

        if ($election->status === 'active') {
            return back()->with('error', 'End the active election before archiving.');
        }

        if ($election->status !== 'published') {
            return back()->with('error', 'Only a PUBLISHED election can be archived.');
        }

        if (Candidate::where('is_archived', false)->count() === 0) {
            return back()->with('error', 'There is no active data to archive.');
        }

        try {
            DB::transaction(function () use ($election) {
                Candidate::where('is_archived', false)->update(['is_archived' => true]);
                $election->update(['status' => 'pending']);
            });

            $this->audit('Archived election data. Dashboard reset for next election cycle.');

            return redirect()->route('candidates.index')
                ->with('success', 'Election archived! The dashboard is now clear for the next cycle.');
        } catch (\Exception $e) {
            return back()->with('error', 'A system error occurred while archiving. Please try again.');
        }
    }

    // =========================================================================
    // ARCHIVES (Admin — view historical data)
    // =========================================================================

    public function archives()
    {
        $this->requireRole(User::ADMIN);

        $archivedCandidates = Candidate::where('is_archived', true)
            ->with('position')
            ->withCount('votes')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy(function ($candidate) {
                return \Carbon\Carbon::parse($candidate->updated_at)->format('F d, Y \a\t h:i A');
            });

        return view('candidates.archives', compact('archivedCandidates'));
    }

    // =========================================================================
    // PROFILE (all roles)
    // =========================================================================

    public function profile()
    {
        $user = auth()->user();

        $voteCount = Vote::where('user_id', $user->id)->count();
        $lastVote  = Vote::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('candidates.profile', compact('user', 'voteCount', 'lastVote'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email,' . $user->id,
            'current_password'     => 'nullable|string',
            'new_password'         => 'nullable|string|min:8|confirmed',
        ], [
            'name.required'          => 'Your name cannot be empty.',
            'email.required'         => 'A valid email is required.',
            'email.unique'           => 'This email is already in use by another account.',
            'new_password.min'       => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        // If changing password, verify current password first
        if ($request->filled('new_password')) {
            if (! $request->filled('current_password') || ! Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Your current password is incorrect.'])
                    ->withInput();
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        $this->audit("Updated own profile (name/email" . ($request->filled('new_password') ? '/password' : '') . ").");

        return back()->with('success', 'Profile updated successfully.');
    }
}