<x-guest-layout>
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow shadow-sm border-0 rounded-3" style="border-color: var(--js-border-strong) !important; background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(12px);">
 <div class="p-6 p-4">
 <text-3xl font-semibold tracking-tight class="text-xl font-semibold tracking-tight mb-1 font-bold" style="color: var(--js-text-primary);">Create an account</text-3xl font-semibold tracking-tight>
 <p class="mb-4" style="color: var(--js-text-muted-foreground); font-size: 0.875rem;">Get started with LetsBelajar for free</p>

 <form method="POST" action="{{ route('register') }}">
 @csrf
 <div class="mb-3">
 <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
 <input type="text" id="name" name="name" value="{{ old('name') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('name') is-invalid @enderror" placeholder="John Doe" required autofocus>
 @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Email</label>
 <input type="email" id="email" name="email" value="{{ old('email') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('email') is-invalid @enderror" placeholder="you@example.com" required>
 @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Password</label>
 <div class="input-group">
 <input type="password" id="password" name="password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 @error('password') is-invalid @enderror" placeholder="••••••••" required>
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 toggle-password" data-target="password" tabindex="-1">
 <span class="toggle-password-icon">Show</span>
 </button>
 @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 </div>
 <div class="mb-3">
 <label for="password_confirmation" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Confirm Password</label>
 <div class="input-group">
 <input type="password" id="password_confirmation" name="password_confirmation" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" placeholder="••••••••" required>
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 toggle-password" data-target="password_confirmation" tabindex="-1">
 <span class="toggle-password-icon">Show</span>
 </button>
 </div>
 </div>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full py-2">Create account</button>
 </form>

 <div class="text-center my-3" style="color: var(--js-text-muted-foreground); font-size: 0.8rem;">or</div>

 <button type="button" id="google-signin" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-light w-full py-2 flex items-center justify-center gap-2">
 <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
 <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
 <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
 <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
 <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
 </svg>
 Continue with Google
 </button>
 <div id="google-error" class="text-destructive text-sm text-center mt-2" style="min-height: 1rem;"></div>

 <p class="text-center mt-3 mb-0 text-sm" style="color: var(--js-text-muted-foreground);">
 Already have an account? <a href="{{ route('login') }}" class="no-underline font-medium" style="color: var(--js-accent-hover);">Sign in</a>
 </p>
 </div>
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
