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
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .live-indicator { display: inline-block; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; margin-right: 6px; animation: pulse-red 2s infinite; }
    
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

    /* NEW: Compact Ballot UI */
    .ballot-check-compact { display: flex; align-items: center; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.2s; background: #f8fafc; }
    .ballot-check-compact:hover { border-color: var(--um-maroon); background: #ffffff; box-shadow: 0 4px 10px rgba(138,21,56,0.05); }
    .ballot-check-compact input[type="radio"] { display: none; }
    .custom-radio-small { width: 22px; height: 22px; border: 2px solid #94a3b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background: white; flex-shrink: 0;}
    .ballot-check-compact input[type="radio"]:checked + .custom-radio-small { border-color: var(--um-maroon); background: var(--um-maroon); box-shadow: 0 0 0 3px rgba(138,21,56, 0.15); }
    .ballot-check-compact input[type="radio"]:checked + .custom-radio-small::after { content: ''; width: 8px; height: 8px; background: var(--um-gold); border-radius: 50%; }
    .ballot-check-compact:has(input[type="radio"]:checked) { border-color: var(--um-maroon); background: var(--um-maroon-light); }

</style>

@php
    $electionStatus = isset($election) ? $election->status : 'pending';
    
    $isWithinWeek = false;
    if ($electionStatus === 'published' && isset($election)) {
        $isWithinWeek = \Carbon\Carbon::parse($election->updated_at)->addDays(7)->isFuture();
    }

    if (!isset($finalTally)) $finalTally = collect();
    if (!isset($maxVotesPerPosition)) $maxVotesPerPosition = [];
@endphp

<div class="dash-wrap">

    @if(auth()->user()->role_id === 1)
        <div class="d-flex justify-content-between align-items-center mb-4 reveal flex-wrap gap-3">
            <h2 class="dash-title m-0" style="font-size: 38px;">Admin Workspace</h2>
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute text-muted" style="top: 50%; transform: translateY(-50%); left: 15px;"></i>
                    <input type="text" id="liveSearch" class="form-control rounded-pill ps-5 py-2" placeholder="Search candidates or partylists..." style="width: 300px; border: 2px solid #e2e8f0; font-size: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                </div>
                <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: {{ $electionStatus == 'active' ? '#10b981' : ($electionStatus == 'closed' || $electionStatus == 'certified' ? '#f59e0b' : '#3b82f6') }}; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    Status: {{ strtoupper($electionStatus) }}
                </span>
            </div>
        </div>

        <div class="dash-card reveal mb-5 p-5 text-center">
            <div style="width: 64px; height: 64px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;"><i class="bi bi-sliders"></i></div>
            <h3 class="dash-title mb-3">Master System Controls</h3>
            <p class="text-muted mb-4 fs-6" style="max-width: 500px; margin: 0 auto;">Control the UM Student Council election flow. Starting the election opens the portal for students to cast their official ballots.</p>
            <div class="d-flex justify-content-center gap-4 mt-4">
                <form action="{{ route('election.toggle') }}" method="POST" class="confirm-form" data-title="Start Election?" data-text="This will open the portal for students to cast their official ballots." data-btn="Yes, Start Election" data-color="#8a1538">
                    @csrf <input type="hidden" name="status" value="active">
                    <button type="submit" class="btn-dash-primary" {{ $electionStatus == 'active' || $electionStatus == 'certified' ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                        <i class="bi bi-play-circle-fill me-2"></i> START ELECTION
                    </button>
                </form>
                
                <form action="{{ route('election.toggle') }}" method="POST" class="confirm-form" data-title="End Election?" data-text="This will close voting and compile results for the Electoral Board." data-btn="Yes, End Election" data-color="#dc2626">
                    @csrf <input type="hidden" name="status" value="closed">
                    <button type="submit" class="btn-dash-danger" {{ $electionStatus == 'closed' || $electionStatus == 'certified' || $electionStatus == 'published' || $electionStatus == 'pending' ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                        <i class="bi bi-stop-circle-fill me-2"></i> END ELECTION
                    </button>
                </form>
                
                <form action="{{ route('election.archive') }}" method="POST" class="confirm-form" data-title="Archive Results?" data-text="This will wipe the dashboard and move the current data to permanent archives." data-btn="Yes, Archive System" data-color="#0f172a">
                    @csrf
                    <button type="submit" class="btn btn-dark fw-bold" style="padding: 14px 28px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" {{ $electionStatus !== 'published' ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                        <i class="bi bi-archive-fill me-2"></i> ARCHIVE RESULTS
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="dash-card reveal" style="transition-delay: 0.2s;">
                    <div class="dash-card-header bg-gradient-maroon d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2 text-white">
                            <i class="bi bi-diagram-3-fill fs-5"></i>
                            <span class="fs-5 m-0" style="font-family: 'Bricolage Grotesque';">Official CCSG Candidates</span>
                        </div>
                        <a href="{{ $electionStatus === 'pending' ? route('candidates.create') : '#' }}" 
                           class="btn btn-sm fw-bold px-3 py-2" 
                           style="background: var(--um-gold); color: #0f172a; border-radius: 8px; {{ $electionStatus !== 'pending' ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                           <i class="bi bi-plus-circle-fill me-1"></i> Register Partylist
                        </a>
                    </div>
                    
                    <div class="table-responsive p-2">
                        <table class="table table-borderless align-middle m-0">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em;">
                                    <th class="ps-4 py-3" style="width: 35%;">College & Partylist</th>
                                    <th style="width: 45%;">Candidate Name</th>
                                    <th class="text-end pe-4" style="width: 20%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($electionStatus === 'pending')
                                    @if($candidates->isEmpty())
                                        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-4 d-block mb-2"></i> No candidates registered yet.</td></tr>
                                    @else
                                        @foreach($candidates as $positionName => $positionCandidates)
                                            
                                            <tr style="background: #f8fafc;">
                                                <td colspan="3" class="fw-bold py-2 ps-4" style="color: var(--um-maroon); font-family: 'Bricolage Grotesque'; font-size: 15px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; border-top: 1px solid #e2e8f0;">
                                                    <i class="bi bi-person-badge-fill me-2" style="color: var(--um-gold-dark);"></i> {{ $positionName }}
                                                </td>
                                            </tr>

                                            @foreach($positionCandidates as $candidate)
                                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                                    <td class="ps-4 py-3">
                                                        <div class="d-flex flex-column align-items-start gap-1">
                                                            <span class="badge" style="background: #1e293b; color: white; font-size: 10px; letter-spacing: 0.05em;">
                                                                <i class="bi bi-building me-1"></i> {{ $candidate->college ?? 'UM' }}
                                                            </span>
                                                            <span class="badge" style="background: var(--um-maroon-light); color: var(--um-maroon); border: 1px solid #fbcfe8; font-weight: 700; font-size: 11px; text-transform: uppercase;">
                                                                <i class="bi bi-flag-fill me-1"></i> {{ $candidate->partylist ?? 'Independent' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="fw-bold text-dark fs-6" style="font-family: 'Bricolage Grotesque';">{{ $candidate->candidate_name }}</td>
                                                    <td class="text-end pe-4">
                                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                                            <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-sm" style="background: #f0fdf4; color: #10b981; border: 1px solid #a7f3d0; border-radius: 8px; padding: 6px 12px;" title="Edit Candidate">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                            
                                                            <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" class="m-0 confirm-form" data-title="Remove Candidate?" data-text="This will archive this candidate record. Proceed?" data-btn="Yes, Remove" data-color="#ef4444">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-sm" style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; border-radius: 8px; padding: 6px 12px;" title="Delete Candidate">
                                                                    <i class="bi bi-trash3-fill"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted" style="background: #f8fafc; border-radius: 12px;">
                                            <i class="bi bi-shield-lock-fill fs-3 d-block mb-2 text-warning"></i> 
                                            The candidate list is securely hidden while the election is <strong>{{ strtoupper($electionStatus) }}</strong>.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @else
        
        @if(auth()->user()->role_id === 2)
            <div class="d-flex justify-content-between align-items-center mb-5 reveal">
                <h2 class="dash-title m-0" style="font-size: 38px;">Integrity Dashboard</h2>
                <span style="background: white; border: 1px solid #cbd5e1; color: #475569; padding: 8px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);"><i class="bi bi-eye-fill me-1 text-warning"></i> Audit Mode</span>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card maroon-stat reveal" style="transition-delay: 0.1s;">
                        <div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 13px; opacity: 0.9;">Total Students</div>
                        <div class="stat-value">{{ $totalVoters ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card gold-stat reveal" style="transition-delay: 0.2s;">
                        <div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 13px; opacity: 0.8; color: #0f172a;">Total Ballots Cast</div>
                        <div class="stat-value">{{ $totalVoted ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card dark-stat reveal" style="transition-delay: 0.3s; cursor: pointer; position: relative; padding-bottom: 45px;" onclick="toggleTurnoutDetails()" id="turnoutCard" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.05)';">
                        <div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 13px; opacity: 0.9; color: #e2e8f0;">Voter Turnout</div>
                        <div class="stat-value text-white">{{ $turnoutPercentage ?? 0 }}%</div>
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
                        @if(isset($votesPerPosition))
                            @foreach($votesPerPosition as $position => $count)
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 bg-white border rounded shadow-sm">
                                    <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 11px; letter-spacing: 0.05em;">{{ $position }}</div>
                                    <div class="fs-4 fw-bold" style="color: var(--um-maroon); font-family: 'Bricolage Grotesque';">{{ $count }} <span class="fs-6 text-muted fw-normal" style="font-family: 'DM Sans';">votes</span></div>
                                </div>
                            </div>
                            @endforeach
                        @endif
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
        @endif

        @if($electionStatus === 'active')
            
            @if(auth()->user()->role_id === 3 && !$hasVoted)
                <div class="text-center mb-5 mt-3 reveal">
                    <span style="background: white; color: var(--um-maroon); padding: 8px 20px; border-radius: 50px; font-weight: 800; font-size: 12px; letter-spacing: 0.15em; text-transform: uppercase; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">Secure Submission</span>
                    <h2 class="dash-title mt-4" style="font-size: 42px;">Student Council Ballot</h2>
                    <p class="text-muted fs-6 mt-2">Review the candidates carefully. You MUST select a candidate for every position.</p>
                    
                    <div class="position-relative mx-auto mt-4" style="max-width: 400px;">
                        <i class="bi bi-search position-absolute text-muted" style="top: 50%; transform: translateY(-50%); left: 16px;"></i>
                        <input type="text" id="liveSearch" class="form-control rounded-pill ps-5 py-3 shadow-sm border-0" placeholder="Search for a specific candidate..." style="font-size: 15px; font-weight: 500;">
                    </div>
                </div>

                <div class="mx-auto" style="max-width: 1000px;">
                    <form id="votingForm" action="{{ route('vote.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            @foreach($groupedCandidates as $positionName => $candidates)
                                <div class="col-md-6">
                                    <div class="dash-card h-100 mb-0 reveal" style="transition-delay: {{ $loop->index * 0.05 }}s;">
                                        
                                        <div class="dash-card-header bg-gradient-maroon d-flex justify-content-between align-items-center py-2 px-3">
                                            <span style="font-size: 15px;">{{ $positionName }}</span>
                                            <span style="font-size: 10px; background: rgba(255,255,255,0.2); color: white; padding: 4px 8px; border-radius: 6px; text-transform: uppercase; font-family: 'DM Sans'; font-weight: 700;">Select 1</span>
                                        </div>
                                        
                                        <div class="p-3" style="background: #ffffff;">
                                            @foreach($candidates as $candidate)
                                                <label class="ballot-check-compact w-100 mb-2" for="cand_{{ $candidate->id }}">
                                                    <input type="radio" name="votes[{{ $candidate->position_id }}]" id="cand_{{ $candidate->id }}" value="{{ $candidate->id }}">
                                                    <div class="custom-radio-small me-3"></div>
                                                    
                                                    <div class="flex-grow-1">
                                                        <span style="font-family: 'Bricolage Grotesque'; font-weight: 700; font-size: 16px; color: var(--text-dark); display: block;">{{ $candidate->candidate_name }}</span>
                                                        <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                                            <span class="badge" style="background: #1e293b; color: white; font-size: 9px; padding: 4px 6px;"><i class="bi bi-building me-1"></i>{{ $candidate->college ?? 'UM' }}</span>
                                                            <span class="badge" style="background: var(--um-maroon-light); color: var(--um-maroon); font-size: 9px; text-transform: uppercase; border: 1px solid #fbcfe8; padding: 4px 6px;"><i class="bi bi-flag-fill me-1"></i>{{ $candidate->partylist ?? 'Independent' }}</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="dash-card p-5 text-center mt-5 reveal" style="background: #f8fafc; border: 2px dashed #cbd5e1;">
                            <h3 class="dash-title mb-3" style="font-size: 26px;">Ready to seal your ballot?</h3>
                            <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">By clicking submit, you confirm your choices. Your identity will be permanently detached from this ballot for total anonymity.</p>
                            <button type="button" onclick="confirmVote()" class="btn-dash-primary w-100 py-3" style="font-size: 16px; max-width: 400px; box-shadow: 0 10px 25px rgba(138,21,56,0.3);">Review & Submit Ballot <i class="bi bi-shield-lock-fill ms-2"></i></button>
                        </div>
                    </form>
                </div>
            
            @else
                
                @if(auth()->user()->role_id === 3 && $hasVoted)
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
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4 reveal">
                    <h4 class="dash-title m-0" style="font-size: 28px;">Live Election Tally</h4>
                    <span class="badge" style="background: white; color: var(--text-dark); border: 1px solid #cbd5e1; font-size: 12px; padding: 8px 14px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <span class="live-indicator"></span> Real-Time Broadcast
                    </span>
                </div>
                
                <div class="row mb-5">
                    @foreach($tally as $positionName => $positionCandidates)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="dash-card reveal m-0 h-100" style="transition-delay: {{ $loop->index * 0.1 }}s; border-top: 4px solid var(--um-maroon);">
                                <div class="text-center py-3" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    <h5 class="m-0" style="font-family: 'Bricolage Grotesque'; font-weight: 800; color: var(--um-maroon-dark); text-transform: uppercase; letter-spacing: 0.05em; font-size: 16px;">{{ $positionName }}</h5>
                                </div>
                                
                                <div class="p-3 bg-white">
                                    @php 
                                        // Find the highest votes in this specific position to calculate the progress bars
                                        $maxVotes = $positionCandidates->max('votes_count');
                                        $maxVotes = $maxVotes > 0 ? $maxVotes : 1; 
                                    @endphp

                                    @foreach($positionCandidates->sortByDesc('votes_count')->values() as $index => $candidate)
                                        @php 
                                            $isLeader = $index === 0 && $candidate->votes_count > 0; 
                                            $percentage = ($candidate->votes_count / $maxVotes) * 100;
                                        @endphp
                                        
                                        <div class="position-relative p-3 mb-3 rounded" style="background: {{ $isLeader ? '#fffbeb' : '#f8fafc' }}; border: 1px solid {{ $isLeader ? 'var(--um-gold)' : '#e2e8f0' }}; overflow: hidden; box-shadow: {{ $isLeader ? '0 4px 12px rgba(217, 119, 6, 0.15)' : 'none' }}; transition: all 0.3s;">
                                            
                                            <div style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $percentage }}%; background: {{ $isLeader ? 'rgba(253, 184, 19, 0.15)' : 'rgba(138, 21, 56, 0.04)' }}; z-index: 0; transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                                            
                                            <div class="d-flex align-items-center position-relative" style="z-index: 1;">
                                                <div class="fw-bold text-center me-3" style="width: 24px; color: {{ $isLeader ? 'var(--um-gold-dark)' : '#94a3b8' }}; font-size: {{ $isLeader ? '20px' : '15px' }};">
                                                    @if($isLeader)
                                                        <i class="bi bi-trophy-fill drop-shadow"></i>
                                                    @else
                                                        #{{ $index + 1 }}
                                                    @endif
                                                </div>
                                                
                                                <div class="flex-grow-1">
                                                    <span class="fw-bold text-dark d-block mb-1" style="font-family: 'Bricolage Grotesque'; font-size: 16px;">{{ $candidate->candidate_name }}</span>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge" style="background: #1e293b; color: white; font-size: 9px; padding: 4px 6px;">
                                                            {{ $candidate->college ?? 'UM' }}
                                                        </span>
                                                        <span class="badge" style="background: {{ $isLeader ? 'var(--um-gold)' : 'var(--um-maroon-light)' }}; color: {{ $isLeader ? '#0f172a' : 'var(--um-maroon)' }}; font-size: 9px; padding: 4px 6px; {{ !$isLeader ? 'border: 1px solid #fbcfe8;' : '' }}">
                                                            {{ $candidate->partylist ?? 'IND' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-end ms-2">
                                                    <div style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 24px; color: {{ $isLeader ? 'var(--um-gold-dark)' : 'var(--text-dark)' }}; line-height: 1;">
                                                        {{ number_format($candidate->votes_count) }}
                                                    </div>
                                                    <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-top: 4px; letter-spacing: 0.05em;">
                                                        {{ $candidate->votes_count === 1 ? 'Vote' : 'Votes' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(auth()->user()->role_id === 2)
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
                                        <canvas id="liveChart_{{ $loop->index }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            @foreach($tally as $positionName => $positionCandidates)
                                (function() {
                                    const ctx = document.getElementById('liveChart_{{ $loop->index }}').getContext('2d');
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
                @endif
                @endif

        @elseif($electionStatus === 'published' && $isWithinWeek)

        {{-- ═══════════════════════════════════════════════════════
             PUBLISHED RESULTS — Tabbed UI (no endless scrolling)
        ═══════════════════════════════════════════════════════ --}}
        <style>
            /* ── Results page styles ─────────────────────────────── */
            .results-hero {
                background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark));
                border-radius: 20px; padding: 32px 28px 24px;
                margin-bottom: 28px; position: relative; overflow: hidden;
            }
            .results-hero::before {
                content:''; position:absolute; top:-40px; right:-40px;
                width:200px; height:200px; background:rgba(253,184,19,.1); border-radius:50%;
            }
            .results-hero::after {
                content:''; position:absolute; bottom:-60px; left:-30px;
                width:250px; height:250px; background:rgba(255,255,255,.04); border-radius:50%;
            }

            /* Winner podium cards */
            .winners-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 12px;
                margin-bottom: 28px;
            }
            .winner-chip {
                background: var(--bg-surface);
                border: 1.5px solid var(--border-col);
                border-radius: 14px; padding: 14px 16px;
                display: flex; align-items: center; gap: 12px;
                transition: all .2s; cursor: default;
                position: relative; overflow: hidden;
            }
            .winner-chip::before {
                content:''; position:absolute; left:0; top:0; bottom:0;
                width:4px; background: var(--um-gold); border-radius:4px 0 0 4px;
            }
            .winner-chip:hover { transform:translateY(-2px); box-shadow: 0 6px 18px rgba(138,21,56,.1); border-color: var(--um-gold); }
            .winner-avatar {
                width:40px; height:40px; border-radius:50%;
                background: linear-gradient(135deg,var(--um-maroon),var(--um-maroon-dark));
                color: var(--um-gold); display:flex; align-items:center; justify-content:center;
                font-size:16px; flex-shrink:0;
            }

            /* Position tabs */
            .pos-tabs-wrap {
                background: var(--bg-surface);
                border: 1px solid var(--border-col);
                border-radius: 16px; overflow: hidden;
                margin-bottom: 0;
            }
            .pos-tabs-header {
                display: flex; overflow-x: auto; gap: 0;
                background: var(--bg-surface-alt);
                border-bottom: 2px solid var(--border-col);
                padding: 0 4px;
                scrollbar-width: thin;
                -webkit-overflow-scrolling: touch;
            }
            .pos-tabs-header::-webkit-scrollbar { height: 3px; }
            .pos-tab-btn {
                padding: 13px 18px; font-size: 13px; font-weight: 700;
                color: var(--text-muted); background: transparent; border: none;
                border-bottom: 3px solid transparent; cursor: pointer;
                white-space: nowrap; transition: all .2s; font-family:'DM Sans',sans-serif;
                margin-bottom: -2px; flex-shrink: 0;
            }
            .pos-tab-btn:hover  { color: var(--um-maroon); background: var(--um-maroon-light); }
            .pos-tab-btn.active { color: var(--um-maroon); border-bottom-color: var(--um-maroon); background: var(--bg-surface); font-weight:800; }

            /* Tab panels */
            .pos-tab-panel { display: none; padding: 24px; }
            .pos-tab-panel.active { display: block; }

            /* Candidate result row */
            .result-row {
                display: flex; align-items: center; gap: 14px;
                padding: 14px 18px; border-radius: 12px;
                margin-bottom: 10px; position: relative; overflow: hidden;
                border: 1.5px solid var(--border-col);
                background: var(--bg-surface);
                transition: all .2s;
            }
            .result-row.winner {
                border-color: var(--um-gold) !important;
                background: #fffbeb;
            }
            [data-theme="dark"] .result-row.winner { background: #2a1f00; }
            .result-row:hover { transform: translateX(4px); }

            /* Animated fill bar behind row */
            .result-row-fill {
                position: absolute; left:0; top:0; bottom:0;
                background: rgba(138,21,56,.05);
                border-radius: 10px; transition: width 1.2s cubic-bezier(.4,0,.2,1);
                z-index: 0;
            }
            .result-row.winner .result-row-fill { background: rgba(253,184,19,.12); }
            .result-row > * { position: relative; z-index: 1; }

            .rank-badge {
                width: 30px; height: 30px; border-radius: 50%;
                display: flex; align-items:center; justify-content:center;
                font-weight:800; font-size:13px; flex-shrink:0;
            }
            .rank-1 { background: var(--um-gold); color:#0f172a; }
            .rank-2 { background: #e2e8f0; color:#475569; }
            .rank-3 { background: #fde68a; color:#92400e; }
            .rank-n { background: var(--bg-surface-alt); color:var(--text-muted); border:1px solid var(--border-col); }

            /* Responsive */
            @media (max-width: 600px) {
                .winners-grid { grid-template-columns: 1fr 1fr; }
                .pos-tab-btn { padding: 11px 12px; font-size:12px; }
                .result-row  { padding: 12px 14px; gap: 10px; }
                .results-hero { padding: 22px 18px 18px; }
            }
            @media (max-width: 380px) {
                .winners-grid { grid-template-columns: 1fr; }
            }
        </style>

        {{-- Hero banner --}}
        <div class="results-hero reveal">
            <div style="position:relative;z-index:1;">
                <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                    <div style="width:50px;height:50px;background:rgba(253,184,19,.2);border:2px solid rgba(253,184,19,.4);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--um-gold);">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:800;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.1em;">University of Mindanao</div>
                        <h2 style="font-family:'Bricolage Grotesque';font-weight:800;color:white;font-size:clamp(22px,4vw,36px);margin:0;letter-spacing:-0.02em;">Official Election Results</h2>
                    </div>
                </div>
                <p style="color:rgba(255,255,255,.7);font-size:14px;margin:0;">
                    Certified and published by the Electoral Board. Browse winners by position using the tabs below.
                </p>
            </div>
        </div>

        {{-- Winner Podium Grid --}}
        <div class="mb-3 reveal" style="transition-delay:.1s;">
            <div style="font-size:11px;font-weight:800;color:var(--text-faint);text-transform:uppercase;letter-spacing:.1em;margin-bottom:12px;">
                <i class="bi bi-star-fill me-1" style="color:var(--um-gold);"></i> Elected Officials at a Glance
            </div>
            <div class="winners-grid">
                @foreach($finalTally as $positionName => $positionCandidates)
                    @php $winner = $positionCandidates->sortByDesc('votes_count')->first(); @endphp
                    @if($winner && $winner->votes_count > 0)
                    <div class="winner-chip">
                        <div class="winner-avatar"><i class="bi bi-person-fill"></i></div>
                        <div style="min-width:0;">
                            <div style="font-size:10px;font-weight:800;color:var(--text-faint);text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $positionName }}</div>
                            <div style="font-family:'Bricolage Grotesque';font-weight:800;font-size:14px;color:var(--text-heading);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $winner->candidate_name }}</div>
                            <div style="font-size:11px;color:var(--um-maroon);font-weight:700;margin-top:1px;">{{ number_format($winner->votes_count) }} votes</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Tabbed detail view --}}
        <div class="pos-tabs-wrap reveal" style="transition-delay:.15s;">
            {{-- Tab buttons --}}
            <div class="pos-tabs-header" id="posTabs" role="tablist">
                @foreach($finalTally as $positionName => $positionCandidates)
                    @php
                        $tabId   = 'tab_' . $loop->index;
                        $panelId = 'panel_' . $loop->index;
                        $winner  = $positionCandidates->sortByDesc('votes_count')->first();
                    @endphp
                    <button class="pos-tab-btn {{ $loop->first ? 'active' : '' }}"
                            id="{{ $tabId }}"
                            data-panel="{{ $panelId }}"
                            onclick="switchTab('{{ $tabId }}','{{ $panelId }}')"
                            role="tab"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            aria-controls="{{ $panelId }}">
                        {{ $positionName }}
                        @if($winner && $winner->votes_count > 0)
                            <span style="display:inline-block;width:6px;height:6px;background:var(--um-gold);border-radius:50%;margin-left:5px;vertical-align:middle;"></span>
                        @endif
                    </button>
                @endforeach
            </div>

            {{-- Tab panels --}}
            @foreach($finalTally as $positionName => $positionCandidates)
                @php
                    $panelId  = 'panel_' . $loop->index;
                    $maxVotes = isset($maxVotesPerPosition[$positionName]) ? $maxVotesPerPosition[$positionName] : 1;
                    $maxVotes = $maxVotes > 0 ? $maxVotes : 1;
                    $sorted   = $positionCandidates->sortByDesc('votes_count')->values();
                    $totalPos = $sorted->sum('votes_count');
                @endphp
                <div class="pos-tab-panel {{ $loop->first ? 'active' : '' }}"
                     id="{{ $panelId }}"
                     role="tabpanel"
                     aria-labelledby="tab_{{ $loop->index }}">

                    {{-- Position summary header --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-3"
                         style="border-bottom:2px solid var(--border-col);">
                        <div>
                            <h4 style="font-family:'Bricolage Grotesque';font-weight:800;font-size:clamp(16px,3vw,22px);color:var(--text-heading);margin:0;text-transform:uppercase;letter-spacing:.04em;">
                                {{ $positionName }}
                            </h4>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:3px;">
                                {{ $sorted->count() }} candidate(s) &bull; {{ number_format($totalPos) }} total votes cast
                            </div>
                        </div>
                        @php $topWinner = $sorted->first(); @endphp
                        @if($topWinner && $topWinner->votes_count > 0)
                            <span style="background:var(--um-maroon-light);color:var(--um-maroon);border:1px solid #fbcfe8;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;">
                                <i class="bi bi-trophy-fill me-1" style="color:var(--um-gold-dark);"></i>
                                {{ $topWinner->candidate_name }}
                            </span>
                        @endif
                    </div>

                    {{-- Candidate result rows --}}
                    @foreach($sorted as $index => $candidate)
                        @php
                            $pct      = round(($candidate->votes_count / $maxVotes) * 100);
                            $isWinner = $index === 0 && $candidate->votes_count > 0;
                            $rankClass = match($index){ 0=>'rank-1', 1=>'rank-2', 2=>'rank-3', default=>'rank-n' };
                            $sharePct  = $totalPos > 0 ? round(($candidate->votes_count / $totalPos) * 100, 1) : 0;
                        @endphp
                        <div class="result-row {{ $isWinner ? 'winner' : '' }}" data-width="{{ $pct }}">
                            <div class="result-row-fill" style="width:0%;"></div>

                            {{-- Rank badge --}}
                            <div class="rank-badge {{ $rankClass }}">
                                @if($isWinner) <i class="bi bi-trophy-fill" style="font-size:12px;"></i>
                                @else {{ $index + 1 }} @endif
                            </div>

                            {{-- Name + partylist --}}
                            <div class="flex-grow-1" style="min-width:0;">
                                <div style="font-family:'Bricolage Grotesque';font-weight:800;font-size:clamp(14px,2.5vw,17px);color:var(--text-heading);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $candidate->candidate_name }}
                                    @if($isWinner) <i class="bi bi-patch-check-fill ms-1" style="color:var(--um-maroon);font-size:13px;"></i> @endif
                                </div>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @if($candidate->partylist)
                                        <span style="font-size:10px;font-weight:700;background:var(--um-maroon-light);color:var(--um-maroon);border:1px solid #fbcfe8;padding:2px 8px;border-radius:20px;">
                                            {{ $candidate->partylist }}
                                        </span>
                                    @endif
                                    @if($candidate->college)
                                        <span style="font-size:10px;font-weight:700;background:var(--bg-surface-alt);color:var(--text-muted);border:1px solid var(--border-col);padding:2px 8px;border-radius:20px;">
                                            {{ $candidate->college }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Progress bar --}}
                            <div style="flex:1;min-width:60px;max-width:180px;">
                                <div style="width:100%;height:7px;background:var(--border-col);border-radius:4px;overflow:hidden;">
                                    <div class="bar-fill" data-pct="{{ $pct }}"
                                         style="height:100%;width:0%;background:{{ $isWinner ? 'var(--um-gold)' : 'var(--um-maroon)' }};border-radius:4px;transition:width 1.2s cubic-bezier(.4,0,.2,1);"></div>
                                </div>
                                <div style="font-size:10px;color:var(--text-muted);font-weight:700;margin-top:3px;text-align:right;">{{ $sharePct }}% share</div>
                            </div>

                            {{-- Vote count --}}
                            <div class="text-end" style="min-width:64px;flex-shrink:0;">
                                <div style="font-family:'Bricolage Grotesque';font-weight:800;font-size:clamp(18px,3vw,24px);color:{{ $isWinner ? 'var(--um-gold-dark)' : 'var(--text-heading)' }};line-height:1;">
                                    {{ number_format($candidate->votes_count) }}
                                </div>
                                <div style="font-size:10px;color:var(--text-faint);text-transform:uppercase;font-weight:700;letter-spacing:.05em;">
                                    {{ $candidate->votes_count === 1 ? 'vote' : 'votes' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <script>
        // ── Tab switcher ──────────────────────────────────────────────
        function switchTab(tabId, panelId) {
            // Deactivate all
            document.querySelectorAll('.pos-tab-btn').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected','false');
            });
            document.querySelectorAll('.pos-tab-panel').forEach(p => p.classList.remove('active'));

            // Activate chosen
            const btn   = document.getElementById(tabId);
            const panel = document.getElementById(panelId);
            btn.classList.add('active');
            btn.setAttribute('aria-selected','true');
            panel.classList.add('active');

            // Scroll active tab into view on mobile
            btn.scrollIntoView({ behavior:'smooth', block:'nearest', inline:'center' });

            // Animate bars in new panel
            setTimeout(() => animateBars(panel), 60);
        }

        // ── Animate progress bars ─────────────────────────────────────
        function animateBars(container) {
            container.querySelectorAll('.bar-fill').forEach(bar => {
                bar.style.width = bar.dataset.pct + '%';
            });
            container.querySelectorAll('.result-row-fill').forEach(fill => {
                const row = fill.closest('.result-row');
                fill.style.width = (row.dataset.width || 0) + '%';
            });
        }

        // Animate first panel on load
        document.addEventListener('DOMContentLoaded', function () {
            const firstPanel = document.querySelector('.pos-tab-panel.active');
            if (firstPanel) setTimeout(() => animateBars(firstPanel), 400);
        });

        // Keyboard navigation for tabs
        document.getElementById('posTabs').addEventListener('keydown', function(e) {
            const tabs   = [...this.querySelectorAll('.pos-tab-btn')];
            const active = tabs.findIndex(t => t.classList.contains('active'));
            if (e.key === 'ArrowRight' && active < tabs.length - 1) tabs[active+1].click();
            if (e.key === 'ArrowLeft'  && active > 0)               tabs[active-1].click();
        });
        </script>

        @elseif($electionStatus === 'published' && !$isWithinWeek)
            <div class="dash-card mx-auto reveal text-center p-5" style="max-width: 600px; margin-top: 60px; border-top: 6px solid #94a3b8;">
                <div style="width: 90px; height: 90px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 24px;"><i class="bi bi-archive-fill"></i></div>
                <h2 class="dash-title" style="font-size: 32px;">Results Archived</h2>
                <p class="text-muted fs-6 mt-3">
                    The results for the previous election have been archived as 7 days have passed since publication. Please wait for the next election cycle to begin.
                </p>
            </div>

        @else
            <div class="dash-card mx-auto reveal text-center p-5" style="max-width: 600px; margin-top: 60px; border-top: 6px solid #94a3b8;">
                <div style="width: 90px; height: 90px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 24px;"><i class="bi bi-lock-fill"></i></div>
                <h2 class="dash-title" style="font-size: 32px;">Election Status: {{ ucfirst($electionStatus) }}</h2>
                <p class="text-muted fs-6 mt-3">
                    @if($electionStatus === 'pending') The polls have not opened yet. Please wait for the UM Administrator to officially start the election.
                    @elseif($electionStatus === 'closed' || $electionStatus === 'certified') The polls are officially closed. The results are currently being audited by the Electoral Board and await Admin publication. 
                    @else The live tally is currently offline. @endif
                </p>
            </div>
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
        
        @if(session('error')) Swal.fire('Oops!', "{{ session('error') }}", 'error'); @endif
        @if(session('voted_success')) Swal.fire({ title: 'Success!', text: "{{ session('voted_success') }}", icon: 'success', confirmButtonColor: '#8a1538' }); @endif
        @if(session('success') && !session('voted_success')) Swal.fire('Updated', "{{ session('success') }}", 'success'); @endif
    });
</script>

<script>
    // Beautiful SweetAlert replacements for default browser confirms
    // Beautiful SweetAlert replacements for default browser confirms
    function confirmVote() {
        // 1. Error Trapping: Gather all radio button groups
        const allRadioGroups = new Set([...document.querySelectorAll('#votingForm input[type="radio"]')].map(r => r.name));
        const selectedRadios = document.querySelectorAll('#votingForm input[type="radio"]:checked');

        // Check if the user missed any positions
        if (selectedRadios.length < allRadioGroups.size) {
            Swal.fire({
                title: 'Incomplete Ballot!',
                text: "You must select a candidate for every position before submitting your official ballot.",
                icon: 'error',
                confirmButtonColor: '#8a1538'
            });
            return; // Stop the submission process
        }

        // 2. Build the UM-Style Summary Table dynamically
        let summaryHTML = `
            <div style="text-align: left; margin-top: 10px;">
                <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 15px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">Your Verified Selections</div>
                <table style="width: 100%; border-collapse: collapse; font-family: 'DM Sans', sans-serif;">
                    <tbody>
        `;

        selectedRadios.forEach(radio => {
            const label = radio.closest('label');
            
            // FIX: We now look for the custom font name instead of the font size!
            const candidateName = label.querySelector('span[style*="Bricolage Grotesque"]').innerText;
            const positionName = radio.closest('.dash-card').querySelector('.dash-card-header span:first-child').innerText;

            summaryHTML += `
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 0; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; width: 45%;">${positionName}</td>
                            <td style="padding: 12px 0; color: var(--um-maroon); font-size: 16px; font-weight: bold; font-family: 'Bricolage Grotesque';">${candidateName}</td>
                        </tr>
            `;
        });

        summaryHTML += `
                    </tbody>
                </table>
                <div style="margin-top: 20px; font-size: 13px; color: #ef4444; background: #fef2f2; padding: 12px 16px; border-radius: 8px; border: 1px dashed #fecaca; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <span style="font-weight: 600;">Warning: You cannot change or edit your vote once it is sealed and submitted to the server.</span>
                </div>
            </div>
        `;

        // 3. Display the beautifully styled Pre-Submission Modal
        Swal.fire({
            title: '<span style="font-family: \'Bricolage Grotesque\'; font-weight: 800; font-size: 28px; color: var(--text-dark);">Review Your Ballot</span>',
            html: summaryHTML,
            showCancelButton: true,
            confirmButtonColor: '#8a1538', 
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Submit Final Ballot <i class="bi bi-shield-lock-fill ms-2"></i>',
            cancelButtonText: 'Back to Editing',
            width: '600px', 
            customClass: {
                popup: 'rounded-4 shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form to the backend
                document.getElementById('votingForm').submit();
            }
        })
    }
</script>

<script>
    // Real-time Search Engine
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('liveSearch');
        if(searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                
                // For Admin Table Rows
                const tableRows = document.querySelectorAll('tbody tr');
                tableRows.forEach(row => {
                    // Don't filter the position headers or empty states
                    if(row.cells.length > 1 && !row.hasAttribute('colspan')) {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(term) ? '' : 'none';
                    }
                });

                // For Voter Ballot Cards
                const ballotCards = document.querySelectorAll('.ballot-check-compact');
                ballotCards.forEach(card => {
                    const text = card.innerText.toLowerCase();
                    card.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection