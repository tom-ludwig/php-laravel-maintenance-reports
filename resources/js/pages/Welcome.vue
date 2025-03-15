<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const props = defineProps<{
  name: string;
  quote: {
    message: string;
    author: string;
  };
  auth: {
    user: any;
  };
}>();

const handleManageProjects = () => {
  if (props.auth.user) {
    router.visit(route('projects.index'));
  } else {
    router.visit(route('login'));
  }
};

const handleCreateAccount = () => {
  if (props.auth.user) {
    router.visit(route('projects.index'));
  } else {
    router.visit(route('register'));
  }
};
</script>

<template>
  <Head>
    <title>Welcome - Maintenance Reports</title>
  </Head>

  <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h1 class="text-center text-3xl font-bold text-gray-900 mb-2">
        Maintenance Reports
      </h1>
      <p class="text-center text-sm text-gray-600 mb-8">
        Create and manage maintenance reports efficiently
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="space-y-6">
          <div>
            <Button
              @click="handleManageProjects"
              class="w-full"
              variant="default"
            >
              {{ props.auth.user ? 'Manage Projects' : 'Login to Manage Projects' }}
            </Button>
            <div v-if="!props.auth.user" class="mt-2 text-sm text-center text-gray-600">
              or
              <Button
                @click="handleCreateAccount"
                variant="link"
                class="text-blue-600 hover:text-blue-500"
              >
                create an account
              </Button>
            </div>
          </div>

          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-gray-500">Or continue as guest</span>
            </div>
          </div>

          <div>
            <Link
              href="/scan"
              class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Scan QR Code
            </Link>
            <p class="mt-2 text-xs text-center text-gray-500">
              Only scanning QR codes and submitting reports is available for guests
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-8 text-center text-sm text-gray-500">
      <p class="italic">
        "{{ quote.message }}"
      </p>
      <p class="mt-1">
        - {{ quote.author }}
      </p>
    </div>
  </div>
</template>
