@extends('layouts.app')

@section('title', 'Settings — LetsBelajar')
@section('page-title', 'Settings')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Settings</h1>
    <p class="mb-0 small" style="color: var(--js-text-muted);">Manage your profile information and account settings.</p>
</div>

<div style="max-width: 700px;">
    {{-- Profile Info --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5 class="h6 fw-semibold mb-1" style="color: var(--js-text-primary);">
                <i data-lucide="user" class="w-4 h-4 me-2" style="color: var(--js-accent-hover); vertical-align: -2px;"></i>
                Profile Information
            </h5>
            <p class="small mb-3" style="color: var(--js-text-muted);">Update your name and email address.</p>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                @if (session('status') === 'profile-updated')
                    <span class="ms-2 small" style="color: var(--js-success);">Saved.</span>
                @endif
            </form>
        </div>
    </div>

    {{-- Password --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5 class="h6 fw-semibold mb-1" style="color: var(--js-text-primary);">
                <i data-lucide="lock" class="w-4 h-4 me-2" style="color: var(--js-accent-hover); vertical-align: -2px;"></i>
                Update Password
            </h5>
            <p class="small mb-3" style="color: var(--js-text-muted);">Use a long, random password to stay secure.</p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                    @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                    @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
                @if (session('status') === 'password-updated')
                    <span class="ms-2 small" style="color: var(--js-success);">Updated.</span>
                @endif
            </form>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="card border-0 shadow-sm" style="border-color: rgba(239, 68, 68, 0.2) !important;">
        <div class="card-body">
            <h5 class="h6 fw-semibold mb-1" style="color: var(--js-danger);">
                <i data-lucide="trash-2" class="w-4 h-4 me-2" style="vertical-align: -2px;"></i>
                Delete Account
            </h5>
            <p class="small mb-3" style="color: var(--js-text-muted);">Once deleted, all data will be permanently removed.</p>

            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
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
                <h5 class="modal-title" style="color: var(--js-text-primary);">Are you sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    @if ($user->firebase_uid)
                        <p class="small" style="color: var(--js-text-secondary);">This action cannot be undone. Enter your account email address (<strong>{{ $user->email }}</strong>) to confirm deletion.</p>
                        <input type="email" name="email" class="form-control @error('email', 'userDeletion') is-invalid @enderror" placeholder="{{ $user->email }}" required>
                        @error('email', 'userDeletion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    @else
                        <p class="small" style="color: var(--js-text-secondary);">This action cannot be undone. Enter your password to confirm.</p>
                        <input type="password" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password" required>
                        @error('password', 'userDeletion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-outline-danger">Delete Account</button>
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
