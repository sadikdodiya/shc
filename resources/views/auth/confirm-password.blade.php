<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-input 
                    id="password" 
                    class="block w-full mt-1"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    autofocus
                />
                <button type="button" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                        onclick="togglePasswordVisibility('password')">
                    <i class="far fa-eye" id="toggle-password"></i>
                </button>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <x-button class="bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Confirm') }}
            </x-button>
        </div>
    </form>

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
    </script>
    @endpush
</x-guest-layout>
