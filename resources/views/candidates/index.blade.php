@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Typography & Animations */
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }
    
    @keyframes subtle-bounce {
        0%, 100% { transform: translate(-50%, 0); }
        50% { transform: translate(-50%, 4px); }
    }
    .arrow-bounce { animation: subtle-bounce 2s infinite ease-in-out; }
    
    /* Crisp White Cards */
    .dash-card { 
        background: #ffffff; 
        border-radius: 20px; 
        border: 1px solid #cbd5e1; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
        overflow: hidden; 
        margin-bottom: 32px; 
        height: 100%; 
    }
    .dash-card:hover { 
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        border-color: #94a3b8; 
    }
    
    .dash-card-header { padding: 16px 24px; border-bottom: 1px solid #e2e8f0; font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 16px; background: #f8fafc; color: var(--text-dark);}
    .bg-gradient-maroon { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); color: #ffffff; border-bottom: none; letter-spacing: 0.03em; }
    
    /* UM Branded Buttons */
    .btn-dash-primary { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); color: white; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; border: none; transition: all 0.2s ease; box-shadow: 0 4px 15px rgba(138,21,56,0.25); text-transform: uppercase; letter-spacing: 0.05em;}
    .btn-dash-primary:hover { transform: translateY(-2px); color: white; box-shadow: 0 8px 25px rgba(138,21,56,0.35); background: linear-gradient(135deg, #a31c44, var(--um-maroon));}
    .btn-dash-danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; border: none; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);}
    .btn-dash-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(239, 68, 68, 0.35);}
    
    /* Stat Cards */
    .stat-card { border-radius: 20px; padding: 32px 24px; text-align: center; color: white; box-shadow: 0 10px 20px rgba(0,0,0,0.05); position: relative; overflow: hidden;}
    .stat-card::after { content: ''; position: absolute; top: -50%; right: -20%; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; pointer-events: none;}
    .maroon-stat { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); }
    .gold-stat { background: linear-gradient(135deg, var(--um-gold), var(--um-gold-dark)); color: #0f172a; }
    .dark-stat { background: linear-gradient(135deg, #334155, #0f172a); }
    .stat-value { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 48px; margin-top: 8px; line-height: 1; letter-spacing: -0.02em; }
    
    /* Ballot UI */
    .ballot-check { display: flex; align-items: center; padding: 24px; border: 2px solid #e2e8f0; border-radius: 16px; cursor: pointer; transition: all 0.2s; background: #f8fafc; margin-bottom: 16px; }
    .ballot-check:hover { border-color: var(--um-maroon); background: #ffffff; box-shadow: 0 8px 20px rgba(138,21,56,0.08); transform: translateY(-2px);}
    .ballot-check input[type="radio"] { display: none; }
    .custom-radio { width: 26px; height: 26px; border: 2px solid #94a3b8; border-radius: 50%; margin-right: 24px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background: white;}
    .ballot-check input[type="radio"]:checked + .custom-radio { border-color: var(--um-maroon); background: var(--um-maroon); box-shadow: 0 0 0 5px rgba(138,21,56, 0.15); }
    .ballot-check input[type="radio"]:checked + .custom-radio::after { content: ''; width: 10px; height: 10px; background: var(--um-gold); border-radius: 50%; }
    .ballot-check:has(input[type="radio"]:checked) { border-color: var(--um-maroon); background: var(--um-maroon-light); }
</style>

@php
    $election = \App\Models\Election::first();
    $electionStatus = $election ? $election->status : 'pending';
@endphp

<div class="dash-wrap">

    @if(auth()->user()->role_id === 1)
        <div class="d-flex justify-content-between align-items-center mb-4 reveal">
            <h2 class="dash-title m-0" style="font-size: 38px;">Admin Workspace</h2>
            <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: {{ $electionStatus == 'active' ? '#10b981' : ($electionStatus == 'closed' ? '#ef4444' : '#f59e0b') }}; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                Status: {{ strtoupper($electionStatus) }}
            </span>
        </div>

        <div class="dash-card reveal mb-5 p-5 text-center">
            <div style="width: 64px; height: 64px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;"><i class="bi bi-sliders"></i></div>
            <h3 class="dash-title mb-3">Master System Controls</h3>
            <p class="text-muted mb-4 fs-6" style="max-width: 500px; margin: 0 auto;">Control the UM Student Council election flow. Starting the election opens the portal for students to cast their official ballots.</p>
            <div class="d-flex justify-content-center gap-4 mt-4">
                <form action="{{ route('election.toggle') }}" method="POST">
                    @csrf <input type="hidden" name="status" value="active">
                    <button type="submit" class="btn-dash-primary" {{ $electionStatus == 'active' ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                        <i class="bi bi-play-circle-fill me-2"></i> START ELECTION
                    </button>
                </form>
                <form action="{{ route('election.toggle') }}" method="POST">
                    @csrf <input type="hidden" name="status" value="closed">
                    <button type="submit" class="btn-dash-danger" {{ $electionStatus == 'closed' ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                        <i class="bi bi-stop-circle-fill me-2"></i> END ELECTION
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="dash-card reveal" style="transition-delay: 0.1s;">
                    <div class="dash-card-header bg-gradient-maroon">Register Candidate</div>
                    <div class="p-5 text-center">
                        <p class="text-muted mb-4">Add new students to the official ballot.</p>
                        <a href="{{ route('candidates.create') }}" class="btn-dash-primary w-100 text-decoration-none">Open Form <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="dash-card reveal" style="transition-delay: 0.2s;">
                    <div class="dash-card-header">Official SSG Candidates</div>
                    <div class="table-responsive p-2">
                        <table class="table table-borderless align-middle m-0">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em;">
                                    <th class="ps-4 py-3">Candidate Name</th><th>Position</th><th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($candidates as $candidate)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="ps-4 py-3 fw-bold text-dark fs-6" style="font-family: 'Bricolage Grotesque';">{{ $candidate->candidate_name }}</td>
                                        <td><span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 700;">{{ $candidate->position->position_name }}</span></td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Remove candidate from ballot?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm" style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; border-radius: 8px; padding: 6px 12px;"><i class="bi bi-trash3-fill"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-5 text-muted">No candidates registered.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role_id === 2)
        <div class="d-flex justify-content-between align-items-center mb-5 reveal">
            <h2 class="dash-title m-0" style="font-size: 38px;">Integrity Dashboard</h2>
            <span style="background: white; border: 1px solid #cbd5e1; color: #475569; padding: 8px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);"><i class="bi bi-eye-fill me-1 text-warning"></i> Audit Mode</span>
        </div>

        <div class="row mb-4">
            <div class="col-md-4"><div class="stat-card maroon-stat reveal" style="transition-delay: 0.1s;"><div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 13px; opacity: 0.9;">Total Students</div><div class="stat-value">{{ $totalVoters }}</div></div></div>
            <div class="col-md-4"><div class="stat-card gold-stat reveal" style="transition-delay: 0.2s;"><div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 13px; opacity: 0.8; color: #0f172a;">Total Ballots Cast</div><div class="stat-value">{{ $totalVoted }}</div></div></div>
            
            <div class="col-md-4">
                <div class="stat-card dark-stat reveal" style="transition-delay: 0.3s; cursor: pointer; position: relative; padding-bottom: 45px;" onclick="toggleTurnoutDetails()" id="turnoutCard" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.05)';">
                    <div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 13px; opacity: 0.9; color: #e2e8f0;">Voter Turnout</div>
                    <div class="stat-value text-white">{{ $turnoutPercentage }}%</div>
                    
                    <div class="arrow-bounce" style="position: absolute; bottom: 12px; left: 50%; background: rgba(255,255,255,0.15); padding: 4px 16px; border-radius: 20px; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; white-space: nowrap;">
                        <span style="font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.1em; margin-right: 6px; color: white;">View Breakdown</span>
                        <i class="bi bi-chevron-down text-white" id="turnoutIcon" style="transition: transform 0.3s; font-size: 14px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div id="turnoutBreakdown" style="display: none;">
            <div class="dash-card reveal p-4 mb-5" style="border-top: 4px solid var(--um-maroon); background: #f8fafc;">
                <h5 class="dash-title mb-4 fs-5"><i class="bi bi-diagram-3-fill text-muted me-2"></i>Participation by Position</h5>
                <div class="row g-3">
                    @foreach($votesPerPosition as $position => $count)
                    <div class="col-md-3 col-sm-6">
                        <div class="p-3 bg-white border rounded shadow-sm">
                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 11px; letter-spacing: 0.05em;">{{ $position }}</div>
                            <div class="fs-4 fw-bold" style="color: var(--um-maroon); font-family: 'Bricolage Grotesque';">{{ $count }} <span class="fs-6 text-muted fw-normal" style="font-family: 'DM Sans';">votes</span></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
            function toggleTurnoutDetails() {
                const panel = document.getElementById('turnoutBreakdown');
                const icon = document.getElementById('turnoutIcon');
                if (panel.style.display === 'none') {
                    panel.style.display = 'block';
                    icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                } else {
                    panel.style.display = 'none';
                    icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
                }
            }
        </script>

        @if($electionStatus === 'active')
            <div class="d-flex justify-content-between align-items-center mb-4 reveal">
                <h4 class="dash-title m-0">Live Election Tally</h4>
                <span class="badge" style="background: var(--um-maroon-light); color: var(--um-maroon); font-size: 12px; padding: 6px 12px;"><i class="bi bi-broadcast me-1"></i> Real-Time Updates</span>
            </div>
            
            <div class="row mb-5">
                @foreach($tally as $positionName => $positionCandidates)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="dash-card reveal m-0" style="transition-delay: {{ $loop->index * 0.1 }}s;">
                            <div class="dash-card-header bg-gradient-maroon text-center fs-6 py-3">{{ $positionName }}</div>
                            <div class="p-3 bg-white">
                                @foreach($positionCandidates->sortByDesc('votes_count') as $index => $candidate)
                                    <div class="d-flex align-items-center p-2 mb-2 rounded" style="background: {{ $index === 0 ? '#fdf2f5' : '#f8fafc' }}; border: 1px solid {{ $index === 0 ? '#fbcfe8' : '#e2e8f0' }};">
                                        <div class="fw-bold me-2 text-muted" style="font-size: 14px; width: 15px;">#{{ $index + 1 }}</div>
                                        <div class="ms-2 flex-grow-1">
                                            <span class="fw-bold text-dark d-block" style="font-family: 'Bricolage Grotesque'; font-size: 15px; line-height: 1.1;">{{ $candidate->candidate_name }}</span>
                                            @if($index === 0)
                                                <span style="color: var(--um-gold-dark); font-size: 10px; font-weight: 800;"><i class="bi bi-star-fill me-1"></i>LEADER</span>
                                            @endif
                                        </div>
                                        <div class="text-end ms-2">
                                            <span style="background: {{ $index === 0 ? 'var(--um-maroon)' : '#475569' }}; color: white; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 14px;">
                                                {{ $candidate->votes_count }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 reveal">
                <h4 class="dash-title m-0">Live Margin Predictions</h4>
                <i class="bi bi-bar-chart-line-fill text-muted fs-4"></i>
            </div>

            <div class="row mb-5">
                @foreach($tally as $positionName => $positionCandidates)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="dash-card reveal p-4 m-0" style="transition-delay: {{ $loop->index * 0.1 }}s; display: flex; flex-direction: column;">
                            <h6 class="text-center fw-bold mb-4" style="color: var(--text-dark); text-transform: uppercase;">{{ $positionName }} Race</h6>
                            <div style="position: relative; flex-grow: 1; width: 100%; min-height: 250px;">
                                <canvas id="auditorChart_{{ $loop->index }}"></canvas>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    @foreach($tally as $positionName => $positionCandidates)
                        (function() {
                            const ctx = document.getElementById('auditorChart_{{ $loop->index }}').getContext('2d');
                            const labels = {!! json_encode($positionCandidates->pluck('candidate_name')) !!};
                            const data = {!! json_encode($positionCandidates->pluck('votes_count')) !!};

                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Votes',
                                        data: data,
                                        backgroundColor: '#8a1538', 
                                        borderColor: '#5c0d24',
                                        borderWidth: 1,
                                        borderRadius: 4,
                                        hoverBackgroundColor: '#fdb813' 
                                    }]
                                },
                                options: { 
                                    responsive: true, 
                                    maintainAspectRatio: false,
                                    scales: { 
                                        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                                        x: { 
                                            grid: { display: false }, 
                                            ticks: { 
                                                display: true, 
                                                font: { family: 'DM Sans', size: 10 },
                                                color: '#475569',
                                                maxRotation: 45,
                                                minRotation: 0
                                            } 
                                        } 
                                    },
                                    plugins: { 
                                        legend: { display: false },
                                        tooltip: { callbacks: { title: function(context) { return context[0].label; } } }
                                    }
                                }
                            });
                        })();
                    @endforeach
                });
            </script>
        @else
            <div class="dash-card mx-auto reveal text-center p-5 mt-5" style="max-width: 600px; border-top: 6px solid #94a3b8;">
                <div style="width: 90px; height: 90px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 24px;"><i class="bi bi-lock-fill"></i></div>
                <h2 class="dash-title" style="font-size: 32px;">Live Tracking Offline</h2>
                <p class="text-muted fs-6 mt-3">
                    @if($electionStatus === 'pending') The polls have not opened yet. Live tracking will begin once the election starts.
                    @else The election is officially closed. The live tally has been cleared. Please review the <strong>Audit Ledger</strong> for the final certified results. @endif
                </p>
            </div>
        @endif

    @else
        
        @if($electionStatus !== 'active')
            <div class="dash-card mx-auto reveal text-center p-5" style="max-width: 600px; margin-top: 60px; border-top: 6px solid #94a3b8;">
                <div style="width: 90px; height: 90px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 24px;"><i class="bi bi-lock-fill"></i></div>
                <h2 class="dash-title" style="font-size: 32px;">Election {{ ucfirst($electionStatus) }}</h2>
                <p class="text-muted fs-6 mt-3">
                    @if($electionStatus === 'pending') The polls have not opened yet. Please wait for the UM Administrator to officially start the election.
                    @else The polls are officially closed. The live tally is no longer available. You can view your ballot receipt in the <strong>Election History</strong> tab. @endif
                </p>
            </div>

        @elseif($hasVoted)
            <div class="dash-card mx-auto reveal text-center p-5 mb-5" style="max-width: 800px; margin-top: 20px; border-top: 6px solid var(--um-maroon);">
                <div class="d-flex justify-content-center gap-4 align-items-center mb-4">
                    <div style="width: 70px; height: 70px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 35px;"><i class="bi bi-shield-lock-fill"></i></div>
                    <div class="text-start">
                        <h2 class="dash-title m-0" style="font-size: 28px; color: var(--text-dark);">Ballot Secured</h2>
                        <p class="text-muted m-0 mt-1 fs-6">Your digital ballot has been cryptographically sealed.</p>
                    </div>
                </div>
                
                <div class="text-start reveal" style="background: #f8fafc; padding: 24px; border-radius: 16px; transition-delay: 0.1s;">
                    <h5 class="fw-bold mb-3" style="font-family: 'Bricolage Grotesque'; color: #0f172a;"><i class="bi bi-check2-circle text-success me-2"></i> Your Verified Selections</h5>
                    <div class="row g-3">
                        @foreach($votedCandidates as $candidate)
                            <div class="col-md-6">
                                <div class="p-3 bg-white border rounded shadow-sm d-flex justify-content-between align-items-center">
                                    <span style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700;">{{ $candidate->position->position_name }}</span>
                                    <strong style="color: var(--um-maroon); font-size: 15px;">{{ $candidate->candidate_name }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center mb-5 mt-5 reveal">
                <span style="background: white; color: var(--um-gold-dark); border: 1px solid #fde68a; padding: 6px 16px; border-radius: 50px; font-weight: 800; font-size: 12px; letter-spacing: 0.15em; text-transform: uppercase; box-shadow: 0 4px 15px rgba(0,0,0,0.03);"><i class="bi bi-broadcast me-1"></i> Live Updates</span>
                <h2 class="dash-title mt-4" style="font-size: 36px;">Current Election Tally</h2>
                <p class="text-muted">Watch the results unfold in real-time. Refresh the page for the latest counts.</p>
            </div>

            <div class="row mx-auto" style="max-width: 1000px;">
                @foreach($tally as $positionName => $positionCandidates)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="dash-card reveal m-0" style="transition-delay: {{ $loop->index * 0.1 }}s;">
                            <div class="dash-card-header bg-gradient-maroon text-center fs-6 py-3">{{ $positionName }}</div>
                            <div class="p-3 bg-white">
                                @foreach($positionCandidates->sortByDesc('votes_count') as $index => $candidate)
                                    <div class="d-flex align-items-center p-2 mb-2 rounded" style="background: {{ $index === 0 ? '#fdf2f5' : '#f8fafc' }}; border: 1px solid {{ $index === 0 ? '#fbcfe8' : '#e2e8f0' }};">
                                        <div class="fw-bold me-2 text-muted" style="font-size: 14px; width: 15px;">#{{ $index + 1 }}</div>
                                        <div style="width: 35px; height: 35px; background: white; border: 2px solid {{ $index === 0 ? 'var(--um-maroon)' : '#cbd5e1' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #94a3b8; flex-shrink: 0; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="ms-2 flex-grow-1">
                                            <span class="fw-bold text-dark d-block" style="font-family: 'Bricolage Grotesque'; font-size: 14px; line-height: 1.1;">{{ $candidate->candidate_name }}</span>
                                            @if($index === 0)
                                                <span style="color: var(--um-gold-dark); font-size: 9px; font-weight: 800;"><i class="bi bi-star-fill me-1"></i>LEADER</span>
                                            @endif
                                        </div>
                                        <div class="text-end ms-2">
                                            <span style="background: {{ $index === 0 ? 'var(--um-maroon)' : '#475569' }}; color: white; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 13px;">
                                                {{ $candidate->votes_count }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row mx-auto mb-5" style="max-width: 1000px;">
                @foreach($tally as $positionName => $positionCandidates)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="dash-card reveal p-4 m-0" style="transition-delay: {{ $loop->index * 0.1 }}s; display: flex; flex-direction: column;">
                            <h6 class="text-center fw-bold mb-4" style="color: var(--text-dark); text-transform: uppercase;">{{ $positionName }} Race</h6>
                            <div style="position: relative; flex-grow: 1; width: 100%; min-height: 250px;">
                                <canvas id="voterChart_{{ $loop->index }}"></canvas>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    @foreach($tally as $positionName => $positionCandidates)
                        (function() {
                            const ctx = document.getElementById('voterChart_{{ $loop->index }}').getContext('2d');
                            const labels = {!! json_encode($positionCandidates->pluck('candidate_name')) !!};
                            const data = {!! json_encode($positionCandidates->pluck('votes_count')) !!};

                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Votes',
                                        data: data,
                                        backgroundColor: '#8a1538',
                                        borderColor: '#5c0d24',
                                        borderWidth: 1,
                                        borderRadius: 4,
                                        hoverBackgroundColor: '#fdb813'
                                    }]
                                },
                                options: { 
                                    responsive: true, 
                                    maintainAspectRatio: false,
                                    scales: { 
                                        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                                        x: { 
                                            grid: { display: false }, 
                                            ticks: { display: true, font: { family: 'DM Sans', size: 10 }, color: '#475569', maxRotation: 45, minRotation: 0 } 
                                        } 
                                    },
                                    plugins: { legend: { display: false } }
                                }
                            });
                        })();
                    @endforeach
                });
            </script>

        @else
            <div class="text-center mb-5 mt-3 reveal">
                <span style="background: white; color: var(--um-maroon); padding: 8px 20px; border-radius: 50px; font-weight: 800; font-size: 12px; letter-spacing: 0.15em; text-transform: uppercase; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">Secure Submission</span>
                <h2 class="dash-title mt-4" style="font-size: 42px;">Student Council Ballot</h2>
                <p class="text-muted fs-6 mt-2">Review the candidates carefully. You MUST select a candidate for every position.</p>
            </div>

            <div class="mx-auto" style="max-width: 800px;">
                <form id="votingForm" action="{{ route('vote.store') }}" method="POST">
                    @csrf
                    @foreach($groupedCandidates as $positionName => $candidates)
                        <div class="dash-card mb-5 reveal" style="transition-delay: {{ $loop->index * 0.1 }}s;">
                            <div class="dash-card-header bg-gradient-maroon d-flex justify-content-between align-items-center">
                                <span>{{ $positionName }}</span>
                                <span style="font-size: 11px; background: rgba(255,255,255,0.2); color: white; padding: 6px 12px; border-radius: 8px; text-transform: uppercase; font-family: 'DM Sans'; font-weight: 700;">Select 1</span>
                            </div>
                            <div class="p-4" style="background: #ffffff;">
                                @foreach($candidates as $candidate)
                                    <label class="ballot-check w-100" for="cand_{{ $candidate->id }}">
                                        <input type="radio" name="votes[{{ $candidate->position_id }}]" id="cand_{{ $candidate->id }}" value="{{ $candidate->id }}">
                                        <div class="custom-radio"></div>
                                        
                                        <div style="width: 60px; height: 60px; background: #f1f5f9; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #94a3b8; flex-shrink: 0; margin-right: 20px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>

                                        <div>
                                            <span style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 20px; color: var(--text-dark); display: block; margin-bottom: 6px;">{{ $candidate->candidate_name }}</span>
                                            <span style="color: #64748b; font-size: 14px; line-height: 1.6;">{{ $candidate->platform_description }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="dash-card p-5 text-center mt-5 reveal" style="background: #f8fafc; border: 2px dashed #cbd5e1;">
                        <h3 class="dash-title mb-3" style="font-size: 26px;">Ready to seal your ballot?</h3>
                        <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">By clicking submit, you confirm your choices. Your identity will be permanently detached from this ballot for total anonymity.</p>
                        <button type="button" onclick="confirmVote()" class="btn-dash-primary w-100 py-3" style="font-size: 16px; max-width: 400px; box-shadow: 0 10px 25px rgba(138,21,56,0.3);">Submit Official Ballot <i class="bi bi-shield-lock-fill ms-2"></i></button>
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