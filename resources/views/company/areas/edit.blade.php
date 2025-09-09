@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">
                        <a href="{{ route('company.areas.index') }}" class="text-blue-600 hover:text-blue-900">Areas</a>
                        <span class="text-gray-400 mx-2">/</span>
                        <a href="{{ route('company.areas.show', $area) }}" class="text-blue-600 hover:text-blue-900">{{ $area->name }}</a>
                        <span class="text-gray-400 mx-2">/</span>
                        Edit
                    </h2>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Whoops!</strong>
                        <span class="block sm:inline"> There are some errors with your input.</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('company.areas.update', $area) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Basic Information</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Update the basic details of the service area.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Area Name <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            id="name" 
                                            value="{{ old('name', $area->name) }}"
                                            required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Location Details</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Update the location details for this area.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                        <input 
                                            type="text" 
                                            name="address" 
                                            id="address" 
                                            value="{{ old('address', $area->address) }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="city" 
                                            id="city" 
                                            value="{{ old('city', $area->city) }}" 
                                            required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="state" class="block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="state" 
                                            id="state" 
                                            value="{{ old('state', $area->state) }}" 
                                            required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="country" class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="country" 
                                            id="country" 
                                            value="{{ old('country', $area->country) }}" 
                                            required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="pincode" 
                                            id="pincode" 
                                            value="{{ old('pincode', $area->pincode) }}" 
                                            required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Contact Information</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Primary contact details for this area.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                                        <input 
                                            type="text" 
                                            name="contact_person" 
                                            id="contact_person" 
                                            value="{{ old('contact_person', $area->contact_person) }}" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                        <input 
                                            type="text" 
                                            name="contact_number" 
                                            id="contact_number" 
                                            value="{{ old('contact_number', $area->contact_number) }}" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status and Notes -->
                    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Status & Notes</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Update status and add any additional notes.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                        <select 
                                            id="status" 
                                            name="status" 
                                            required
                                            class="mt-1 block w-full border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                            <option value="active" {{ old('status', $area->status) === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $area->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-span-6">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea 
                                            name="notes" 
                                            id="notes" 
                                            rows="3" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >{{ old('notes', $area->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between">
                        <div>
                            @can('delete', $area)
                            <button 
                                type="button" 
                                onclick="if(confirm('Are you sure you want to delete this area? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                <i class="fas fa-trash mr-2"></i> Delete Area
                            </button>
                            @endcan
                        </div>
                        <div class="flex">
                            <a 
                                href="{{ route('company.areas.show', $area) }}" 
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3"
                            >
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Delete Form (Hidden) -->
                @can('delete', $area)
                <form id="delete-form" action="{{ route('company.areas.destroy', $area) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any custom JavaScript here if needed
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any initialization code here
    });
</script>
@endpush

@endsection
