<x-guest-layout>
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow shadow-sm border-0 rounded-3">
 <div class="p-6 p-4">
 <h2 class="text-xl font-semibold tracking-tight mb-1 text-foreground font-semibold">Forgot password?</h2>
 <p class="text-muted-foreground mb-4">Enter your email and we'll send you a reset link.</p>

 <form method="POST" action="{{ route('password.email') }}">
 @csrf
 <div class="mb-3">
 <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Email</label>
 <input type="email" id="email" name="email" value="{{ old('email') }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('email') is-invalid @enderror" required>
 @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full py-2 rounded-2">Send reset link</button>
 </form>

 <p class="text-center mt-3 mb-0 text-muted-foreground">
 Remember your password? <a href="{{ route('login') }}" class="no-underline font-medium" style="color: #3B82F6;">Sign in</a>
 </p>
 </div>
 </div>
</x-guest-layout>
