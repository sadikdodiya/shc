@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Stock Movement Details
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
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('company.items.part-entries.index', $item) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Movements
                </a>
                @can('update', $entry)
                <a href="{{ route('company.items.part-entries.edit', [$item, $entry]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                @endcan
                @can('delete', $entry)
                <form action="{{ route('company.items.part-entries.destroy', [$item, $entry]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>

        <!-- Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Movement Information
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Item
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $item->name }} ({{ $item->code }})
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Transaction Type
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($entry->type === 'in')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-arrow-down mr-1"></i> Stock In
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-arrow-up mr-1"></i> Stock Out
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Quantity
                        </dt>
                        <dd class="mt-1 text-sm font-medium {{ $entry->type === 'in' ? 'text-green-600' : 'text-red-600' }} sm:mt-0 sm:col-span-2">
                            {{ $entry->formatted_quantity }} {{ $item->unit }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Reference
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $entry->reference ?? 'N/A' }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Date & Time
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $entry->created_at->format('M d, Y h:i A') }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Recorded By
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $entry->user->name }}
                        </dd>
                    </div>
                    @if($entry->notes)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Notes
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                            {{ $entry->notes }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Stock Impact -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Stock Impact
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-gray-500 rounded-md p-3">
                                        <i class="fas fa-boxes text-white"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Before Transaction
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ number_format($entry->type === 'in' 
                                                        ? $item->stock_quantity - $entry->quantity 
                                                        : $item->stock_quantity + $entry->quantity, 2) }}
                                                </div>
                                                <div class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                                                    {{ $item->unit }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 {{ $entry->type === 'in' ? 'bg-green-500' : 'bg-red-500' }} rounded-md p-3">
                                        <i class="fas {{ $entry->type === 'in' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-white"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                {{ $entry->type === 'in' ? 'Added' : 'Removed' }}
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold {{ $entry->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $entry->formatted_quantity }}
                                                </div>
                                                <div class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                                                    {{ $item->unit }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <i class="fas fa-warehouse text-white"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                After Transaction
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $item->formatted_stock_quantity }}
                                                </div>
                                                <div class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                                                    {{ $item->unit }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between">
            @if($previous = $entry->getPreviousEntry())
                <a href="{{ route('company.items.part-entries.show', [$item, $previous]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Previous
                </a>
            @else
                <div></div>
            @endif
            
            @if($next = $entry->getNextEntry())
                <a href="{{ route('company.items.part-entries.show', [$item, $next]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Next <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @else
                <div></div>
            @endif
        </div>
    </div>
</div>
@endsection
