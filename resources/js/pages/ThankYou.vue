<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
</script>

<template>
  <AppLayout>
    <Head>
      <title>Thank You - Maintenance Reports</title>
    </Head>

    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8 text-center">
        <div>
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Thank You!
          </h2>
          <p class="mt-2 text-center text-sm text-gray-600">
            Your maintenance report has been submitted successfully.
          </p>
        </div>
        <div class="mt-4">
          <p class="text-gray-500">You can now close this window.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 