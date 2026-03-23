<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-500/30 bg-green-500/10 px-4 py-1.5">
            <span class="h-1.5 w-1.5 rounded-full bg-green-400 badge-pulse"></span>
            <span class="text-xs font-semibold uppercase tracking-widest text-green-400">Welcome back</span>
        </div>
        <h1 class="font-display text-3xl font-extrabold text-white">Sign in to EventHub</h1>
        <p class="mt-2 text-sm text-slate-400">เข้าสู่ระบบเพื่อจัดการ Events และลงทะเบียนกิจกรรม</p>
    </div>

    {{-- Card --}}
    <div class="glass-card rounded-2xl p-8">
        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-5 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm font-medium text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="label-dark">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="input-dark" placeholder="you@example.com"
                    required autofocus autocomplete="username">
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="label-dark" style="margin-bottom:0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-green-400 hover:text-green-300 transition-colors">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password"
                    class="input-dark" placeholder="••••••••"
                    required autocomplete="current-password">
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2.5">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-green-500 focus:ring-green-500 focus:ring-offset-0">
                <label for="remember_me" class="text-sm text-slate-400 cursor-pointer select-none">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary mt-2">Sign in</button>
        </form>

        {{-- Divider --}}
        <div class="my-6 flex items-center gap-3">
            <div class="flex-1 h-px bg-white/10"></div>
            <span class="text-xs text-slate-500">or</span>
            <div class="flex-1 h-px bg-white/10"></div>
        </div>

        <p class="text-center text-sm text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-green-400 hover:text-green-300 transition-colors">Create one free →</a>
        </p>

        {{-- Demo hint --}}
        <div class="mt-6 rounded-xl border border-green-500/20 bg-green-500/8 p-3 text-xs text-slate-400 text-center">
            Demo admin: <span class="font-semibold text-green-400">admin@example.com</span> · <span class="font-semibold text-green-400">password</span>
        </div>
    </div>
</x-guest-layout>
