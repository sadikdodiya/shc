@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Stock Movement
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fas fa-box mr-1.5"></i>
                        {{ $item->name }} ({{ $item->code }})
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5"></i>
                        {{ $entry->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="mt-2">
                        @if($entry->type === 'in')
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-arrow-down mr-1"></i> Stock In
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-arrow-up mr-1"></i> Stock Out
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('company.items.part-entries.show', [$item, $entry]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Cancel
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('company.items.part-entries.update', [$item, $entry]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-6">
                        <!-- Transaction Type (Readonly) -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Transaction Type
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="mt-1">
                                    @if($entry->type === 'in')
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i> Stock In
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up mr-1"></i> Stock Out
                                        </span>
                                    @endif
                                    <p class="mt-2 text-sm text-gray-500">
                                        Transaction type cannot be changed after creation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity (Readonly) -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Quantity
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="mt-1">
                                    <p class="text-lg font-medium {{ $entry->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $entry->formatted_quantity }} {{ $item->unit }}
                                    </p>
                                    <p class="mt-2 text-sm text-gray-500">
                                        Quantity cannot be changed after creation. Please create a new entry to adjust stock levels.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Reference -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="reference" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Reference
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="reference" id="reference" value="{{ old('reference', $entry->reference) }}" class="max-w-lg block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-2 text-sm text-gray-500">Reference (e.g., PO #, Invoice #)</p>
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
                                <textarea id="notes" name="notes" rows="4" class="max-w-lg shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border border-gray-300 rounded-md">{{ old('notes', $entry->notes) }}</textarea>
                                <p class="mt-2 text-sm text-gray-500">Add any additional notes about this transaction.</p>
                                @error('notes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Stock Impact -->
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-gray-200 sm:pt-5">
                            <span class="block text-sm font-medium text-gray-700">
                                Stock Impact
                            </span>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Current Stock</p>
                                            <p class="text-lg font-semibold text-gray-900">{{ $item->formatted_stock_quantity }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Transaction</p>
                                            <p class="text-lg font-semibold {{ $entry->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $entry->formatted_quantity }} {{ $item->unit }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('company.items.part-entries.show', [$item, $entry]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Movement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Disable form submission if the entry is older than 1 day
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        const createdAt = new Date('{{ $entry->created_at }}');
        const oneDayInMs = 24 * 60 * 60 * 1000;
        const isOlderThanOneDay = (Date.now() - createdAt) > oneDayInMs;
        
        if (isOlderThanOneDay) {
            submitButton.disabled = true;
            submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            
            // Add a message
            const message = document.createElement('p');
            message.className = 'mt-2 text-sm text-red-600';
            message.textContent = 'This entry is older than 1 day and cannot be modified.';
            submitButton.parentNode.insertBefore(message, submitButton.nextSibling);
        }
    });
</script>
@endpush

@endsection
