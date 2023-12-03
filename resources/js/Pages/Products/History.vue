<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import moment from "moment";
import { useAlertsStore } from "@/Stores/alerts";

const props = defineProps({
  products: [],
  product: Object,
  orders: [],
  total_material: Number,
});
const alertsStore = useAlertsStore();

const form = useForm({
  product_id: "empty",
  from: "",
  to: "",
});

const parseDate = (date) => {
  return moment(date).format("DD-MM-YYYY");
};

const handleSubmitSearch = () => {
  if (form.product_id == "empty") {
    return alertsStore.error("Por favor seleccione un producto");
  }

  form.post(route("products.history.search"))
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
    <div class="py-1 border">
      <div class="bg-white grid v-screen">
        <div class="mt-3 mb-3 ms-10 w-full">
          <form class="flex" @submit.prevent="handleSubmitSearch">
            <div class="p-3 pb-0">
              <InputLabel for="product" value="Producto:"></InputLabel>
              <SelectInput
                id="product"
                v-model="form.product_id"
                @update="
                  (e) => {
                    form.product_id = e;
                  }
                "
                class="mt-1 block w-full"
                :options="products"
                placeholder="Seleccione el producto"
              ></SelectInput>
            </div>

            <div class="p-3 pb-0">
              <InputLabel for="date_from" value="Desde:"></InputLabel>
              <TextInput
                id="date_from"
                ref="dateInput"
                v-model="form.from"
                type="date"
                class="mt-1 block w-3/4 ml-2"
                required
              ></TextInput>
            </div>

            <div class="p-3 pb-0">
              <InputLabel for="date_to" value="Hasta:"></InputLabel>
              <TextInput
                id="date_to"
                ref="dateInput"
                v-model="form.to"
                type="date"
                class="mt-1 block w-3/4 ml-2"
                required
              ></TextInput>
            </div>

            <div class="p-3 pb-0 flex mt-4">
                <PrimaryButton class="mt-1 ms-4">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </PrimaryButton>
                <Link :href="route('products.history')" class="mt-1 ms-4 flex">
                    <SecondaryButton class="w-full h-full">
                        <i class="fa-solid fa-rotate-left"></i>
                    </SecondaryButton>
                </Link>
            </div>

          </form>
        </div>
        <div class="mt-3 mb-3 flex ms-10">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Total de material vendido: {{ props.total_material ?? 0}} Mts
          </h2>
        </div>
      </div>
      <div
        class="bg-white grid v-screen place-items-center overflow-x-auto py-3"
      >
        <table class="table-auto border border-gray-400" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">ID Orden/Venta</th>
              <th class="px-2 py-2">Descripcion</th>
              <th class="px-2 py-2">Monto</th>
              <th class="px-2 py-2">Cantidad de material</th>
              <th class="px-2 py-2">Fecha de la orden/venta</th>
              <th class="px-2 py-2">Detalles</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="order in orders"
              :key="order.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2 text-center">
                {{ order.id }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ order.description }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ order.amount }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ order.qty_material }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ parseDate(order.created_at) }}
              </td>
              
              <td class="px-2 py-2 flex justify-center">
                <Link :href="route('orders.show', order.id)" class="mt-1 ms-4 flex">
                    <SecondaryButton class="w-full h-full">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </SecondaryButton>
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
