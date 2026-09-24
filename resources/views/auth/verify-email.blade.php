<x-guest-layout>
    <div class="p-6 md:p-8 text-center">
        <h2 class="text-2xl font-bold tracking-tight text-foreground mb-2">Verify your email</h2>
        <p class="text-sm text-muted-foreground mb-6 leading-relaxed">
            Please verify your email address by clicking the link we sent to you.
        </p>

        @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-md bg-green-500/10 p-4 border border-green-500/20">
            <p class="text-sm font-medium text-green-600 dark:text-green-400">
                A new verification link has been sent to your email.
            </p>
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
            @csrf
            <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-8 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                Resend verification email
            </button>
        </form>
    </div>
</x-guest-layout>
