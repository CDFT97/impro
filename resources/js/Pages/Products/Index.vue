<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Swal from "sweetalert2";
import Paginator from "@/Components/Paginator.vue";
import { useDolarStore } from "@/Stores/dolar";
import { useAlertsStore } from "@/Stores/alerts";

const alertsStore = useAlertsStore();
const dolarStore = useDolarStore();
const modal = ref(false);
const title = ref("");
const operation = ref(1);
const id = ref("");

const props = defineProps({
  products: [],
});

const form = useForm({
  name: "",
  material: "",
  stock_meters: 0,
  // stock_quantity: 0,
  unit_price_in_dollars: 0,
  description: "",
});
const handleAlertByStock = () => {
  const low_stock_products = props.products.data.filter(
    (item) => item.stock_meters <= 10
  );
  if (low_stock_products.length > 0) {
    alertsStore.warning(`Existen productos con poca existencia!`);
  }
};
handleAlertByStock();
const openModal = (op, product) => {
  modal.value = true;
  operation.value = op;
  form.reset();
  if (op == 1) {
    title.value = "Registrar Producto";
    form.dolar_price = dolarStore.dolar_price;
  } else {
    id.value = product.id;
    title.value = "Editar Producto";
    form.name = product.name;
    form.material = product.material;
    form.stock_meters = product.stock_meters;
    // form.stock_quantity = product.stock_quantity;
    form.unit_price_in_dollars = product.unit_price_in_dollars;
    form.description = product.description;
  }
};
const closeModal = () => {
  form.reset();
  modal.value = false;
};
const save = () => {
  if (operation.value == 1) {
    form.post(route("products.store"), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.put(route("products.update", id.value), {
      onSuccess: () => closeModal(),
    });
  }
};
const deleteItem = async (product) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere eliminar el producto  ${product.name}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      form.delete(route("products.destroy", product.id));
    }
  } catch (error) {
    console.error(error);
  }
};
const updateProvider = (e) => {
  form.provider_id = e;
};

const calculateTotal = () => {
  form.amount = (form.amount_usd * form.dolar_price).toFixed(2);
};
</script>

<template>
  <Head title="Inventario" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Lista de Productos
      </h2>
    </template>
    <div class="py-1 border rounded-md">
      <div class="bg-white grid v-screen">
        <div class="mt-3 mb-3 flex ms-10">
          <PrimaryButton @click="openModal(true)">
            <i class="fa-solid fa-plus-circle"></i> Nuevo Producto
          </PrimaryButton>
        </div>
      </div>
      <div
        class="bg-white grid v-screen place-items-center overflow-x-auto py-3"
      >
        <table class="table-auto border border-gray-400" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">Nombre</th>
              <th class="px-2 py-2">Material</th>
              <th class="px-2 py-2">Metros Disp.</th>
              <!-- <th class="px-2 py-2">Unidades</th> -->
              <th class="px-2 py-2">Precio Unitario $</th>
              <th class="px-2 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="product in products.data"
              :key="product.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2">
                {{ product.name }}
              </td>
              <td class="px-2 py-2 text-end">
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
              <!-- <td class="px-2 py-2 text-center" >
                {{ product.stock_quantity }}
              </td> -->
              <td class="px-2 py-2 text-center">
                {{ product.unit_price_in_dollars }}
              </td>
              <td class="px-2 py-2 flex justify-center">
                <WarningButton @click="openModal(0, product)">
                  <i class="fa-solid fa-edit"></i>
                </WarningButton>
                <DangerButton class="ml-2" @click="deleteItem(product)">
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white grid v-screen place-items-center pb-10">
        <Paginator :paginated_data="products"></Paginator>
      </div>
    </div>
    <!-- Modal Start -->
    <Modal :show="modal" @close="closeModal">
      <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
      <div class="p-3 pb-0">
        <InputLabel for="name" value="Nombre:"></InputLabel>
        <TextInput
          id="name"
          v-model="form.name"
          @input="calculateTotal"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Nombre del producto"
        ></TextInput>
        <InputError :message="form.errors.name" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="material" value="Material:"></InputLabel>
        <TextInput
          id="material"
          ref="materialInput"
          v-model="form.material"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Material "
        ></TextInput>
        <InputError :message="form.errors.material" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel
          for="stock_meters"
          value="Metros en existencia:"
        ></InputLabel>
        <TextInput
          id="stock_meters"
          ref="stock_metersInput"
          v-model="form.stock_meters"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-3/4"
          placeholder="Monto en bolivares"
        ></TextInput>
        <InputError
          :message="form.errors.stock_meters"
          class="mt-2"
        ></InputError>
      </div>
      <!-- <div class="p-3 pb-0">
        <InputLabel for="stock_quantity" value="Unidades en existencia:"></InputLabel>
        <TextInput
          id="stock_quantity"
          ref="stock_quantityInput"
          v-model="form.stock_quantity"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-3/4"
          placeholder="Monto en bolivares"
        ></TextInput>
        <InputError :message="form.errors.stock_quantity" class="mt-2"></InputError>
      </div> -->
      <div class="p-3 pb-0">
        <InputLabel
          for="unit_price_in_dollars"
          value="Precio Unitario $:"
        ></InputLabel>
        <TextInput
          id="unit_price_in_dollars"
          ref="unit_price_in_dollarsInput"
          v-model="form.unit_price_in_dollars"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-3/4"
          placeholder="Monto en bolivares"
        ></TextInput>
        <InputError
          :message="form.errors.unit_price_in_dollars"
          class="mt-2"
        ></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="description" value="Descripción:"></InputLabel>

        <textarea
          id="description"
          rows="4"
          class="block p-2.5 w-full border-gray-500 text-sm text-gray-900 bg-gray-50 rounded-lg border focus:ring-blue-500 focus:border-indigo-600"
          placeholder="Descripción de la compra"
          v-model="form.description"
        ></textarea>
        <InputError
          :message="form.errors.description"
          class="mt-2"
        ></InputError>
      </div>
      <div class="p-3 mt-2">
        <PrimaryButton :disabled="form.processing" @click="save">
          <i class="fa-solid fa-save"></i> Guardar
        </PrimaryButton>
        <SecondaryButton
          class="ml-3"
          :disabled="form.processing"
          @click="closeModal"
        >
          Cancelar
        </SecondaryButton>
      </div>
    </Modal>
    <!-- Modal End -->
  </AuthenticatedLayout>
</template>
