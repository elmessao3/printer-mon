<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  printer: Object,
})

const form = useForm({
  name: props.printer.name,
  ip_address: props.printer.ip_address,
  model: props.printer.model,
  serial_number: props.printer.serial_number,
  site: props.printer.site,
  location: props.printer.location,
  supplier_email: props.printer.supplier_email,
})

const submit = () => {
  form.put(route('printers.update', props.printer.id))
}
</script>

<template>
  <div class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-3xl mx-auto">

      <!-- Header -->
      <div class="mb-6">
        <Link
          :href="route('printers.show', printer.id)"
          class="text-sm font-medium text-blue-600 hover:text-blue-800"
        >
          &larr; Back to Printer
        </Link>

        <h1 class="text-3xl font-bold text-gray-800 mt-2">
          Edit Printer
        </h1>
      </div>

      <!-- Form Card -->
      <div class="bg-white p-6 shadow-md rounded-lg">

        <form @submit.prevent="submit" class="space-y-4">

          <!-- Name -->
          <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input v-model="form.name" type="text" class="w-full border p-2 rounded" />
          </div>

          <!-- IP -->
          <div>
            <label class="block text-sm font-semibold mb-1">IP Address</label>
            <input v-model="form.ip_address" type="text" class="w-full border p-2 rounded" />
          </div>

          <!-- Model -->
          <div>
            <label class="block text-sm font-semibold mb-1">Model</label>
            <input v-model="form.model" type="text" class="w-full border p-2 rounded" />
          </div>

          <!-- Serial -->
          <div>
            <label class="block text-sm font-semibold mb-1">S/N</label>
            <input v-model="form.serial_number" type="text" class="w-full border p-2 rounded" />
          </div>

          <!-- Site -->
          <div>
            <label class="block text-sm font-semibold mb-1">Site</label>
            <input v-model="form.site" type="text" class="w-full border p-2 rounded" />
          </div>

          <!-- Location -->
          <div>
            <label class="block text-sm font-semibold mb-1">Location</label>
            <input v-model="form.location" type="text" class="w-full border p-2 rounded" />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold mb-1">Supplier Email</label>
            <input v-model="form.supplier_email" type="email" class="w-full border p-2 rounded" />
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-2 pt-4">

            <Link
              :href="route('printers.show', printer.id)"
              class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
            >
              Cancel
            </Link>

            <button
              type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
              :disabled="form.processing"
            >
              Save Changes
            </button>

          </div>

        </form>

      </div>

    </div>
  </div>
</template>