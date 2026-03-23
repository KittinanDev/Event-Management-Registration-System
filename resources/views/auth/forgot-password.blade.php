<x-guest-layout>
    <div class="backdrop-blur-sm bg-white/[0.04] border border-white/[0.08] rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-white mb-1">Reset Password</h1>
            <p class="text-sm text-slate-400">Enter your email and we'll send you a password reset link.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-400 bg-green-500/10 border border-green-500/20 rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-400 mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" class="input-dark w-full" value="{{ old('email') }}" required autofocus />
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary w-full">
                Send Password Reset Link
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Back to Login</a>
        </div>
    </div>
</x-guest-layout>
