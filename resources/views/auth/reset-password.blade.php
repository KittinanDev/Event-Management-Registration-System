<x-guest-layout>
    <div class="backdrop-blur-sm bg-white/[0.04] border border-white/[0.08] rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-white mb-1">Set New Password</h1>
            <p class="text-sm text-slate-400">Choose a strong password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="block text-sm font-medium text-slate-400 mb-1.5">Email</label>
                <input id="email" type="email" name="email" class="input-dark w-full" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-400 mb-1.5">New Password</label>
                <input id="password" type="password" name="password" class="input-dark w-full" required autocomplete="new-password" />
                @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-1.5">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="input-dark w-full" required autocomplete="new-password" />
                @error('password_confirmation') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary w-full">Reset Password</button>
        </form>
    </div>
</x-guest-layout>
