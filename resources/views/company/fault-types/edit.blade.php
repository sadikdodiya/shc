@extends('layouts.company')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Edit Fault Type</h2>
            <p class="text-gray-600 text-sm">Update the details of this fault type.</p>
        </div>
        
        <form action="{{ route('company.fault-types.update', $faultType) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $faultType->name) }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $faultType->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input id="status-active" name="status" type="radio" value="active" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                               {{ old('status', $faultType->status) === 'active' ? 'checked' : '' }}>
                        <label for="status-active" class="ml-2 block text-sm text-gray-700">
                            Active
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="status-inactive" name="status" type="radio" value="inactive" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                               {{ old('status', $faultType->status) === 'inactive' ? 'checked' : '' }}>
                        <label for="status-inactive" class="ml-2 block text-sm text-gray-700">
                            Inactive
                        </label>
                    </div>
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-between items-center">
                <div>
                    <form action="{{ route('company.fault-types.destroy', $faultType) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this fault type?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('company.fault-types.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Fault Type
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
