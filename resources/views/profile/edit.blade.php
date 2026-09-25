@extends('layouts.app')

@section('title', 'Profile Settings — LetsBelajar')
@section('page-title', 'Profile')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold tracking-tight text-foreground">Profile Settings</h1>
    <p class="text-sm text-muted-foreground mt-1">Manage your account settings and preferences.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
    
    {{-- Update Profile Information --}}
    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-foreground flex items-center mb-1">
                <i data-lucide="user" class="w-5 h-5 mr-2 text-primary"></i>
                Profile Information
            </h2>
            <p class="text-sm text-muted-foreground mb-6">Update your account's profile information and email address.</p>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('patch')
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-20 w-20 overflow-hidden rounded-full border-2 border-primary/30 bg-muted shrink-0">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Profile Picture" class="h-full w-full object-cover" />
                    </div>
                    <div class="flex-1">
                        <label for="avatar" class="text-sm font-medium text-foreground block mb-1">Profile Picture</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="block w-full text-sm text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                        @error('avatar')<p class="text-sm text-destructive mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="name" class="text-sm font-medium text-foreground">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50" required autofocus autocomplete="name">
                    @error('name')<p class="text-sm text-destructive mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="space-y-1">
                    <label for="email" class="text-sm font-medium text-foreground">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50" required autocomplete="username">
                    @error('email')<p class="text-sm text-destructive mt-1">{{ $message }}</p>@enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-amber-600 dark:text-amber-500">
                                Your email address is unverified.
                                <button form="send-verification" class="underline text-sm font-medium hover:text-amber-700 dark:hover:text-amber-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    Click here to re-send the verification email.
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    A new verification link has been sent to your email address.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="pt-2 flex items-center gap-4">
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">Save Changes</button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-green-600 dark:text-green-400 transition" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Saved.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-foreground flex items-center mb-1">
                <i data-lucide="lock" class="w-5 h-5 mr-2 text-primary"></i>
                Update Password
            </h2>
            <p class="text-sm text-muted-foreground mb-6">Ensure your account is using a long, random password to stay secure.</p>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')
                
                <div class="space-y-1">
                    <label for="current_password" class="text-sm font-medium text-foreground">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                    @error('current_password', 'updatePassword')<p class="text-sm text-destructive mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="space-y-1">
                    <label for="password" class="text-sm font-medium text-foreground">New Password</label>
                    <input type="password" id="password" name="password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                    @error('password', 'updatePassword')<p class="text-sm text-destructive mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="space-y-1">
                    <label for="password_confirmation" class="text-sm font-medium text-foreground">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                </div>
                
                <div class="pt-2 flex items-center gap-4">
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">Update Password</button>
                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-green-600 dark:text-green-400 transition" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Saved.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="rounded-xl border border-destructive/50 bg-card shadow-sm overflow-hidden md:col-span-2 max-w-2xl" x-data="{ confirmDelete: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-destructive flex items-center mb-1">
                <i data-lucide="trash-2" class="w-5 h-5 mr-2"></i>
                Delete Account
            </h2>
            <p class="text-sm text-muted-foreground mb-6">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

            <button type="button" @click="confirmDelete = true" class="inline-flex h-10 items-center justify-center rounded-md bg-destructive/10 text-destructive border border-destructive/20 hover:bg-destructive hover:text-destructive-foreground px-4 py-2 text-sm font-medium transition-colors">
                Delete My Account
            </button>
        </div>

        {{-- Alpine Modal for Deletion --}}
        <div x-show="confirmDelete" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="confirmDelete" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>
            
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="confirmDelete" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative transform overflow-hidden rounded-xl bg-card text-left shadow-xl transition-all border border-border sm:my-8 sm:w-full sm:max-w-lg">
                        
                        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
                            @csrf
                            @method('delete')
                            
                            <h2 class="text-lg font-bold text-foreground mb-2">Are you sure you want to delete your account?</h2>
                            
                            @if ($user->firebase_uid)
                                <p class="text-sm text-muted-foreground mb-4">This action cannot be undone. Enter your account email address (<strong>{{ $user->email }}</strong>) to confirm deletion.</p>
                                <input type="email" name="email" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-destructive focus-visible:border-destructive disabled:cursor-not-allowed disabled:opacity-50" placeholder="{{ $user->email }}" required>
                                @error('email', 'userDeletion')<p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>@enderror
                            @else
                                <p class="text-sm text-muted-foreground mb-4">This action cannot be undone. Enter your password to confirm you would like to permanently delete your account.</p>
                                <input type="password" name="password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-destructive focus-visible:border-destructive disabled:cursor-not-allowed disabled:opacity-50" placeholder="Password" required>
                                @error('password', 'userDeletion')<p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>@enderror
                            @endif

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="confirmDelete = false" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
                                    Cancel
                                </button>
                                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground shadow transition-colors hover:bg-destructive/90">
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
