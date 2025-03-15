<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

const scanning = ref(false);

// This is a placeholder - we'll need to implement actual QR code scanning
const startScanning = () => {
  scanning.value = true;
  // Implement QR code scanning logic here
};
</script>

<template>
  <Head>
    <title>Scan QR Code - Maintenance Reports</title>
  </Head>

  <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h1 class="text-center text-3xl font-bold text-gray-900 mb-2">
        Scan QR Code
      </h1>
      <p class="text-center text-sm text-gray-600 mb-8">
        Scan a project's QR code to submit a maintenance report
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="space-y-6">
          <div class="flex flex-col items-center justify-center">
            <div v-if="!scanning" class="text-center">
              <Button @click="startScanning" class="mb-4">Start Scanning</Button>
              <p class="text-sm text-gray-500">
                Position the QR code within your camera view
              </p>
            </div>
            <div v-else class="w-full aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
              <!-- QR code scanner will be mounted here -->
              <p class="text-gray-500">Camera view will appear here</p>
            </div>
          </div>

          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-gray-500">Or</span>
            </div>
          </div>

          <div class="text-center">
            <Link
              href="/login"
              class="text-blue-600 hover:text-blue-500"
            >
              Login to manage projects
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template> 