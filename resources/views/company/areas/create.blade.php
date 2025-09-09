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
                        Create New Area
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

                <form action="{{ route('company.areas.store') }}" method="POST">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Basic Information</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Enter the basic details of the service area.
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
                                            value="{{ old('name') }}"
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
                                    Enter the location details for this area.
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
                                            value="{{ old('address') }}"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="city" 
                                            id="city" 
                                            value="{{ old('city') }}" 
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
                                            value="{{ old('state') }}" 
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
                                            value="{{ old('country', 'India') }}" 
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
                                            value="{{ old('pincode') }}" 
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
                                            value="{{ old('contact_person') }}" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                        <input 
                                            type="text" 
                                            name="contact_number" 
                                            id="contact_number" 
                                            value="{{ old('contact_number') }}" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Additional Information</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Any additional notes or information about this area.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea 
                                            name="notes" 
                                            id="notes" 
                                            rows="3" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <a 
                            href="{{ route('company.areas.index') }}" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Save Area
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
