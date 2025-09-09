@extends('layouts.superadmin')

@section('title', 'Edit Package: ' . $package->name)

@section('content')
<div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Edit Package: {{ $package->name }}
        </h3>
        
        <form action="{{ route('admin.packages.update', $package) }}" method="POST" class="mt-5">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Package Information</h3>
                </div>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Company</label>
                        <div class="mt-1">
                            <p class="text-sm text-gray-900">{{ $package->company->name }}</p>
                            <input type="hidden" name="company_id" value="{{ $package->company_id }}">
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Package Name <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ config('app.currency_symbol', '$') }}</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price', $package->price) }}" step="0.01" min="0" required
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md @error('price') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="0.00">
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="duration_months" class="block text-sm font-medium text-gray-700">Duration (Months) <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="number" name="duration_months" id="duration_months" value="{{ old('duration_months', $package->duration_months) }}" min="1" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('duration_months') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('duration_months')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="staff_limit" class="block text-sm font-medium text-gray-700">Staff Limit <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="number" name="staff_limit" id="staff_limit" value="{{ old('staff_limit', $package->staff_limit) }}" min="1" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('staff_limit') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('staff_limit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $package->start_date->format('Y-m-d')) }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('start_date') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select id="status" name="status" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('status') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="pending" {{ old('status', $package->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ old('status', $package->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('status', $package->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ old('status', $package->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                            @foreach($features as $feature => $label)
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="feature-{{ $feature }}" name="features[]" type="checkbox" value="{{ $feature }}" 
                                        {{ in_array($feature, old('features', $package->features ?? [])) ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="feature-{{ $feature }}" class="font-medium text-gray-700">{{ $label }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('features')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="pt-5">
                <div class="flex justify-between">
                    <div>
                        <a href="{{ route('admin.packages.show', $package) }}" class="bg-gray-100 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View Details
                        </a>
                    </div>
                    <div class="flex">
                        <a href="{{ route('admin.packages.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Package
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Calculate end date based on start date and duration
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const durationMonthsInput = document.getElementById('duration_months');
        
        function calculateEndDate() {
            if (startDateInput.value && durationMonthsInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + parseInt(durationMonthsInput.value));
                
                // Format the date as YYYY-MM-DD
                const formattedDate = endDate.toISOString().split('T')[0];
                document.getElementById('end_date').textContent = formattedDate;
            }
        }
        
        startDateInput.addEventListener('change', calculateEndDate);
        durationMonthsInput.addEventListener('input', calculateEndDate);
        
        // Initial calculation
        calculateEndDate();
    });
</script>
@endpush
@endsection
