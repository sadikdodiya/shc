@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Add Stock Movement
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fas fa-box mr-1.5"></i>
                        {{ $item->name }} ({{ $item->code }})
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fas fa-boxes mr-1.5"></i>
                        Current Stock: {{ $item->formatted_stock_quantity }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('company.items.part-entries.index', $item) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Movements
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('company.items.part-entries.store', $item) }}" method="POST">
                @csrf
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-6">
                        <!-- Transaction Type -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="type" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Transaction Type <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-center">
                                        <input id="type-in" name="type" type="radio" value="in" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked>
                                        <label for="type-in" class="ml-3 block text-sm font-medium text-gray-700">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-down mr-1"></i> Stock In
                                            </span>
                                            <p class="text-gray-500 text-sm mt-1">Add items to inventory</p>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="type-out" name="type" type="radio" value="out" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="type-out" class="ml-3 block text-sm font-medium text-gray-700">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-arrow-up mr-1"></i> Stock Out
                                            </span>
                                            <p class="text-gray-500 text-sm mt-1">Remove items from inventory</p>
                                        </label>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="max-w-lg rounded-md shadow-sm">
                                    <input type="number" step="0.01" min="0.01" name="quantity" id="quantity" value="{{ old('quantity') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('quantity') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" required>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Enter the quantity in {{ $item->unit }}</p>
                                @error('quantity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Reference -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="reference" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Reference
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="reference" id="reference" value="{{ old('reference') }}" class="max-w-lg block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-2 text-sm text-gray-500">Optional reference (e.g., PO #, Invoice #)</p>
                                @error('reference')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="notes" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Notes
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <textarea id="notes" name="notes" rows="3" class="max-w-lg shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                                <p class="mt-2 text-sm text-gray-500">Add any additional notes about this transaction.</p>
                                @error('notes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Stock Preview -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-gray-200 sm:pt-5">
                            <span class="block text-sm font-medium text-gray-700">
                                Current Stock
                            </span>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Current Stock</p>
                                            <p class="text-lg font-semibold text-gray-900">{{ $item->formatted_stock_quantity }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">After Transaction</p>
                                            <p id="after-transaction" class="text-lg font-semibold text-gray-900">{{ $item->formatted_stock_quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Movement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update the after transaction preview when quantity or type changes
    document.addEventListener('DOMContentLoaded', function() {
        const typeInputs = document.querySelectorAll('input[name="type"]');
        const quantityInput = document.getElementById('quantity');
        const afterTransactionEl = document.getElementById('after-transaction');
        const unit = '{{ $item->unit }}';
        const currentStock = {{ $item->stock_quantity }};
        
        function updateAfterTransaction() {
            const type = document.querySelector('input[name="type"]:checked').value;
            const quantity = parseFloat(quantityInput.value) || 0;
            
            let newStock = type === 'in' 
                ? currentStock + quantity 
                : currentStock - quantity;
                
            // Ensure stock doesn't go below 0
            newStock = Math.max(0, newStock);
            
            afterTransactionEl.textContent = newStock.toFixed(2) + ' ' + unit;
            
            // Update color based on stock level
            const minStock = {{ $item->minimum_stock ?? 'null' }};
            if (minStock !== null && newStock <= minStock) {
                afterTransactionEl.classList.add('text-yellow-600');
                afterTransactionEl.classList.remove('text-green-600', 'text-gray-900');
            } else if (newStock <= 0) {
                afterTransactionEl.classList.add('text-red-600');
                afterTransactionEl.classList.remove('text-yellow-600', 'text-green-600', 'text-gray-900');
            } else {
                afterTransactionEl.classList.add('text-green-600');
                afterTransactionEl.classList.remove('text-yellow-600', 'text-red-600', 'text-gray-900');
            }
        }
        
        // Add event listeners
        typeInputs.forEach(input => {
            input.addEventListener('change', updateAfterTransaction);
        });
        
        quantityInput.addEventListener('input', updateAfterTransaction);
        
        // Initial update
        updateAfterTransaction();
    });
</script>
@endpush

@endsection
