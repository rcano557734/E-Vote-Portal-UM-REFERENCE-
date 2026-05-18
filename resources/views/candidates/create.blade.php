@extends('layouts.app')
@section('title', 'Register Partylist')

@section('content')
<style>
    .dash-title    { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .form-control, .form-select { border-radius: 10px; padding: 12px 16px; border: 2px solid #e2e8f0; background: #f8fafc; font-weight: 500; transition: all .2s; }
    .form-control:focus, .form-select:focus { border-color: var(--um-maroon); box-shadow: 0 0 0 4px rgba(138,21,56,.1); background: #fff; }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444 !important; background: #fef2f2 !important; }

    .partylist-header {
        position: sticky; top: 78px; z-index: 40;
        background: rgba(255,255,255,.97);
        backdrop-filter: blur(10px);
        padding: 22px 28px; border-radius: 16px;
        border: 2px solid var(--um-maroon);
        box-shadow: 0 10px 25px rgba(138,21,56,.1);
        margin-bottom: 36px;
    }

    .position-card {
        border: 1.5px solid #e2e8f0; border-radius: 14px;
        padding: 22px; margin-bottom: 16px;
        background: #fff;
        transition: all .3s cubic-bezier(.4,0,.2,1);
        position: relative; overflow: hidden;
    }
    .position-card::before {
        content: ''; position: absolute; left: 0; top: 0;
        height: 100%; width: 4px; background: var(--um-maroon);
        transform: scaleY(0); transition: transform .3s; transform-origin: top;
    }
    .position-card:focus-within { box-shadow: 0 8px 25px rgba(0,0,0,.07); transform: translateY(-2px); }
    .position-card:focus-within::before { transform: scaleY(1); }
    .position-card:focus-within .position-title { color: var(--um-maroon); }

    .position-title {
        font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 16px;
        color: var(--um-maroon-dark); margin-bottom: 14px;
        text-transform: uppercase; letter-spacing: .05em;
        border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;
        transition: color .2s;
    }

    .btn-save-slate {
        background: var(--um-gold); color: #0f172a;
        padding: 14px 28px; border-radius: 12px; font-size: 15px;
        font-weight: 800; border: none; cursor: pointer;
        text-transform: uppercase; letter-spacing: .05em;
        box-shadow: 0 4px 15px rgba(253,184,19,.35); transition: all .2s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-save-slate:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(253,184,19,.45); }

    /* Progress counter */
    .progress-counter {
        background: #f1f5f9; border-radius: 10px; padding: 12px 18px;
        display: flex; align-items: center; gap: 12px; margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }
    .counter-bubble {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--um-maroon); color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px; flex-shrink: 0;
        font-family: 'Bricolage Grotesque';
    }
</style>

<div style="max-width: 920px; margin: 0 auto;">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="dash-title m-0" style="font-size: 36px;">Register Partylist</h2>
            <p class="text-muted mt-1 mb-0">Official UM Council of College Student Government (CCSG)</p>
        </div>
        <a href="{{ route('candidates.index') }}"
           class="btn btn-outline-secondary fw-bold rounded-pill px-4">
            <i class="bi bi-x-lg me-2"></i> Cancel
        </a>
    </div>

    {{-- Validation errors summary --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4" style="background:#fef2f2;">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</div>
            <ul class="mb-0 ps-3" style="font-size:14px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('candidates.store') }}" method="POST" id="partylistForm" novalidate>
        @csrf

        {{-- Sticky Partylist Header --}}
        <div class="partylist-header">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div style="width:56px;height:56px;background:var(--um-maroon);color:var(--um-gold);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;">
                    <i class="bi bi-flag-fill"></i>
                </div>
                <div class="flex-grow-1 row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-uppercase" style="font-size:11px;letter-spacing:.1em;color:#64748b;margin-bottom:4px;">
                            Official Partylist Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="partylist_name"
                               class="form-control form-control-lg border-0 bg-transparent px-0 @error('partylist_name') is-invalid @enderror"
                               style="font-family:'Bricolage Grotesque';font-weight:800;font-size:24px;color:var(--text-dark);box-shadow:none!important;"
                               placeholder="Enter Partylist Name..."
                               value="{{ old('partylist_name') }}"
                               required autofocus>
                        @error('partylist_name')
                            <div class="text-danger fw-bold" style="font-size:12px;margin-top:4px;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 border-start ps-4">
                        <label class="form-label fw-bold text-uppercase" style="font-size:11px;letter-spacing:.1em;color:#64748b;margin-bottom:4px;">
                            College / Department <span class="text-danger">*</span>
                        </label>
                        <select name="college"
                                class="form-select form-select-lg border-0 bg-transparent px-0 fw-bold @error('college') is-invalid @enderror"
                                style="font-size:18px;color:var(--um-maroon);box-shadow:none!important;cursor:pointer;" required>
                            <option value="" disabled {{ old('college') ? '' : 'selected' }}>Select College...</option>
                            @foreach([
                                'CCE'   => 'CCE (College of Computing Education)',
                                'CEE'   => 'CEE (College of Engineering Education)',
                                'CASE'  => 'CASE (College of Arts and Sciences Education)',
                                'CBAE'  => 'CBAE (College of Business Administration Education)',
                                'CAE'   => 'CAE (College of Accounting Education)',
                                'CCJE'  => 'CCJE (College of Criminal Justice Education)',
                                'CAFAE' => 'CAFAE (College of Architecture & Fine Arts)',
                                'CHE'   => 'CHE (College of Hospitality Education)',
                                'CHSE'  => 'CHSE (College of Health Sciences Education)',
                                'CLE'   => 'CLE (College of Legal Education)',
                                'CTE'   => 'CTE (College of Teacher Education)',
                                'TS'    => 'TS (Technical School)',
                                'BED'   => 'BED (Basic Education Department)',
                                'PS'    => 'PS (Professional Schools)',
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ old('college') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('college')
                            <div class="text-danger fw-bold" style="font-size:12px;margin-top:4px;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Instructions + Progress --}}
        <div class="progress-counter">
            <div class="counter-bubble" id="filledCount">0</div>
            <div>
                <div style="font-weight:700;font-size:14px;color:var(--text-dark);" id="progressLabel">Positions filled</div>
                <div style="font-size:12px;color:#64748b;">Fill in at least one candidate name to save this partylist</div>
            </div>
        </div>

        <div class="alert alert-info border-0 rounded-3 mb-4" style="background:#f0f9ff;color:#0369a1;font-size:14px;">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Instructions:</strong> Enter the candidate name for each position. Leave a field blank if this partylist does not have a candidate for that position.
        </div>

        {{-- Position Cards --}}
        @foreach($positions as $position)
            <div class="position-card">
                <div class="position-title">
                    <i class="bi bi-person-badge-fill me-2" style="color:var(--um-gold-dark);font-size:14px;"></i>
                    {{ $position->position_name }}
                </div>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-muted" style="font-size:12px;">Candidate Full Name</label>
                        <input type="text"
                               name="candidates[{{ $position->id }}][name]"
                               class="form-control candidate-name-input"
                               placeholder="Leave blank if none..."
                               value="{{ old('candidates.' . $position->id . '.name') }}"
                               maxlength="255">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label fw-semibold text-muted" style="font-size:12px;">Platform / Credentials <span style="color:#94a3b8;">(Optional)</span></label>
                        <textarea name="candidates[{{ $position->id }}][platform]"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Brief platform description..."
                                  maxlength="500">{{ old('candidates.' . $position->id . '.platform') }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Submit --}}
        <div class="d-flex align-items-center justify-content-end gap-3 mt-4 pt-2 border-top">
            <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4">Discard</a>
            <button type="submit" class="btn-save-slate" id="submitBtn">
                Save Partylist Slate <i class="bi bi-check2-circle ms-2"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs  = document.querySelectorAll('.candidate-name-input');
    const counter = document.getElementById('filledCount');
    const label   = document.getElementById('progressLabel');
    const total   = inputs.length;

    function updateCounter() {
        const filled = [...inputs].filter(i => i.value.trim() !== '').length;
        counter.textContent = filled;
        label.textContent   = `${filled} of ${total} positions filled`;
        counter.style.background = filled === 0 ? '#94a3b8' : 'var(--um-maroon)';
    }

    inputs.forEach(i => i.addEventListener('input', updateCounter));
    updateCounter();
});
</script>
@endpush
@endsection