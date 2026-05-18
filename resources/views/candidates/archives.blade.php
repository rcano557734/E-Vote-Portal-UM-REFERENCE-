@extends('layouts.app')

@section('content')
<style>
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .dash-card { background: #ffffff; border-radius: 20px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; margin-bottom: 40px;}
    
    /* FIX: Official UM Theme Header */
    .cycle-header { 
        background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); 
        color: white; 
        padding: 20px 30px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        border-bottom: 4px solid var(--um-gold); 
    }
    
    .table-archive th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; }
    .table-archive td { padding: 16px 0; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-archive tr:last-child td { border-bottom: none; }
</style>

<div class="dash-wrap">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="dash-title m-0" style="font-size: 38px;">Historical Ledger</h2>
            <p class="text-muted mt-2 mb-0">Permanent records of all past Student Council elections.</p>
        </div>
        <span class="badge bg-white text-dark border px-4 py-2 fs-6 rounded-pill"><i class="bi bi-server text-muted me-2"></i> System Archives</span>
    </div>

    @if($archivedCandidates->isEmpty())
        <div class="text-center p-5 mx-auto" style="max-width: 600px; margin-top: 50px; background: white; border-radius: 20px; border: 2px dashed #cbd5e1;">
            <div style="font-size: 50px; color: #94a3b8; margin-bottom: 15px;"><i class="bi bi-inbox-fill"></i></div>
            <h3 class="dash-title fs-4">No Archived Data</h3>
            <p class="text-muted">There are no historical election records stored in the database yet. When an active election is archived, the data will appear here.</p>
        </div>
    @else
        @foreach($archivedCandidates as $cycleDate => $candidates)
            <div class="dash-card">
                <div class="cycle-header">
                    <div>
                        <div style="font-size: 12px; font-weight: 700; letter-spacing: 0.1em; opacity: 0.8; text-transform: uppercase; margin-bottom: 4px; color: #fbcfe8;">Official Election Record</div>
                        <h3 class="m-0" style="font-family: 'Bricolage Grotesque'; font-size: 24px;">{{ $cycleDate }}</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(253, 184, 19, 0.15); color: var(--um-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>

                <div class="p-4 bg-white table-responsive">
                    <table class="table table-borderless table-archive m-0" style="min-width: 800px;">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 25%;">Candidate Name</th>
                                <th style="width: 20%;">Position Ran For</th>
                                <th style="width: 35%;">Platform / Description</th>
                                <th class="text-end pe-3" style="width: 20%;">Final Votes Recorded</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groupedByPosition = $candidates->groupBy('position.position_name');
                            @endphp

                            @foreach($groupedByPosition as $positionName => $positionCandidates)
                                @php
                                    $sortedCandidates = $positionCandidates->sortByDesc('votes_count')->values();
                                @endphp

                                @foreach($sortedCandidates as $index => $candidate)
                                    @php 
                                        $isWinner = $index === 0 && $candidate->votes_count > 0; 
                                    @endphp
                                    <tr style="background: {{ $isWinner ? '#fdf8e7' : 'transparent' }}; border-radius: 8px;">
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div style="width: 32px; height: 32px; background: {{ $isWinner ? 'var(--um-gold)' : '#f1f5f9' }}; color: {{ $isWinner ? '#0f172a' : '#64748b' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-right: 12px; flex-shrink: 0;">
                                                    @if($isWinner)
                                                        <i class="bi bi-trophy-fill"></i>
                                                    @else
                                                        {{ $index + 1 }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold" style="color: var(--text-dark); font-size: 15px;">{{ $candidate->candidate_name }}</div>
                                                    @if($isWinner)
                                                        <div style="font-size: 10px; font-weight: 800; color: var(--um-gold-dark); text-transform: uppercase; margin-top: 2px;">Elected Official</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-3 py-2">{{ $positionName }}</span>
                                        </td>
                                        <td>
                                            <div class="text-muted" style="font-size: 13px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $candidate->platform_description }}">
                                                {{ $candidate->platform_description }}
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="fw-bold fs-5" style="font-family: 'Bricolage Grotesque'; color: {{ $isWinner ? 'var(--um-maroon)' : '#475569' }};">
                                                {{ number_format($candidate->votes_count) }}
                                            </span>
                                            <span class="text-muted ms-1" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ $candidate->votes_count === 1 ? 'Vote' : 'Votes' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection 