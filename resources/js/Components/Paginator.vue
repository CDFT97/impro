<script setup>
import { useForm } from "@inertiajs/vue3";
const formPage = useForm({});

const props = defineProps({
  paginated_data: [],
});
const setClass = (pagination_data, current) => {
  if (pagination_data.current_page == current) {
    return "flex items-center justify-center px-3 h-8 text-white border border-gray-300 bg-gray-500 cursor-not-allowed";
  } else {
    return "flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700";
  }
};

const handlePagination = (url, pagination_data, current) => {
    
    if(pagination_data.current_page == current){
        return console.log("sin accion");
    }
    formPage.get(url);
}

const handleNextAndPrev = (url) => {
    if(url) formPage.get(url);
}
</script>

<template>
  <nav aria-label="Page navigation example">
    <ul class="inline-flex -space-x-px text-sm">
      <li>
        <a
          href="#"
          class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700"
          :class="
            paginated_data.prev_page_url == null ? 'cursor-not-allowed' : ''
          "
          @click="
            handleNextAndPrev(
              paginated_data.prev_page_url,
              paginated_data,
              paginated_data.current_page
            )
          "
          >Previous</a
        >
      </li>
      <template v-for="link in paginated_data.links" :key="link">
        <li v-if="parseInt(link.label)">
          <a
            href="#"
            aria-current="page"
            :class="setClass(paginated_data, link.label)"
            @click="handlePagination(link.url, paginated_data, link.label)"
            >{{ link.label }}</a
          >
        </li>
      </template>
      <li>
        <a
          href="#"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700"
          :class="
            paginated_data.next_page_url == null ? 'cursor-not-allowed' : ''
          "
          @click="handleNextAndPrev(paginated_data.next_page_url)"
          >Next</a
        >
      </li>
    </ul>
  </nav>
</template>