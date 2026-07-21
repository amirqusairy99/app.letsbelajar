<x-guest-layout>
    <div class="card shadow-sm border-0 rounded-3" style="border-color: var(--js-border-strong) !important; background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(12px);">
        <div class="card-body p-4 text-center">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background: rgba(251, 191, 36, 0.15);">
                <i data-lucide="credit-card" class="w-6 h-6" style="color: #FBBF24;"></i>
            </div>

            <h2 class="h4 mb-1 fw-bold" style="color: var(--js-text-primary);">Your free trial has expired</h2>
            <p class="mb-4" style="color: var(--js-text-muted); font-size: 0.9rem;">
                Please pay
                <span class="fw-bold" style="color: var(--js-text-primary);">RM5.90</span>/month
                and submit your payment proof. Access will be restored after verification.
            </p>

            <div class="d-flex justify-content-center mb-3">
                <div class="p-3 rounded-3" style="background: #ffffff;">
                    <img src="{{ asset('payment-qr.jpeg') }}" alt="Payment QR code" style="width: 200px; height: 200px; object-fit: contain;">
                </div>
            </div>

            <p class="small mb-1" style="color: var(--js-text-secondary);">Scan the QR code above to make your payment</p>
            <p class="small fw-semibold mb-3" style="color: var(--js-text-primary);">Amount due: RM5.90</p>

            <div class="d-grid gap-2">
                <a href="https://wa.me/601153791284?text={{ urlencode('Hello, I have completed the RM5.90 monthly payment for my LetsBelajar account. Please find my proof of payment attached. Thank you.') }}"
                   target="_blank" rel="noopener"
                   class="btn btn-success w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    Send proof of payment on WhatsApp
                </a>
            </div>

            <p class="small mt-3 mb-0" style="color: var(--js-text-muted);">
                Questions? WhatsApp us at <span class="fw-semibold" style="color: var(--js-text-primary);">011 5379 1284</span>.
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0" style="color: var(--js-text-muted);">
                    Log out
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
