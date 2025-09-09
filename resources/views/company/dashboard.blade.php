@extends('layouts.company')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Welcome Banner -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 text-blue-100">Here's what's happening with your service center today.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('company.brands.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-blue-800 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i> Add New Brand
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Brands Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-tag text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Brands</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['brands'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('company.brands.index') }}" class="font-medium text-blue-600 hover:text-blue-500">View all</a>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['products'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">View all</a>
                </div>
            </div>
        </div>

        <!-- Complaints Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Open Complaints</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['complaints'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">View all</a>
                </div>
            </div>
        </div>

        <!-- Staff Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Staff</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['staff'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">View all</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Activity</h3>
                    <p class="mt-1 text-sm text-gray-500">A summary of recent activities in your service center.</p>
                </div>
                <div class="bg-white overflow-hidden">
                    @if(count($recentActivities) > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($recentActivities as $activity)
                                <li class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas {{ $activity['icon'] }} text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">No recent activities to display</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                    <p class="mt-1 text-sm text-gray-500">Quickly access common tasks.</p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('company.brands.create') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-tag text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Add New Brand</p>
                                <p class="text-xs text-gray-500">Create a new product brand</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-box text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Add New Product</p>
                                <p class="text-xs text-gray-500">Add a new product to catalog</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-tools text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Register Complaint</p>
                                <p class="text-xs text-gray-500">Register a new customer complaint</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-user-plus text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Add Staff Member</p>
                                <p class="text-xs text-gray-500">Invite a new staff member</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
