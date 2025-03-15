<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Maintenance Report</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Project: {{ project.name }}
        </p>
      </div>
    </header>

    <!-- Main content -->
    <main>
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
          <form @submit.prevent="submitForm" class="space-y-8">
            <!-- Name Section -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                  <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Reporter Information</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      Please provide your name for the maintenance report.
                    </p>
                  </div>
                  <div class="mt-5 md:col-span-2 md:mt-0">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input
                          id="name"
                          v-model="form.name"
                          type="text"
                          required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                          placeholder="Your name"
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Status Section -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                  <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Maintenance Status</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      Select the current status of the maintenance task.
                    </p>
                  </div>
                  <div class="mt-5 md:col-span-2 md:mt-0">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <StatusPicker
                          v-model="form.status"
                          id="status"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                  <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Additional Notes</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      Add any relevant notes or observations about the maintenance task.
                    </p>
                  </div>
                  <div class="mt-5 md:col-span-2 md:mt-0">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea
                          id="notes"
                          v-model="form.notes"
                          rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                          placeholder="Add any relevant notes here"
                        ></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Images Section -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                  <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Documentation</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      Upload images related to the maintenance task.
                    </p>
                  </div>
                  <div class="mt-5 md:col-span-2 md:mt-0">
                    <div class="flex justify-center rounded-md border-2 border-dashed border-gray-300 dark:border-gray-600 px-6 pt-5 pb-6">
                      <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                          <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                            <span>Upload files</span>
                            <input
                              id="file-upload"
                              type="file"
                              multiple
                              accept="image/*"
                              @change="handleImageUpload"
                              class="sr-only"
                            >
                          </label>
                          <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB each</p>
                      </div>
                    </div>
                    
                    <!-- Image Previews -->
                    <div v-if="imagePreviewUrls.length" class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                      <div v-for="(url, index) in imagePreviewUrls" :key="index" class="relative group">
                        <div class="aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-lg">
                          <img :src="url" alt="" class="object-cover">
                          <button
                            type="button"
                            @click="removeImage(index)"
                            class="absolute top-2 right-2 p-1.5 rounded-full bg-gray-900/50 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                          >
                            <span class="sr-only">Remove image</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="form.processing"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-indigo-500 dark:hover:bg-indigo-400 disabled:opacity-50"
              >
                {{ form.processing ? 'Submitting...' : 'Submit Report' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import StatusPicker from '@/components/StatusPicker.vue';

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
});

const imagePreviewUrls = ref([]);
const selectedImages = ref([]);

const form = useForm({
  project_id: props.project.id,
  name: '',
  status: '',
  notes: '',
  images: []
});

const handleImageUpload = (e) => {
  const files = Array.from(e.target.files);
  files.forEach(file => {
    if (file.size <= 2 * 1024 * 1024) { // 2MB limit
      selectedImages.value.push(file);
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviewUrls.value.push(e.target.result);
      };
      reader.readAsDataURL(file);
    } else {
      alert('File size should not exceed 2MB');
    }
  });
  form.images = selectedImages.value;
};

const removeImage = (index) => {
  imagePreviewUrls.value.splice(index, 1);
  selectedImages.value.splice(index, 1);
  form.images = selectedImages.value;
};

const submitForm = () => {
  form.post(route('maintenance.store'), {
    onSuccess: () => {
      form.reset();
      imagePreviewUrls.value = [];
      selectedImages.value = [];
      router.visit(route('thank-you'));
    },
  });
};
</script> 