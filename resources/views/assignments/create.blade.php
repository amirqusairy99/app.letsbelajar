@extends('layouts.app')

@section('title', 'Create Assignment — LetsBelajar')
@section('page-title', 'Create Assignment')

@section('content')
<div class="flex items-center mb-4">
 <a href="{{ route('assignments.index') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-link text-secondary p-0 me-3">
 <i data-lucide="arrow-left" class="w-5 h-5"></i>
 </a>
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-semibold text-light">Create Assignment</text-4xl font-extrabold tracking-tight lg:text-5xl>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3" style="max-width: 700px;">
 <div class="p-6">
 <form method="POST" action="{{ route('assignments.store') }}">
 @csrf
 <div class="row g-3">
 <div class="col-12">
 <label for="subject" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Subject</label>
 <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('subject') is-invalid @enderror" required>
 @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12">
 <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Assignment Name</label>
 <input type="text" id="name" name="name" value="{{ old('name') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('name') is-invalid @enderror" required>
 @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12">
 <label for="lecturer_name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Lecturer Name</label>
 <input type="text" id="lecturer_name" name="lecturer_name" value="{{ old('lecturer_name') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('lecturer_name') is-invalid @enderror">
 @error('lecturer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12 col-md-6">
 <label for="due_date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Due Date</label>
 <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('due_date') is-invalid @enderror">
 @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12 col-md-6">
 <label for="status" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Status</label>
 <select id="status" name="status" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('status') is-invalid @enderror">
 <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
 <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
 </select>
 @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12">
 <label for="description" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Description</label>
 <textarea id="description" name="description" rows="4" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
 @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12 flex gap-2 pt-2">
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2">Create Assignment</button>
 <a href="{{ route('assignments.index') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2">Cancel</a>
 </div>
 </div>
 </form>
 </div>
</div>
@endsection
