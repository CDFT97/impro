<template>
  <div class="container mx-auto px-5 py-2 lg:px-32 lg:pt-12">
    <div class="flex flex-wrap md:-m-2 justify-center w-full">
      <div
        class="flex w-1/4 flex-wrap border-2 border-green-600 rounded-lg ml-2"
        v-for="image in images"
        :key="image.id"
      >
        <div class="w-full p-1 md:p-2">
          <span
            class="text-lg text-red-600 cursor-pointer ml-4"
            @click="deleteImage(image)"
          >
            <i class="fa-regular fa-trash-can"></i>
        </span>
        <span class="text-bold ml-6 text-xl">{{ image.name }}</span>
        
          <img
            style="max-width: 2500px; max-height: 250px; object-fit: scale-down"
            alt="gallery"
            class="block h-full w-full rounded-lg object-cover object-center pb-4 px-2"
            :src="image.url"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
const form = useForm({});
const props = defineProps({
  images: [],
});

const deleteImage = async (image) => {
  const alerta = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alerta.fire({
      title: `Quiere eliminar la imagen: ${image.name}?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: '<i class="fa-solid fa-check"></i> Si, eliminalo',
      cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    });
    if (result.isConfirmed) {
      form.delete(route("orders.delete.image", image.id));
    }
  } catch (error) {
    console.error(error);
  }
};
</script>