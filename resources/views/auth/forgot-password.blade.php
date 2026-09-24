<x-guest-layout>
    <div class="p-6 md:p-8">
        <h2 class="text-2xl font-bold tracking-tight text-foreground mb-1">Forgot password?</h2>
        <p class="text-sm text-muted-foreground mb-6">Enter your email and we'll send you a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            
            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold text-foreground">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary {{ $errors->has('email') ? 'border-destructive ring-destructive' : '' }}" 
                       required autofocus>
                @error('email')
                    <p class="text-sm text-destructive font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 mt-2">
                Send reset link
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-muted-foreground">
            Remember your password? <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline underline-offset-4">Sign in</a>
        </p>
    </div>
</x-guest-layout>
