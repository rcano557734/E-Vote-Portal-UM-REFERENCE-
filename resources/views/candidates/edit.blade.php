@extends('layouts.app')
@section('title', 'Edit Candidate')

@section('content')
<style>
    .dash-title    { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; color: var(--text-dark); letter-spacing: -0.03em; }
    .dash-card     { background: #fff; border-radius: 20px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0,0,0,.03); padding: 40px; }
    .input-um      { width: 100%; padding: 15px 16px; border-radius: 12px; border: 2px solid #e2e8f0; font-family: 'DM Sans', sans-serif; font-size: 15px; color: var(--text-dark); background: #f8fafc; transition: all .2s; outline: none; }
    .input-um:focus { border-color: var(--um-maroon); background: #fff; box-shadow: 0 0 0 4px rgba(138,21,56,.1); }
    .input-um.is-invalid { border-color: #ef4444; background: #fef2f2; }
    .label-um      { font-weight: 800; text-transform: uppercase; font-size: 12px; color: #64748b; letter-spacing: .1em; display: block; margin-bottom: 6px; }
    .btn-save      { background: linear-gradient(135deg, var(--um-gold), var(--um-gold-dark)); color: #0f172a; padding: 16px 32px; border-radius: 12px; font-weight: 800; border: none; width: 100%; box-shadow: 0 4px 15px rgba(251,191,36,.3); text-transform: uppercase; letter-spacing: .05em; font-size: 15px; cursor: pointer; transition: all .2s; }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(251,191,36,.4); }
    .field-error   { color: #ef4444; font-size: 12px; font-weight: 600; margin-top: 5px; display: block; }
</style>

<div style="max-width: 680px; margin: 0 auto;">

    <a href="{{ route('candidates.index') }}"
       class="text-decoration-none text-muted fw-bold mb-4 d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>

    <div class="dash-card" style="border-top: 6px solid var(--um-gold);">

        {{-- Header --}}
        <div class="text-center mb-5">
            <div style="width:68px;height:68px;background:#fffbeb;color:var(--um-gold-dark);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 18px;">
                <i class="bi bi-pencil-square"></i>
            </div>
            <h2 class="dash-title m-0" style="font-size:30px;">Update Candidate Record</h2>
            <p class="text-muted mt-2 mb-0">
                Editing: <strong>{{ $candidate->candidate_name }}</strong>
            </p>
        </div>

        {{-- Errors Summary --}}
        @if($errors->any())
            <div class="alert border-0 rounded-3 mb-4" style="background:#fef2f2;color:#991b1b;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the errors below before saving.</strong>
            </div>
        @endif

        <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- Candidate Name --}}
            <div class="mb-4">
                <label class="label-um" for="candidate_name">Candidate Full Name <span class="text-danger">*</span></label>
                <input type="text" id="candidate_name" name="candidate_name"
                       class="input-um @error('candidate_name') is-invalid @enderror"
                       value="{{ old('candidate_name', $candidate->candidate_name) }}"
                       placeholder="Enter full name" required maxlength="255">
                @error('candidate_name')
                    <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Position --}}
            <div class="mb-4">
                <label class="label-um" for="position_id">Running For Position <span class="text-danger">*</span></label>
                <select id="position_id" name="position_id"
                        class="input-um @error('position_id') is-invalid @enderror"
                        required>
                    <option value="" disabled>Select a position...</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}"
                            {{ old('position_id', $candidate->position_id) == $position->id ? 'selected' : '' }}>
                            {{ $position->position_name }}
                        </option>
                    @endforeach
                </select>
                @error('position_id')
                    <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Platform --}}
            <div class="mb-5">
                <label class="label-um" for="platform_description">Platform &amp; Qualifications <span class="text-danger">*</span></label>
                <textarea id="platform_description" name="platform_description"
                          class="input-um @error('platform_description') is-invalid @enderror"
                          rows="4" placeholder="Describe goals, credentials, and platform..."
                          required maxlength="1000">{{ old('platform_description', $candidate->platform_description) }}</textarea>
                <div style="text-align:right;font-size:11px;color:#94a3b8;margin-top:4px;">
                    <span id="charCount">{{ strlen(old('platform_description', $candidate->platform_description)) }}</span>/1000
                </div>
                @error('platform_description')
                    <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Readonly info --}}
            <div class="p-3 rounded-3 mb-5" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:13px;color:#64748b;">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Partylist:</strong> {{ $candidate->partylist ?? 'N/A' }} &bull;
                <strong>College:</strong> {{ $candidate->college ?? 'N/A' }}
                <br><span style="font-size:11px;margin-top:4px;display:block;">Partylist and College can only be changed by re-registering the partylist.</span>
            </div>

            <button type="submit" class="btn-save">
                <i class="bi bi-floppy-fill me-2"></i> Save Changes
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const textarea  = document.getElementById('platform_description');
    const charCount = document.getElementById('charCount');
    textarea.addEventListener('input', function () {
        charCount.textContent = this.value.length;
        charCount.style.color = this.value.length > 950 ? '#ef4444' : '#94a3b8';
    });
</script>
@endpush
@endsection