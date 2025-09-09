@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header with back button -->
                <div class="flex items-center mb-6">
                    <a 
                        href="{{ route('company.areas.index') }}" 
                        class="text-blue-600 hover:text-blue-900 mr-4"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-2xl font-semibold text-gray-800">
                        {{ $area->name }}
                    </h2>
                    <div class="ml-auto flex space-x-3">
                        @can('update', $area)
                        <a 
                            href="{{ route('company.areas.edit', $area) }}" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        @endcan
                        
                        @can('toggleStatus', $area)
                        <form action="{{ route('company.areas.toggle-status', $area) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md {{ $area->status === 'active' ? 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:ring-yellow-500' : 'text-green-700 bg-green-100 hover:bg-green-200 focus:ring-green-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2"
                            >
                                <i class="fas {{ $area->status === 'active' ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                {{ $area->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Area Information
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Detailed information about the service area.
                        </p>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Status
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $area->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($area->status) }}
                            </span>
                        </dd>

                        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
                            Full Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $area->full_address }}
                        </dd>

                        @if($area->contact_person || $area->contact_number)
                            <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
                                Contact Information
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($area->contact_person)
                                    <div>{{ $area->contact_person }}</div>
                                @endif
                                @if($area->contact_number)
                                    <div class="text-blue-600">{{ $area->contact_number }}</div>
                                @endif
                            </dd>
                        @endif

                        @if($area->notes)
                            <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
                                Notes
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                                {{ $area->notes }}
                            </dd>
                        @endif

                        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
                            Created At
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $area->created_at->format('M d, Y h:i A') }}
                        </dd>

                        <dt class="text-sm font-medium text-gray-500 mt-4 sm:mt-0">
                            Last Updated
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $area->updated_at->format('M d, Y h:i A') }}
                        </dd>
                    </div>
                </div>

                <!-- Staff Members Section -->
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Staff Members ({{ $area->staff_count }})
                        </h3>
                        @can('create', App\Models\User::class)
                        <a 
                            href="#" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <i class="fas fa-plus mr-1"></i> Add Staff
                        </a>
                        @endcan
                    </div>

                    @if($area->staff_count > 0)
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @foreach($area->staff as $staff)
                                    <li>
                                        <a href="#" class="block hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-blue-600 truncate">
                                                        {{ $staff->name }}
                                                    </p>
                                                    <div class="ml-2 flex-shrink-0 flex
                                                        @if($staff->is_active)
                                                            text-green-800 bg-green-100
                                                        @else
                                                            text-gray-800 bg-gray-100
                                                        @endif
                                                        px-2 py-0.5 rounded-full text-xs font-medium">
                                                        {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <p class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-envelope mr-1.5"></i>
                                                            {{ $staff->email }}
                                                        </p>
                                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                            <i class="fas fa-phone-alt mr-1.5"></i>
                                                            {{ $staff->phone ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        <i class="far fa-calendar-alt mr-1.5"></i>
                                                        <p>Joined {{ $staff->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="fas fa-users-slash text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">No staff members assigned to this area yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Complaints Section -->
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Recent Complaints ({{ $area->complaints_count }})
                        </h3>
                        @can('create', App\Models\Complaint::class)
                        <a 
                            href="#" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <i class="fas fa-plus mr-1"></i> New Complaint
                        </a>
                        @endcan
                    </div>

                    @if($area->complaints_count > 0)
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @foreach($area->complaints as $complaint)
                                    <li>
                                        <a href="#" class="block hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-blue-600 truncate">
                                                        Complaint #{{ $complaint->id }}
                                                        <span class="text-gray-500">- {{ $complaint->customer_name }}</span>
                                                    </p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if($complaint->status === 'open')
                                                                bg-green-100 text-green-800
                                                            @elseif($complaint->status === 'in_progress')
                                                                bg-blue-100 text-blue-800
                                                            @elseif($complaint->status === 'resolved')
                                                                bg-gray-100 text-gray-800
                                                            @else
                                                                bg-red-100 text-red-800
                                                            @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <p class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-tools mr-1.5"></i>
                                                            {{ $complaint->faultType->name ?? 'N/A' }}
                                                        </p>
                                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                            <i class="fas fa-user-tie mr-1.5"></i>
                                                            {{ $complaint->assignedTo->name ?? 'Unassigned' }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        <i class="far fa-calendar-alt mr-1.5"></i>
                                                        <p>Created {{ $complaint->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="mt-4 text-right">
                            <a 
                                href="#" 
                                class="text-sm font-medium text-blue-600 hover:text-blue-500"
                            >
                                View all complaints <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="fas fa-clipboard-check text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">No complaints found for this area.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@can('delete', $area)
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="delete-modal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Delete Area
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this area? This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button 
                    type="button" 
                    onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
                >
                    Cancel
                </button>
                <form action="{{ route('company.areas.destroy', $area) }}" method="POST" class="sm:col-start-1">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

@endsection

@push('scripts')
<script>
    function confirmDelete() {
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endpush
