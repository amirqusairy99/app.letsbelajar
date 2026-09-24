<x-guest-layout>
    <div class="p-6 md:p-8">
        <h2 class="text-2xl font-bold tracking-tight text-foreground mb-1">Reset password</h2>
        <p class="text-sm text-muted-foreground mb-6">Enter your new password below.</p>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold text-foreground">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary {{ $errors->has('email') ? 'border-destructive ring-destructive' : '' }}" 
                       required autofocus>
                @error('email')
                    <p class="text-sm text-destructive font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-2">
                <label for="password" class="text-sm font-semibold text-foreground">New Password</label>
                <input type="password" id="password" name="password" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary {{ $errors->has('password') ? 'border-destructive ring-destructive' : '' }}" 
                       required>
                @error('password')
                    <p class="text-sm text-destructive font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm font-semibold text-foreground">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary" 
                       required>
            </div>

            <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 mt-2">
                Reset password
            </button>
        </form>
    </div>
</x-guest-layout>
