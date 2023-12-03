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
import { useDolarStore } from "@/Stores/dolar";
import { useAlertsStore } from "@/Stores/alerts";
import axios from "axios";

const alertsStore = useAlertsStore();
const dolarStore = useDolarStore();
const props = defineProps({
  order: Object,
  products: Array,
});
const showModal = ref(false);
const form = useForm({
  p_unit_usd: 0,
  p_unit_bs: 0,
  product_id: "empty",
  p_unit_usd: 0,
  p_unit_bs: 0,
  format: 0,
  quantity: 0,
  m2: 0,
  m: 0,
  p_total_usd: 0,
  p_total_bs: 0,
  dollar_price: 0,
  // datos para el formulario de orden
  description: props.order.description,
  status: props.order.status,
  hash: props.order.hash,
});

// const orderFormData = ref({
//   id: props.order.id,
//   client_id: props.order.client_id,
//   description: "",
//   status: props.order.status,
//   hash: "",
// });

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

const openModal = (edit = false) => {
  form.reset();
  if (!edit) {
    form.dollar_price = dolarStore.getDolar;
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};
const parseDate = (date) => {
  return moment(date).format("DD-MM-YYYY");
};

const HandleSubmit = async () => {


  form.put(route("orders.update", props.order.id),{});
  // try {
  //   await axios.put(route("orders.update", props.order.id), orderFormData);
  //   alertsStore.success("Orden actualizada con exito");
  //   location.reload();
  // } catch (error) {
  //   console.error(error);
  //   alertsStore.error("Ha ocurrido un error contacte con el administrador");
  // }
};

const updateProduct = (e) => {
  form.product_id = e;
  const product = props.products.find((product) => product.id == e);
  form.p_unit_usd = product.unit_price_in_dollars;

  form.p_unit_bs = (
    Number(product.unit_price_in_dollars) * Number(dolarStore.getDolar)
  ).toFixed(2);

  calculateTotal();
};

const calculateTotal = () => {
  console.log("total");
  form.p_total_usd = (
    parseFloat(form.p_unit_usd) *
    parseFloat(form.format) *
    parseFloat(form.m) *
    parseFloat(form.m2) *
    parseFloat(form.quantity)
  ).toFixed(2);

  calculateTotalbs();
};

const calculateTotalbs = () => {
  form.p_total_bs = (form.p_total_usd * dolarStore.getDolar).toFixed(2);
};

const save = () => {
  form.put(route("orders.update", props.order.id), {
    onSuccess: () => {
      closeModal();
    },
  });
};

const updateFormStatus = (status) => {
  // orderFormData.value.status = status;
  form.status = status;
}
</script>

<template>
  <Head title="Ventas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Venta # {{ props.order.id }}
      </h2>
    </template>
    <div class="py-1 border rounded-md">
      <div class="bg-white grid v-screen">
        <div class="sm:ms-2 flex flex-wrap ms-7 mt-4">
          <form @submit.prevent="HandleSubmit" class="sm:flex">
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="client_id" value="Cliente" />
              <TextInput
                id="client_id"
                v-model="props.order.client.name"
                type="text"
                class="mt-1 block w-full"
                disabled
              />
            </div>
            <div class="mx-1 sm:w-1/5 w-full">
              <InputLabel for="description" value="Descripción*" />
              <TextInput
                v-model="form.description"
                id="description"
                type="text"
                class="mt-1 block w-full"
              />
            </div>
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="hash" value="Referencia*" />
              <TextInput
                id="hash"
                v-model="form.hash"
                type="text"
                class="mt-1 block w-full"
              />
            </div>
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="status" value="Estado" />
              <SelectInput
                id="status"
                v-model="form.status"
                @update="updateFormStatus"
                class="mt-1 block w-full"
                :options="[
                  { id: 0, name: 'Pendiente' },
                  { id: 1, name: 'Completada' },
                  { id: 2, name: 'Cancelada' }
                ]"
                placeholder="Seleccione el estado de la orden"
              ></SelectInput>
            </div>
            <div class="sm:w-1/5 w-full mx-1">
              <InputLabel for="status" value="Fecha" />
              <TextInput
                id="status"
                type="text"
                class="mt-1 block w-full"
                :value="parseDate(props.order.created_at)"
                disabled
              />
            </div>
            <PrimaryButton class="ms-1 sm:ms-2 sm:mt-6 mt-2 sm:w-1/6 w-full">
              Guardar
            </PrimaryButton>
          </form>
        </div>
        <div class="mt-3 mb-1 block ms-4 flex-wrap sm:flex">
          <PrimaryButton v-if="props.order.status == 0"
            @click="openModal(false)"
            class="ms-4 sm:ms-1 w-52 sm:w-1/6"
          >
            <i class="fa-solid fa-plus-circle"></i> Agregar Producto
          </PrimaryButton>
          <div class="ms-10 mt-2">
            <p class="flex text-xl">Total: $ {{ props.order.amount }}</p>
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
              <th class="px-2 py-2">Remover</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="product in order.products"
              :key="product.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2 text-center">{{ product.id }}</td>
              <td class="px-2 py-2">{{ product.id }}</td>
              <td class="px-2 py-2">{{ product.id }}</td>
              <td class="px-2 py-2">{{ product.id }}</td>
              <td class="px-2 py-2">{{ product.id }}</td>
              <td class="px-2 py-2">{{ product.id }}</td>
              <td class="px-2 py-2">{{ product.id }}</td>
              <td class="px-2 py-2 flex justify-center">
                <DangerButton class="ml-2" @click="deleteItem(product)">
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
      <h2 class="p-3 text-lg font.medium text-hray-900">Agregar Producto</h2>
      <form @submit.prevent="HandleSubmit">
        <div class="p-3 pb-0">
          <InputLabel for="client_id" value="Productos:"></InputLabel>
          <SelectInput
            id="client_id"
            v-model="form.product_id"
            @update="updateProduct"
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
          <InputLabel for="p_unit_usd" value="Precio Unitario $:"></InputLabel>
          <TextInput
            id="p_unit_usd"
            v-model="form.p_unit_usd"
            type="number"
            step="0.01"
            min="0.01"
            class="mt-1 block w-3/4"
            readonly
          ></TextInput>
          <InputError
            :message="form.errors.p_unit_usd"
            class="mt-2"
          ></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="dollar_price" value="Valor del Dolar:"></InputLabel>
          <TextInput
            id="dollar_price"
            v-model="form.dollar_price"
            type="number"
            step="0.01"
            min="0.01"
            class="mt-1 block w-3/4"
          ></TextInput>
          <InputError
            :message="form.errors.dollar_price"
            class="mt-2"
          ></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel
            for="p_unit_bs"
            value="Precio Unitario en Bolivares:"
          ></InputLabel>
          <TextInput
            id="p_unit_bs"
            v-model="form.p_unit_bs"
            type="number"
            step="0.01"
            min="0.01"
            class="mt-1 block w-3/4"
            placeholder="0"
            readonly
          ></TextInput>
          <InputError
            :message="form.errors.p_unit_bs"
            class="mt-2"
          ></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="format" value="Formato:"></InputLabel>
          <TextInput
            @change="calculateTotal"
            id="format"
            v-model="form.format"
            type="number"
            step="0.01"
            min="0.01"
            placeholder="0"
            class="mt-1 block w-3/4"
            required
          ></TextInput>
          <InputError :message="form.errors.format" class="mt-2"></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="quantity" value="Cantidad:"></InputLabel>
          <TextInput
            @change="calculateTotal"
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
            @change="calculateTotal"
            id="m2"
            v-model="form.m2"
            type="number"
            min="0.01"
            step="0.01"
            placeholder="7.5"
            class="mt-1 block w-3/4"
            required
          ></TextInput>
          <InputError :message="form.errors.m2" class="mt-2"></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="m" value="M:"></InputLabel>
          <TextInput
            @change="calculateTotal"
            id="m"
            v-model="form.m"
            type="number"
            min="0.01"
            step="0.01"
            class="mt-1 block w-3/4"
            placeholder="14"
            required
          ></TextInput>
          <InputError :message="form.errors.m" class="mt-2"></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel for="p_total_usd" value="Precio Total $:"></InputLabel>
          <TextInput
            id="p_total_usd"
            v-model="form.p_total_usd"
            type="number"
            step="0.01"
            min="0.01"
            class="mt-1 block w-3/4"
            placeholder="0"
            readonly
          ></TextInput>
          <InputError
            :message="form.errors.p_total_usd"
            class="mt-2"
          ></InputError>
        </div>

        <div class="p-3 pb-0">
          <InputLabel
            for="p_total_bs"
            value="Precio Total Bolivares:"
          ></InputLabel>
          <TextInput
            id="p_total_bs"
            v-model="form.p_total_bs"
            type="number"
            step="0.01"
            min="0.01"
            class="mt-1 block w-3/4"
            placeholder="0"
            readonly
          ></TextInput>
          <InputError
            :message="form.errors.p_total_bs"
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
