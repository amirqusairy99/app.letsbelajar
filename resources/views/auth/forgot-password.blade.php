<x-guest-layout>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <h2 class="h4 mb-1 text-light fw-semibold">Forgot password?</h2>
            <p class="text-secondary mb-4">Enter your email and we'll send you a reset link.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-light">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control rounded-2 @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 rounded-2">Send reset link</button>
            </form>

            <p class="text-center mt-3 mb-0 text-secondary">
                Remember your password? <a href="{{ route('login') }}" class="text-decoration-none fw-medium" style="color: #3B82F6;">Sign in</a>
            </p>
        </div>
    </div>
</x-guest-layout>
