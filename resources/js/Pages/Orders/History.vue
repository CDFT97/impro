<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Paginator from "@/Components/Paginator.vue";
import moment from "moment";
const props = defineProps({
  orders: [],
});

const setStatus = (status) => {
  if (status == 1) return "Confirmada";
  else return "Cancelada";
};

const parseDate = (date) => {
  return moment(date).format("DD-MM-YYYY");
};
</script>

<template>
  <Head title="Ordenes" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historial de ordenes
      </h2>
    </template>
    <div class="py-1 border rounded-md">
      <div class="bg-white grid v-screen">
        <div class="mt-3 mb-3 flex ms-10">
          <!-- <PrimaryButton @click="openModal(true)">
            <i class="fa-solid fa-plus-circle"></i> Nueva Orden
          </PrimaryButton> -->
        </div>
      </div>
      <div
        class="bg-white grid v-screen place-items-center overflow-x-auto py-3"
      >
        <table class="table-auto border border-gray-400" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">ID</th>
              <th class="px-2 py-2">Cliente</th>
              <th class="px-2 py-2">Descripción</th>
              <th class="px-2 py-2">Estado</th>
              <th class="px-2 py-2">Monto</th>
              <th class="px-2 py-2">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="order in orders.data"
              :key="order.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2">
                {{ order.id }}
              </td>
              <td class="px-2 py-2 text-end">
                {{ order.client.name }} {{ order.client.last_name }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ order.description }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ setStatus(order.status) }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ order.amount }}
              </td>
              <td class="px-2 py-2 flex justify-center">
                {{ parseDate(order.created_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white grid v-screen place-items-center pb-10">
        <Paginator :paginated_data="orders"></Paginator>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
