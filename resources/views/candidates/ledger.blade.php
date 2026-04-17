@extends('layouts.app')

@section('content')
<style>
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }
    
    .dash-card { background: #ffffff; border-radius: 20px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; margin-bottom: 32px; }
    
    /* Elegant UM Log Event Window */
    .um-log-window { background: #ffffff; border-radius: 16px; padding: 0; height: 650px; overflow-y: auto; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.02);}
    .log-header { background: #f8fafc; padding: 16px 24px; border-bottom: 2px solid var(--um-maroon); position: sticky; top: 0; z-index: 10; display: flex; align-items: center; justify-content: space-between;}
    .log-entry { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 16px; transition: background 0.2s;}
    .log-entry:hover { background: #fdf2f5; }
    .log-icon { width: 32px; height: 32px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;}
    
    /* Custom Scrollbar */
    .um-log-window::-webkit-scrollbar { width: 6px; }
    .um-log-window::-webkit-scrollbar-track { background: #f8fafc; }
    .um-log-window::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    
    /* Progress Bar Styling */
    .vote-progress-track { width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-top: 8px;}
    .vote-progress-fill { height: 100%; background: var(--um-maroon); border-radius: 4px; transition: width 1s ease-out;}
    .rank-circle { width: 28px; height: 28px; background: var(--um-maroon); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; font-family: 'DM Sans'; flex-shrink: 0;}
</style>

<div class="dash-wrap">
    <div class="d-flex justify-content-between align-items-center mb-5 reveal">
        <div>
            <h2 class="dash-title m-0" style="font-size: 38px;">Audit Ledger</h2>
            <p class="text-muted mt-2 mb-0">System-wide transparency records and final election tallies.</p>
        </div>
        <div style="background: white; border: 1px solid #cbd5e1; padding: 10px 20px; border-radius: 50px; font-weight: 800; font-size: 13px; color: var(--text-dark); box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            ELECTION STATUS: 
            <span class="{{ $electionStatus === 'closed' || $electionStatus === 'certified' || $electionStatus === 'published' ? 'text-danger' : 'text-success' }} ms-1">
                {{ strtoupper($electionStatus) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold reveal"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-5 col-md-12 reveal">
            <h4 class="dash-title mb-3 fs-5"><i class="bi bi-card-list me-2 text-muted"></i> System Event Ledger</h4>
            <div class="um-log-window">
                <div class="log-header">
                    <span style="font-family: 'Bricolage Grotesque'; font-weight: 800; color: var(--text-dark);"><i class="bi bi-shield-check text-success me-2"></i> UM E-Vote Security Core</span>
                    <span class="badge bg-light text-muted border">Live Feed</span>
                </div>
                
                @forelse($logs as $log)
                    <div class="log-entry">
                        <div class="log-icon"><i class="bi bi-fingerprint"></i></div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold" style="color: var(--text-dark); font-size: 14px;">{{ $log->user->name }}</span>
                                <span class="text-muted" style="font-size: 11px; font-family: monospace;">{{ $log->created_at->format('M d, H:i:s') }}</span>
                            </div>
                            <div style="color: #475569; font-size: 13px; line-height: 1.5;">{{ $log->action_description }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center text-muted">No system events recorded yet.</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-7 col-md-12 reveal" style="transition-delay: 0.2s;">
            <h4 class="dash-title mb-3 fs-5"><i class="bi bi-file-earmark-lock-fill me-2 text-muted"></i> Certified Election Results</h4>
            
            <div class="dash-card p-0" style="height: 650px; display: flex; flex-direction: column;">
                @if($electionStatus === 'pending' || $electionStatus === 'active')
                    <div class="text-center" style="margin: auto; padding: 40px;">
                        <div style="width: 80px; height: 80px; background: #f1f5f9; color: #94a3b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px;"><i class="bi bi-lock-fill"></i></div>
                        <h3 class="dash-title fs-4">Results Encrypted & Sealed</h3>
                        <p class="text-muted">The final certified results cannot be compiled or viewed until the UM Administrator officially closes the election.</p>
                        <div class="mt-4 p-3 rounded text-start" style="background: var(--um-maroon-light); border: 1px solid #fbcfe8; font-size: 13px; color: var(--um-maroon);">
                            <strong><i class="bi bi-info-circle-fill me-1"></i> Security Protocol:</strong> To maintain cryptographic anonymity and prevent early bias, final tallies are completely hidden until polls officially close.
                        </div>
                    </div>
                @else
                    <div class="p-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); color: white;">
                        <span style="font-weight: 700; font-family: 'DM Sans'; letter-spacing: 0.05em;"><i class="bi bi-patch-check-fill me-2 text-warning"></i> OFFICIAL TALLY</span>
                        <span style="font-family: 'DM Sans'; font-size: 12px; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px;">Total Ballots: {{ $totalBallots }}</span>
                    </div>

                    @if($electionStatus === 'closed')
                        <div class="p-4 text-center border-bottom" style="background: #fdf2f5;">
                            <h5 class="dash-title fs-5 mb-2 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Action Required</h5>
                            <p class="text-muted fs-6 mb-3">Review the final tallies below. Once verified, send the certified data to Administration for publishing.</p>
                            <form action="{{ route('election.certify') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger fw-bold px-4 py-2" style="border-radius: 8px; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);">
                                    Certify & Send Results to Admin <i class="bi bi-send-fill ms-2"></i>
                                </button>
                            </form>
                        </div>
                    @elseif($electionStatus === 'certified' || $electionStatus === 'published')
                        <div class="p-3 text-center border-bottom bg-light text-success fw-bold">
                            <i class="bi bi-check-circle-fill me-2"></i> Results Certified and Sent. Awaiting Admin Publication.
                        </div>
                    @endif
                    
                    <div class="p-4" style="overflow-y: auto; flex-grow: 1; background: #ffffff;">
                        @foreach($finalTally as $positionName => $positionCandidates)
                            <div class="mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2" style="border-bottom: 2px solid #e2e8f0;">
                                    <h4 class="m-0" style="font-family: 'Bricolage Grotesque'; font-weight: 800; color: var(--text-dark); text-transform: uppercase;">{{ $positionName }}</h4>
                                    <span class="text-muted" style="font-size: 11px;">As of {{ now()->format('M d, Y H:i') }}</span>
                                </div>
                                
                                <div class="px-2">
                                    @foreach($positionCandidates->sortByDesc('votes_count') as $index => $candidate)
                                        @php
                                            // Calculate percentage for the progress bar
                                            $maxVotes = isset($maxVotesPerPosition[$positionName]) ? $maxVotesPerPosition[$positionName] : 1;
                                            $percentage = ($candidate->votes_count / $maxVotes) * 100;
                                        @endphp
                                        
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="rank-circle">{{ $index + 1 }}</div>
                                            
                                            <div class="ms-3 flex-grow-1 pe-4">
                                                <div class="d-flex justify-content-between align-items-end mb-1">
                                                    <span style="font-family: 'DM Sans'; font-size: 16px; font-weight: 700; color: var(--text-dark);">{{ $candidate->candidate_name }}</span>
                                                    @if($index === 0 && $candidate->votes_count > 0)
                                                        <i class="bi bi-star-fill text-warning" style="font-size: 12px;"></i>
                                                    @endif
                                                </div>
                                                <div class="vote-progress-track">
                                                    <div class="vote-progress-fill" style="width: {{ $percentage }}%;"></div>
                                                </div>
                                            </div>

                                            <div class="text-end" style="min-width: 80px;">
                                                <div style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 18px; color: var(--text-dark);">
                                                    {{ number_format($candidate->votes_count) }}
                                                </div>
                                                <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Votes</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="p-3 border-top text-center" style="background: #f8fafc; font-size: 12px; color: #64748b;">
                        <i class="bi bi-shield-check me-1"></i> Data mathematically verified and cryptographically sealed by UM E-Vote System.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const reveals = document.querySelectorAll(".reveal");
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add("active"); });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        reveals.forEach(reveal => observer.observe(reveal));
    });
</script>
@endsection