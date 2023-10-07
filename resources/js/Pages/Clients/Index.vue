<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Modal from "@/Components/Modal.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Swal from "sweetalert2";
import Paginator from "@/Components/Paginator.vue";

const nameInput = ref(null);
const modal = ref(false);
const title = ref("");
const operation = ref(1);
const id = ref("");

const props = defineProps({
  clients: [],
});

const form = useForm({
  name: "",
  last_name: "",
  ci: "",
  address: "",
  phone_number: "",
  email: "",
  company: "",
  type: 3,
  discount: 0,
  description : ""
});

const openModal = (op, client) => {
  modal.value = true;
  operation.value = op;
  form.reset();
  if (op == 1) {
    
    title.value = "Registrar Cliente";
  } else {
    id.value = client.id;
    title.value = "Editar Cliente";
    form.name = client.name;
    form.last_name = client.last_name;
    form.ci = client.ci;
    form.address = client.address;
    form.phone_number = client.phone_number;
    form.email = client.email;
    form.company = client.company;
    form.type = client.type
    form.description = client.description
    if(client.type == 1) {
      form.discount = client.discount
    }
  }
};
const closeModal = () => {
  form.reset();
  modal.value = false;
};
const save = () => {
  if (operation.value == 1) {
    form.post(route("clients.store"), {
        onSuccess: () => closeModal(),
    })
  } else {
    form.put(route("clients.update", id.value),{
        onSuccess: () => closeModal(),
    })
  }
};
const deleteClient = async (client) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere eliminar al cliente  ${client.name} ${client.last_name}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      form.delete(route("clients.destroy", client.id));
    }
  } catch (error) {
    console.error(error);
  }
};

const updateFormType = (e) => {
  form.type = e
}
</script>

<template>
  <Head title="Clientes" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Clientes
      </h2>
    </template>
    <div class="py-12 border rounded-md">
      <div class="bg-white grid v-screen ">
        <div class="mt-3 mb-3 flex ms-10">
          <PrimaryButton @click="openModal(true)">
            <i class="fa-solid fa-plus-circle"></i> Registrar Cliente
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
              <th class="px-2 py-2">Nombre</th>
              <th class="px-2 py-2">Apellido</th>
              <th class="px-2 py-2">Correo</th>
              <th class="px-2 py-2">Telefono</th>
              <th class="px-2 py-2">Empresa</th>
              <th class="px-2 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="client in clients.data"
              :key="client.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2">{{ client.id }}</td>
              <td class="px-2 py-2">
                {{ client.name }}
              </td>
              <td class="px-2 py-2">
                {{ client.last_name }}
              </td>
              <td class="px-2 py-2">
                {{ client.email }}
              </td>
              <td class="px-2 py-2">
                {{ client.phone_number }}
              </td>
              <td class="px-2 py-2">
                {{ client.company }}
              </td>

              <td class="px-2 py-2 flex justify-center">
                <WarningButton @click="openModal(0, client)">
                  <i class="fa-solid fa-edit"></i>
                </WarningButton>
                <DangerButton class="ml-2" @click="deleteClient(client)">
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white grid v-screen place-items-center pb-10">
        <Paginator :paginated_data="clients"></Paginator>
      </div>
    </div>
    <!-- Modal Start -->
    <Modal :show="modal" @close="closeModal">
      <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
      <div class="p-3 mt-1 pb-0">
        <InputLabel for="name" value="Nombre:"></InputLabel>
        <TextInput
          id="name"
          ref="nameInput"
          v-model="form.name"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Nombre"
        ></TextInput>
        <InputError :message="form.errors.name" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="last_name" value="Apellido:"></InputLabel>
        <TextInput
          id="last_name"
          ref="last_nameInput"
          v-model="form.last_name"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Apellido"
        ></TextInput>
        <InputError :message="form.errors.last_name" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="ci" value="Cedula:"></InputLabel>
        <TextInput
          id="ci"
          ref="ciInput"
          v-model="form.ci"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Cedula"
        ></TextInput>
        <InputError :message="form.errors.ci" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="address" value="Dirección:"></InputLabel>
        <TextInput
          id="address"
          ref="addressInput"
          v-model="form.address"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Dirección"
        ></TextInput>
        <InputError :message="form.errors.address" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="email" value="Correo:"></InputLabel>
        <TextInput
          id="email"
          v-model="form.email"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Correo"
        ></TextInput>
        <InputError :message="form.errors.email" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="phone_number" value="Telefono:"></InputLabel>
        <TextInput
          id="phone_number"
          v-model="form.phone_number"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Telefono"
        ></TextInput>
        <InputError :message="form.errors.phone_number" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="company" value="Empresa:"></InputLabel>
        <TextInput
          id="company"
          v-model="form.company"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Empresa"
        ></TextInput>
        <InputError :message="form.errors.company" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="description" value="Descripción:"></InputLabel>
        <TextInput
          id="description"
          v-model="form.description"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Descripcion o comentario"
        ></TextInput>
        <InputError :message="form.errors.description" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0">
        <InputLabel for="type" value="Tipo:"></InputLabel>
        <SelectInput
          id="type"
          v-model="form.type"
          @update="updateFormType"
          class="mt-1 block w-full"
          :options="[{id: 0, name: 'Cliente'}, {id: 1, name: 'Publicista'}]"
          placeholder="Seleccione Tipo de cliente"
        ></SelectInput>
        <InputError :message="form.errors.type" class="mt-2"></InputError>
      </div>
      <div class="p-3 pb-0" v-if="form.type == 1">
        <InputLabel for="discount" value="Descuento(%):"></InputLabel>
        <TextInput
          id="discount"
          v-model="form.discount"
          type="number"
          min="0"
          class="mt-1 block w-3/4"
          placeholder="% de descuento, ejemplo: 5"
        ></TextInput>
        <InputError :message="form.errors.discount" class="mt-2"></InputError>
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
