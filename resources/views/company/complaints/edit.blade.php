@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Complaint #{{ $complaint->complaint_no }}</h1>
                <a href="{{ route('company.complaints.show', $complaint) }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>

            <form action="{{ route('company.complaints.update', $complaint) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Customer Information</h3>
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Customer Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $complaint->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $complaint->email) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile *</label>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $complaint->mobile) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alt_mobile" class="block text-sm font-medium text-gray-700">Alternate Mobile</label>
                        <input type="text" name="alt_mobile" id="alt_mobile" value="{{ old('alt_mobile', $complaint->alt_mobile) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('alt_mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Information -->
                    <div class="col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Product Information</h3>
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand *</label>
                        <select name="brand_id" id="brand_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $complaint->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Product *</label>
                        <select name="product_id" id="product_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    data-brand="{{ $product->brand_id }}"
                                    {{ old('product_id', $complaint->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                        <input type="date" name="purchase_date" id="purchase_date" 
                            value="{{ old('purchase_date', optional($complaint->purchase_date)->format('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="warranty_status" class="block text-sm font-medium text-gray-700">Warranty Status *</label>
                        <select name="warranty_status" id="warranty_status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="in_warranty" {{ old('warranty_status', $complaint->warranty_status) == 'in_warranty' ? 'selected' : '' }}>In Warranty</option>
                            <option value="out_of_warranty" {{ old('warranty_status', $complaint->warranty_status) == 'out_of_warranty' ? 'selected' : '' }}>Out of Warranty</option>
                        </select>
                        @error('warranty_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complaint Details -->
                    <div class="col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Complaint Details</h3>
                    </div>

                    <div>
                        <label for="fault_type_id" class="block text-sm font-medium text-gray-700">Fault Type *</label>
                        <select name="fault_type_id" id="fault_type_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Select Fault Type</option>
                            @foreach($faultTypes as $faultType)
                                <option value="{{ $faultType->id }}" {{ old('fault_type_id', $complaint->fault_type_id) == $faultType->id ? 'selected' : '' }}>{{ $faultType->name }}</option>
                            @endforeach
                        </select>
                        @error('fault_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="call_type" class="block text-sm font-medium text-gray-700">Call Type *</label>
                        <select name="call_type" id="call_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="warranty" {{ old('call_type', $complaint->call_type) == 'warranty' ? 'selected' : '' }}>Warranty</option>
                            <option value="paid" {{ old('call_type', $complaint->call_type) == 'paid' ? 'selected' : '' }}>Paid Service</option>
                            <option value="amc" {{ old('call_type', $complaint->call_type) == 'amc' ? 'selected' : '' }}>AMC</option>
                        </select>
                        @error('call_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Problem Description *</label>
                        <textarea name="description" id="description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('description', $complaint->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Information -->
                    <div class="col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Service Address</h3>
                    </div>

                    <div class="col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <textarea name="address" id="address" rows="2" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('address', $complaint->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="landmark" class="block text-sm font-medium text-gray-700">Landmark</label>
                        <input type="text" name="landmark" id="landmark" value="{{ old('landmark', $complaint->landmark) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('landmark')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="area_id" class="block text-sm font-medium text-gray-700">Area *</label>
                        <select name="area_id" id="area_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $complaint->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $complaint->city) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">State *</label>
                        <input type="text" name="state" id="state" value="{{ old('state', $complaint->state) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode *</label>
                        <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $complaint->pincode) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('pincode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assignment -->
                    <div class="col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Assignment</h3>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="assigned_staff_id" class="block text-sm font-medium text-gray-700">Assign To (Optional)</label>
                        <select name="assigned_staff_id" id="assigned_staff_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Unassigned</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ old('assigned_staff_id', $complaint->assigned_staff_id) == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_staff_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="pending" {{ old('status', $complaint->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $complaint->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ old('status', $complaint->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="cancelled" {{ old('status', $complaint->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Resolution Notes (shown only for resolved/cancelled) -->
                    @if(in_array($complaint->status, ['resolved', 'cancelled']) || old('status') === 'resolved' || old('status') === 'cancelled')
                    <div class="col-span-2">
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700">
                            {{ $complaint->status === 'resolved' || old('status') === 'resolved' ? 'Resolution Notes' : 'Cancellation Reason' }} *
                        </label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">
                            Created: {{ $complaint->created_at->format('M d, Y h:i A') }}
                            @if($complaint->created_at != $complaint->updated_at)
                                <br>Last Updated: {{ $complaint->updated_at->format('M d, Y h:i A') }}
                            @endif
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('company.complaints.show', $complaint) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Complaint
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filter products based on selected brand
    document.getElementById('brand_id').addEventListener('change', function() {
        const brandId = this.value;
        const productSelect = document.getElementById('product_id');
        
        // Enable/disable product select based on brand selection
        productSelect.disabled = !brandId;
        
        if (!brandId) {
            // Reset product options if no brand is selected
            productSelect.innerHTML = '<option value="">Select Product</option>';
            return;
        }
        
        // Filter and show only products for the selected brand
        const options = productSelect.querySelectorAll('option');
        let hasVisibleOptions = false;
        
        options.forEach(option => {
            if (option.value === '' || option.dataset.brand === brandId) {
                option.style.display = '';
                hasVisibleOptions = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset selected value if current selection is not valid for the new brand
        if (productSelect.value && productSelect.querySelector(`option[value="${productSelect.value}"]`).style.display === 'none') {
            productSelect.value = '';
        }
    });
    
    // Show/hide resolution notes based on status
    document.getElementById('status').addEventListener('change', function() {
        const status = this.value;
        const resolutionNotesField = document.getElementById('resolution_notes_field');
        
        if (status === 'resolved' || status === 'cancelled') {
            if (!resolutionNotesField) {
                // Create the resolution notes field if it doesn't exist
                const statusField = document.querySelector('select[name="status"]');
                const container = statusField.closest('.grid').parentNode;
                
                const newField = document.createElement('div');
                newField.id = 'resolution_notes_field';
                newField.className = 'col-span-2';
                newField.innerHTML = `
                    <label for="resolution_notes" class="block text-sm font-medium text-gray-700">
                        ${status === 'resolved' ? 'Resolution Notes' : 'Cancellation Reason'} *
                    </label>
                    <textarea name="resolution_notes" id="resolution_notes" rows="3" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                    @error('resolution_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                `;
                
                statusField.closest('.col-span-2').after(newField);
            } else {
                // Update the label if the field already exists
                const label = resolutionNotesField.querySelector('label');
                label.textContent = status === 'resolved' ? 'Resolution Notes *' : 'Cancellation Reason *';
                resolutionNotesField.style.display = '';
            }
        } else if (resolutionNotesField) {
            resolutionNotesField.style.display = 'none';
        }
    });
    
    // Trigger change event on page load if brand is already selected
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize brand filter
        const brandSelect = document.getElementById('brand_id');
        if (brandSelect.value) {
            brandSelect.dispatchEvent(new Event('change'));
        }
        
        // Initialize status field
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection
