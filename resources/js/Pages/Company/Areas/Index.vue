<template>
  <CompanyLayout title="Areas">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Service Areas
        </h2>
        <Link 
          v-if="$page.props.can.create"
          :href="route('company.areas.create')"
          class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          <i class="fas fa-plus mr-2"></i> Add Area
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
              <span class="block sm:inline">{{ $page.props.flash.success }}</span>
            </div>
            
            <div v-if="$page.props.flash.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <span class="block sm:inline">{{ $page.props.flash.error }}</span>
            </div>

            <!-- Search and Filter -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
              <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                  v-model="search"
                  type="text"
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  placeholder="Search areas..."
                />
              </div>
              
              <div class="flex space-x-2 w-full sm:w-auto">
                <select 
                  v-model="statusFilter"
                  class="block w-full sm:w-40 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                  <option value="">All Status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
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
                  <tr v-for="area in filteredAreas" :key="area.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                          <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ area.name }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ area.city }}, {{ area.state }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ area.city }}, {{ area.state }}</div>
                      <div class="text-sm text-gray-500">{{ area.pincode }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div v-if="area.contact_person" class="text-sm text-gray-900">{{ area.contact_person }}</div>
                      <div v-if="area.contact_number" class="text-sm text-gray-500">{{ area.contact_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ area.staff_count }} Staff
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        :class="{
                          'bg-green-100 text-green-800': area.status === 'active',
                          'bg-red-100 text-red-800': area.status === 'inactive'
                        }" 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      >
                        {{ area.status === 'active' ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="flex justify-end space-x-2">
                        <Link 
                          :href="route('company.areas.show', area.id)" 
                          class="text-blue-600 hover:text-blue-900 mr-3"
                          title="View"
                        >
                          <i class="fas fa-eye"></i>
                        </Link>
                        <Link 
                          :href="route('company.areas.edit', area.id)" 
                          class="text-indigo-600 hover:text-indigo-900 mr-3"
                          title="Edit"
                        >
                          <i class="fas fa-edit"></i>
                        </Link>
                        <button 
                          @click="toggleStatus(area)" 
                          :class="{
                            'text-yellow-600 hover:text-yellow-900': area.status === 'active',
                            'text-green-600 hover:text-green-900': area.status === 'inactive'
                          }"
                          class="mr-3"
                          :title="area.status === 'active' ? 'Deactivate' : 'Activate'"
                        >
                          <i :class="area.status === 'active' ? 'fas fa-pause' : 'fas fa-play'"></i>
                        </button>
                        <button 
                          @click="confirmAreaDeletion(area)" 
                          class="text-red-600 hover:text-red-900"
                          title="Delete"
                        >
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="areas.data.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                      No areas found. Create your first area to get started.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
              <Pagination :links="areas.links" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <ConfirmationModal :show="confirmingAreaDeletion" @close="confirmingAreaDeletion = false">
      <template #title>
        Delete Area
      </template>

      <template #content>
        Are you sure you want to delete this area? This action cannot be undone.
      </template>

      <template #footer>
        <SecondaryButton @click="confirmingAreaDeletion = false">
          Cancel
        </SecondaryButton>

        <DangerButton
          class="ml-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click="deleteArea"
        >
          Delete Area
        </DangerButton>
      </template>
    </ConfirmationModal>
  </CompanyLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/CompanyLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
  areas: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');
const confirmingAreaDeletion = ref(false);
const areaToDelete = ref(null);

const form = useForm({});

const filteredAreas = computed(() => {
  return props.areas.data.filter(area => {
    const matchesSearch = area.name.toLowerCase().includes(search.value.toLowerCase()) ||
                         area.city.toLowerCase().includes(search.value.toLowerCase()) ||
                         area.state.toLowerCase().includes(search.value.toLowerCase());
    
    const matchesStatus = !statusFilter.value || area.status === statusFilter.value;
    
    return matchesSearch && matchesStatus;
  });
});

const confirmAreaDeletion = (area) => {
  areaToDelete.value = area;
  confirmingAreaDeletion.value = true;
};

const deleteArea = () => {
  form.delete(route('company.areas.destroy', areaToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      confirmingAreaDeletion.value = false;
      areaToDelete.value = null;
    },
  });
};

const toggleStatus = (area) => {
  form.patch(route('company.areas.toggle-status', area.id), {
    preserveScroll: true,
  });
};
</script>
