<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
  name: null,
  ip_address: null,
  serial_number: null,
  model: null,
  site: null,
  location: null,
  snmp_community: 'public',
  supplier_email: null,
  status: null,
  image: null,
})

const imageUrl = ref(null)

function handleImageUpload(event) {
  const file = event.target.files[0]

  if (file) {
    form.image = file
    imageUrl.value = URL.createObjectURL(file)
  }
}

function submit() {
  form.post(route('printers.store'), {
    forceFormData: true
  })
}
</script>

<template>
<div class="bg-gray-100 min-h-screen flex items-center justify-center p-8">
<div class="max-w-3xl w-full bg-white p-8 shadow-lg rounded-lg">

<h1 class="text-2xl font-bold text-gray-800 mb-6">
Add New Printer
</h1>

<form @submit.prevent="submit">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- Printer Name -->
<div>
<label class="block text-sm font-medium text-gray-700">
Printer Name
</label>

<input
v-model="form.name"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
{{ form.errors.name }}
</div>
</div>

<!-- IP Address -->
<div>
<label class="block text-sm font-medium text-gray-700">
IP Address
</label>

<input
v-model="form.ip_address"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.ip_address" class="text-red-600 text-sm mt-1">
{{ form.errors.ip_address }}
</div>
</div>
<!---- Serial Number -->
<div>
  <label class="block text-sm font-medium text-gray-700">
    Serial Number
  </label>

  <input
    v-model="form.serial_number"
    type="text"
    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
  />
</div>
<!-- Model -->
<div>
<label class="block text-sm font-medium text-gray-700">
Model
</label>

<input
v-model="form.model"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.model" class="text-red-600 text-sm mt-1">
{{ form.errors.model }}
</div>
</div>

<!-- Site -->
<div>
<label class="block text-sm font-medium text-gray-700">
Site
</label>

<input
v-model="form.site"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.site" class="text-red-600 text-sm mt-1">
{{ form.errors.site }}
</div>
</div>

<!-- Location -->
<div>
<label class="block text-sm font-medium text-gray-700">
Location
</label>

<input
v-model="form.location"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.location" class="text-red-600 text-sm mt-1">
{{ form.errors.location }}
</div>
</div>

<!-- SNMP -->
<div>
<label class="block text-sm font-medium text-gray-700">
SNMP Community
</label>

<input
v-model="form.snmp_community"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.snmp_community" class="text-red-600 text-sm mt-1">
{{ form.errors.snmp_community }}
</div>
</div>

<!-- Supplier Email -->
<div>
<label class="block text-sm font-medium text-gray-700">
Supplier Email
</label>

<input
v-model="form.supplier_email"
type="email"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.supplier_email" class="text-red-600 text-sm mt-1">
{{ form.errors.supplier_email }}
</div>
</div>

<!-- Status -->
<div>
<label class="block text-sm font-medium text-gray-700">
Status
</label>

<input
v-model="form.status"
type="text"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
/>

<div v-if="form.errors.status" class="text-red-600 text-sm mt-1">
{{ form.errors.status }}
</div>
</div>

<!-- Image -->
<div class="md:col-span-2">

<label class="block text-sm font-medium text-gray-700">
Printer Image
</label>

<input
type="file"
@change="handleImageUpload"
class="mt-1 block w-full text-sm text-gray-500"
/>

<div v-if="form.errors.image" class="text-red-600 text-sm mt-1">
{{ form.errors.image }}
</div>

<img
v-if="imageUrl"
:src="imageUrl"
class="mt-4 h-32 w-32 object-cover rounded-md"
/>

</div>

</div>

<!-- Actions -->
<div class="mt-8 flex justify-end space-x-4">

<Link
:href="route('printers.index')"
class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md"
>
Cancel
</Link>

<button
type="submit"
:disabled="form.processing"
class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
>
Save Printer
</button>

</div>

</form>

</div>
</div>
</template>