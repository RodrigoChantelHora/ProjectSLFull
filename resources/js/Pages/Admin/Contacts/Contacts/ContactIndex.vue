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
    contacts: {
        type: [Object, Array],
        required: true
    },
    countContacts: {
        type: String,
    },
    success: {
        type: String,
    },
    error: {
        type: String,
    }
});

const updatePage = (page) => {
  selectedPage.value = page;
};

const links = [
  //{ href: '/contato/visualizar', icon: 'fa-solid fa-eye', label: 'Visualizar' },
  { href: '/contatos/registro/editar', icon: 'fa-solid fa-pen', label: 'Editar' },
  //{ href: '/contato/inativar', icon: 'fa-solid fa-ban', label: 'Ativar/Inativar' },
  { href: '/contato/deletar', icon: 'fa-solid fa-trash-can', label: 'Deletar' }
];

</script>

<template>
    <Head title="Grupos de Contatos" />
    <AppLayout :success="success" :error="error">
        <!-- Componente NavPage, emitindo 'selectPage' -->
        <NavPage
            :headTitle="'Contatos'"
            :countData="countContacts"
            :routeIndex="'contact.index'"
            :routeSelect="'contact.index'"
            :routeSearch="'contact.search'"
            :routeCreate="'contact.create'"
        />

        <NavPageContact :routeContact="1" />

        <table class="table-auto w-full bg-white border border-gray-300 shadow-lg">
            <thead class="text-gray-700">
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">#</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left">Nome</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">E-mail</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">Contato</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">Grupo</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">Status</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-left hidden md:table-cell">Usuário</th>
                    <th class="px-4 py-2 border-b border-gray-300 text-right"></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="contact in contacts.data"
                    :key="contact.id"
                    class="hover:bg-gray-50 transition duration-200"
                >
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">{{ contact.id }}</td>
                    <td class="px-4 py-2 border-b border-gray-200">{{ contact.name }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">{{ contact.email }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">{{ contact.whatsapp }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell"
                        :class="contact.group && contact.group.status === 1 ? '' : 'text-red-500'"
                    >
                        {{ contact.group ? contact.group.name : 'Sem grupo' }}
                    </td>


                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">
                        <span
                            :class="contact.status == 1
                                ? 'bg-green-100 text-green-600'
                                : 'bg-red-100 text-red-600'"
                            class="px-2 py-1 rounded-lg text-sm font-semibold"
                        >
                            {{ contact.status == 1 ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border-b border-gray-200 hidden md:table-cell">{{ contact.created_user }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 text-right">
                        <DropdownItem :links="links" :dataId="contact.id" :dataToken="contact.token" />
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="float-end">
            <Pagination class="mt-4" :links="contacts.links" />
        </div>
    </AppLayout>
</template>
