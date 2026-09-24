<x-guest-layout>
    <div class="p-6 md:p-8">
        <h2 class="text-2xl font-bold tracking-tight text-foreground mb-1">Create an account</h2>
        <p class="mb-8 text-sm text-muted-foreground">Get started with LetsBelajar for free</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            
            <div class="space-y-2">
                <label for="name" class="text-sm font-semibold text-foreground">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary {{ $errors->has('name') ? 'border-destructive ring-destructive' : '' }}" 
                       placeholder="John Doe" required autofocus>
                @error('name')
                    <p class="text-sm text-destructive font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold text-foreground">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary {{ $errors->has('email') ? 'border-destructive ring-destructive' : '' }}" 
                       placeholder="you@example.com" required>
                @error('email')
                    <p class="text-sm text-destructive font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-semibold text-foreground">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" 
                           class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary {{ $errors->has('password') ? 'border-destructive ring-destructive' : '' }}" 
                           placeholder="••••••••" required>
                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors toggle-password" data-target="password" tabindex="-1">
                        <span class="text-xs font-medium toggle-password-icon">Show</span>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-destructive font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm font-semibold text-foreground">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary" 
                           placeholder="••••••••" required>
                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors toggle-password" data-target="password_confirmation" tabindex="-1">
                        <span class="text-xs font-medium toggle-password-icon">Show</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 mt-6">
                Create account
            </button>
        </form>

        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-border"></span>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-card px-2 text-muted-foreground font-semibold">Or continue with</span>
            </div>
        </div>

        <button type="button" id="google-signin" class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">
            <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Google
        </button>
        <div id="google-error" class="text-destructive text-sm font-medium text-center mt-3 min-h-[1.25rem]"></div>

        <p class="text-center mt-6 text-sm text-muted-foreground">
            Already have an account? <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline underline-offset-4">Sign in</a>
        </p>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.target);
                const icon = button.querySelector('.toggle-password-icon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'Hide';
                } else {
                    input.type = 'password';
                    icon.textContent = 'Show';
                }
            });
        });

        const googleBtn = document.getElementById('google-signin');
        if (googleBtn) {
            googleBtn.addEventListener('click', async function () {
                const errorEl = document.getElementById('google-error');
                if (errorEl) errorEl.textContent = '';
                googleBtn.disabled = true;
                const original = googleBtn.innerHTML;
                googleBtn.innerHTML = 'Please wait…';
                try {
                    await window.signInWithGoogle();
                } catch (err) {
                    googleBtn.disabled = false;
                    googleBtn.innerHTML = original;
                    let msg = 'Google sign-in failed. Please try again.';
                    if (err && err.response && err.response.data && err.response.data.message) {
                        msg = err.response.data.message;
                    } else if (err && (err.code || err.message)) {
                        msg = err.code || err.message;
                    }
                    if (errorEl) errorEl.textContent = msg;
                    console.error(err);
                }
            });
        }
    </script>
</x-guest-layout>
