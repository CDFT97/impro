<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Paginator from "@/Components/Paginator.vue";
import moment from "moment"

const props = defineProps({
  purchases: [],
  provider: {}
});

const parseDate = (date) => {
    return moment(date).format("DD-MM-YYYY HH:mm:ss"); 
}
</script>

<template>
  <Head title="Compras a Proveedores" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Compras al Proveedor {{ provider.name }} {{ provider.last_name }}
      </h2>
    </template>
    <div class="py-12 border rounded-md">
      <div
        class="bg-white grid v-screen place-items-center overflow-x-auto py-3"
      >
        <table class="table-auto border border-gray-400" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">ID</th>
              <th class="px-2 py-2">Descripción</th>
              <th class="px-2 py-2">Monto USD</th>
              <th class="px-2 py-2">Monto Bs</th>
              <th class="px-2 py-2">Fecha</th>
              <th class="px-2 py-2">Utl Actualización</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="purchase in purchases.data"
              :key="purchase.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2 text-center">{{ purchase.id }}</td>
              <td class="px-2 py-2">
                {{ purchase.description }}
              </td>
              <td class="px-2 py-2 text-end">
                {{ purchase.amount_usd }}
              </td>
              <td class="px-2 py-2 text-end">
                {{ purchase.amount }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ purchase.date }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ parseDate(purchase.updated_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white grid v-screen place-items-center pb-10">
        <Paginator :paginated_data="purchases"></Paginator>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
