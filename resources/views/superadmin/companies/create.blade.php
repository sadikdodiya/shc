@extends('layouts.superadmin')

@section('title', 'Add New Company')

@section('content')
<div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Add New Company
        </h3>
        
        <form action="{{ route('admin.companies.store') }}" method="POST" class="mt-5">
            @csrf
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Company Information</h3>
                </div>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Company Name <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('contact_person') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('contact_person')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('address') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="city" id="city" value="{{ old('city') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('city') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="state" class="block text-sm font-medium text-gray-700">State/Province <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="state" id="state" value="{{ old('state') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('state') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('state')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">ZIP/Postal Code <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('postal_code') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('postal_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="country" class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select id="country" name="country" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('country') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Select a country</option>
                                @foreach($countries as $code => $name)
                                    <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <div class="mt-1">
                            <input type="url" name="website" id="website" value="{{ old('website') }}"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('website') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('website')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-5 mt-10">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Admin Account</h3>
                    <p class="mt-2 max-w-4xl text-sm text-gray-500">This will create an admin account for the company.</p>
                </div>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="admin_name" class="block text-sm font-medium text-gray-700">Admin Name <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('admin_name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('admin_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin Email <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('admin_email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('admin_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="admin_phone" class="block text-sm font-medium text-gray-700">Admin Phone <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="tel" name="admin_phone" id="admin_phone" value="{{ old('admin_phone') }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('admin_phone') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('admin_phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="password" name="password" id="password" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('admin.companies.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Initialize phone input with intl-tel-input
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.querySelector('#phone');
        const adminPhoneInput = document.querySelector('#admin_phone');
        
        if (phoneInput) {
            window.intlTelInput(phoneInput, {
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
                separateDialCode: true,
                preferredCountries: ['us', 'gb', 'ca', 'au'],
                initialCountry: 'auto',  
                geoIpLookup: function(success, failure) {
                    fetch('https://ipinfo.io/json?token=YOUR_IPINFO_TOKEN')
                        .then(response => response.json())
                        .then(data => success(data.country.toLowerCase()))
                        .catch(() => failure());
                },
            });
        }
        
        if (adminPhoneInput) {
            window.intlTelInput(adminPhoneInput, {
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
                separateDialCode: true,
                preferredCountries: ['us', 'gb', 'ca', 'au'],
                initialCountry: 'auto',
                geoIpLookup: function(success, failure) {
                    fetch('https://ipinfo.io/json?token=YOUR_IPINFO_TOKEN')
                        .then(response => response.json())
                        .then(data => success(data.country.toLowerCase()))
                        .catch(() => failure());
                },
            });
        }
    });
</script>
@endpush
@endsection
