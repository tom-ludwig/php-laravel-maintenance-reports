<script setup lang="ts">
import {
  CheckCircleIcon,
  XCircleIcon,
  ClockIcon,
  WrenchIcon
} from '@heroicons/vue/24/solid'

const props = defineProps<{
  status: 'done' | 'not_completed' | 'in_progress' | 'repair_needed'
}>()

const statusConfig = {
  done: {
    label: 'Completed',
    icon: CheckCircleIcon,
    class: 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20'
  },
  not_completed: {
    label: 'Not Complete',
    icon: XCircleIcon,
    class: 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20'
  },
  in_progress: {
    label: 'In Progress',
    icon: ClockIcon,
    class: 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20'
  },
  repair_needed: {
    label: 'Repair Needed',
    icon: WrenchIcon,
    class: 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20'
  }
}
</script>

<template>
  <div
    class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
    :class="statusConfig[status].class"
  >
    <component :is="statusConfig[status].icon" class="h-3.5 w-3.5" />
    {{ statusConfig[status].label }}
  </div>
</template> 