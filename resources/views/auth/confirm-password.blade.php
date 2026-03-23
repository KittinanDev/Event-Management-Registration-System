<x-guest-layout>
    <div class="backdrop-blur-sm bg-white/[0.04] border border-white/[0.08] rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-white mb-1">Confirm Password</h1>
            <p class="text-sm text-slate-400">This is a secure area. Please confirm your password before continuing.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-slate-400 mb-1.5">Password</label>
                <input id="password" type="password" name="password" class="input-dark w-full" required autocomplete="current-password" />
                @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary w-full">Confirm</button>
        </form>
    </div>
</x-guest-layout>
