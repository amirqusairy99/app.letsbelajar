<x-guest-layout>
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow shadow-sm border-0 rounded-3">
 <div class="p-6 p-4">
 <text-3xl font-semibold tracking-tight class="text-xl font-semibold tracking-tight mb-1 text-light font-semibold">Reset password</text-3xl font-semibold tracking-tight>
 <p class="text-secondary mb-4">Enter your new password below.</p>

 <form method="POST" action="{{ route('password.store') }}">
 @csrf
 <input type="hidden" name="token" value="{{ $request->route('token') }}">
 <div class="mb-3">
 <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Email</label>
 <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('email') is-invalid @enderror" required>
 @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Password</label>
 <input type="password" id="password" name="password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('password') is-invalid @enderror" required>
 @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="password_confirmation" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-light">Confirm Password</label>
 <input type="password" id="password_confirmation" name="password_confirmation" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2" required>
 </div>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full py-2 rounded-2">Reset password</button>
 </form>
 </div>
 </div>
</x-guest-layout>
