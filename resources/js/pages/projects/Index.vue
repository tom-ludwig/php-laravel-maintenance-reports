<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter, DialogDescription } from '@/components/ui/dialog'
import { Label } from '@/components/ui/label'
import { Dialog as QrDialog } from '@/components/ui/dialog'
import { PlusIcon, TrashIcon, QrCodeIcon, CalendarIcon, DocumentIcon } from '@heroicons/vue/24/outline'
import QRCode from 'qrcode'
import CustomAlert from '@/Components/CustomAlert.vue'

import { ref, computed, onMounted, watch } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import { ref as vueRef } from 'vue'

const props = defineProps<{
  projects: Array<{
    id: number
    name: string
    description: string
    project_date: string
    form_url: string
  }>
}>()

const selectedProject = vueRef<typeof props.projects[0] | null>(null)
const showQrPreview = vueRef(false)
const qrDataUrl = vueRef<string>('')

const qrCodes = ref<Record<number, string>>({})

const generateQrCode = async (url: string, darkMode = false) => {
  try {
    const options = {
      width: 300,
      margin: 2,
      color: {
        dark: darkMode ? '#fff' : '#000',
        light: darkMode ? '#000' : '#fff'
      }
    }
    return await QRCode.toDataURL(url, options)
  } catch (err) {
    console.error(err)
    return null
  }
}

const generateAllQrCodes = async () => {
  for (const project of props.projects) {
    if (!qrCodes.value[project.id]) {
      qrCodes.value[project.id] = await generateQrCode(project.form_url) || ''
    }
  }
}

// Generate QR codes on mount
onMounted(generateAllQrCodes)

// Watch for changes in projects and generate new QR codes
watch(() => props.projects, generateAllQrCodes, { deep: true })

const showQrCode = async (project: typeof props.projects[0], darkMode = false) => {
  selectedProject.value = project
  qrDataUrl.value = await generateQrCode(project.form_url, darkMode) || ''
  showQrPreview.value = true
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date)
}

const showCreateModal = ref(false)

const form = useForm({
  name: '',
  description: '',
  project_date: ''
})

const createProject = () => {
  form.post('/projects', {
    onSuccess: () => {
      showCreateModal.value = false
      form.reset()
    }
  })
}

const showDeleteAlert = ref(false)
const projectToDelete = ref<typeof props.projects[0] | null>(null)

const confirmDelete = (project: typeof props.projects[0]) => {
    projectToDelete.value = project
    showDeleteAlert.value = true
}

const handleDelete = () => {
    if (projectToDelete.value) {
        router.delete(`/projects/${projectToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteAlert.value = false
                projectToDelete.value = null
            }
        })
    }
}
</script>

<template>
  <AppLayout>
    <Head>
      <title>Projects - Maintenance Reports</title>
    </Head>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Projects</h1>
          <Button @click="showCreateModal = true" class="gap-2">
            <PlusIcon class="h-5 w-5" />
            Create Project
          </Button>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <Card
            v-for="project in projects"
            :key="project.id"
            class="transition-all duration-200 hover:shadow-lg"
          >
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle class="text-xl">{{ project.name }}</CardTitle>
              <Button
                variant="ghost"
                size="icon"
                @click="confirmDelete(project)"
                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
              >
                <TrashIcon class="h-5 w-5" />
              </Button>
            </CardHeader>
            <CardContent>
              <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                <CalendarIcon class="h-4 w-4" />
                <span>{{ formatDate(project.project_date) }}</span>
              </div>
              <div class="flex flex-col gap-2 mb-4">
                <p class="text-sm text-card-foreground">{{ project.description }}</p>
                <p class="text-xs text-muted-foreground">Project ID: {{ project.id }}</p>
              </div>
              
              <div v-if="project.form_url" class="space-y-4">
                <div class="relative group flex justify-center">
                  <img
                    v-if="qrCodes[project.id]"
                    :src="qrCodes[project.id]"
                    alt="QR Code"
                    class="w-32 h-32 rounded-lg shadow-sm transition-transform group-hover:scale-105"
                    @click="showQrCode(project)"
                  />
                  <div 
                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center cursor-pointer"
                    @click="showQrCode(project)"
                  >
                    <QrCodeIcon class="h-8 w-8 text-white" />
                  </div>
                </div>
                <div class="flex flex-col gap-2">
                  <Button
                    variant="outline"
                    size="sm"
                    @click="router.visit(`/projects/${project.id}/reports`)"
                    class="w-full gap-2"
                  >
                    <DocumentIcon class="h-4 w-4" />
                    View Reports
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Create Project Modal -->
        <Dialog :open="showCreateModal" @update:open="showCreateModal = false">
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Create New Project</DialogTitle>
            </DialogHeader>

            <form @submit.prevent="createProject" class="space-y-6">
              <div class="space-y-4">
                <div class="space-y-2">
                  <Label for="name">Name</Label>
                  <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Enter project name"
                  />
                </div>

                <div class="space-y-2">
                  <Label for="description">Description</Label>
                  <Textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    placeholder="Enter project description"
                  />
                </div>

                <div class="space-y-2">
                  <Label for="project_date">Project Date</Label>
                  <Input
                    id="project_date"
                    v-model="form.project_date"
                    type="date"
                    required
                  />
                </div>
              </div>

              <DialogFooter>
                <Button
                  type="button"
                  variant="secondary"
                  @click="showCreateModal = false"
                >
                  Cancel
                </Button>
                <Button
                  type="submit"
                  :disabled="form.processing"
                >
                  {{ form.processing ? 'Creating...' : 'Create Project' }}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>

        <!-- QR Code Preview Modal -->
        <QrDialog :open="showQrPreview" @update:open="showQrPreview = false">
          <DialogContent class="sm:max-w-md">
            <DialogHeader>
              <DialogTitle>QR Code</DialogTitle>
              <DialogDescription>
                Scan this QR code to access the maintenance report form, or click the button below to view it on a dedicated page.
              </DialogDescription>
            </DialogHeader>
            <div class="flex flex-col items-center gap-4 p-6">
              <img
                v-if="qrDataUrl"
                :src="qrDataUrl"
                alt="QR Code"
                class="w-64 h-64 rounded-lg shadow-lg"
              />
              <Link
                v-if="selectedProject"
                :href="`/projects/${selectedProject.id}/qr-code`"
                target="_blank"
                class="inline-flex items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-medium bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 shadow-sm"
              >
                <QrCodeIcon class="h-5 w-5" />
                View QR Code Page
              </Link>
            </div>
          </DialogContent>
        </QrDialog>

        <!-- Delete Confirmation Alert -->
        <CustomAlert
            :is-open="showDeleteAlert"
            title="Delete Project"
            :message="projectToDelete ? `Are you sure you want to delete '${projectToDelete.name}'?'` : ''"
            type="danger"
            confirm-text="Delete"
            @close="showDeleteAlert = false"
            @confirm="handleDelete"
        />
      </div>
    </div>
  </AppLayout>
</template> 