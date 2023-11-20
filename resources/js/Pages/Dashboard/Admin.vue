<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header> Dashboard </template>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900 mb-2">Productos con bajas existencias</h2>
        <table class="table-auto border border-gray-400 rounded" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">Nombre</th>
              <th class="px-2 py-2">Material</th>
              <th class="px-2 py-2">Metros Disp.</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="product in products"
              :key="product.id"
              class="border-t-2 border-gray-200 text-center"
            >
              <td class="px-2 py-2">
                {{ product.name }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ product.material }}
              </td>
              <td class="px-2 py-2 text-center">
                <span
                  v-if="product.stock_meters <= 10"
                  class="bg-red-500 text-white text-sm font-medium mr-2 px-2.5 py-0.5 rounded"
                  >{{ product.stock_meters }}</span
                >
                <span v-else>{{ product.stock_meters }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  products: Object,
})
</script>