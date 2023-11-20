<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import Swal from "sweetalert2";
import moment from "moment";

const props = defineProps({
  orders: Array,
  clients: Array,
});
const showModal = ref(false);
const form = useForm({
  client_id: "empty",
});

const deleteItem = async (order) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere eliminar la orden  ${order.id}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      form.delete(route("orders.destroy", order.id));
    }
  } catch (error) {
    console.error(error);
  }
};

const openModal = () => {
  form.reset();
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};
const parseDate = (date) => {
  return moment(date).format("DD-MM-YYYY HH:mm:ss");
};

const HandleSubmit = () => {
  form.post(route("orders.store"), {});
};

const updateClient = (e) => {
  form.client_id = e;
}
</script>

<template>
  <Head title="Ventas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2>
    </template>
    <div class="py-1 border rounded-md">
      <div class="bg-white grid v-screen">
        <div class="mt-3 mb-3 flex ms-10">
          <PrimaryButton @click="openModal">
            <i class="fa-solid fa-plus-circle"></i> Nueva Compra
          </PrimaryButton>
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
              <th class="px-2 py-2">Monto</th>
              <th class="px-2 py-2">Status</th>
              <th class="px-2 py-2">Fecha</th>
              <th class="px-2 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="order in props.orders"
              :key="order.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2 text-center">{{ order.id }}</td>
              <td class="px-2 py-2">
                {{ order.client.name }} {{ order.client.last_name }} - Company:
                {{ order.client.company }}
              </td>
              <td class="px-2 py-2 text-end">
                {{ order.amount }}
              </td>
              <td class="px-2 py-2 text-end">Pendiente</td>
              <td class="px-2 py-2 text-center">
                {{ parseDate(order.updated_at) }}
              </td>
              <td class="px-2 py-2 flex justify-center">
                <Link :href="route('orders.show', order.id)">
                  <WarningButton>
                    <i class="fa-regular fa-eye"></i>
                  </WarningButton>
                </Link>
                <DangerButton class="ml-2" @click="deleteItem(order)">
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Modal Start -->
    <Modal :show="showModal" @close="closeModal">
      <h2 class="p-3 text-lg font.medium text-hray-900">
        Nueva Orden de Venta
      </h2>
      <form @submit.prevent="HandleSubmit">
        <div class="p-3 pb-0">
          <InputLabel for="client_id" value="Cliente:"></InputLabel>
          <SelectInput
            id="client_id"
            v-model="form.client_id"
            @update="updateClient"
            class="mt-1 block w-full"
            :options="props.clients"
            placeholder="Seleccione al Cliente"
          ></SelectInput>
          <InputError
            :message="form.errors.client_id"
            class="mt-2"
          ></InputError>
        </div>

        <div class="p-3 mt-2">
          <PrimaryButton :disabled="form.processing" @click="save">
            <i class="fa-solid fa-save"></i> Crear
          </PrimaryButton>
          <SecondaryButton
            class="ml-3"
            :disabled="form.processing"
            @click="closeModal"
          >
            Cancelar
          </SecondaryButton>
        </div>
      </form>
    </Modal>
    <!-- Modal End -->
  </AuthenticatedLayout>
</template>
