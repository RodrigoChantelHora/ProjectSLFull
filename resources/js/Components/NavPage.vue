<script setup>
import SpinnerWhite from '@/Components/Loaders/SpinnerWhite.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const create = ref(null);
const total = ref(null);
const searchActive = ref(null);

const createLink = () => {
    create.value = true
};
const totalLink = () => {
    total.value = true
};

const props = defineProps({
    headTitle: String,
    countData: String,
    routeSelect: String,
    routeSearch: String,
    routeCreate: String,
});

const search = useForm({
    search: ''
});

const submitFormSearch = () => {
    searchActive.value = true;
    search.get(route(props.routeSearch), {
        onFinish: () => search.reset()
    })
};
</script>

<template>
    <div class="text-xs sm:text-sm md:text-base">
        <!-- Cabeçalho -->
        <div class="bg-white p-5 pb-3">
            <div class="flex justify-between items-center">
                <h1 class="font-bold text-gray-800">{{ headTitle }}</h1>
            </div>
            <hr class="mt-4 border-gray-300">
        </div>

        <!-- Conteúdo responsivo -->
        <div
            class="w-full bg-white shadow-lg p-4 mb-4 grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-start">

            <!-- Botão de novo cadastro -->
            <div class="order-1">
                <Link @click="createLink()" :href="route(routeCreate)"
                    class="w-full inline-flex justify-center items-center h-10 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                <i v-if="!create" class="fa-solid fa-circle-plus me-2"></i>
                <SpinnerWhite :status="create" class="me-2" />
                {{ create ? 'NOVO CADASTRO...' : 'NOVO CADASTRO' }}
                </Link>
            </div>

            <!-- Total + input -->
            <div class="order-3 sm:order-2 flex sm:col-span-1 gap-2">
                <Link @click="totalLink()" :href="route(routeSelect)"
                    class="flex-1 inline-flex justify-center items-center h-10 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm">
                <i v-if="!total" class="fa-solid fa-list-ul me-2"></i>
                <SpinnerWhite :status="total" class="me-2" />
                {{ total ? 'TOTAL...' : 'TOTAL' }}
                </Link>
                <input type="text" :placeholder="countData" disabled
                    class="flex-1 h-10 text-sm text-center bg-gray-100 border border-gray-300 rounded-md px-2 text-gray-600" />
            </div>

            <!-- Form de pesquisa -->
            <form @submit.prevent="submitFormSearch"
                class="order-2 sm:order-3 md:col-span-2 flex flex-row sm:flex-row gap-2">
                <input v-model="search.search" type="text" placeholder="Buscar..."
                    class="flex-1 h-10 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" />
                <button type="submit"
                    class="h-10 px-4 flex justify-between items-center bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm">
                    <i v-if="!searchActive" class="fa-solid fa-magnifying-glass me-2"></i>
                    <SpinnerWhite v-if="searchActive" :status="searchActive" class="me-2" />
                    <span class="hidden lg:block"> PESQUISAR</span>
                </button>
            </form>
        </div>
    </div>
</template>
