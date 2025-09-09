@extends('layouts.company')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Fault Type Details
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Detailed information about the fault type.
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('company.fault-types.edit', $faultType) }}" 
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('company.fault-types.toggle-status', $faultType) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md {{ $faultType->status === 'active' ? 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:ring-yellow-500' : 'text-green-700 bg-green-100 hover:bg-green-200 focus:ring-green-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <i class="fas {{ $faultType->status === 'active' ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                    {{ $faultType->status === 'active' ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>
    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">
            Name
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            {{ $faultType->name }}
        </dd>
        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
            Description
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            {{ $faultType->description ?? 'No description provided.' }}
        </dd>
        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
            Status
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $faultType->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($faultType->status) }}
            </span>
        </dd>
        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
            Created At
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            {{ $faultType->created_at->format('M d, Y h:i A') }}
        </dd>
        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
            Last Updated
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            {{ $faultType->updated_at->format('M d, Y h:i A') }}
        </dd>
    </div>
    <div class="px-4 py-4 bg-gray-50 text-right sm:px-6">
        <a href="{{ route('company.fault-types.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Back to List
        </a>
    </div>
</div>

@if($faultType->complaints->count() > 0)
<div class="mt-8">
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
        Related Complaints ({{ $faultType->complaints->count() }})
    </h3>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($faultType->complaints as $complaint)
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
                                        <i class="fas fa-user mr-1.5"></i>
                                        {{ $complaint->customer_name }}
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                        <i class="fas fa-phone-alt mr-1.5"></i>
                                        {{ $complaint->customer_phone }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <i class="far fa-calendar-alt mr-1.5"></i>
                                    <p>
                                        Created {{ $complaint->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
@endsection
