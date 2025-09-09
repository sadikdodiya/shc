@extends('layouts.company')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header with Back Button -->
        <div class="mb-6 flex items-center">
            <a href="{{ route('company.products.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                <div class="flex items-center mt-1 text-sm text-gray-500">
                    <span>Brand: {{ $product->brand->name }}</span>
                    <span class="mx-2">•</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
            </div>
            <div class="ml-auto flex space-x-3">
                <a href="{{ route('company.products.edit', $product) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('company.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Product Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed information about the product.</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $product->id }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $product->name }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Brand</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ route('company.brands.show', $product->brand) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $product->brand->name }}
                            </a>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $product->description ?? 'No description provided.' }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                            <form action="{{ route('company.products.toggle-status', $product) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                    ({{ $product->status === 'active' ? 'Deactivate' : 'Activate' }})
                                </button>
                            </form>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $product->created_at->format('M d, Y \a\t h:i A') }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $product->updated_at->format('M d, Y \a\t h:i A') }}
                            <span class="text-gray-500 text-xs">({{ $product->updated_at->diffForHumans() }})</span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Complaints Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Related Complaints</h2>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            
            <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @if($product->complaints->count() > 0)
                        @foreach($product->complaints->take(5) as $complaint)
                            <li>
                                <a href="#" class="block hover:bg-gray-50">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                Complaint #{{ $complaint->id }}
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $complaint->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($complaint->status) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-calendar-alt mr-1.5 h-5 w-5 text-gray-400"></i>
                                                    {{ $complaint->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <i class="fas fa-user mr-1.5 h-5 w-5 text-gray-400"></i>
                                                {{ $complaint->customer_name }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li class="px-4 py-5 text-center text-gray-500">
                            No complaints found for this product.
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any JavaScript for the product show page
    });
</script>
@endpush
