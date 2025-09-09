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

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Total Complaints -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Complaints</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_complaints'] ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('staff.complaints.index') }}" class="font-medium text-blue-600 hover:text-blue-500">View all complaints</a>
                    </div>
                </div>
            </div>

            <!-- Pending Complaints -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['pending_complaints'] ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('staff.complaints.index', ['status' => 'pending']) }}" class="font-medium text-blue-600 hover:text-blue-500">View pending</a>
                    </div>
                </div>
            </div>

            <!-- In Progress Complaints -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['in_progress_complaints'] ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('staff.complaints.index', ['status' => 'in_progress']) }}" class="font-medium text-blue-600 hover:text-blue-500">View in progress</a>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        {{ number_format($stats['total_earnings'] ?? 0, 2) }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('staff.payments.index') }}" class="font-medium text-blue-600 hover:text-blue-500">View payments</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-6">
            <div class="flex flex-wrap -mx-2">
                @if($attendanceStatus && $attendanceStatus['is_clocked_in'])
                    <div class="w-full sm:w-1/2 lg:w-1/4 px-2 mb-4">
                        <form action="{{ route('staff.attendance.clock-out') }}" method="POST" class="h-full">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Clock Out
                            </button>
                        </form>
                    </div>
                @else
                    <div class="w-full sm:w-1/2 lg:w-1/4 px-2 mb-4">
                        <button type="button" onclick="openClockInModal()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Clock In
                        </button>
                    </div>
                @endif
                
                <div class="w-full sm:w-1/2 lg:w-1/4 px-2 mb-4">
                    <a href="{{ route('staff.complaints.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 text-center">
                        <svg class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Complaint
                    </a>
                </div>
                
                <div class="w-full sm:w-1/2 lg:w-1/4 px-2 mb-4">
                    <a href="{{ route('staff.complaints.index') }}" class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 text-center">
                        <svg class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        My Tasks
                    </a>
                </div>
                
                <div class="w-full sm:w-1/2 lg:w-1/4 px-2 mb-4">
                    <a href="{{ route('staff.payments.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 text-center">
                        <svg class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Payment History
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Complaints -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Recent Complaints
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Your most recent assigned complaints
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentComplaints as $complaint)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('staff.complaints.show', $complaint) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $complaint->ticket_id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $complaint->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $complaint->product->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ 
                                                $complaint->status === 'resolved' ? 'bg-green-100 text-green-800' :
                                                ($complaint->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                                ($complaint->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                                            }}">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $complaint->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No complaints assigned to you yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <a href="{{ route('staff.complaints.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        View all complaints
                    </a>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Recent Payments
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Your most recent payment transactions
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentPayments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $payment->description }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $payment->complaint ? 'Complaint #' . $payment->complaint->ticket_id : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="{{ $payment->type === 'credit' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $payment->type === 'credit' ? '+' : '-' }} {{ number_format($payment->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ 
                                                $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                            }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No payment records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <a href="{{ route('staff.payments.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        View all payments
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Status -->
        @if($attendanceStatus)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Today's Attendance
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-{{ $attendanceStatus['is_clocked_in'] ? 'green' : 'gray' }}-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-{{ $attendanceStatus['is_clocked_in'] ? 'green' : 'gray' }-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($attendanceStatus['is_clocked_in'])
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ $attendanceStatus['is_clocked_in'] ? 'Currently Clocked In' : 'Not Clocked In' }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    @if($attendanceStatus['is_clocked_in'])
                                        Clocked in at {{ $attendanceStatus['clock_in_time'] }}
                                        @if($attendanceStatus['duration'])
                                            <br>Duration: {{ $attendanceStatus['duration'] }}
                                        @endif
                                    @else
                                        You have not clocked in today
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if($attendanceStatus['is_clocked_in'])
                                <form action="{{ route('staff.attendance.clock-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Clock Out
                                    </button>
                                </form>
                            @else
                                <button type="button" onclick="openClockInModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Clock In
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Clock In Modal -->
        <div id="clockInModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Clock In</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 mb-4">
                            Please take a selfie to confirm your attendance
                        </p>
                        <div id="camera" class="w-full h-64 bg-gray-200 mb-4 rounded-md overflow-hidden">
                            <video id="video" width="100%" height="100%" autoplay class="w-full h-full object-cover"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                        </div>
                        <div class="text-red-500 text-sm mb-4" id="error"></div>
                        <div class="flex justify-between">
                            <button type="button" id="captureBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">
                                Take Photo
                            </button>
                            <button type="button" id="retakeBtn" class="hidden px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none">
                                Retake
                            </button>
                            <button type="button" id="submitBtn" class="hidden px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none">
                                Submit
                            </button>
                            <button type="button" onclick="closeClockInModal()" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            let stream = null;
            
            function openClockInModal() {
                document.getElementById('clockInModal').classList.remove('hidden');
                startCamera();
            }
            
            function closeClockInModal() {
                document.getElementById('clockInModal').classList.add('hidden');
                stopCamera();
                resetCameraUI();
            }
            
            function startCamera() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                        .then(function(mediaStream) {
                            stream = mediaStream;
                            const video = document.getElementById('video');
                            video.srcObject = mediaStream;
                        })
                        .catch(function(error) {
                            console.error('Error accessing camera:', error);
                            document.getElementById('error').textContent = 'Could not access camera. Please ensure you have granted camera permissions.';
                        });
                } else {
                    document.getElementById('error').textContent = 'Your browser does not support camera access.';
                }
            }
            
            function stopCamera() {
                if (stream) {
                    const tracks = stream.getTracks();
                    tracks.forEach(track => track.stop());
                    stream = null;
                }
            }
            
            function resetCameraUI() {
                document.getElementById('captureBtn').classList.remove('hidden');
                document.getElementById('retakeBtn').classList.add('hidden');
                document.getElementById('submitBtn').classList.add('hidden');
                document.getElementById('video').classList.remove('hidden');
                document.getElementById('canvas').classList.add('hidden');
                document.getElementById('error').textContent = '';
            }
            
            document.getElementById('captureBtn').addEventListener('click', function() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                
                // Set canvas dimensions to match video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                // Draw current video frame to canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Show canvas and hide video
                video.classList.add('hidden');
                canvas.classList.remove('hidden');
                
                // Update UI
                document.getElementById('captureBtn').classList.add('hidden');
                document.getElementById('retakeBtn').classList.remove('hidden');
                document.getElementById('submitBtn').classList.remove('hidden');
                
                // Stop camera stream
                stopCamera();
            });
            
            document.getElementById('retakeBtn').addEventListener('click', function() {
                resetCameraUI();
                startCamera();
            });
            
            document.getElementById('submitBtn').addEventListener('click', function() {
                const canvas = document.getElementById('canvas');
                const imageData = canvas.toDataURL('image/jpeg');
                
                // Here you would typically send the imageData to your server
                // For example, using fetch or AJAX
                const formData = new FormData();
                formData.append('photo', imageData);
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch('{{ route("staff.attendance.clock-in") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        document.getElementById('error').textContent = data.message || 'Failed to clock in. Please try again.';
                        resetCameraUI();
                        startCamera();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('error').textContent = 'An error occurred. Please try again.';
                    resetCameraUI();
                    startCamera();
                });
            });
            
            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('clockInModal');
                if (event.target === modal) {
                    closeClockInModal();
                }
            };
        </script>
        @endpush
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
