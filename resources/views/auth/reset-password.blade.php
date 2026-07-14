<x-guest-layout>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <h2 class="h4 mb-1 text-light fw-semibold">Reset password</h2>
            <p class="text-secondary mb-4">Enter your new password below.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="mb-3">
                    <label for="email" class="form-label text-light">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" class="form-control rounded-2 @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-light">Password</label>
                    <input type="password" id="password" name="password" class="form-control rounded-2 @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-light">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control rounded-2" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 rounded-2">Reset password</button>
            </form>
        </div>
    </div>
</x-guest-layout>
