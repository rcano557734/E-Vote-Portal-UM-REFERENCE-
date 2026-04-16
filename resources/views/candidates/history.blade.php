@extends('layouts.app')

@section('content')
<style>
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }
    
    .dash-card { 
        background: #ffffff; 
        border-radius: 20px; 
        border: 1px solid #cbd5e1; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
        overflow: hidden; 
        margin-bottom: 32px; 
    }
    .dash-card:hover { 
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #94a3b8; 
    }
    .dash-card-header { 
        padding: 20px 28px; 
        font-family: 'Bricolage Grotesque', sans-serif; 
        font-weight: 800; 
        font-size: 17px; 
        background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); 
        color: #ffffff; 
        letter-spacing: 0.03em;
    }
</style>

<div class="dash-wrap">
    <div class="d-flex justify-content-between align-items-center mb-5 reveal">
        <div>
            <h2 class="dash-title m-0" style="font-size: 38px;">Election History</h2>
            <p class="text-muted mt-2 mb-0">Review your past voting records and verified student ballots.</p>
        </div>
        <div style="width: 56px; height: 56px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 4px 10px rgba(138,21,56,0.1);">
            <i class="bi bi-clock-history"></i>
        </div>
    </div>

    <div class="mx-auto" style="max-width: 800px;">
        @forelse($electionHistory as $date => $votes)
            <div class="dash-card reveal" style="transition-delay: {{ $loop->index * 0.1 }}s;">
                <div class="dash-card-header d-flex justify-content-between align-items-center">
                    <span>UM Student Council Election</span>
                    <span class="badge" style="background: var(--um-gold); color: #0f172a; font-family: 'DM Sans'; font-weight: 800;"><i class="bi bi-calendar-event-fill me-1"></i> {{ $date }}</span>
                </div>
                <div class="p-4" style="background: #f8fafc;">
                    <h6 class="fw-bold mb-4" style="color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 13px;">Your Official Selections:</h6>
                    
                    <div class="row g-3">
                        @foreach($votes as $vote)
                            <div class="col-md-6">
                                <div class="p-3 bg-white border rounded d-flex align-items-center" style="border-color: #e2e8f0 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                    <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #94a3b8; margin-right: 15px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <span style="font-size: 11px; color: var(--um-maroon); font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 2px;">{{ $vote->candidate->position->position_name }}</span>
                                        <span style="font-family: 'Bricolage Grotesque'; font-weight: 700; color: var(--text-dark); font-size: 16px;">{{ $vote->candidate->candidate_name }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="dash-card reveal text-center p-5 mt-4" style="border-top: 6px solid #94a3b8;">
                <div style="width: 80px; height: 80px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 35px; margin: 0 auto 20px;"><i class="bi bi-inbox"></i></div>
                <h3 class="dash-title">No History Found</h3>
                <p class="text-muted">You have not participated in any UM elections yet. Your future ballots will appear here.</p>
            </div>
        @endforelse
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