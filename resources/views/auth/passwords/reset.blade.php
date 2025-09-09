<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />
                <x-input 
                    id="email" 
                    class="block mt-1 w-full" 
                    type="email" 
                    name="email" 
                    :value="old('email', $request->email)" 
                    required 
                    autofocus 
                    autocomplete="username"
                />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />
                <div class="relative">
                    <x-input 
                        id="password" 
                        class="block w-full mt-1"
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password"
                        placeholder="Enter your new password"
                    />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            onclick="togglePasswordVisibility('password')">
                        <i class="far fa-eye" id="toggle-password"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Minimum 8 characters, with at least one uppercase letter, one lowercase letter, one number, and one special character.
                </p>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />
                <div class="relative">
                    <x-input 
                        id="password_confirmation" 
                        class="block w-full mt-1"
                        type="password"
                        name="password_confirmation"
                        required 
                        autocomplete="new-password"
                        placeholder="Confirm your new password"
                    />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            onclick="togglePasswordVisibility('password_confirmation')">
                        <i class="far fa-eye" id="toggle-password_confirmation"></i>
                    </button>
                </div>
                <div id="password-match" class="mt-1 text-sm text-gray-600 hidden">
                    <i class="fas fa-check-circle text-green-500"></i> Passwords match
                </div>
                <div id="password-mismatch" class="mt-1 text-sm text-red-600 hidden">
                    <i class="fas fa-times-circle text-red-500"></i> Passwords do not match
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>

    @push('scripts')
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(`toggle-${fieldId}`);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Check if passwords match
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('password-match');
        const passwordMismatch = document.getElementById('password-mismatch');

        function checkPasswords() {
            if (passwordInput.value === '' && confirmPasswordInput.value === '') {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.add('hidden');
                return;
            }

            if (passwordInput.value === confirmPasswordInput.value) {
                passwordMatch.classList.remove('hidden');
                passwordMismatch.classList.add('hidden');
            } else {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.remove('hidden');
            }
        }

        passwordInput.addEventListener('input', checkPasswords);
        confirmPasswordInput.addEventListener('input', checkPasswords);

        // Form validation
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                passwordMismatch.classList.remove('hidden');
                passwordMismatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
    @endpush
</x-guest-layout>
