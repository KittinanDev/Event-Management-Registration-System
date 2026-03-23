<x-guest-layout>
    <div class="backdrop-blur-sm bg-white/[0.04] border border-white/[0.08] rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-green-500/15 mb-4">
                <svg class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">Verify your email</h1>
            <p class="text-sm text-slate-400">Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent you.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm text-green-400 bg-green-500/10 border border-green-500/20 rounded-lg px-4 py-3">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary w-full">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-sm font-medium text-slate-400 hover:text-white transition-colors">Log Out</button>
            </form>
        </div>
    </div>
</x-guest-layout>
