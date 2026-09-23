@extends('layouts.app')

@section('title', 'Settings — LetsBelajar')
@section('page-title', 'Settings')

@section('content')
<div class="mb-4">
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Settings</text-4xl font-extrabold tracking-tight lg:text-5xl>
 <p class="mb-0 text-sm" style="color: var(--js-text-muted-foreground);">Manage your profile information and account settings.</p>
</div>

<div style="max-width: 700px;">
 {{-- Profile Info --}}
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm mb-3">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold mb-1" style="color: var(--js-text-primary);">
 <i data-lucide="user" class="w-4 h-4 me-2" style="color: var(--js-accent-hover); vertical-align: -2px;"></i>
 Profile Information
 </text-lg font-semibold tracking-tight>
 <p class="text-sm mb-3" style="color: var(--js-text-muted-foreground);">Update your name and email address.</p>

 <form method="post" action="{{ route('profile.update') }}">
 @csrf
 @method('patch')
 <div class="mb-3">
 <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Name</label>
 <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('name') is-invalid @enderror" required>
 @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Email</label>
 <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('email') is-invalid @enderror" required>
 @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">Save Changes</button>
 @if (session('status') === 'profile-updated')
 <span class="ms-2 text-sm" style="color: var(--js-success);">Saved.</span>
 @endif
 </form>
 </div>
 </div>

 {{-- Password --}}
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm mb-3">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold mb-1" style="color: var(--js-text-primary);">
 <i data-lucide="lock" class="w-4 h-4 me-2" style="color: var(--js-accent-hover); vertical-align: -2px;"></i>
 Update Password
 </text-lg font-semibold tracking-tight>
 <p class="text-sm mb-3" style="color: var(--js-text-muted-foreground);">Use a long, random password to stay secure.</p>

 <form method="post" action="{{ route('password.update') }}">
 @csrf
 @method('put')
 <div class="mb-3">
 <label for="current_password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Current Password</label>
 <input type="password" id="current_password" name="current_password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('current_password', 'updatePassword') is-invalid @enderror">
 @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">New Password</label>
 <input type="password" id="password" name="password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('password', 'updatePassword') is-invalid @enderror">
 @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="password_confirmation" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Confirm New Password</label>
 <input type="password" id="password_confirmation" name="password_confirmation" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
 </div>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">Update Password</button>
 @if (session('status') === 'password-updated')
 <span class="ms-2 text-sm" style="color: var(--js-success);">Updated.</span>
 @endif
 </form>
 </div>
 </div>

 {{-- Delete Account --}}
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm" style="border-color: rgba(239, 68, 68, 0.2) !important;">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold mb-1" style="color: var(--js-danger);">
 <i data-lucide="trash-2" class="w-4 h-4 me-2" style="vertical-align: -2px;"></i>
 Delete Account
 </text-lg font-semibold tracking-tight>
 <p class="text-sm mb-3" style="color: var(--js-text-muted-foreground);">Once deleted, all data will be permanently removed.</p>

 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-danger inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
 Delete My Account
 </button>
 </div>
 </div>
</div>

{{-- Delete modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered">
 <div class="modal-content border-0">
 <div class="modal-header">
 <text-lg font-semibold tracking-tight class="modal-title" style="color: var(--js-text-primary);">Are you sure?</text-lg font-semibold tracking-tight>
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-close" data-bs-dismiss="modal"></button>
 </div>
 <form method="POST" action="{{ route('profile.destroy') }}">
 @csrf
 @method('delete')
 <div class="modal-body">
 @if ($user->firebase_uid)
 <p class="text-sm" style="color: var(--js-text-secondary);">This action cannot be undone. Enter your account email address (<strong>{{ $user->email }}</strong>) to confirm deletion.</p>
 <input type="email" name="email" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('email', 'userDeletion') is-invalid @enderror" placeholder="{{ $user->email }}" required>
 @error('email', 'userDeletion')<div class="text-destructive text-sm mt-1">{{ $message }}</div>@enderror
 @else
 <p class="text-sm" style="color: var(--js-text-secondary);">This action cannot be undone. Enter your password to confirm.</p>
 <input type="password" name="password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password" required>
 @error('password', 'userDeletion')<div class="text-destructive text-sm mt-1">{{ $message }}</div>@enderror
 @endif
 </div>
 <div class="modal-footer">
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2" data-bs-dismiss="modal">Cancel</button>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-danger">Delete Account</button>
 </div>
 </form>
 </div>
 </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
<script>
 document.addEventListener('DOMContentLoaded', function() {
 var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
 deleteModal.show();
 });
</script>
@endif
@endsection
