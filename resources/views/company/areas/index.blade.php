@extends('layouts.company')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Service Areas</h2>
                    @can('create', App\Models\Area::class)
                    <a href="{{ route('company.areas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i> Add Area
                    </a>
                    @endcan
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Search and Filter -->
                <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <form action="{{ route('company.areas.index') }}" method="GET" class="w-full">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search areas..."
                            />
                        </form>
                    </div>
                    
                    <div class="flex space-x-2 w-full sm:w-auto">
                        <form action="{{ route('company.areas.index') }}" method="GET" class="w-full sm:w-auto">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select 
                                name="status"
                                onchange="this.form.submit()"
                                class="block w-full sm:w-40 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            >
                                <option value="" {{ request('status') === null ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Areas Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Staff
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($areas as $area)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $area->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $area->city }}, {{ $area->state }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $area->city }}, {{ $area->state }}</div>
                                        <div class="text-sm text-gray-500">{{ $area->pincode }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($area->contact_person)
                                            <div class="text-sm text-gray-900">{{ $area->contact_person }}</div>
                                        @endif
                                        @if($area->contact_number)
                                            <div class="text-sm text-gray-500">{{ $area->contact_number }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $area->staff_count }} Staff
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $area->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                        >
                                            {{ ucfirst($area->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a 
                                                href="{{ route('company.areas.show', $area) }}" 
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                                title="View"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('update', $area)
                                            <a 
                                                href="{{ route('company.areas.edit', $area) }}" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                                title="Edit"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('toggleStatus', $area)
                                            <form action="{{ route('company.areas.toggle-status', $area) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button 
                                                    type="submit"
                                                    class="mr-3 {{ $area->status === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}"
                                                    title="{{ $area->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                                >
                                                    <i class="fas {{ $area->status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                            @endcan
                                            
                                            @can('delete', $area)
                                            <form action="{{ route('company.areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this area? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Delete"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        No areas found. Create your first area to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $areas->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
