<x-guest-layout>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4 text-center">
            <h2 class="h4 mb-1 text-light fw-semibold">Verify your email</h2>
            <p class="text-secondary mb-4">Please verify your email address by clicking the link we sent.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success rounded-2">
                    A new verification link has been sent to your email.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary rounded-2">Resend verification email</button>
            </form>
        </div>
    </div>
</x-guest-layout>
