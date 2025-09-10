<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email/Phone -->
            <div>
                <x-label for="email" :value="__('Email or Phone')" />
                <div class="relative mt-1 rounded-md shadow-sm">
                    <x-input 
                        id="email" 
                        class="block w-full pl-3 pr-12" 
                        type="text" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        placeholder="Enter your email or phone number"
                        autocomplete="username"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span id="input-type-indicator" class="text-gray-500 sm:text-sm">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500" id="input-hint">
                    Enter your email address or phone number
                </p>
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
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    <button type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            onclick="togglePasswordVisibility()">
                        <i class="far fa-eye" id="toggle-password"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <x-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>

    @push('scripts')
    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
            return false; // Prevent form submission
        }

        // Update input type indicator based on input
        document.getElementById('email').addEventListener('input', function(e) {
            const input = e.target.value;
            const indicator = document.getElementById('input-type-indicator');
            const hint = document.getElementById('input-hint');
            
            if (input.includes('@')) {
                // Email input
                indicator.innerHTML = '<i class="fas fa-envelope"></i>';
                hint.textContent = 'Enter your email address';
            } else if (/^[0-9+\-\s()]*$/.test(input)) {
                // Phone input
                indicator.innerHTML = '<i class="fas fa-phone"></i>';
                hint.textContent = 'Enter your phone number with country code';
                    e.target.value = cleaned;
                }
            } else {
                // Default to email
                indicator.innerHTML = '<i class="fas fa-user"></i>';
                hint.textContent = 'Enter your email or phone number';
                e.target.setAttribute('type', 'text');
            }
        });

        // Handle Enter key to move to password field or submit form
        document.getElementById('email').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('password').focus();
            }
        });
        
        // Allow form submission with Enter key in password field
        document.getElementById('password').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').requestSubmit();
            }
        });
    </script>
    @endpush
</x-guest-layout>
