@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Payment #{{ $payment->id }} - {{ $payment->staff->name }}
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Created on {{ $payment->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('company.staff-payments.edit', $payment) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('company.staff-payments.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Back to Payments
                    </a>
                </div>
            </div>

            <!-- Status Bar -->
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ][$payment->status] ?? 'bg-gray-100 text-gray-800';
                                
                                $typeColors = [
                                    'credit' => 'bg-green-100 text-green-800',
                                    'debit' => 'bg-red-100 text-red-800',
                                ][$payment->type] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                            
                            <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors }}">
                                {{ ucfirst($payment->type) }}
                                @if($payment->is_salary_advance)
                                    (Advance)
                                @endif
                            </span>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($payment->status === 'approved' && $payment->approved_at)
                                Approved on {{ $payment->approved_at->format('M d, Y') }} by {{ $payment->approvedBy->name ?? 'System' }}
                            @elseif($payment->status === 'rejected' && $payment->updated_at)
                                Rejected on {{ $payment->updated_at->format('M d, Y') }}
                            @else
                                Awaiting approval
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold {{ $payment->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $payment->type === 'credit' ? '+' : '-' }}{{ number_format($payment->amount, 2) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Staff Information</h3>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-medium text-xl">{{ substr($payment->staff->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $payment->staff->name }}</h4>
                                    <div class="text-sm text-gray-500">{{ $payment->staff->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->staff->phone ?? 'N/A' }}</div>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($payment->staff->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Payment Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Transaction Type</span>
                                    <span class="text-sm text-gray-900 font-medium">
                                        {{ ucfirst($payment->type) }}
                                        @if($payment->is_salary_advance)
                                            (Advance)
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Payment Method</span>
                                    <span class="text-sm text-gray-900 font-medium">
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </div>
                                @if($payment->reference)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Reference</span>
                                    <span class="text-sm text-gray-900 font-mono">{{ $payment->reference }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Payment Date</span>
                                    <span class="text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Status</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($payment->notes)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Notes</h3>
                            <div class="prose max-w-none text-sm text-gray-800">
                                {!! nl2br(e($payment->notes)) !!}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div>
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <p class="text-gray-700">{{ $payment->description }}</p>
                        </div>

                        @if($payment->is_salary_advance)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Salary Advance</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>This is an advance payment against future salary. It will be deducted from the staff's next salary payment.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Audit Trail</h3>
                            </div>
                            <div class="px-4 py-5 sm:p-0
                                <dl class="sm:divide-y sm:divide-gray-200">
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $payment->created_at->format('M d, Y h:i A') }}<br>
                                            <span class="text-gray-500">by {{ $payment->createdBy->name ?? 'System' }}</span>
                                        </dd>
                                    </div>
                                    @if($payment->updated_at != $payment->created_at)
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $payment->updated_at->format('M d, Y h:i A') }}<br>
                                            <span class="text-gray-500">by {{ $payment->updatedBy->name ?? 'System' }}</span>
                                        </dd>
                                    </div>
                                    @endif
                                    @if($payment->approved_by && $payment->status === 'approved')
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Approved</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $payment->approved_at ? $payment->approved_at->format('M d, Y h:i A') : 'N/A' }}<br>
                                            <span class="text-gray-500">by {{ $payment->approvedBy->name ?? 'System' }}</span>
                                        </dd>
                                    </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-4 py-4 sm:px-6 border-t border-gray-200 bg-gray-50 flex justify-between">
                <div>
                    @if($payment->status === 'pending')
                        <form action="{{ route('company.staff-payments.update-status', [$payment, 'status' => 'approved']) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Approve Payment
                            </button>
                        </form>
                        
                        <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden');" 
                                class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Reject
                        </button>
                        
                        <form id="reject-form" action="{{ route('company.staff-payments.update-status', [$payment, 'status' => 'rejected']) }}" method="POST" class="hidden mt-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex">
                                <input type="text" name="rejection_reason" required 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                       placeholder="Reason for rejection">
                                <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Confirm Reject
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
                
                <div>
                    <a href="{{ route('company.staff-payments.edit', $payment) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </a>
                    
                    @can('delete', $payment)
                    <form action="{{ route('company.staff-payments.destroy', $payment) }}" method="POST" class="inline ml-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this payment record? This action cannot be undone.')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Any additional JavaScript for the show page can go here
</script>
@endpush
@endsection
