@extends('layouts.superadmin')

@section('title', 'Package: ' . $package->name)

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $package->name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Package details and information.
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.packages.edit', $package) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Edit
            </a>
            @if($package->status !== 'expired' && $package->status !== 'cancelled')
                <form action="{{ route('admin.packages.expire', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this package as expired?');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Mark as Expired
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to List
            </a>
        </div>
    </div>
    
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Package Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->name }}</dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Company</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <a href="{{ route('admin.companies.show', $package->company) }}" class="text-indigo-600 hover:text-indigo-900">
                        {{ $package->company->name }}
                    </a>
                </dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Price</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ number_format($package->price, 2) }} {{ config('app.currency', 'USD') }}</dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->duration_months }} months</dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->start_date->format('M d, Y') }}</dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->end_date->format('M d, Y') }}</dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @php
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'expired' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusColor = $statusColors[$package->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                        {{ ucfirst($package->status) }}
                    </span>
                    @if($package->isExpired() && $package->status !== 'expired' && $package->status !== 'cancelled')
                        <span class="ml-1 text-xs text-red-500">(Expired)</span>
                    @endif
                </dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Staff Limit</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->staff_limit }} staff members</dd>
            </div>
            
            @if($package->description)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $package->description }}</dd>
            </div>
            @endif
            
            @if(!empty($package->features))
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Features</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($package->features as $feature)
                            @if(isset($features[$feature]))
                                <li>{{ $features[$feature] }}</li>
                            @else
                                <li>{{ ucfirst(str_replace('_', ' ', $feature)) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </dd>
            </div>
            @endif
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->created_at->format('M d, Y') }}</dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $package->updated_at->format('M d, Y') }}</dd>
            </div>
        </dl>
    </div>
    
    <!-- Usage Statistics -->
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Usage Statistics</h3>
        
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Staff Members</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        {{ $package->company->users()->where('id', '!=', $package->company->admin_user_id)->count() }} / {{ $package->staff_limit }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View all staff members</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Services</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        {{ $package->company->services()->count() }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View all services</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Upcoming Appointments</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">
                                        {{ $package->company->appointments()->where('start_time', '>=', now())->count() }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View all appointments</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
        
        <div class="flow-root">
            <ul class="-mb-8">
                @php
                    $activities = [
                        [
                            'type' => 'created',
                            'date' => $package->created_at,
                            'description' => 'Package was created',
                            'user' => 'System'
                        ],
                        [
                            'type' => 'updated',
                            'date' => $package->updated_at,
                            'description' => 'Package details were updated',
                            'user' => 'System'
                        ]
                    ];
                    
                    // Sort activities by date in descending order
                    usort($activities, function($a, $b) {
                        return $b['date'] <=> $a['date'];
                    });
                @endphp
                
                @foreach($activities as $index => $activity)
                <li>
                    <div class="relative pb-8">
                        @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                @if($activity['type'] === 'created')
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @elseif($activity['type'] === 'updated')
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </span>
                                @else
                                    <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        {{ $activity['description'] }}
                                        <span class="font-medium text-gray-900">{{ $activity['user'] }}</span>
                                    </p>
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                    <time datetime="{{ $activity['date']->toIso8601String() }}">
                                        {{ $activity['date']->diffForHumans() }}
                                    </time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- Danger Zone -->
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium text-red-700">Danger Zone</h3>
        <div class="mt-4 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Delete this package
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>
                            Once you delete a package, there is no going back. Please be certain.
                        </p>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete package
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
