<script setup>
import { Link } from '@inertiajs/vue3'

const { printers } = defineProps({
  printers: {
    type: Array,
    default: () => []
  }
})
</script>

<template>
<div class="bg-gray-100 min-h-screen p-8">

<div class="max-w-7xl mx-auto">

<div class="flex justify-between items-center mb-6">

<h1 class="text-3xl font-bold text-gray-800">
Printer Dashboard
</h1>

<Link
:href="route('printers.create')"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
>
Add Printer
</Link>

</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">

<table class="min-w-full leading-normal">

<thead>
<tr>

<th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">
Name
</th>

<th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">
Status
</th>

<th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">
Model
</th>

<th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">
Location
</th>

<th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">
IP Address
</th>

</tr>
</thead>

<tbody>

<tr v-if="printers.length === 0">
<td colspan="5" class="text-center py-10 text-gray-500">
No printers have been added yet.
</td>
</tr>

<tr
v-for="printer in printers"
:key="printer.id"
class="border-b border-gray-200 hover:bg-gray-50"
>

<td class="px-5 py-4 text-sm font-semibold">
<Link
:href="route('printers.show', printer.id)"
class="hover:text-blue-600"
>
{{ printer.name }}
</Link>
</td>

<td class="px-5 py-4 text-sm">

<span
:class="{
'bg-green-200 text-green-800': printer.status === 'online',
'bg-red-200 text-red-800': printer.status === 'offline',
'bg-gray-200 text-gray-800': printer.status !== 'online' && printer.status !== 'offline'
}"
class="px-3 py-1 rounded-full text-xs font-semibold"
>

{{ printer.status }}

</span>

</td>

<td class="px-5 py-4 text-sm">
{{ printer.model }}
</td>

<td class="px-5 py-4 text-sm">
{{ printer.location }}
</td>

<td class="px-5 py-4 text-sm">
{{ printer.ip_address }}
</td>

</tr>

</tbody>

</table>

</div>

</div>

</div>
</template>