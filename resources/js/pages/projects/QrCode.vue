<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { QrCodeIcon } from '@heroicons/vue/24/outline'
import QRCode from 'qrcode'
import { onMounted, ref } from 'vue'

const props = defineProps<{
  project: {
    id: number
    name: string
    form_url: string
  }
}>()

const qrDataUrl = ref('')

onMounted(async () => {
  try {
    qrDataUrl.value = await QRCode.toDataURL(props.project.form_url, {
      width: 800,
      margin: 2,
      color: {
        dark: '#000',
        light: '#fff'
      }
    })
  } catch (err) {
    console.error(err)
  }
})
</script>

<template>
  <Head>
    <title>QR Code - {{ project.name }}</title>
  </Head>

  <div class="min-h-screen bg-white dark:bg-gray-900 flex flex-col items-center justify-center p-4">
    <div class="max-w-3xl w-full text-center space-y-8">
      <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">
        {{ project.name }}
      </h1>
      
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-8">
        <div class="flex justify-center">
          <img
            v-if="qrDataUrl"
            :src="qrDataUrl"
            alt="QR Code"
            class="w-[600px] h-[600px] rounded-lg shadow-md"
          />
        </div>
        
        <div class="space-y-6 text-left">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            Instructions
          </h2>
          <div class="prose dark:prose-invert max-w-none">
            <ol class="list-decimal list-inside space-y-3 text-lg">
              <li>Scan this QR code using your smartphone's camera or QR code scanner app</li>
              <li>You will be directed to the maintenance report form</li>
              <li>Fill out the required information in the form</li>
              <li>Submit the form to complete your maintenance report</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</template> 