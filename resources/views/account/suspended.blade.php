<x-guest-layout>
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow shadow-sm border-0 rounded-3" style="border-color: var(--js-border-strong) !important; background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(12px);">
 <div class="p-6 p-4 text-center">
 <div class="inline-flex items-center justify-center rounded-circle mb-3" style="width: 56px; height: 56px; background: rgba(251, 191, 36, 0.15);">
 <i data-lucide="credit-rounded-xl border border-border bg-card text-card-foreground shadow" class="w-6 h-6" style="color: #FBBF24;"></i>
 </div>

 <h2 class="text-xl font-semibold tracking-tight mb-1 font-bold" style="">Your free trial has expired</h2>
 <p class="mb-4" style=" font-size: 0.9rem;">
 Please pay
 <span class="font-bold" style="">RM5.90</span>/month
 and submit your payment proof. Access will be restored after verification.
 </p>

 <div class="flex justify-center mb-3">
 <div class="p-3 rounded-3" style="background: #ffffff;">
 <img src="{{ asset('payment-qr.jpeg') }}" alt="Payment QR code" style="width: 200px; height: 200px; object-fit: contain;">
 </div>
 </div>

 <p class="text-sm mb-1" style="">Scan the QR code above to make your payment</p>
 <p class="text-sm font-semibold mb-3" style="">Amount due: RM5.90</p>

 <div class="flex flex-col gap-2">
 <a href="https://wa.me/601153791284?text={{ urlencode('Hello, I have completed the RM5.90 monthly payment for my LetsBelajar account. Please find my proof of payment attached. Thank you.') }}"
 target="_blank" rel="noopener"
 class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-success w-full py-2 flex items-center justify-center gap-2">
 <i data-lucide="message-circle" class="w-4 h-4"></i>
 Send proof of payment on WhatsApp
 </a>
 </div>

 <p class="text-sm mt-3 mb-0" style="">
 Questions? WhatsApp us at <span class="font-semibold" style="">011 5379 1284</span>.
 </p>

 <form method="POST" action="{{ route('logout') }}" class="mt-3">
 @csrf
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-link inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm no-underline p-0" style="">
 Log out
 </button>
 </form>
 </div>
 </div>
</x-guest-layout>
