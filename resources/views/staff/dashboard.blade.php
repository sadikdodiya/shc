@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="mt-1 text-blue-100">Here's what's happening with your account today.</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="bg-blue-700 bg-opacity-25 p-3 rounded-full">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Attendance -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Attendance</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    @if($attendance)
                                        @if($attendance->clock_out)
                                            <span class="text-green-600">Clocked Out</span>
                                        @else
                                            <span class="text-blue-600">Clocked In</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Not Clocked In</span>
                                    @endif
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    @if($attendance && !$attendance->clock_out)
                        <form action="{{ route('staff.attendance.clock-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Clock Out
                            </button>
                        </form>
                    @elseif(!$attendance)
                        <form action="{{ route('staff.attendance.clock-in') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photo" id="photo" class="hidden" accept="image/*" capture="environment" required>
                            <button type="button" onclick="document.getElementById('photo').click()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Clock In with Photo
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Total Complaints -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Complaints</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $complaintStats['total'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="sr-only">Total Complaints</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <span class="h-2 w-2 rounded-full bg-blue-500 mr-2"></span>
                            <span class="text-gray-600">Open: {{ $complaintStats['open'] }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-2 w-2 rounded-full bg-yellow-500 mr-2"></span>
                            <span class="text-gray-600">In Progress: {{ $complaintStats['in_progress'] }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-gray-600">Resolved: {{ $complaintStats['resolved'] }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-2 w-2 rounded-full bg-red-500 mr-2"></span>
                            <span class="text-gray-600">Cancelled: {{ $complaintStats['cancelled'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Recent Payments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $recentPayments->count() }}
                                </div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="sr-only">Total Payments</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="space-y-2">
                        @forelse($recentPayments as $payment)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <span class="h-2 w-2 rounded-full {{ $payment->type === 'credit' ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                                    <span class="text-gray-600">{{ $payment->description ?? 'Payment' }}</span>
                                </div>
                                <span class="font-medium {{ $payment->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $payment->type === 'credit' ? '+' : '-' }} ₹{{ number_format($payment->amount, 2) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No recent payments</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Quick Actions</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">Menu</div>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('staff.complaints.index') }}" class="group flex items-center p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-md flex items-center justify-center text-blue-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600">My Complaints</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.attendance') }}" class="group flex items-center p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                            <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-md flex items-center justify-center text-green-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-green-600">Attendance</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.payments') }}" class="group flex items-center p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                            <div class="flex-shrink-0 h-8 w-8 bg-yellow-100 rounded-md flex items-center justify-center text-yellow-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-yellow-600">Payments</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 bg-gray-50 rounded-md hover:bg-gray-100">
                            <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded-md flex items-center justify-center text-purple-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-purple-600">Settings</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Complaints</h3>
                <a href="{{ route('staff.complaints.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
            </div>
        </div>
        <div class="bg-white overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @forelse($recentComplaints as $complaint)
                    <li>
                        <a href="{{ route('staff.complaints.show', $complaint) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-blue-600 truncate">
                                        {{ $complaint->subject }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $complaint->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $complaint->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $complaint->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $complaint->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $complaint->customer->name ?? 'N/A' }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $complaint->customer->email ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p>
                                            {{ $complaint->created_at->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-sm text-gray-500 text-center">No complaints found</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle photo capture and form submission
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    // Submit the form when a photo is selected
                    this.closest('form').submit();
                }
            });
        }
    });
</script>
@endpush
@endsection
