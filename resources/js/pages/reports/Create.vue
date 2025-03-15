<template>
  <AppLayout>
    <Head>
      <title>Submit Report - {{ project.name }}</title>
    </Head>

    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-gray-900">Submit Maintenance Report</h1>
          <p class="mt-2 text-gray-600">Project: {{ project.name }}</p>
        </div>

        <form @submit.prevent="submitReport" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Document Number</label>
            <input
              v-model="form.document_number"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <div v-if="form.errors.document_number" class="mt-1 text-sm text-red-600">
              {{ form.errors.document_number }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Your Name</label>
            <input
              v-model="form.reporter_name"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <div v-if="form.errors.reporter_name" class="mt-1 text-sm text-red-600">
              {{ form.errors.reporter_name }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Description/Notes</label>
            <textarea
              v-model="form.description"
              rows="4"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            ></textarea>
            <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
              {{ form.errors.description }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
              <option value="done">Done</option>
              <option value="half">Half Complete</option>
              <option value="needs_repair">Needs Repair</option>
              <option value="not_completed">Not Completed</option>
            </select>
            <div v-if="form.errors.status" class="mt-1 text-sm text-red-600">
              {{ form.errors.status }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Images</label>
            <input
              type="file"
              @change="handleImageUpload"
              multiple
              accept="image/*"
              class="mt-1 block w-full"
            />
            <div v-if="form.errors.images" class="mt-1 text-sm text-red-600">
              {{ form.errors.images }}
            </div>
          </div>

          <!-- Image Preview -->
          <div v-if="imagePreviewUrls.length" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
            <div v-for="(url, index) in imagePreviewUrls" :key="index" class="relative">
              <img :src="url" alt="Preview" class="w-full h-32 object-cover rounded-lg" />
              <button
                type="button"
                @click="removeImage(index)"
                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
              >
                <span class="sr-only">Remove</span>
                ×
              </button>
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Submitting...' : 'Submit Report' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps<{
  project: {
    id: number
    name: string
    description: string
    project_date: string
  }
}>()

const form = useForm({
  project_id: props.project.id,
  document_number: '',
  reporter_name: '',
  description: '',
  status: 'not_completed',
  images: [] as File[]
})

const imagePreviewUrls = ref<string[]>([])

const handleImageUpload = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (!input.files?.length) return

  Array.from(input.files).forEach(file => {
    form.images.push(file)
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreviewUrls.value.push(e.target?.result as string)
    }
    reader.readAsDataURL(file)
  })
}

const removeImage = (index: number) => {
  form.images.splice(index, 1)
  imagePreviewUrls.value.splice(index, 1)
}

const submitReport = () => {
  form.post(`/reports`, {
    onSuccess: () => {
      window.location.href = '/thank-you'
    }
  })
}
</script> 