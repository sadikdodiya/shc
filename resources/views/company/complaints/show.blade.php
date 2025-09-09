@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Complaint #{{ $complaint->complaint_no }}</h1>
                <p class="text-sm text-gray-500">Created on {{ $complaint->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('company.complaints.edit', $complaint) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('company.complaints.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'resolved' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ][$complaint->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        @if($complaint->assignedStaff)
                            Assigned to: {{ $complaint->assignedStaff->name }}
                        @else
                            Not yet assigned
                        @endif
                    </p>
                </div>
                @if($complaint->status === 'resolved' && $complaint->resolved_at)
                    <div class="text-sm text-gray-500">
                        Resolved on {{ $complaint->resolved_at->format('M d, Y h:i A') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Customer Information
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->email ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->mobile }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Alternate Mobile</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->alt_mobile ?? 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Product Information
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->brand->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Product</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->product->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $complaint->purchase_date ? $complaint->purchase_date->format('M d, Y') : 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Warranty Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->warranty_status)) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Call Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ ucfirst($complaint->call_type) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fault Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $complaint->faultType->name ?? 'N/A' }}
                                </dd>
                            </div>
                        </div>
                        <div class="mt-4">
                            <dt class="text-sm font-medium text-gray-500">Problem Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $complaint->description }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Service Address -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Service Address
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->address }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Landmark</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->landmark ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Area</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->area->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">City</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->city }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">State</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->state }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pincode</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->pincode }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                @if($complaint->status === 'resolved' && $complaint->resolution_notes)
                <!-- Resolution Notes -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-green-50">
                        <h3 class="text-lg leading-6 font-medium text-green-800">
                            Resolution Notes
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-sm text-gray-900 whitespace-pre-line">
                            {{ $complaint->resolution_notes }}
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            Resolved on {{ $complaint->resolved_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
                @endif

                @if($complaint->status === 'cancelled' && $complaint->resolution_notes)
                <!-- Cancellation Reason -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-red-50">
                        <h3 class="text-lg leading-6 font-medium text-red-800">
                            Cancellation Reason
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-sm text-gray-900 whitespace-pre-line">
                            {{ $complaint->resolution_notes }}
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            Cancelled on {{ $complaint->updated_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Actions
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        @if($complaint->status === 'pending' || $complaint->status === 'in_progress')
                            @if($complaint->status === 'pending')
                                <form action="{{ route('company.complaints.update-status', $complaint) }}" method="POST" class="mb-4">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Mark as In Progress
                                    </button>
                                </form>
                            @endif

                            @if($complaint->status === 'in_progress')
                                <form action="{{ route('company.complaints.update-status', $complaint) }}" method="POST" class="mb-4">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Mark as Resolved
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('company.complaints.update-status', $complaint) }}" method="POST" class="mb-4">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('Are you sure you want to cancel this complaint? This action cannot be undone.')">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Cancel Complaint
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('company.complaints.edit', $complaint) }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Details
                        </a>
                    </div>
                </div>

                <!-- Assigned Staff -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Assigned Staff
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        @if($complaint->assignedStaff)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-medium">{{ substr($complaint->assignedStaff->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $complaint->assignedStaff->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $complaint->assignedStaff->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $complaint->assignedStaff->phone }}</div>
                                </div>
                            </div>
                            
                            <form action="{{ route('company.complaints.assign-staff', $complaint) }}" method="POST" class="mt-4">
                                @csrf
                                <div>
                                    <label for="assigned_staff_id" class="block text-sm font-medium text-gray-700">Change Assignment</label>
                                    <select id="assigned_staff_id" name="assigned_staff_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">Unassign</option>
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id }}" {{ $complaint->assigned_staff_id == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="mt-2 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Assignment
                                </button>
                            </form>
                        @else
                            <p class="text-sm text-gray-500 mb-4">No staff member assigned yet.</p>
                            <form action="{{ route('company.complaints.assign-staff', $complaint) }}" method="POST">
                                @csrf
                                <div>
                                    <label for="assigned_staff_id" class="block text-sm font-medium text-gray-700">Assign to Staff</label>
                                    <select id="assigned_staff_id" name="assigned_staff_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">Select Staff</option>
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="mt-2 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Assign Staff
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Activity Timeline
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @php
                                    $statusHistory = [
                                        ['status' => 'created', 'icon' => 'M5 8h10M5 8a2 2 0 110-4h1V1h8v3a1 1 0 001 1h3a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h1V3a2 2 0 012-2h3zm3 11a3 3 0 100-6 3 3 0 000 6z', 'color' => 'blue'],
                                        ['status' => 'in_progress', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'yellow'],
                                        ['status' => 'resolved', 'icon' => 'M5 13l4 4L19 7', 'color' => 'green'],
                                        ['status' => 'cancelled', 'icon' => 'M6 18L18 6M6 6l12 12', 'color' => 'red'],
                                    ];
                                    
                                    $currentStatus = $complaint->status === 'pending' ? 'created' : $complaint->status;
                                    $timeline = [
                                        [
                                            'status' => 'created',
                                            'date' => $complaint->created_at,
                                            'description' => 'Complaint created',
                                        ],
                                    ];
                                    
                                    if($complaint->assignedStaff) {
                                        $timeline[] = [
                                            'status' => 'assigned',
                                            'date' => $complaint->assigned_at ?? $complaint->updated_at,
                                            'description' => 'Assigned to ' . $complaint->assignedStaff->name,
                                        ];
                                    }
                                    
                                    if($currentStatus !== 'created' && $currentStatus !== 'pending') {
                                        $timeline[] = [
                                            'status' => $currentStatus,
                                            'date' => $complaint->status_updated_at ?? $complaint->updated_at,
                                            'description' => 'Marked as ' . str_replace('_', ' ', $currentStatus),
                                        ];
                                    }
                                    
                                    // Sort timeline by date
                                    usort($timeline, function($a, $b) {
                                        return $a['date'] <=> $b['date'];
                                    });
                                @endphp
                                
                                @foreach($timeline as $index => $event)
                                    @php
                                        $eventStatus = $event['status'];
                                        $eventIcon = collect($statusHistory)->firstWhere('status', $eventStatus);
                                        $icon = $eventIcon['icon'] ?? null;
                                        $color = $eventIcon['color'] ?? 'gray';
                                        $isCurrent = $eventStatus === $currentStatus;
                                        $isLast = $loop->last;
                                    @endphp
                                    
                                    <li class="relative pb-8">
                                        @if(!$isLast)
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                                        @endif
                                        
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-{{ $color }}-400">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="{{ $icon }}" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $event['description'] }}
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $event['date']->toIso8601String() }}">
                                                        {{ $event['date']->diffForHumans() }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
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
