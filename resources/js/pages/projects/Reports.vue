<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { CalendarIcon, DocumentIcon, ArrowLeftIcon, UserCircleIcon, ClockIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { Link } from '@inertiajs/vue3'
import StatusBadge from '@/components/StatusBadge.vue'
import CustomAlert from '@/components/CustomAlert.vue'
import { ref } from 'vue'

const props = defineProps<{
  project: {
    id: number
    name: string
    description: string
    project_date: string
  }
  reports: Array<{
    id: string
    name: string
    status: 'done' | 'not_completed' | 'in_progress' | 'repair_needed'
    notes: string
    submitted_at: string
    attachments: Array<{
      id: number
      name: string
      url: string
    }>
  }>
}>()

const showDeleteAlert = ref(false)
const reportToDelete = ref<string | null>(null)

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric'
  }).format(date)
}

const getTimeAgo = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const seconds = Math.floor((now.getTime() - date.getTime()) / 1000)
  
  let interval = Math.floor(seconds / 31536000)
  if (interval >= 1) return interval + ' year' + (interval === 1 ? '' : 's') + ' ago'
  
  interval = Math.floor(seconds / 2592000)
  if (interval >= 1) return interval + ' month' + (interval === 1 ? '' : 's') + ' ago'
  
  interval = Math.floor(seconds / 86400)
  if (interval >= 1) return interval + ' day' + (interval === 1 ? '' : 's') + ' ago'
  
  interval = Math.floor(seconds / 3600)
  if (interval >= 1) return interval + ' hour' + (interval === 1 ? '' : 's') + ' ago'
  
  interval = Math.floor(seconds / 60)
  if (interval >= 1) return interval + ' minute' + (interval === 1 ? '' : 's') + ' ago'
  
  return 'just now'
}

const isImageFile = (filename: string) => {
  const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp']
  return imageExtensions.some(ext => filename.toLowerCase().endsWith(ext))
}

const deleteReport = () => {
  if (reportToDelete.value) {
    router.delete(route('reports.destroy', reportToDelete.value), {
      onSuccess: () => {
        showDeleteAlert.value = false
        reportToDelete.value = null
      },
    })
  }
}

const confirmDelete = (reportId: string) => {
  reportToDelete.value = reportId
  showDeleteAlert.value = true
}
</script>

<template>
  <AppLayout>
    <Head>
      <title>Reports - {{ project.name }}</title>
    </Head>

    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="flex items-center gap-4 mb-8">
        <Button
          variant="outline"
          size="icon"
          :href="route('projects.index')"
          as="Link"
          class="shrink-0"
        >
          <ArrowLeftIcon class="h-5 w-5" />
        </Button>
        <div class="flex-grow">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
            {{ project.name }}
          </h1>
          <div class="flex items-center gap-4 text-sm text-muted-foreground mt-2">
            <div class="flex items-center gap-2">
              <CalendarIcon class="h-4 w-4" />
              <span>{{ formatDate(project.project_date) }}</span>
            </div>
            <div class="flex items-center gap-2">
              <DocumentIcon class="h-4 w-4" />
              <span>{{ reports.length }} Reports</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Reports List -->
      <div class="space-y-6">
        <div v-if="reports.length === 0" class="text-center py-12 bg-background rounded-lg border border-border">
          <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No reports</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            No maintenance reports have been submitted for this project yet.
          </p>
        </div>

        <Card
          v-for="report in reports"
          :key="report.id"
          class="transition-all duration-200 hover:shadow-md"
        >
          <CardHeader class="pb-3">
            <CardTitle class="flex items-center justify-between flex-wrap gap-4">
              <div class="flex items-center gap-4">
                <div class="text-xl font-semibold">{{ report.name }}</div>
                <StatusBadge :status="report.status" />
              </div>
              <div class="flex items-center gap-4">
                <div class="flex flex-col items-end text-sm text-muted-foreground">
                  <div class="flex items-center gap-1">
                    <ClockIcon class="h-4 w-4" />
                    <span>{{ getTimeAgo(report.submitted_at) }}</span>
                  </div>
                  <div class="text-xs">
                    {{ formatDate(report.submitted_at) }}
                  </div>
                </div>
                <Button
                  variant="ghost"
                  size="icon"
                  class="text-destructive hover:text-destructive/90 hover:bg-destructive/10"
                  @click="confirmDelete(report.id)"
                >
                  <TrashIcon class="h-5 w-5" />
                </Button>
              </div>
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div v-if="report.notes" class="bg-muted/50 rounded-lg p-4">
              <div class="text-sm font-medium mb-2">Notes</div>
              <div class="text-sm text-muted-foreground whitespace-pre-wrap">{{ report.notes }}</div>
            </div>

            <div v-if="report.attachments.length > 0" class="space-y-2">
              <div class="text-sm font-medium">Attachments</div>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <a
                  v-for="attachment in report.attachments"
                  :key="attachment.id"
                  :href="attachment.url"
                  target="_blank"
                  class="group"
                >
                  <div v-if="isImageFile(attachment.name)" 
                       class="relative aspect-[4/3] overflow-hidden rounded-lg border border-border bg-muted hover:border-border/80 transition-colors">
                    <img 
                      :src="attachment.url" 
                      :alt="attachment.name"
                      class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                      <span class="text-sm text-white truncate">{{ attachment.name }}</span>
                    </div>
                  </div>
                  <div v-else
                       class="flex items-center gap-2 p-3 rounded-lg border border-border hover:bg-accent/50 transition-colors">
                    <DocumentIcon class="h-5 w-5 text-muted-foreground group-hover:text-foreground transition-colors" />
                    <span class="text-sm truncate group-hover:text-foreground transition-colors">{{ attachment.name }}</span>
                  </div>
                </a>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <CustomAlert
      :is-open="showDeleteAlert"
      @close="showDeleteAlert = false"
      title="Delete Report"
      message="Are you sure you want to delete this report? This action cannot be undone and will also delete all associated images."
      type="danger"
      confirm-text="Delete Report"
      @confirm="deleteReport"
    />
  </AppLayout>
</template> 