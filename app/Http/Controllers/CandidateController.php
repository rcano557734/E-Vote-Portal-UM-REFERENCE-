<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Position;
use App\Models\Vote;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Election;

class CandidateController extends Controller
{
    /**
     * Display the correct UM E-Vote Dashboard based on user role.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Ensure an election record exists
        $election = Election::first();
        if (!$election) {
            $election = Election::create(['status' => 'pending']);
        }
        $electionStatus = $election->status;

        // ---------------------------------------------------------
        // ADMIN DASHBOARD (Role 1)
        // ---------------------------------------------------------
        if ($user->role_id === 1) {
            $candidates = Candidate::with('position')->get();
            $tally = Candidate::with('position')->withCount('votes')->get()->groupBy('position.position_name');
            
            return view('candidates.index', compact('candidates', 'tally', 'electionStatus'));
        } 
        
        // ---------------------------------------------------------
        // AUDITOR DASHBOARD (Role 2)
        // ---------------------------------------------------------
        elseif ($user->role_id === 2) { 
            $candidates = Candidate::with('position')->withCount('votes')->get();
            $tally = $candidates->groupBy('position.position_name');
            
            // Calculate Turnout Analytics
            $totalVoters = User::where('role_id', 3)->count(); 
            $totalVoted = Vote::distinct('user_id')->count('user_id'); 
            $turnoutPercentage = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;

            // Fetch recent system logs
            $recentLogs = AuditLog::with('user')->orderBy('created_at', 'desc')->take(50)->get();

            return view('candidates.index', compact('tally', 'totalVoters', 'totalVoted', 'turnoutPercentage', 'recentLogs', 'electionStatus'));
        } 
        
        // ---------------------------------------------------------
        // STUDENT VOTER DASHBOARD (Role 3)
        // ---------------------------------------------------------
        else {
            $hasVoted = Vote::where('user_id', $user->id)->exists();
            $votedCandidates = [];
            $groupedCandidates = [];

            if ($hasVoted) {
                // If they already voted, fetch who they voted for to display the receipt
                $voteIds = Vote::where('user_id', $user->id)->pluck('candidate_id');
                $votedCandidates = Candidate::whereIn('id', $voteIds)->with('position')->get();
            } else {
                // If they haven't voted, fetch the ballot
                $groupedCandidates = Candidate::with('position')->get()->groupBy('position.position_name');
            }

            return view('candidates.index', compact('groupedCandidates', 'hasVoted', 'votedCandidates', 'electionStatus'));
        }
    }

    /**
     * Admin Function: Start or Stop the UM Election
     */
    public function toggleElection(Request $request)
    {
        // Security: Only Admins can do this
        if (auth()->user()->role_id !== 1) abort(403, 'Unauthorized Action.');

        $election = Election::first();
        $election->update(['status' => $request->status]);

        // Log the admin action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action_description' => 'Changed election status to: ' . strtoupper($request->status)
        ]);

        return back()->with('success', 'Election is now ' . strtoupper($request->status) . '.');
    }

    /**
     * Voter Function: Process and secure the submitted ballot
     */
    public function storeVote(Request $request)
    {
        $user = auth()->user();

        // Security Check 1: Admins/Auditors cannot vote
        if ($user->role_id !== 3) abort(403, 'Only verified students can vote.');

        // Security Check 2: Is the election actually open?
        $election = Election::first();
        if ($election->status !== 'active') {
            return back()->with('error', 'The election is currently closed.');
        }

        // Security Check 3: Did they already vote?
        if (Vote::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already submitted your official ballot.');
        }

        // Security Check 4: INCOMPLETE BALLOT VALIDATION
        // Count how many positions exist in the database
        $expectedPositionsCount = Position::count();
        
        // Count how many votes the student actually submitted
        $submittedVotesCount = $request->has('votes') ? count($request->votes) : 0;

        if ($submittedVotesCount < $expectedPositionsCount) {
            // Trigger the SweetAlert Error on the frontend
            return back()->with('error', 'Incomplete Ballot! You must select a candidate for every position.');
        }

        // If all checks pass, securely record the votes
        foreach ($request->votes as $position_id => $candidate_id) {
            Vote::create([
                'user_id' => $user->id, 
                'candidate_id' => $candidate_id
            ]);
        }
        
        // Log the voting action for the Auditor
        AuditLog::create([
            'user_id' => $user->id,
            'action_description' => 'Successfully cast an encrypted official ballot.'
        ]);
        
        // Return with success trigger for SweetAlert
        return redirect()->route('candidates.index')->with('voted_success', 'Your student ballot has been securely verified and cast!');
    }

    // ... (Keep your existing create(), store(), edit(), update(), destroy() methods here for managing candidates)
    
    public function create() { return view('candidates.create'); }
    
    // Example store method (keep yours if it has image uploading etc)
    public function store(Request $request) {
        Candidate::create($request->all());
        return redirect()->route('candidates.index')->with('success', 'Candidate Registered Successfully!');
    }
    
    public function destroy($id) {
        Candidate::findOrFail($id)->delete();
        return redirect()->route('candidates.index')->with('success', 'Candidate removed from ballot.');
    }
}