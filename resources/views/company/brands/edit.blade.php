@extends('layouts.company')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Brand</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li>
                        <div class="flex items-center">
                            <a href="{{ route('company.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('company.brands.index') }}" class="text-blue-600 hover:text-blue-800 ml-1 md:ml-2">Brands</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-400 ml-1 md:ml-2">Edit {{ $brand->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('company.brands.update', $brand) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-600">*</span></label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input id="status-active" name="status" type="radio" value="active" 
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                   {{ old('status', $brand->status) === 'active' ? 'checked' : '' }}>
                            <label for="status-active" class="ml-2 block text-sm text-gray-700">Active</label>
                        </div>
                        <div class="flex items-center">
                            <input id="status-inactive" name="status" type="radio" value="inactive" 
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                   {{ old('status', $brand->status) === 'inactive' ? 'checked' : '' }}>
                            <label for="status-inactive" class="ml-2 block text-sm text-gray-700">Inactive</label>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        <a href="{{ route('company.brands.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to Brands
                        </a>
                    </div>
                    <div class="flex space-x-4">
                        <button type="button" onclick="confirmDelete('{{ $brand->name }}')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Brand
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Brand
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form (Hidden) -->
            <form id="delete-form" action="{{ route('company.brands.destroy', $brand) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(brandName) {
        if (confirm(`Are you sure you want to delete "${brandName}"? This action cannot be undone.`)) {
            event.preventDefault();
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush

@endsection
