<x-guest-layout>
    <div class="rounded-2xl border border-border bg-card/80 backdrop-blur-xl text-card-foreground shadow-xl overflow-hidden max-w-md w-full mx-auto">
        <div class="p-8 text-center">
            
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-500/10 shadow-inner">
                <i data-lucide="credit-card" class="h-7 w-7 text-amber-500"></i>
            </div>

            <h2 class="text-2xl font-bold tracking-tight text-foreground mb-2">Your free trial has expired</h2>
            <p class="text-muted-foreground text-sm leading-relaxed mb-6">
                Please pay <span class="font-bold text-foreground">RM5.90</span>/month and submit your payment proof. Access will be restored after verification.
            </p>

            <div class="flex justify-center mb-6">
                <div class="p-4 rounded-xl bg-white shadow-inner">
                    <img src="{{ asset('payment-qr.jpeg') }}" alt="Payment QR code" class="w-48 h-48 object-contain">
                </div>
            </div>

            <div class="space-y-1 mb-6">
                <p class="text-sm font-medium text-muted-foreground">Scan the QR code above to make your payment</p>
                <p class="text-lg font-bold text-foreground">Amount due: RM5.90</p>
            </div>

            <div class="flex flex-col gap-3 mb-6">
                <a href="https://wa.me/601153791284?text={{ urlencode('Hello, I have completed the RM5.90 monthly payment for my LetsBelajar account. Please find my proof of payment attached. Thank you.') }}"
                   target="_blank" rel="noopener"
                   style="background-color: #16a34a; color: white;"
                   class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-md px-8 py-2 text-sm font-medium shadow transition-opacity hover:opacity-90">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    Send proof on WhatsApp
                </a>
            </div>

            <p class="text-sm text-muted-foreground mb-6">
                Questions? WhatsApp us at <span class="font-semibold text-foreground">011 5379 1284</span>.
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-muted-foreground hover:text-foreground underline underline-offset-4 transition-colors">
                    Log out
                </button>
            </form>
            
        </div>
    </div>
</x-guest-layout>
