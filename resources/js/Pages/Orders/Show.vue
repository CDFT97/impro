<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import Swal from "sweetalert2";
import moment from "moment";

const props = defineProps({
  order: Object,
  products: Array,
});
const showModal = ref(false);
const form = useForm({});

const removeItem = async (order) => {
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
};
</script>

<template>
  <Head title="Ventas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Venta # {{ props.order.id }}</h2>
    </template>
    <div class="py-1 border rounded-md">
      <div class="bg-white grid v-screen">
        <div class="sm:ms-2 flex flex-wrap  ms-7 mt-4">
          <form @submit.prevent="HandleSubmit" class="sm:flex">
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="client_id" value="Cliente" />
              <TextInput
                id="client_id"
                type="text"
                class="mt-1 block w-full"
                value="Pedro"
                disabled
              />
            </div>
            <div class="mx-1 sm:w-1/5 w-full">
              <InputLabel for="description" value="Descripción*" />
              <TextInput
                id="description"
                type="text"
                class="mt-1 block w-full"
                value="King Stickers"
              />
            </div>
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="hash" value="Referencia*" />
              <TextInput
                id="hash"
                type="text"
                class="mt-1 block w-full"
                value="588433215"
              />
            </div>
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="status" value="Estado" />
              <TextInput
                id="status"
                type="text"
                class="mt-1 block w-full"
                value="Completed"
                disabled
              />
            </div>
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="status" value="Fecha" />
              <TextInput
                id="status"
                type="text"
                class="mt-1 block w-full"
                value="23/12/2023"
                disabled
              />
            </div>
            <PrimaryButton class="ms-1 sm:ms-2 sm:mt-6 mt-2 sm:w-1/6 w-full">
              Guardar
            </PrimaryButton>
          </form>
        </div>
        <div class="mt-3 mb-1 block ms-4 flex-wrap sm:flex">
          <PrimaryButton @click="openModal" class="ms-4 sm:ms-1 w-52 sm:w-1/6">
            <i class="fa-solid fa-plus-circle"></i> Agregar Producto
          </PrimaryButton>
          <div class="ms-10 mt-2">
            <p class="flex text-xl">Total: $ 57.69</p>
          </div>
        </div>
      </div>
      <div
        class="bg-white grid v-screen place-items-center overflow-x-auto py-3"
      >
        <table class="table-auto border border-gray-400" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">Producto</th>
              <th class="px-2 py-2">Formato</th>
              <th class="px-2 py-2">Cantidad</th>
              <th class="px-2 py-2">M2</th>
              <th class="px-2 py-2">M</th>
              <th class="px-2 py-2">F..xM2</th>
              <th class="px-2 py-2">P. Unitario</th>
              <th class="px-2 py-2">P Total</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="order in orders"
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
                <WarningButton>
                  <i class="fa-regular fa-eye"></i>
                </WarningButton>
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
        Agregar Producto
      </h2>
      <form @submit.prevent="HandleSubmit">
        <div class="p-3 pb-0">
          <InputLabel for="client_id" value="Productos:"></InputLabel>
          <SelectInput
            id="client_id"
            v-model="form.client_id"
            @update="updateClient"
            class="mt-1 block w-full"
            :options="props.products"
            placeholder="Seleccione un producto"
            required
          ></SelectInput>
          <InputError
            :message="form.errors.client_id"
            class="mt-2"
          ></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="format" value="Formato:"></InputLabel>
          <TextInput
            id="format"
            v-model="form.format"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Formato"
            required
          ></TextInput>
          <InputError :message="form.errors.format" class="mt-2"></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="quantity" value="Cantidad:"></InputLabel>
          <TextInput
            id="quantity"
            v-model="form.quantity"
            type="number"
            min="1"
            placeholder="2"
            required
            class="mt-1 block w-3/4"
          ></TextInput>
          <InputError :message="form.errors.quantity" class="mt-2"></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="m2" value="M2:"></InputLabel>
          <TextInput
            id="m2"
            v-model="form.m2"
            type="number"
            min="1"
            placeholder="7.5"
            class="mt-1 block w-3/4"
            required
          ></TextInput>
          <InputError :message="form.errors.m2" class="mt-2"></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="m" value="M:"></InputLabel>
          <TextInput
            id="m"
            v-model="form.m"
            type="number"
            min="1"
            class="mt-1 block w-3/4"
            placeholder="14"
            required
          ></TextInput>
          <InputError :message="form.errors.m" class="mt-2"></InputError>
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
