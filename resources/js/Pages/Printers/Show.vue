<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

// The 'printer' object we passed from the controller is available here.
// It includes the 'status_logs' array because we loaded it.
const props = defineProps({
  printer: Object,
});

// Helper to format dates nicely
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
}
</script>

<template>
  <div class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-7xl mx-auto">

      <!-- Header -->
      <div class="mb-6">
        <Link :href="route('printers.index')" class="text-sm font-medium text-blue-600 hover:text-blue-800">
          &larr; Back to All Printers
        </Link>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ printer.name }}</h1>
      </div>

      <!-- Printer Details Card -->
      <div class="bg-white p-6 shadow-md rounded-lg mb-8">

  <!-- Header + Buttons -->
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-700">Printer Details</h2>

    <div class="flex gap-2">

      <Link
        :href="route('printers.edit', printer.id)"
        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
      >
        Edit
      </Link>

      <Link
  as="button"
  method="delete"
  :href="route('printers.destroy', printer.id)"
  onclick="return confirm('Are you sure you want to delete this printer?')"
  class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
>
  Delete
</Link>

    </div>
  </div>

  <!-- Details Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div><span class="font-semibold">IP Address:</span> {{ printer.ip_address }}</div>
    <div><span class="font-semibold">Model:</span> {{ printer.model || 'N/A' }}</div>
    <div><span class="font-semibold">Site:</span> {{ printer.site || 'N/A' }}</div>

    <div><span class="font-semibold">Location:</span> {{ printer.location || 'N/A' }}</div>
    <div><span class="font-semibold">S/N:</span> {{ printer.serial_number || 'N/A' }}</div>
    <div><span class="font-semibold">Supplier Email:</span> {{ printer.supplier_email || 'N/A' }}</div>

  </div>

</div>

      <!-- Status History -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-xl font-semibold p-6 text-gray-700">Status History</h2>
        <table class="min-w-full leading-normal">
          <thead>
            <tr>
              <th class="px-5 py-3 border-b-2 border-t-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date Checked</th>
              <th class="px-5 py-3 border-b-2 border-t-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3 border-b-2 border-t-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Toner Level</th>
              <th class="px-5 py-3 border-b-2 border-t-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Drum Level</th>
              <th class="px-5 py-3 border-b-2 border-t-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Error Message</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!printer.status_logs || printer.status_logs.length === 0">
                <td colspan="5" class="text-center py-10 text-gray-500">No status history has been recorded for this printer.</td>
            </tr>
            <tr v-for="log in printer.status_logs" :key="log.id" class="border-b border-gray-200 hover:bg-gray-50">
              <td class="px-5 py-5 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ formatDate(log.created_at) }}</p></td>
              <td class="px-5 py-5 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ log.status }}</p></td>
              <td class="px-5 py-5 bg-white text-sm w-48">
  <div v-if="log.toner_level !== null" class="w-full bg-gray-200 rounded h-4">
    <div
      class="h-4 rounded text-xs text-white text-center"
      :class="{
        'bg-green-500': log.toner_level > 50,
        'bg-yellow-500': log.toner_level <= 50 && log.toner_level > 25,
        'bg-red-500': log.toner_level <= 25
      }"
      :style="{ width: log.toner_level + '%' }"
    >
      {{ log.toner_level }}%
    </div>
  </div>

  <span v-else>N/A</span>
</td>
              <td class="px-5 py-5 bg-white text-sm w-48">
  <div v-if="log.drum_level !== null" class="w-full bg-gray-200 rounded h-4">
    <div
      class="h-4 rounded text-xs text-white text-center"
      :class="{
        'bg-green-500': log.drum_level > 50,
        'bg-yellow-500': log.drum_level <= 50 && log.drum_level > 25,
        'bg-red-500': log.drum_level <= 25
      }"
      :style="{ width: log.drum_level + '%' }"
    >
      {{ log.drum_level }}%
    </div>
  </div>

  <span v-else>N/A</span>
</td>
              <td class="px-5 py-5 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ log.error_message || 'None' }}</p></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>
