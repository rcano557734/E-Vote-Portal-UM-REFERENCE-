@extends('layouts.app')

@section('content')
<style>
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .dash-card { background: #ffffff; border-radius: 16px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; margin-bottom: 32px;}
    
    .ledger-header { 
        background: #f8fafc; 
        padding: 20px 24px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-history th { 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        color: #64748b; 
        background-color: white; 
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-history td { 
        padding: 16px 24px; 
        border-bottom: 1px solid #f1f5f9; 
        vertical-align: middle; 
    }
    .table-history tr:last-child td { border-bottom: none; }
    .table-history tr:hover td { background-color: #f8fafc; }
</style>

<div class="dash-wrap" style="max-width: 900px; margin: 0 auto;">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="dash-title m-0" style="font-size: 38px;">My Voting Ledger</h2>
            <p class="text-muted mt-2 mb-0">Your secure, personal record of ballots cast in previous UM elections.</p>
        </div>
        <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4"><i class="bi bi-arrow-left me-2"></i> Dashboard</a>
    </div>

    @if($electionHistory->isEmpty())
        <div class="text-center p-5 mx-auto" style="margin-top: 50px; background: white; border-radius: 20px; border: 2px dashed #cbd5e1;">
            <div style="font-size: 50px; color: #94a3b8; margin-bottom: 15px;"><i class="bi bi-inbox"></i></div>
            <h3 class="dash-title fs-4">No Voting History</h3>
            <p class="text-muted">You have not participated in any recorded elections yet.</p>
        </div>
    @else
        @foreach($electionHistory as $date => $votes)
            <div class="dash-card">
                <div class="ledger-header">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 40px; height: 40px; background: #ecfdf5; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">Ballot Officially Sealed</div>
                            <h4 class="m-0" style="font-family: 'Bricolage Grotesque'; font-size: 18px; color: var(--text-dark);">{{ $date }}</h4>
                        </div>
                    </div>
                    <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-clock-history me-1"></i> {{ $votes->first()->created_at->format('h:i A') }}
                    </span>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-borderless table-history m-0 w-100">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Position</th>
                                <th style="width: 35%;">Candidate Selected</th>
                                <th style="width: 25%;">Partylist</th>
                                <th class="text-end" style="width: 15%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($votes as $vote)
                                <tr>
                                    <td>
                                        <span class="fw-bold" style="color: #475569; font-size: 13px;">{{ $vote->candidate->position->position_name }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 28px; height: 28px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; margin-right: 12px; flex-shrink: 0;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <span class="fw-bold" style="color: var(--text-dark); font-size: 15px;">{{ $vote->candidate->candidate_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: var(--um-maroon-light); color: var(--um-maroon); font-size: 11px; border: 1px solid #fbcfe8; padding: 6px 10px; font-weight: 700; text-transform: uppercase;">
                                            {{ $vote->candidate->partylist ?? 'Independent' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <i class="bi bi-shield-lock-fill" style="color: #10b981; font-size: 16px;" title="Vote Encrypted and Secured"></i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection