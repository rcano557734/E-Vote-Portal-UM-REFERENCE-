@extends('layouts.app')
@section('title', 'Access Control')

@section('content')
<style>
    .dash-title  { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .dash-card   { background: #fff; border-radius: 20px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0,0,0,.05); padding: 30px; }
    .input-um    {
        width: 100%; padding: 13px 16px; border-radius: 12px;
        border: 2px solid #e2e8f0; font-family: 'DM Sans', sans-serif;
        font-size: 14px; color: var(--text-dark); background: #f8fafc;
        transition: all .2s; outline: none; margin-bottom: 4px;
    }
    .input-um:focus    { border-color: var(--um-maroon); background: #fff; box-shadow: 0 0 0 4px rgba(138,21,56,.1); }
    .input-um.is-error { border-color: #ef4444 !important; }
    .field-error { color: #ef4444; font-size: 12px; font-weight: 600; margin-bottom: 10px; display: block; }
    .btn-dash-primary {
        background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark));
        color: white; padding: 13px 24px; border-radius: 12px;
        font-weight: 700; border: none; width: 100%;
        box-shadow: 0 4px 15px rgba(138,21,56,.25);
        font-family: 'DM Sans', sans-serif; font-size: 14px;
        cursor: pointer; transition: all .2s; text-transform: uppercase; letter-spacing: .05em;
    }
    .btn-dash-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(138,21,56,.35); }
    .auditor-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px; border-radius: 12px; background: #f8fafc;
        border: 1px solid #e2e8f0; margin-bottom: 10px; transition: all .2s;
    }
    .auditor-row:hover { background: #f1f5f9; border-color: #cbd5e1; }
</style>

<div>
    <div class="mb-5">
        <h2 class="dash-title m-0" style="font-size: 38px;">Access Control</h2>
        <p class="text-muted mt-2">Manage Electoral Auditors and Official Publication settings.</p>
    </div>

    {{-- ── Official Publication Portal ─────────────────────────────── --}}
    <div class="dash-card mb-5 p-0 overflow-hidden"
         style="border: 1px solid {{ $electionStatus === 'certified' ? '#fcd34d' : '#e2e8f0' }}; box-shadow: 0 10px 30px rgba(0,0,0,.03);">

        {{-- Portal Header --}}
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center"
             style="background: {{ $electionStatus === 'certified' ? '#fffdf5' : '#f8fafc' }};">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:{{ $electionStatus==='certified'?'var(--um-gold)':'#e2e8f0' }};color:{{ $electionStatus==='certified'?'#0f172a':'#94a3b8' }};border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <h3 class="dash-title m-0 fs-4">Official Publication Portal</h3>
                    <p class="text-muted m-0 mt-1" style="font-size:13px;">Publish certified results to the student body dashboard.</p>
                </div>
            </div>

            @if(in_array($electionStatus, ['pending','active','closed']))
                <span class="badge px-4 py-2 rounded-pill" style="background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;"><i class="bi bi-lock-fill me-1"></i> Awaiting Board Approval</span>
            @elseif($electionStatus === 'certified')
                <span class="badge bg-warning text-dark px-4 py-2 rounded-pill shadow-sm" style="font-weight:800;"><i class="bi bi-exclamation-circle-fill me-1"></i> Action Required</span>
            @else
                <span class="badge px-4 py-2 rounded-pill shadow-sm" style="background:#eff6ff;color:#1e3a8a;border:1px solid #bfdbfe;"><i class="bi bi-broadcast me-1"></i> Successfully Published</span>
            @endif
        </div>

        {{-- Portal Content --}}
        <div class="p-5">
            @if(in_array($electionStatus, ['pending','active','closed']))
                <div class="text-center text-muted py-5">
                    <div style="font-size:56px;color:#cbd5e1;margin-bottom:20px;"><i class="bi bi-shield-lock"></i></div>
                    <h4 class="fw-bold text-dark mb-3" style="font-family:'Bricolage Grotesque';">Portal Temporarily Locked</h4>
                    <p style="max-width:500px;margin:0 auto;line-height:1.6;">This portal unlocks automatically once the Electoral Board formally audits and certifies the final ledger.</p>
                </div>

            @elseif($electionStatus === 'certified')
                <div class="mb-4 text-center">
                    <h4 class="fw-bold text-dark" style="font-family:'Bricolage Grotesque';">Review Certified Winners</h4>
                    <p class="text-muted">Verify the results below before publishing to all students.</p>
                </div>

                <div class="row g-4 mb-5">
                    @foreach($finalTally as $positionName => $positionCandidates)
                        @php $winner = $positionCandidates->sortByDesc('votes_count')->first(); @endphp
                        <div class="col-xl-6">
                            <div class="d-flex align-items-center p-4 border rounded-4"
                                 style="background:white;border-color:#fde68a!important;box-shadow:0 4px 15px rgba(253,184,19,.05);">
                                <div style="width:54px;height:54px;background:#fffbeb;color:var(--um-gold-dark);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;margin-right:20px;flex-shrink:0;">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>
                                <div class="flex-grow-1 pe-3">
                                    <div class="text-muted text-uppercase fw-bold" style="font-size:11px;letter-spacing:.1em;margin-bottom:4px;">{{ $positionName }}</div>
                                    <h5 class="fw-bold text-dark m-0" style="font-family:'Bricolage Grotesque';font-size:18px;">{{ $winner->candidate_name ?? 'No Votes' }}</h5>
                                </div>
                                <div class="text-end border-start ps-4">
                                    <div class="fw-bold" style="color:var(--um-maroon);font-size:24px;font-family:'Bricolage Grotesque';line-height:1;">{{ number_format($winner->votes_count ?? 0) }}</div>
                                    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;font-weight:800;letter-spacing:.05em;margin-top:4px;">Votes</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('election.publish') }}" method="POST"
                      class="text-center pt-4 border-top confirm-form"
                      data-title="Publish Official Results?"
                      data-text="This will make certified results visible to all students. This cannot be undone."
                      data-btn="Yes, Publish Now"
                      data-color="#d97706">
                    @csrf
                    <button type="submit"
                            class="btn btn-warning text-dark fw-bold px-5 py-3 shadow"
                            style="border-radius:12px;font-size:16px;letter-spacing:.05em;text-transform:uppercase;transition:transform .2s;"
                            onmouseover="this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                        Approve &amp; Publish Official Results <i class="bi bi-send-check-fill ms-2"></i>
                    </button>
                </form>

            @elseif($electionStatus === 'published')
                <div class="text-center py-5">
                    <div style="font-size:56px;color:#10b981;margin-bottom:20px;"><i class="bi bi-check-circle-fill"></i></div>
                    <h4 class="fw-bold text-dark mb-2" style="font-family:'Bricolage Grotesque';">Publication Successful</h4>
                    <p class="text-muted" style="max-width:500px;margin:0 auto;">Results are currently live and visible on the student dashboard.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Register Auditor + Active Auditors ──────────────────────── --}}
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="dash-card h-100">
                <h4 class="dash-title mb-1 fs-5"><i class="bi bi-person-plus-fill text-muted me-2"></i>Register New Auditor</h4>
                <p class="text-muted mb-4" style="font-size:13px;">Create a secure Electoral Board auditor account.</p>

                <form action="{{ route('auditor.store') }}" method="POST" id="auditorForm" novalidate>
                    @csrf

                    <label class="form-label-um" style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Full Name</label>
                    <input type="text" name="name"
                           class="input-um @error('name') is-error @enderror"
                           placeholder="Auditor Full Name"
                           value="{{ old('name') }}" required>
                    @error('name') <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                    <label class="form-label-um mt-2" style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Email Address</label>
                    <input type="email" name="email"
                           class="input-um @error('email') is-error @enderror"
                           placeholder="Official Email Address"
                           value="{{ old('email') }}" required>
                    @error('email') <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                    <label class="form-label-um mt-2" style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="auditorPwd" name="password"
                               class="input-um @error('password') is-error @enderror"
                               placeholder="Min. 8 characters" required
                               style="padding-right:44px;">
                        <button type="button" onclick="togglePwd('auditorPwd',this)"
                                style="position:absolute;right:14px;top:43%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    @error('password') <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                    <label class="form-label-um mt-2" style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Confirm Password</label>
                    <div style="position:relative;">
                        <input type="password" id="auditorPwdConfirm" name="password_confirmation"
                               class="input-um"
                               placeholder="Re-enter password" required
                               style="padding-right:44px;">
                        <button type="button" onclick="togglePwd('auditorPwdConfirm',this)"
                                style="position:absolute;right:14px;top:43%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn-dash-primary mt-4">
                        <i class="bi bi-person-check-fill me-2"></i> Create Auditor Account
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="dash-card h-100">
                <h4 class="dash-title mb-1 fs-5"><i class="bi bi-shield-lock-fill text-muted me-2"></i>Active Auditors</h4>
                <p class="text-muted mb-4" style="font-size:13px;">{{ $auditors->count() }} auditor(s) currently registered.</p>

                @forelse($auditors as $auditor)
                    <div class="auditor-row">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:42px;height:42px;background:var(--um-maroon-light);color:var(--um-maroon);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0;">
                                {{ strtoupper(substr($auditor->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:14px;">{{ $auditor->name }}</div>
                                <div style="font-size:12px;color:#64748b;">{{ $auditor->email }}</div>
                            </div>
                        </div>
                        <span class="badge rounded-pill"
                              style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;font-size:11px;font-weight:800;padding:6px 12px;">
                            <i class="bi bi-eye-fill me-1"></i> Auditor
                        </span>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        No auditors registered yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePwd(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
}
</script>
@endpush
@endsection