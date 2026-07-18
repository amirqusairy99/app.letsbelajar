@extends('layouts.app')

@section('title', 'Edit Assignment — LetsBelajar')
@section('page-title', 'Edit Assignment')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-link text-secondary p-0 me-3">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <h1 class="h3 fw-semibold text-light">Edit Assignment</h1>
</div>

<div class="card border-0 shadow-sm rounded-3" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('assignments.update', $assignment) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label for="subject" class="form-label text-light">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $assignment->subject) }}" class="form-control rounded-2 @error('subject') is-invalid @enderror" required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="name" class="form-label text-light">Assignment Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $assignment->name) }}" class="form-control rounded-2 @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="lecturer_name" class="form-label text-light">Lecturer Name</label>
                    <input type="text" id="lecturer_name" name="lecturer_name" value="{{ old('lecturer_name', $assignment->lecturer_name) }}" class="form-control rounded-2 @error('lecturer_name') is-invalid @enderror">
                    @error('lecturer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="due_date" class="form-label text-light">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $assignment->due_date) }}" class="form-control rounded-2 @error('due_date') is-invalid @enderror">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="status" class="form-label text-light">Status</label>
                    <select id="status" name="status" class="form-select rounded-2 @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $assignment->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $assignment->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="description" class="form-label text-light">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-control rounded-2 @error('description') is-invalid @enderror">{{ old('description', $assignment->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary rounded-2">Update Assignment</button>
                    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-secondary rounded-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
