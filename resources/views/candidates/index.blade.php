@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root { --um-maroon: #8a1538; --um-maroon-dark: #630f28; --um-gold: #fdb813; }
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; }
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }
    
    .dash-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 40px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 28px; }
    .dash-card-header { background: rgba(255, 255, 255, 0.5); padding: 20px 28px; border-bottom: 1px solid rgba(241, 245, 249, 0.8); font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 17px; }
    .bg-gradient-maroon { background: linear-gradient(90deg, #fff0f2, rgba(255,255,255,0.4)); color: var(--um-maroon); border-bottom-color: #fbcfe8; }
    
    .btn-dash-primary { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; border: none; transition: transform 0.2s; box-shadow: 0 4px 15px rgba(138,21,56,0.25);}
    .btn-dash-primary:hover { transform: translateY(-2px); color: white; box-shadow: 0 8px 25px rgba(138,21,56,0.35);}
    .btn-dash-danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; border: none; transition: transform 0.2s;}
    
    .stat-card { border-radius: 20px; padding: 32px 24px; text-align: center; color: white; }
    .maroon-stat { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); }
    .gold-stat { background: linear-gradient(135deg, #eab308, #b45309); }
    .dark-stat { background: linear-gradient(135deg, #334155, #0f172a); }
    
    .ballot-check { display: flex; align-items: center; padding: 24px; border: 2px solid transparent; border-radius: 16px; cursor: pointer; transition: all 0.2s; background: white; margin-bottom: 16px; }
    .ballot-check input[type="radio"] { display: none; }
    .custom-radio { width: 24px; height: 24px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 20px; display: flex; align-items: center; justify-content: center; }
    .ballot-check input[type="radio"]:checked + .custom-radio { border-color: var(--um-maroon); background: var(--um-maroon); box-shadow: 0 0 0 4px rgba(138,21,56, 0.2); }
    .ballot-check input[type="radio"]:checked + .custom-radio::after { content: ''; width: 10px; height: 10px; background: white; border-radius: 50%; }
    .ballot-check:has(input[type="radio"]:checked) { border-color: var(--um-maroon); background: #fff0f2; }
</style>

@php
    $election = \App\Models\Election::first();
    $electionStatus = $election ? $election->status : 'pending';
@endphp

<div class="dash-wrap">

    @if(auth()->user()->role_id === 1)
        <div class="d-flex justify-content-between align-items-center mb-4 reveal">
            <h2 class="dash-title m-0" style="font-size: 36px;">Admin Workspace</h2>
            <span class="badge bg-dark px-3 py-2 fs-6 rounded-pill">Status: {{ strtoupper($electionStatus) }}</span>
        </div>

        <div class="dash-card reveal mb-5 p-4 text-center" style="background: white;">
            <h4 class="dash-title mb-3">Master System Controls</h4>
            <p class="text-muted mb-4">Start the UM Student Council election to allow students to cast ballots.</p>
            <div class="d-flex justify-content-center gap-4">
                <form action="{{ route('election.toggle') }}" method="POST">
                    @csrf <input type="hidden" name="status" value="active">
                    <button type="submit" class="btn-dash-primary" {{ $electionStatus == 'active' ? 'disabled style=opacity:0.5;' : '' }}>
                        <i class="bi bi-play-circle me-1"></i> START ELECTION
                    </button>
                </form>
                <form action="{{ route('election.toggle') }}" method="POST">
                    @csrf <input type="hidden" name="status" value="closed">
                    <button type="submit" class="btn-dash-danger" {{ $electionStatus == 'closed' ? 'disabled style=opacity:0.5;' : '' }}>
                        <i class="bi bi-stop-circle me-1"></i> END ELECTION
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="dash-card reveal">
                    <div class="dash-card-header bg-gradient-maroon">Register Candidate</div>
                    <div class="p-5 text-center">
                        <a href="{{ route('candidates.create') }}" class="btn-dash-primary w-100 text-decoration-none">Create Form <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="dash-card reveal">
                    <div class="dash-card-header">Official SSG Candidates</div>
                    <div class="table-responsive p-3">
                        <table class="table align-middle">
                            @foreach($candidates as $candidate)
                                <tr>
                                    <td class="fw-bold">{{ $candidate->candidate_name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $candidate->position->position_name }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role_id === 2)
        <div class="d-flex justify-content-between align-items-center mb-5 reveal">
            <h2 class="dash-title m-0" style="font-size: 36px;">Integrity Dashboard</h2>
        </div>

        <div class="row mb-5">
            <div class="col-md-4"><div class="stat-card maroon-stat reveal"><div>Total Students</div><div class="stat-value">{{ $totalVoters }}</div></div></div>
            <div class="col-md-4"><div class="stat-card gold-stat reveal"><div>Ballots Cast</div><div class="stat-value">{{ $totalVoted }}</div></div></div>
            <div class="col-md-4"><div class="stat-card dark-stat reveal"><div>Voter Turnout</div><div class="stat-value">{{ $turnoutPercentage }}%</div></div></div>
        </div>

        <div class="dash-card reveal p-4 mb-5">
            <h4 class="dash-title mb-2">Live Vote Margin Predictions</h4>
            <p class="text-muted mb-4">Statistical breakdown based on current student turnout.</p>
            <canvas id="predictionChart" height="100"></canvas>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4 class="dash-title mb-4 reveal">System Audit Logs</h4>
                <div class="dash-card reveal p-3" style="max-height: 400px; overflow-y: auto;">
                    @foreach($recentLogs as $log)
                        <div class="d-flex mb-3 border-bottom pb-2">
                            <i class="bi bi-clock-history text-muted me-3 mt-1"></i>
                            <div>
                                <div class="fw-bold">{{ $log->user->name }} <span class="text-muted fw-normal">— {{ $log->action_description }}</span></div>
                                <div style="font-size: 12px;" class="text-muted">{{ $log->created_at->format('M d, Y - g:i A') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('predictionChart').getContext('2d');
                const labels = []; const data = [];
                @foreach($tally as $pos => $cands)
                    @foreach($cands as $c)
                        labels.push('{{ $c->candidate_name }}');
                        data.push({{ $c->votes_count }});
                    @endforeach
                @endforeach

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Verified Votes',
                            data: data,
                            backgroundColor: '#8a1538', // UM Maroon
                            borderColor: '#630f28',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });
            });
        </script>

    @else
        
        @if($hasVoted)
            <div class="dash-card mx-auto reveal text-center p-5" style="max-width: 600px; margin-top: 60px; border: 2px solid rgba(138,21,56, 0.3);">
                <div style="width: 80px; height: 80px; background: var(--um-maroon); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px;"><i class="bi bi-shield-check"></i></div>
                <h2 class="dash-title" style="color: var(--um-maroon);">Ballot Secured</h2>
                <p class="text-muted">You have already submitted your vote. Please wait for another election cycle to revote.</p>
            </div>
        
        @elseif($electionStatus !== 'active')
            <div class="dash-card mx-auto reveal text-center p-5" style="max-width: 600px; margin-top: 60px;">
                <div style="width: 80px; height: 80px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px;"><i class="bi bi-lock-fill"></i></div>
                <h2 class="dash-title">Election {{ ucfirst($electionStatus) }}</h2>
                <p class="text-muted">
                    @if($electionStatus === 'pending') The polls have not opened yet. Please wait for the UM Administrator to start the election.
                    @else The polls are officially closed. The results are currently being audited by the Electoral Board. @endif
                </p>
            </div>

        @else
            <div class="text-center mb-5 mt-4 reveal">
                <h2 class="dash-title mt-4" style="font-size: 42px;">Student Council Ballot</h2>
                <p class="text-muted">Review the candidates carefully. You MUST vote for all required positions.</p>
            </div>

            <div class="mx-auto" style="max-width: 760px;">
                <form id="votingForm" action="{{ route('vote.store') }}" method="POST">
                    @csrf
                    @foreach($groupedCandidates as $positionName => $candidates)
                        <div class="dash-card mb-5 reveal">
                            <div class="dash-card-header bg-gradient-maroon">{{ $positionName }}</div>
                            <div class="p-4" style="background: rgba(248, 250, 252, 0.5);">
                                @foreach($candidates as $candidate)
                                    <label class="ballot-check w-100" for="cand_{{ $candidate->id }}">
                                        <input type="radio" name="votes[{{ $candidate->position_id }}]" id="cand_{{ $candidate->id }}" value="{{ $candidate->id }}">
                                        <div class="custom-radio"></div>
                                        <div>
                                            <span class="cand-name">{{ $candidate->candidate_name }}</span>
                                            <span class="cand-plat">{{ $candidate->platform_description }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="dash-card p-5 text-center mt-5 reveal" style="background: white;">
                        <button type="button" onclick="confirmVote()" class="btn-dash-primary w-100 py-3" style="font-size: 18px; max-width: 400px;">Submit Secure Ballot</button>
                    </div>
                </form>
            </div>

            <script>
                function confirmVote() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You cannot change your vote after submitting your student ballot!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8a1538',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, submit my ballot!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('votingForm').submit();
                        }
                    })
                }
            </script>
        @endif
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const reveals = document.querySelectorAll(".reveal");
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add("active"); });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        reveals.forEach(reveal => observer.observe(reveal));
        
        @if(session('error')) Swal.fire('Incomplete Ballot!', "{{ session('error') }}", 'error'); @endif
        @if(session('voted_success')) Swal.fire({ title: 'Success!', text: "{{ session('voted_success') }}", icon: 'success', confirmButtonColor: '#8a1538' }); @endif
        @if(session('success') && !session('voted_success')) Swal.fire('Updated', "{{ session('success') }}", 'success'); @endif
    });
</script>
@endsection