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
import { Link } from '@inertiajs/vue3'
const nameInput = ref(null);
const modal = ref(false);
const title = ref("");
const operation = ref(1);
const id = ref("");

const props = defineProps({
  providers: [],
});

const form = useForm({
  name: "",
  last_name: "",
  ci: "",
  rif: "",
  phone_number: "",
  address: "",
  email: "",
  description : "",
  company: "",
});

const openModal = (op, provider) => {
  modal.value = true;
  operation.value = op;
  form.reset();
  if (op == 1) {
    title.value = "Registrar Proveedor";
  } else {
    id.value = provider.id;
    title.value = "Editar Proveedor";
    form.name = provider.name;
    form.last_name = provider.last_name;
    form.ci = provider.ci;
    form.rif = provider.rif;
    form.phone_number = provider.phone_number;
    form.address = provider.address;
    form.email = provider.email;
    form.description = provider.description;
    form.company = provider.company;
  }
};
const closeModal = () => {
  form.reset();
  modal.value = false;
};
const save = () => {
  if (operation.value == 1) {
    form.post(route("providers.store"), {
        onSuccess: () => closeModal(),
    })
  } else {
    form.put(route("providers.update", id.value),{
        onSuccess: () => closeModal(),
    })
  }
};
const deleteProvider = async (provider) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere eliminar al proveedor  ${provider.name}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      form.delete(route("providers.destroy", provider.id));
    }
  } catch (error) {
    console.error(error);
  }
};

</script>

<template>
  <Head title="Proveedores" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Proveedores
      </h2>
    </template>
    <div class="py-1 border rounded-md">
      <div class="bg-white grid v-screen ">
        <div class="mt-3 mb-3 flex ms-10">
          <PrimaryButton @click="openModal(true)">
            <i class="fa-solid fa-plus-circle"></i> Registrar Proveedor
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
              <th class="px-2 py-2">Cedula</th>
              <th class="px-2 py-2">Rif</th>
              <th class="px-2 py-2">Telefono</th>
              <th class="px-2 py-2">email</th>
              <th class="px-2 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="provider in providers.data"
              :key="provider.id"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2">{{ provider.id }}</td>
              <td class="px-2 py-2">
                {{ provider.name }}
              </td>
              <td class="px-2 py-2">
                {{ provider.ci }}
              </td>
              <td class="px-2 py-2">
                {{ provider.rif }}
              </td>
              <td class="px-2 py-2">
                {{ provider.phone_number }}
              </td>
              <td class="px-2 py-2">
                {{ provider.email }}
              </td>

              <td class="px-2 py-2 flex justify-center">
                <Link :href="route('provider.show.purchases', provider.id)" :active="route().current('provider.show.purchases')">
                <PrimaryButton class="mr-2">
                    <i class="fa-brands fa-shopify"></i>
                  </PrimaryButton>
                </Link>
                <WarningButton @click="openModal(0, provider)">
                  <i class="fa-solid fa-edit"></i>
                </WarningButton>
                <DangerButton class="ml-2" @click="deleteProvider(provider)">
                  <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white grid v-screen place-items-center pb-10">
        <Paginator :paginated_data="providers"></Paginator>
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
        <InputLabel for="ci" value="Rif:"></InputLabel>
        <TextInput
          id="rif"
          ref="rifInput"
          v-model="form.rif"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Rif"
        ></TextInput>
        <InputError :message="form.errors.rif" class="mt-2"></InputError>
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
