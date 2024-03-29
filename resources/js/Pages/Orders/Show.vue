<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import ImageGallery from "@/Components/ImageGallery.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { onMounted, ref } from "vue";
import Swal from "sweetalert2";
import moment from "moment";
import { useDolarStore } from "@/Stores/dolar";

const formats = [
  {
    id: '1.07',
    name: '1.07'
  },
  {
    id: '1.10',
    name: '1.10'
  },
  {
    id: 1.18,
    name: 1.18
  },
  {
    id: 2.20,
    name: 2.20
  }
]

const dolarStore = useDolarStore();
const props = defineProps({
  order: Object,
  products: Array,
});
const showModal = ref(false);
const form = useForm({
  product_id: "empty",
  p_unit_usd: 0,
  p_unit_bs: 0,
  format: 'empty',
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
  image: null,
  image_name: null,
});

onMounted(() => {
  form.description = props.order.description;
  form.status = props.order.status;
  form.hash = props.order.hash;
});
const removeItem = async (product) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere remover este producto de la orden:  ${product.name}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      console.log(product.pivot);
      form.dollar_price = product.pivot.dollar_price;
      form.format = product.pivot.format;
      form.m = product.pivot.m;
      form.m2 = product.pivot.m2;
      form.product_id = product.id;
      form.quantity = product.pivot.quantity;
      form.p_total_bs = product.pivot.total_price_bs;
      form.p_total_usd = product.pivot.total_price_usd;
      form.p_unit_bs = product.pivot.unit_price_bs;
      form.p_unit_usd = product.pivot.unit_price_usd;

      form.post(route("orders.remove.product", props.order.id), {
        onSuccess: () => {
          form.reset();
        },
      });
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
  form.reset();
};
const parseDate = (date) => {
  return moment(date).format("DD-MM-YYYY");
};

const HandleSubmit = () => {
  form.put(route("orders.update", props.order.id), {
    onSuccess: () => {},
  });
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
  let m = parseFloat(form.m);
  let m2 = parseFloat(form.m2);
  let quantity = parseFloat(form.quantity);
  let unit_price = parseFloat(form.p_unit_usd);
  let fm2 = parseFloat(m * m2);
  const total_price = parseFloat(fm2 * quantity * unit_price).toFixed(2);
  form.p_total_usd = total_price;

  calculateTotalbs();
};

const calculateTotalbs = () => {
  form.p_total_bs = (form.p_total_usd * dolarStore.getDolar).toFixed(2);
};

const addProductForm = () => {
  form.post(route("orders.add.product", props.order.id), {
    onSuccess: () => {
      closeModal();
    },
  });
};

const updateFormStatus = (status) => {
  form.status = status;
};

const submitImage = () => {
  form.post(route("orders.add.image", props.order.id), {
    onSuccess: () => {
      form.reset();
    }
  })
};

const updateFormat = (e) => {
  form.format = e;
  console.log(form.format)
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
                  { id: 2, name: 'Cancelada' },
                ]"
                placeholder="Seleccione el estado de la orden"
                :disabled="props.order.status != 0 ? true : false"
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
            <PrimaryButton
              v-if="props.order.status == 0"
              class="ms-1 sm:ms-2 sm:mt-6 mt-2 sm:w-1/6 w-full"
            >
              Guardar
            </PrimaryButton>
          </form>
        </div>
        <div class="mt-3 mb-1 block ms-4 flex-wrap sm:flex">
          <PrimaryButton
            v-if="props.order.status == 0"
            @click="openModal(false)"
            class="ms-4 sm:ms-1 w-52 sm:w-1/6"
          >
            <i class="fa-solid fa-plus-circle"></i> Agregar Producto
          </PrimaryButton>
          <div class="ms-10 mt-2 flex">
            <span class="flex text-xl">Total: $ {{ props.order.amount }}</span>
            <span 
              v-if="props.order.client.type === 1"
              class="flex text-xl ml-4">Este publicista posee un descuento del : $ {{ props.order.client.discount }} %</span>
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
              <td class="px-2 py-2 text-center">{{ product.name }}</td>
              <td class="px-2 py-2 text-center">{{ product.pivot.format }}</td>
              <td class="px-2 py-2 text-center">
                {{ product.pivot.quantity }}
              </td>
              <td class="px-2 py-2 text-center">{{ product.pivot.m2 }}</td>
              <td class="px-2 py-2 text-center">{{ product.pivot.m }}</td>
              <td class="px-2 py-2 text-center">
                {{ (product.pivot.m2 * product.pivot.m).toFixed(2) }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ product.pivot.unit_price_usd }}
              </td>
              <td class="px-2 py-2 text-center">
                {{ product.pivot.total_price_usd }}
              </td>
              <td class="px-2 py-2 flex justify-center">
                <DangerButton
                  v-if="props.order.status === 0 ? true : false"
                  class="ml-2"
                  @click="removeItem(product)"
                >
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="mt-4 text-center" v-if="props.order.status == 1">
          <h3 class="text-xl font-bold">Galeria de la orden</h3>
          <form @submit.prevent="submitImage" class="text-center">
            <div class="mb-3">
              <InputLabel for="status" value="Nombre de imagen" />
              <TextInput
                id="status"
                type="text"
                class="mt-1 block w-full"
                v-model="form.image_name"
                required
              />
            </div>
            <div class="mb-3">
              <label for="formFile" class="mb-2 inline-block text-neutral-700"
                >Imagen</label
              >
              <input
                class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none"
                type="file"
                id="formFile"
                @input="form.image = $event.target.files[0]"
                required
              />
              <InputError :message="form.errors.image" class="mt-2"></InputError>
              <progress
                v-if="form.progress"
                :value="form.progress.percentage"
                max="100"
                class="w-full bg-gray-200 rounded-full h-2.5"
              >
                {{ form.progress.percentage }}%
              </progress>
            </div>
            <PrimaryButton>Agregar</PrimaryButton>
          </form>
        </div>
        <ImageGallery :images="props.order.images"></ImageGallery>
      </div>
    </div>

    <!-- Modal Start -->
    <Modal :show="showModal" @close="closeModal">
      <h2 class="p-3 text-lg font.medium text-hray-900">Agregar Producto</h2>
      <form @submit.prevent="addProductForm">
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
          <SelectInput
            id="format"
            v-model="form.format"
            @update="updateFormat"
            class="mt-1 block w-full"
            :options="formats"
            placeholder="Seleccione un formato"
            required
          ></SelectInput>
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
          <PrimaryButton :disabled="form.processing">
            <i class="fa-solid fa-save"></i> Agregar
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
