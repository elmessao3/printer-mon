<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  printers: Object,
  filters: Object,
})

const search = ref(props.filters?.search || '')
const status = ref(props.filters?.status || '')

const data = ref(props.printers.data || [])
const nextPage = ref(props.printers.next_page_url)

let timeout = null
let loading = false

/* ---------------- SEARCH ---------------- */
const triggerSearch = () => {
  clearTimeout(timeout)

  timeout = setTimeout(() => {
    router.get(route('printers.index'), {
      search: search.value,
      status: status.value,
    }, {
      preserveState: false,
      replace: true,
      onSuccess: (page) => {
        data.value = page.props.printers.data
        nextPage.value = page.props.printers.next_page_url
      }
    })
  }, 300)
}

watch(search, triggerSearch)
watch(status, triggerSearch)

/* ---------------- LOAD MORE ---------------- */
const loadMore = async () => {
  if (!nextPage.value || loading) return

  loading = true

  const res = await fetch(nextPage.value, {
    headers: { Accept: 'application/json' }
  })

  const json = await res.json()

  data.value.push(...json.data)
  nextPage.value = json.next_page_url

  loading = false
}

/* ---------------- SCROLL ---------------- */
const handleScroll = () => {
  const nearBottom =
    window.innerHeight + window.scrollY >= document.body.offsetHeight - 200

  if (nearBottom) loadMore()
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<template>
<div class="bg-gray-100 min-h-screen p-8">

  <div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-red-800">
        Printer Dashboard
      </h1>

      <Link
        :href="route('printers.create')"
        class="bg-blue-600 text-white px-4 py-2 rounded"
      >
        Add Printer
      </Link>
    </div>

    <!-- SEARCH -->
    <div class="flex gap-3 mb-6">

      <input
        v-model="search"
        placeholder="Search printers..."
        class="border px-3 py-2 rounded w-64"
      />

      <select v-model="status" class="border px-3 py-2 rounded">
        <option value="">All</option>
        <option value="online">Online</option>
        <option value="offline">Offline</option>
        <option value="error">Error</option>
      </select>

    </div>

    <!-- TABLE -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">

      <table class="min-w-full">

        <thead >
          <tr>
            <th class="px-5 py-3 text-left text-xs">Name</th>
            <th class="px-5 py-3 text-left text-xs">Status</th>
            <th class="px-5 py-3 text-left text-xs">Model</th>
            <th class="px-5 py-3 text-left text-xs">Serial</th>
            <th class="px-5 py-3 text-left text-xs">Site</th>
            <th class="px-5 py-3 text-left text-xs">IP</th>
            <th class="px-5 py-3 text-left text-xs">Toner</th>
            <th class="px-5 py-3 text-left text-xs">Drum</th>
          </tr>
          
        </thead>
        
        <tbody>

          <tr v-if="!data.length">
            <td colspan="8" class="text-center py-10 text-gray-500">
              No printers found
            </td>
          </tr>

          <tr v-for="printer in data" :key="printer.id">

            <td class="px-5 py-4">
              <Link :href="route('printers.show', printer.id)">
                {{ printer.name }}
              </Link>
            </td>

            <!--  STATUS -->
    <td class="px-5 py-4">
  <span
    class="px-3 py-1 rounded-full text-xs font-semibold"
    :class="{
      'bg-green-200 text-green-800': printer.current_status === 'online',
      'bg-red-200 text-red-800': printer.current_status === 'offline',
      'bg-gray-200 text-gray-800': printer.current_status === 'unknown'
    }"
  >
    {{ printer.current_status }}
  </span>
</td>

            <td class="px-5 py-4">{{ printer.model ?? 'N/A' }}</td>
            <td class="px-5 py-4">{{ printer.serial_number ?? 'N/A' }}</td>
            <td class="px-5 py-4">{{ printer.site ?? 'N/A' }}</td>
            <td class="px-5 py-4">{{ printer.ip_address ?? 'N/A' }}</td>

            <td class="px-5 py-4">
  {{ printer.latest_status?.toner_level ?? 'N/A' }}%
</td>

<td class="px-5 py-4">
  {{ printer.latest_status?.drum_level ?? 'N/A' }}%
</td>

          </tr>

        </tbody>
      </table>

      <div v-if="loading" class="text-center py-4 text-gray-500">
        Loading...
      </div>

    </div>
  </div>
</div>
</template>