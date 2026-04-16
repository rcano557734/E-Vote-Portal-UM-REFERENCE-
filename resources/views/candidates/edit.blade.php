@extends('layouts.app')
@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
        <div class="header-blue bg-warning text-dark rounded-top">Update Candidate</div>
        <div class="card-body border border-top-0">
            <form action="{{ route('candidates.update', $candidate->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="fw-bold">Candidate Name</label>
                    <input type="text" name="candidate_name" class="form-control" value="{{ $candidate->candidate_name }}" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Position</label>
                    <select name="position_id" class="form-select" required>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ $candidate->position_id == $pos->id ? 'selected' : '' }}>{{ $pos->position_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Platform / Party List</label>
                    <input type="text" name="platform_description" class="form-control" value="{{ $candidate->platform_description }}" required>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('candidates.index') }}" class="btn btn-secondary fw-bold w-50">Cancel</a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold w-50">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection