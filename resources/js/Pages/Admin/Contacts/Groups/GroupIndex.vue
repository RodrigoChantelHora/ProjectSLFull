<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavPage from '@/Components/NavPage.vue';
import DropdownItem from '@/Components/DropdownItem.vue';
import Pagination from '@/Components/Pagination.vue';
import NavPageContact from '@/Components/Contacts/NavPageContact.vue';

const selectedPage = ref('index'); // Define a página padrão como 'index'

defineProps({
    groups: {
        type: [Object, Array],
        required: true
    },
    countGroups: {
        type: String,
    },
    success: {
        type: String,
    },
    error: {
        type: String,
    }
});

// Função para atualizar a página selecionada
const updatePage = (page) => {
  selectedPage.value = page;
};

const links = [
  //{ href: '/contato/visualizar', icon: 'fa-solid fa-eye', label: 'Visualizar' },
  { href: '/contatos/grupos/registro/editar', icon: 'fa-solid fa-pen', label: 'Editar' },
  //{ href: '/contato/inativar', icon: 'fa-solid fa-ban', label: 'Ativar/Inativar' },
  { href: '/contato/grupos/deletar', icon: 'fa-solid fa-trash-can', label: 'Deletar' }
];

</script>

<template>
    <Head title="Grupos de Contatos" />
    <AppLayout :success="success" :error="error">
        <!-- Componente NavPage, emitindo 'selectPage' -->
        <NavPage
            :headTitle="'Grupos de contatos'"
            :countData="countGroups"
            :routeSelect="'contactGroup.index'"
            :routeSearch="'contactGroup.search'"
            :routeCreate="'contactGroup.create'"
        />

        <NavPageContact :routeContact="2" />

        <table class="table-auto w-full bg-white border border-gray-300 shadow-lg">
            <thead class="text-gray-700">
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">#</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left">Nome</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">Status</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">Usuário</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-right"></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="group in groups.data"
                    :key="group.id"
                    class="hover:bg-gray-50 transition duration-200"
                >
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">{{ group.id }}</td>
                    <td class="px-4 py-2 border-b border-gray-200">
                        {{ group.name }}
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">
                        <span
                            :class="group.status == 1
                                ? 'bg-green-100 text-green-600'
                                : 'bg-red-100 text-red-600'"
                            class="px-2 py-1 rounded-lg text-sm font-semibold"
                        >
                            {{ group.status == 1 ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">{{ group.created_user }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 text-right">
                        <DropdownItem :links="links" :dataId="group.id" :dataToken="group.token" />
                    </td>
                </tr>
            </tbody>
        </table>


        <div class="float-end">
            <Pagination class="mt-4" :links="groups.links" />
        </div>
    </AppLayout>
</template>
