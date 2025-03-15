<template>
  <div class="relative">
    <button
      type="button"
      @click="isOpen = !isOpen"
      class="w-full flex items-center justify-between gap-2 px-3 py-2 text-left text-sm rounded-lg border focus:outline-none focus:ring-2 focus:ring-offset-2"
      :class="[
        modelValue ? statusConfig[modelValue].buttonClass : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300',
        'hover:bg-gray-50 dark:hover:bg-gray-700'
      ]"
    >
      <div class="flex items-center gap-2">
        <component
          :is="modelValue ? statusConfig[modelValue].icon : QuestionMarkCircleIcon"
          class="h-5 w-5"
          :class="modelValue ? statusConfig[modelValue].iconClass : 'text-gray-400'"
        />
        <span>{{ modelValue ? statusConfig[modelValue].label : 'Select status' }}</span>
      </div>
      <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
    </button>

    <div
      v-if="isOpen"
      class="absolute z-10 mt-1 w-full rounded-lg bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 py-1"
    >
      <div class="max-h-60 overflow-auto">
        <button
          v-for="(config, status) in statusConfig"
          :key="status"
          type="button"
          @click="selectStatus(status)"
          class="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="modelValue === status ? 'bg-gray-50 dark:bg-gray-700' : ''"
        >
          <component
            :is="config.icon"
            class="h-5 w-5"
            :class="config.iconClass"
          />
          <span :class="config.textClass">{{ config.label }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import {
  CheckCircleIcon,
  XCircleIcon,
  ClockIcon,
  WrenchIcon,
  QuestionMarkCircleIcon,
  ChevronUpDownIcon
} from '@heroicons/vue/24/solid';

const props = defineProps({
  modelValue: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);

const statusConfig = {
  done: {
    label: 'Completed',
    icon: CheckCircleIcon,
    iconClass: 'text-green-500 dark:text-green-400',
    textClass: 'text-green-700 dark:text-green-400',
    buttonClass: 'border-green-500 dark:border-green-400 bg-green-50 dark:bg-green-500/10'
  },
  not_complete: {
    label: 'Not Complete',
    icon: XCircleIcon,
    iconClass: 'text-red-500 dark:text-red-400',
    textClass: 'text-red-700 dark:text-red-400',
    buttonClass: 'border-red-500 dark:border-red-400 bg-red-50 dark:bg-red-500/10'
  },
  in_progress: {
    label: 'In Progress',
    icon: ClockIcon,
    iconClass: 'text-blue-500 dark:text-blue-400',
    textClass: 'text-blue-700 dark:text-blue-400',
    buttonClass: 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-500/10'
  },
  repair_needed: {
    label: 'Repair Needed',
    icon: WrenchIcon,
    iconClass: 'text-yellow-500 dark:text-yellow-400',
    textClass: 'text-yellow-700 dark:text-yellow-400',
    buttonClass: 'border-yellow-500 dark:border-yellow-400 bg-yellow-50 dark:bg-yellow-500/10'
  }
};

const selectStatus = (status) => {
  emit('update:modelValue', status);
  isOpen.value = false;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    isOpen.value = false;
  }
};

// Add and remove click outside listener
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script> 