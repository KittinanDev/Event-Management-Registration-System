<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-500/30 bg-green-500/10 px-4 py-1.5">
            <span class="h-1.5 w-1.5 rounded-full bg-green-400 badge-pulse"></span>
            <span class="text-xs font-semibold uppercase tracking-widest text-green-400">Free account</span>
        </div>
        <h1 class="font-display text-3xl font-extrabold text-white">Create your account</h1>
        <p class="mt-2 text-sm text-slate-400">เริ่มต้นใช้งาน EventHub ฟรี ไม่มีค่าใช้จ่าย</p>
    </div>

    {{-- Card --}}
    <div class="glass-card rounded-2xl p-8">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <div>
                <label for="full_name" class="label-dark">Full Name</label>
                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}"
                    class="input-dark" placeholder="John Doe"
                    required autofocus autocomplete="name">
                @error('full_name')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="label-dark">Phone <span class="text-slate-600">(optional)</span></label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                    class="input-dark" placeholder="0812345678"
                    autocomplete="tel">
                @error('phone')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="label-dark">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="input-dark" placeholder="you@example.com"
                    required autocomplete="username">
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="label-dark">Password</label>
                <input id="password" type="password" name="password"
                    class="input-dark" placeholder="Min. 8 characters"
                    required autocomplete="new-password">
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="label-dark">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="input-dark" placeholder="Repeat password"
                    required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary mt-2">Create account</button>
        </form>

        {{-- Divider --}}
        <div class="my-6 flex items-center gap-3">
            <div class="flex-1 h-px bg-white/10"></div>
            <span class="text-xs text-slate-500">or</span>
            <div class="flex-1 h-px bg-white/10"></div>
        </div>

        <p class="text-center text-sm text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-green-400 hover:text-green-300 transition-colors">Sign in →</a>
        </p>
    </div>
</x-guest-layout>
