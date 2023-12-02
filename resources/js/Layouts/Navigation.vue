<template>
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
         class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
         class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-green-700 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 ">
        <div class="flex justify-center items-center mt-8">
            <div class="flex items-center rounded-lg bg-white py-2 px-3">
                <img src="/img/logo.svg" width="200" height="50" alt="" class="logo_normal">
            </div>
        </div>

        <nav class="mt-10" x-data="{ isMultiLevelMenuOpen: false }">
            <nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                <template #icon>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </template>
                Dashboard
            </nav-link>

            
            <nav-link :href="route('clients.index')" :active="route().current('clients.index')">
                <template #icon>
                    <i class="fa-solid fa-users"></i>
                </template>
                Clientes
            </nav-link>

            <nav-link :href="route('providers.index')" :active="route().current('providers.index')">
                <template #icon>
                    <i class="fa-solid fa-truck"></i>
                </template>
                Proveedores
            </nav-link>

            <nav-link :href="route('purchases.index')" :active="route().current('purchases.index')">
                <template #icon>
                    <i class="fa-solid fa-cart-shopping"></i>
                </template>
                Compras
            </nav-link>
            <!-- Ventas dos niveles -->
            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showingTwoLevelMenu = !showingTwoLevelMenu">
            <i class="fa-solid fa-cash-register"></i>
            <span class="mx-3">Ventas</span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="max-h-0 opacity-25"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="max-h-0 opacity-0">
                <div v-show="showingTwoLevelMenu">
                    <ul class="overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu">
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link class="w-full active" :href="route('orders.index')" >En Curso</Link>
                        </li>
                    </ul>
                </div>
            </transition>
            <!-- End dos niveles -->

            <nav-link :href="route('products.index')" >
                <template #icon>
                    <i class="fa-solid fa-boxes-stacked"></i>
                </template>
                Inventario
            </nav-link>

            <!-- <nav-link :href="route('users.index')" :active="route().current('users.index')">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </template>
                Users
            </nav-link> -->

        </nav>
    </div>

</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

export default {
    components: {
        NavLink,
        Link,
    },

    setup() {
        let showingTwoLevelMenu = ref(false)

        return {
            showingTwoLevelMenu
        }
    },
}
</script>

<style scoped>

</style>
