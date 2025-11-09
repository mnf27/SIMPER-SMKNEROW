<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Ganti Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Pastikan akun anda menggunakan kata sandi yang kuat dan sulit ditebak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="relative">
            <x-input-label for="update_password_current_password" :value="__('Password Lama')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full" autocomplete="current-password" placeholder="Masukkan kata sandi lama Anda" />
            <button type="button" id="toggleCurrentPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center mt-6 text-gray-500 focus:outline-none"
                title="Tampilkan password">
                <svg id="eyeOpenCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg id="eyeClosedCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.056 10.056 0 012.223-3.653M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.88 9.88A3 3 0 0114.12 14.12" />
                </svg>
            </button>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="relative">
            <x-input-label for="update_password_password" :value="__('Password Baru')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" placeholder="Masukkan kata sandi baru" />
            <button type="button" id="toggleNewPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center mt-6 text-gray-500 focus:outline-none"
                title="Tampilkan password">
                <svg id="eyeOpenNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg id="eyeClosedNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.056 10.056 0 012.223-3.653M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.88 9.88A3 3 0 0114.12 14.12" />
                </svg>
            </button>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="relative">
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" placeholder="Ketik ulang kata sandi baru Anda" />
            <button type="button" id="toggleConfirmPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center mt-6 text-gray-500 focus:outline-none"
                title="Tampilkan password">
                <svg id="eyeOpenConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg id="eyeClosedConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.056 10.056 0 012.223-3.653M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.88 9.88A3 3 0 0114.12 14.12" />
                </svg>
            </button>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

{{-- Script toggle password --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fields = [
            { input: 'update_password_current_password', eyeOpen: 'eyeOpenCurrent', eyeClosed: 'eyeClosedCurrent', button: 'toggleCurrentPassword' },
            { input: 'update_password_password', eyeOpen: 'eyeOpenNew', eyeClosed: 'eyeClosedNew', button: 'toggleNewPassword' },
            { input: 'update_password_password_confirmation', eyeOpen: 'eyeOpenConfirm', eyeClosed: 'eyeClosedConfirm', button: 'toggleConfirmPassword' },
        ];

        fields.forEach(f => {
            const input = document.getElementById(f.input);
            const btn = document.getElementById(f.button);
            const open = document.getElementById(f.eyeOpen);
            const close = document.getElementById(f.eyeClosed);

            btn.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                open.classList.toggle('hidden', !isHidden);
                close.classList.toggle('hidden', isHidden);
                btn.title = isHidden ? 'Sembunyikan password' : 'Tampilkan password';
            });
        });
    });
</script>