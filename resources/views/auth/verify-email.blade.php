<x-guest-layout>
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow shadow-sm border-0 rounded-3">
 <div class="p-6 p-4 text-center">
 <h2 class="text-xl font-semibold tracking-tight mb-1 text-foreground font-semibold">Verify your email</h2>
 <p class="text-muted-foreground mb-4">Please verify your email address by clicking the link we sent.</p>

 @if (session('status') == 'verification-link-sent')
 <div class="alert alert-success rounded-2">
 A new verification link has been sent to your email.
 </div>
 @endif

 <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
 @csrf
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2">Resend verification email</button>
 </form>
 </div>
 </div>
</x-guest-layout>
