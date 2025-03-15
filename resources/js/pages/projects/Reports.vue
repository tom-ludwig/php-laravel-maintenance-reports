<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { CalendarIcon, DocumentIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline'
import { Link } from '@inertiajs/vue3'

const props = defineProps<{
  project: {
    id: number
    name: string
    description: string
    project_date: string
  }
  reports: Array<{
    id: number
    submitted_at: string
    submitter_name: string
    submitter_email: string
    notes: string
    attachments: Array<{
      id: number
      name: string
      url: string
    }>
  }>
}>()

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
</script>

<template>
  <AppLayout>
    <Head>
      <title>Reports - {{ project.name }}</title>
    </Head>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex items-center gap-4 mb-6">
          <Button
            variant="outline"
            size="icon"
            :href="route('projects.index')"
            as="Link"
            class="shrink-0"
          >
            <ArrowLeftIcon class="h-5 w-5" />
          </Button>
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
              {{ project.name }} - Reports
            </h1>
            <div class="flex items-center gap-2 text-sm text-muted-foreground mt-1">
              <CalendarIcon class="h-4 w-4" />
              <span>{{ formatDate(project.project_date) }}</span>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div v-if="reports.length === 0" class="text-center py-12">
            <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No reports</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              No maintenance reports have been submitted for this project yet.
            </p>
          </div>

          <Card
            v-for="report in reports"
            :key="report.id"
            class="transition-all duration-200"
          >
            <CardHeader class="pb-3">
              <CardTitle class="text-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span>Report by {{ report.submitter_name }}</span>
                </div>
                <span class="text-sm text-muted-foreground">
                  {{ formatDate(report.submitted_at) }}
                </span>
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div>
                <div class="text-sm text-muted-foreground">Email</div>
                <div>{{ report.submitter_email }}</div>
              </div>

              <div>
                <div class="text-sm text-muted-foreground">Notes</div>
                <div class="whitespace-pre-wrap">{{ report.notes }}</div>
              </div>

              <div v-if="report.attachments.length > 0">
                <div class="text-sm text-muted-foreground mb-2">Attachments</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                  <a
                    v-for="attachment in report.attachments"
                    :key="attachment.id"
                    :href="attachment.url"
                    target="_blank"
                    class="flex items-center gap-2 p-2 rounded-lg border border-border hover:bg-accent transition-colors"
                  >
                    <DocumentIcon class="h-5 w-5 text-muted-foreground" />
                    <span class="text-sm truncate">{{ attachment.name }}</span>
                  </a>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 