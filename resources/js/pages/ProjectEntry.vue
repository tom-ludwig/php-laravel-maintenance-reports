<template>
    <AppLayout>
        <div class="flex min-h-[calc(100vh-4rem)] items-center justify-center p-6">
            <div class="w-full max-w-md">
                <div class="overflow-hidden rounded-lg bg-white shadow-lg dark:bg-gray-800">
                    <div class="px-6 py-8">
                        <h2 class="mb-6 text-center text-2xl font-bold text-gray-900 dark:text-white">Project Entry</h2>
                        
                        <div class="space-y-6">
                            <!-- Project ID Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Enter Project ID
                                </label>
                                <div class="mt-2 flex rounded-md shadow-sm">
                                    <input
                                        type="text"
                                        v-model="projectId"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:placeholder:text-gray-400 sm:text-sm sm:leading-6"
                                        placeholder="Enter ID or scan QR code"
                                    />
                                    <button
                                        @click="goToMaintenanceForm"
                                        :disabled="!isValidId"
                                        class="ml-3 inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                                    >
                                        Go
                                    </button>
                                </div>
                                <p v-if="projectId && !isValidId" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    Please enter a valid project ID
                                </p>
                            </div>

                            <!-- Divider with "or" -->
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="bg-white px-2 text-gray-500 dark:bg-gray-800 dark:text-gray-400">Or</span>
                                </div>
                            </div>

                            <!-- QR Scanner Button -->
                            <div class="flex justify-center">
                                <button
                                    @click="startScanning"
                                    class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                                >
                                    <QrCode class="mr-2 h-5 w-5" />
                                    Scan QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Scanner Modal -->
            <TransitionRoot appear :show="isScanning" as="template">
                <Dialog as="div" @close="stopScanning" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/25 dark:bg-black/40" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                        Scan QR Code
                                    </DialogTitle>

                                    <div class="mt-4">
                                        <div class="overflow-hidden rounded-lg">
                                            <QrcodeStream
                                                v-if="isScanning"
                                                @detect="onDecode"
                                                @init="onInit"
                                            />
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button
                                            type="button"
                                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-400"
                                            @click="stopScanning"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { QrCode } from 'lucide-vue-next';
import { QrcodeStream } from 'vue-qrcode-reader';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';

const projectId = ref('');
const isScanning = ref(false);
const hasError = ref(false);

const isValidId = computed(() => {
    // Accept any non-empty string that contains only numbers
    return /^\d+$/.test(projectId.value);
});

const startScanning = () => {
    hasError.value = false;
    isScanning.value = true;
};

const stopScanning = () => {
    isScanning.value = false;
};

const onDecode = (result) => {
    projectId.value = result.text;
    stopScanning();
    if (isValidId.value) {
        goToMaintenanceForm();
    }
};

const onInit = async (promise) => {
    try {
        await promise;
    } catch (error) {
        hasError.value = true;
        if (error.name === 'NotAllowedError') {
            console.error('Camera permission denied');
        } else if (error.name === 'NotFoundError') {
            console.error('No camera found');
        } else {
            console.error(error);
        }
    }
};

const goToMaintenanceForm = () => {
    router.visit(route('maintenance.show', { id: projectId.value }));
};
</script> 