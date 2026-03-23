<section class="space-y-5">
    <div>
        <h2 class="text-base font-semibold text-white">Delete Account</h2>
        <p class="text-sm text-slate-400 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
    </div>

    <button type="button" class="btn-red" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Delete Account</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6" style="background:#111827">
            @csrf
            @method('delete')

            <h2 class="text-base font-semibold text-white mb-2">Are you sure you want to delete your account?</h2>
            <p class="text-sm text-slate-400 mb-5">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>

            <div class="mb-5">
                <label for="password" class="label block mb-1.5 text-sm sr-only">Password</label>
                <input id="password" name="password" type="password" class="input-field w-3/4" placeholder="Password" />
                @if($errors->userDeletion->get('password')) <p class="error-msg mt-1 text-xs">{{ $errors->userDeletion->first('password') }}</p> @endif
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" class="btn-slate" x-on:click="$dispatch('close')">Cancel</button>
                <button type="submit" class="btn-red">Delete Account</button>
            </div>
        </form>
    </x-modal>
</section>
