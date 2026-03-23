<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Account</p>
            <h2 class="text-xl font-bold text-white leading-tight">Profile Settings</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="card p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="card p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="card p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
