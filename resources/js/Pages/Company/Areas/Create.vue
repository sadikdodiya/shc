<template>
  <CompanyLayout title="Create Area">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <Link :href="route('company.areas.index')" class="text-blue-600 hover:text-blue-900">Areas</Link>
          <span class="text-gray-400 mx-2">/</span>
          Create Area
        </h2>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Basic Information</h3>
                      <p class="mt-1 text-sm text-gray-500">
                        Enter the basic details of the service area.
                      </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                          <label for="name" class="block text-sm font-medium text-gray-700">Area Name <span class="text-red-500">*</span></label>
                          <input 
                            type="text" 
                            id="name" 
                            v-model="form.name" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.name }"
                          />
                          <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                            {{ form.errors.name }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Location Information -->
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Location Details</h3>
                      <p class="mt-1 text-sm text-gray-500">
                        Enter the location details for this area.
                      </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                          <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                          <input 
                            type="text" 
                            id="address" 
                            v-model="form.address" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.address }"
                          />
                          <p v-if="form.errors.address" class="mt-2 text-sm text-red-600">
                            {{ form.errors.address }}
                          </p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                          <input 
                            type="text" 
                            id="city" 
                            v-model="form.city" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.city }"
                          />
                          <p v-if="form.errors.city" class="mt-2 text-sm text-red-600">
                            {{ form.errors.city }}
                          </p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="state" class="block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></label>
                          <input 
                            type="text" 
                            id="state" 
                            v-model="form.state" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.state }"
                          />
                          <p v-if="form.errors.state" class="mt-2 text-sm text-red-600">
                            {{ form.errors.state }}
                          </p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="country" class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                          <input 
                            type="text" 
                            id="country" 
                            v-model="form.country" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.country }"
                          />
                          <p v-if="form.errors.country" class="mt-2 text-sm text-red-600">
                            {{ form.errors.country }}
                          </p>
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                          <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode <span class="text-red-500">*</span></label>
                          <input 
                            type="text" 
                            id="pincode" 
                            v-model="form.pincode" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.pincode }"
                          />
                          <p v-if="form.errors.pincode" class="mt-2 text-sm text-red-600">
                            {{ form.errors.pincode }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Contact Information</h3>
                      <p class="mt-1 text-sm text-gray-500">
                        Primary contact details for this area.
                      </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                          <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                          <input 
                            type="text" 
                            id="contact_person" 
                            v-model="form.contact_person" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.contact_person }"
                          />
                          <p v-if="form.errors.contact_person" class="mt-2 text-sm text-red-600">
                            {{ form.errors.contact_person }}
                          </p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                          <input 
                            type="text" 
                            id="contact_number" 
                            v-model="form.contact_number" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.contact_number }"
                          />
                          <p v-if="form.errors.contact_number" class="mt-2 text-sm text-red-600">
                            {{ form.errors.contact_number }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Additional Notes -->
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Additional Information</h3>
                      <p class="mt-1 text-sm text-gray-500">
                        Any additional notes or information about this area.
                      </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                          <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                          <textarea 
                            id="notes" 
                            v-model="form.notes" 
                            rows="3" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': form.errors.notes }"
                          ></textarea>
                          <p v-if="form.errors.notes" class="mt-2 text-sm text-red-600">
                            {{ form.errors.notes }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end">
                  <Link 
                    :href="route('company.areas.index')" 
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Cancel
                  </Link>
                  <button 
                    type="submit" 
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                  >
                    <span v-if="!form.processing">Save Area</span>
                    <span v-else class="flex items-center">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Saving...
                    </span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </CompanyLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CompanyLayout from '@/Layouts/CompanyLayout.vue';
import { Link } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  address: '',
  city: '',
  state: '',
  country: 'India',
  pincode: '',
  contact_person: '',
  contact_number: '',
  notes: '',
});

const submit = () => {
  form.post(route('company.areas.store'), {
    onFinish: () => form.reset(),
  });
};
</script>
