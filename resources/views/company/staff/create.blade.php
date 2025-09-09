@extends('layouts.company')

@section('title', 'Add New Staff Member')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-semibold text-gray-800">Add New Staff Member</h2>
        <p class="mt-1 text-sm text-gray-600">Fill in the details below to add a new staff member to your company.</p>
    </div>

    <form action="{{ route('company.staff.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf

        <!-- Personal Information -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-user-circle mr-2"></i>Personal Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <x-label for="name" value="Full Name *" />
                    <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required autofocus />
                    <x-input-error for="name" class="mt-1" />
                </div>

                <!-- Email -->
                <div>
                    <x-label for="email" value="Email Address *" />
                    <x-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required />
                    <x-input-error for="email" class="mt-1" />
                </div>

                <!-- Phone -->
                <div>
                    <x-label for="phone" value="Phone Number *" />
                    <x-input id="phone" name="phone" type="tel" class="mt-1 block w-full" value="{{ old('phone') }}" required />
                    <x-input-error for="phone" class="mt-1" />
                </div>

                <!-- Alternate Phone -->
                <div>
                    <x-label for="alt_phone" value="Alternate Phone" />
                    <x-input id="alt_phone" name="alt_phone" type="tel" class="mt-1 block w-full" value="{{ old('alt_phone') }}" />
                    <x-input-error for="alt_phone" class="mt-1" />
                </div>

                <!-- Date of Birth -->
                <div>
                    <x-label for="dob" value="Date of Birth *" />
                    <x-input id="dob" name="dob" type="date" class="mt-1 block w-full" value="{{ old('dob') }}" required />
                    <x-input-error for="dob" class="mt-1" />
                </div>

                <!-- Joining Date -->
                <div>
                    <x-label for="joining_date" value="Joining Date *" />
                    <x-input id="joining_date" name="joining_date" type="date" class="mt-1 block w-full" value="{{ old('joining_date', now()->format('Y-m-d')) }}" required />
                    <x-input-error for="joining_date" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-address-card mr-2"></i>Address Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Address Line 1 -->
                <div class="md:col-span-2">
                    <x-label for="address" value="Address Line 1 *" />
                    <x-input id="address" name="address" type="text" class="mt-1 block w-full" value="{{ old('address') }}" required />
                    <x-input-error for="address" class="mt-1" />
                </div>

                <!-- Address Line 2 -->
                <div class="md:col-span-2">
                    <x-label for="address2" value="Address Line 2" />
                    <x-input id="address2" name="address2" type="text" class="mt-1 block w-full" value="{{ old('address2') }}" />
                    <x-input-error for="address2" class="mt-1" />
                </div>

                <!-- City -->
                <div>
                    <x-label for="city" value="City *" />
                    <x-input id="city" name="city" type="text" class="mt-1 block w-full" value="{{ old('city') }}" required />
                    <x-input-error for="city" class="mt-1" />
                </div>

                <!-- State -->
                <div>
                    <x-label for="state" value="State *" />
                    <x-input id="state" name="state" type="text" class="mt-1 block w-full" value="{{ old('state') }}" required />
                    <x-input-error for="state" class="mt-1" />
                </div>

                <!-- Pincode -->
                <div>
                    <x-label for="pincode" value="Pincode *" />
                    <x-input id="pincode" name="pincode" type="text" class="mt-1 block w-full" value="{{ old('pincode') }}" required />
                    <x-input-error for="pincode" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-phone-alt mr-2"></i>Emergency Contact
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Emergency Contact Name -->
                <div>
                    <x-label for="emergency_contact" value="Contact Person Name *" />
                    <x-input id="emergency_contact" name="emergency_contact" type="text" class="mt-1 block w-full" value="{{ old('emergency_contact') }}" required />
                    <x-input-error for="emergency_contact" class="mt-1" />
                </div>

                <!-- Emergency Phone -->
                <div>
                    <x-label for="emergency_phone" value="Emergency Phone Number *" />
                    <x-input id="emergency_phone" name="emergency_phone" type="tel" class="mt-1 block w-full" value="{{ old('emergency_phone') }}" required />
                    <x-input-error for="emergency_phone" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Identity Proof -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-id-card mr-2"></i>Identity Proof
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Aadhar Number -->
                <div>
                    <x-label for="aadhar_number" value="Aadhar Number *" />
                    <x-input id="aadhar_number" name="aadhar_number" type="text" class="mt-1 block w-full" value="{{ old('aadhar_number') }}" required />
                    <x-input-error for="aadhar_number" class="mt-1" />
                </div>

                <!-- PAN Number -->
                <div>
                    <x-label for="pan_number" value="PAN Number" />
                    <x-input id="pan_number" name="pan_number" type="text" class="mt-1 block w-full" value="{{ old('pan_number') }}" />
                    <x-input-error for="pan_number" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-university mr-2"></i>Bank Details
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bank Name -->
                <div>
                    <x-label for="bank_name" value="Bank Name *" />
                    <x-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" value="{{ old('bank_name') }}" required />
                    <x-input-error for="bank_name" class="mt-1" />
                </div>

                <!-- Account Number -->
                <div>
                    <x-label for="account_number" value="Account Number *" />
                    <x-input id="account_number" name="account_number" type="text" class="mt-1 block w-full" value="{{ old('account_number') }}" required />
                    <x-input-error for="account_number" class="mt-1" />
                </div>

                <!-- IFSC Code -->
                <div>
                    <x-label for="ifsc_code" value="IFSC Code *" />
                    <x-input id="ifsc_code" name="ifsc_code" type="text" class="mt-1 block w-full" value="{{ old('ifsc_code') }}" required />
                    <x-input-error for="ifsc_code" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Salary Information -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-money-bill-wave mr-2"></i>Salary Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Salary Type -->
                <div>
                    <x-label for="salary_type" value="Salary Type *" />
                    <select id="salary_type" name="salary_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                        <option value="" disabled selected>Select Salary Type</option>
                        <option value="fixed" {{ old('salary_type') == 'fixed' ? 'selected' : '' }}>Fixed Salary</option>
                        <option value="per_call" {{ old('salary_type') == 'per_call' ? 'selected' : '' }}>Per Call</option>
                        <option value="commission" {{ old('salary_type') == 'commission' ? 'selected' : '' }}>Commission Based</option>
                    </select>
                    <x-input-error for="salary_type" class="mt-1" />
                </div>

                <!-- Salary Amount -->
                <div>
                    <x-label for="salary" value="Salary Amount (₹) *" />
                    <x-input id="salary" name="salary" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('salary') }}" required />
                    <x-input-error for="salary" class="mt-1" />
                </div>

                <!-- Allow Part Deduction -->
                <div class="flex items-center">
                    <input id="allow_part_deduction" name="allow_part_deduction" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('allow_part_deduction') ? 'checked' : '' }}>
                    <label for="allow_part_deduction" class="ml-2 block text-sm text-gray-700">
                        Allow Part Deduction
                    </label>
                </div>

                <!-- Status -->
                <div class="flex items-center">
                    <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Active
                    </label>
                </div>
            </div>
        </div>

        <!-- Roles -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-user-shield mr-2"></i>Roles & Permissions
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($roles as $id => $name)
                    <div class="flex items-center">
                        <input id="role-{{ $id }}" name="roles[]" type="checkbox" value="{{ $id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($id, old('roles', [])) ? 'checked' : '' }}>
                        <label for="role-{{ $id }}" class="ml-2 block text-sm text-gray-700">
                            {{ ucfirst($name) }}
                        </label>
                    </div>
                @endforeach
            </div>
            <x-input-error for="roles" class="mt-1" />
        </div>

        <!-- Documents -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-file-upload mr-2"></i>Documents
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-center w-full">
                    <label for="documents" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF (MAX. 5MB each)</p>
                        </div>
                        <input id="documents" name="documents[]" type="file" class="hidden" multiple />
                    </label>
                </div>
                <div id="file-list" class="grid grid-cols-1 gap-2">
                    <!-- Files will be listed here -->
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-key mr-2"></i>Account Security
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <x-label for="password" value="Password *" />
                    <x-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                    <x-input-error for="password" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-label for="password_confirmation" value="Confirm Password *" />
                    <x-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                    <x-input-error for="password_confirmation" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
            <a href="{{ route('company.staff.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Cancel
            </a>
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-user-plus mr-2"></i>Add Staff Member
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // File upload preview
    document.getElementById('documents').addEventListener('change', function(e) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = ''; // Clear previous list
        
        Array.from(e.target.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-md';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-file-alt text-gray-500 mr-2"></i>
                    <span class="text-sm text-gray-700">${file.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024).toFixed(2)} KB)</span>
                </div>
            `;
            fileList.appendChild(fileItem);
        });
    });

    // Initialize date pickers with max date as today for DOB
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dob').setAttribute('max', today);
    });
</script>
@endpush
@endsection
