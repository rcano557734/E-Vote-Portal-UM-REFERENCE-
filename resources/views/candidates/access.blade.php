@extends('layouts.app')

@section('content')
<style>
    .dash-title { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .dash-card { background: #ffffff; border-radius: 20px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); padding: 30px; }
    .input-um { width: 100%; padding: 14px; border-radius: 12px; border: 2px solid #e2e8f0; font-family: 'DM Sans'; margin-bottom: 16px; transition: border-color 0.2s;}
    .input-um:focus { outline: none; border-color: var(--um-maroon); }
    .btn-dash-primary { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); color: white; padding: 14px 28px; border-radius: 12px; font-weight: 700; border: none; width: 100%; box-shadow: 0 4px 15px rgba(138,21,56,0.25);}
</style>

<div class="dash-wrap reveal active">
    <div class="mb-5">
        <h2 class="dash-title m-0" style="font-size: 38px;">Access Control</h2>
        <p class="text-muted mt-2">Manage Electoral Board, System Auditors, and Official Publications.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    @if($electionStatus === 'certified')
        <div class="dash-card p-5 mb-5" style="background: #fffdf5; border-color: #fde68a;">
            <div class="text-center mb-4">
                <div style="font-size: 40px; color: var(--um-gold); margin-bottom: 15px;"><i class="bi bi-clipboard2-check-fill"></i></div>
                <h3 class="dash-title">Review Certified Results</h3>
                <p class="text-muted">The Electoral Board has audited and certified these final standings. Please review the processed data below before publishing it.</p>
            </div>

            <div class="row justify-content-center mb-5 g-3">
                @foreach($finalTally as $positionName => $positionCandidates)
                    @php $winner = $positionCandidates->sortByDesc('votes_count')->first(); @endphp
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded text-center shadow-sm" style="border-color: #fde68a !important;">
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.1em;">{{ $positionName }} Winner</div>
                            <h5 class="fw-bold text-dark mt-2 mb-2" style="font-family: 'Bricolage Grotesque';">{{ $winner->candidate_name ?? 'No Votes' }}</h5>
                            <div class="badge" style="background: var(--um-maroon); font-size: 13px;">{{ $winner->votes_count ?? 0 }} Votes</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('election.publish') }}" method="POST" class="text-center border-top pt-4">
                @csrf
                <button type="submit" class="btn btn-warning text-dark fw-bold px-5 py-3 shadow" style="border-radius: 12px; font-size: 16px; letter-spacing: 0.05em; text-transform: uppercase;">
                    Approve & Publish Official Results <i class="bi bi-megaphone-fill ms-2"></i>
                </button>
            </form>
        </div>
    @elseif($electionStatus === 'published')
        <div class="alert alert-primary fw-bold mb-5 border-0 shadow-sm text-center py-4" style="background: #eff6ff; color: #1e3a8a; border-radius: 16px;">
            <i class="bi bi-broadcast me-2 fs-4"></i> The official results have been published to the student portal.
        </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="dash-card">
                <h4 class="dash-title mb-4 fs-5"><i class="bi bi-person-plus-fill text-muted me-2"></i> Register New Auditor</h4>
                <form action="{{ route('auditor.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" class="input-um" placeholder="Auditor Full Name" required>
                    <input type="email" name="email" class="input-um" placeholder="Official Email Address" required>
                    <input type="password" name="password" class="input-um" placeholder="Secure Password" required>
                    <button type="submit" class="btn-dash-primary mt-2">Create Auditor Account</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="dash-card" style="height: 100%;">
                <h4 class="dash-title mb-4 fs-5"><i class="bi bi-shield-lock-fill text-muted me-2"></i> Active Auditors</h4>
                @foreach($auditors as $auditor)
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2 bg-light">
                        <div class="d-flex align-items-center">
                            <div style="width: 40px; height: 40px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;" class="me-3">AU</div>
                            <div>
                                <div class="fw-bold text-dark">{{ $auditor->name }}</div>
                                <div style="font-size: 12px;" class="text-muted">{{ $auditor->email }}</div>
                            </div>
                        </div>
                        <span class="badge bg-secondary">System Auditor</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection