@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Add New Item</h2>
                    <a href="{{ route('company.items.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Items
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <div class="font-bold">There were some issues with your submission:</div>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('company.items.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Item Information
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Basic details about the item.
                            </p>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                            <dl class="sm:divide-y sm:divide-gray-200">
                                <!-- Name -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="name" class="block">Item Name *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </dd>
                                </div>

                                <!-- Code -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="code" class="block">Item Code</label>
                                        <p class="text-xs text-gray-400 mt-1">Leave blank to auto-generate</p>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </dd>
                                </div>

                                <!-- Description -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="description" class="block">Description</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <textarea id="description" name="description" rows="3" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description') }}</textarea>
                                    </dd>
                                </div>

                                <!-- Category and Unit -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="category" class="block">Category *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1">
                                        <input type="text" name="category" id="category" value="{{ old('category', 'General') }}" required
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </dd>
                                    <dt class="text-sm font-medium text-gray-500 sm:col-start-1">
                                        <label for="unit" class="block">Unit *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1">
                                        <select id="unit" name="unit" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="pcs" {{ old('unit', 'pcs') == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilograms</option>
                                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Grams</option>
                                            <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Liters</option>
                                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliters</option>
                                            <option value="m" {{ old('unit') == 'm' ? 'selected' : '' }}>Meters</option>
                                            <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>Centimeters</option>
                                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                                            <option value="set" {{ old('unit') == 'set' ? 'selected' : '' }}>Set</option>
                                            <option value="pair" {{ old('unit') == 'pair' ? 'selected' : '' }}>Pair</option>
                                        </select>
                                    </dd>
                                </div>

                                <!-- Prices -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="purchase_price" class="block">Purchase Price *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1">
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" name="purchase_price" id="purchase_price" 
                                                value="{{ old('purchase_price', '0.00') }}" step="0.01" min="0" required
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                                placeholder="0.00">
                                        </div>
                                    </dd>
                                    <dt class="text-sm font-medium text-gray-500 sm:col-start-1">
                                        <label for="selling_price" class="block">Selling Price *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1">
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" name="selling_price" id="selling_price" 
                                                value="{{ old('selling_price', '0.00') }}" step="0.01" min="0" required
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                                placeholder="0.00">
                                        </div>
                                    </dd>
                                </div>

                                <!-- Stock -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="stock_quantity" class="block">Initial Stock *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1">
                                        <input type="number" name="stock_quantity" id="stock_quantity" 
                                            value="{{ old('stock_quantity', '0') }}" min="0" required
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </dd>
                                    <dt class="text-sm font-medium text-gray-500 sm:col-start-1">
                                        <label for="minimum_stock" class="block">Minimum Stock Level *</label>
                                        <p class="text-xs text-gray-400 mt-1">Low stock alert threshold</p>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1">
                                        <input type="number" name="minimum_stock" id="minimum_stock" 
                                            value="{{ old('minimum_stock', '5') }}" min="0" required
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </dd>
                                </div>

                                <!-- Status -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="status" class="block">Status *</label>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </dd>
                                </div>

                                <!-- Notes -->
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        <label for="notes" class="block">Notes</label>
                                        <p class="text-xs text-gray-400 mt-1">Any additional information</p>
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <textarea id="notes" name="notes" rows="3" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('company.items.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-calculate selling price if purchase price changes
    document.getElementById('purchase_price').addEventListener('change', function() {
        const purchasePrice = parseFloat(this.value) || 0;
        const sellingPriceInput = document.getElementById('selling_price');
        
        // Only auto-update if selling price is not manually set or is zero
        if (!sellingPriceInput.value || parseFloat(sellingPriceInput.value) === 0) {
            // Add a default 20% markup
            const sellingPrice = purchasePrice * 1.2;
            sellingPriceInput.value = sellingPrice.toFixed(2);
        }
    });
</script>
@endpush
@endsection
