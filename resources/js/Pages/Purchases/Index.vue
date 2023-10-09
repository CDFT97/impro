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
import SelectInput from "@/Components/SelectInput.vue";
import { useDolarStore } from "@/Stores/dolar";
import moment from "moment"
const dolarStore = useDolarStore();
const modal = ref(false);
const title = ref("");
const operation = ref(1);
const id = ref("");

const props = defineProps({
  purchases: [],
  providers: [],
});

const form = useForm({
  amount_usd: 0,
  amount: 0,
  date: "",
  description: "",
  dolar_price: 0,
  provider_id: "empty",
});
const openModal = (op, purchase) => {
  modal.value = true;
  operation.value = op;
  form.reset();
  if (op == 1) {
    title.value = "Registrar Compra";
    form.dolar_price = dolarStore.dolar_price;
  } else {
    id.value = purchase.id;
    title.value = "Editar Compra";
    form.amount_usd = purchase.amount_usd;
    form.amount = purchase.amount;
    form.date = purchase.date;
    form.description = purchase.description;
    form.dolar_price = purchase.dolar_price;
    form.provider_id = purchase.provider_id;
  }
};
const closeModal = () => {
  form.reset();
  modal.value = false;
};
const save = () => {
  if (operation.value == 1) {
    form.post(route("purchases.store"), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.put(route("purchases.update", id.value), {
      onSuccess: () => closeModal(),
    });
  }
};
const deleteItem = async (purchase) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere eliminar la compra  ${purchase.id}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      form.delete(route("purchases.destroy", purchase.id));
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
}

const parseDate = (date) => {
    return moment(date).format("DD-MM-YYYY HH:mm:ss"); 
}
</script>

<template>
  <Head title="Compras" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Compras a Proveedores
      </h2>
    </template>
    <div class="py-12 border rounded-md">
      <div class="bg-white grid v-screen">
        <div class="mt-3 mb-3 flex ms-10">
          <PrimaryButton @click="openModal(true)">
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
              <th class="px-2 py-2">Proveedor</th>
              <th class="px-2 py-2">Monto USD</th>
              <th class="px-2 py-2">Monto Bs</th>
              <th class="px-2 py-2">Fecha</th>
              <th class="px-2 py-2">Utl Actualización</th>
              <th class="px-2 py-2">Acciones</th>
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
                {{ purchase.provider.name }} - {{ purchase.provider.company }}
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
              <td class="px-2 py-2 flex justify-center">
                <WarningButton @click="openModal(0, purchase)">
                  <i class="fa-solid fa-edit"></i>
                </WarningButton>
                <DangerButton class="ml-2" @click="deleteItem(purchase)">
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white grid v-screen place-items-center pb-10">
        <Paginator :paginated_data="purchases"></Paginator>
      </div>
    </div>
    <!-- Modal Start -->
    <Modal :show="modal" @close="closeModal">
      <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
      <div class="p-3 pb-0">
        <InputLabel for="provider" value="Proveedor:"></InputLabel>
        <SelectInput
          id="provider"
          v-model="form.provider_id"
          @update="updateProvider"
          class="mt-1 block w-full"
          :options="providers"
          placeholder="Seleccione un proveedor"
        ></SelectInput>
        <InputError
          :message="form.errors.provider_id"
          class="mt-2"
        ></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="amount_usd" value="Monto USD:"></InputLabel>
        <TextInput
          id="amount_usd"
          ref="amount_usdInput"
          v-model="form.amount_usd"
          @input="calculateTotal"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-3/4"
          placeholder="Monto en divisas"
        ></TextInput>
        <InputError :message="form.errors.amount_usd" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="dolar_price" value="Precio Dolar:"></InputLabel>
        <TextInput
          id="dolar_price"
          ref="dolar_priceInput"
          v-model="form.dolar_price"
          @input="calculateTotal"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-3/4"
          placeholder="Precio del dolar"
        ></TextInput>
        <InputError
          :message="form.errors.dolar_price"
          class="mt-2"
        ></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="amount" value="Monto Bs:"></InputLabel>
        <TextInput
          id="amount"
          ref="amountInput"
          v-model="form.amount"
          type="number"
          min="0"
          step="0.01"
          class="mt-1 block w-3/4"
          placeholder="Monto en bolivares"
        ></TextInput>
        <InputError :message="form.errors.amount" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="ci" value="Fecha:"></InputLabel>
        <TextInput
          id="date"
          ref="dateInput"
          v-model="form.date"
          type="date"
          class="mt-1 block w-3/4"
          placeholder="Fecha"
        ></TextInput>
        <InputError :message="form.errors.date" class="mt-2"></InputError>
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
