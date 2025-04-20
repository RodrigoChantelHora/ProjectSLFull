<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavPage from '@/Components/NavPage.vue';
import DropdownItem from '@/Components/DropdownItem.vue';
import Pagination from '@/Components/Pagination.vue';
import Alert from '@/Components/Alerts/Alert.vue';

const selectedPage = ref('index'); // Define a página padrão como 'index'

defineProps({
    reports: {
        type: [Object, Array],
        required: true
    },
    countReports: {
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
    { href: '/relatorios/registro/editar', icon: 'fa-solid fa-pen', label: 'Editar' },
    { href: '/relatorios/grupo/email', icon: 'fa-solid fa-envelope', label: 'Enviar' },
    //{ href: '/contato/inativar', icon: 'fa-solid fa-ban', label: 'Ativar/Inativar' },
    { href: '/relatorios/deletar', icon: 'fa-solid fa-trash-can', label: 'Deletar' }
];

</script>

<template>

    <Head title="Relatórios" />
    <AppLayout :success="success" :error="error">
        <!-- Navegação -->
        <NavPage :headTitle="'Relatórios'" :countData="countReports" :routeIndex="'report.index'"
            :routeSelect="'report.index'" :routeSearch="'report.search'" :routeCreate="'report.create'" />

        <!-- Tabela responsiva -->
        <div class="">
            <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-md">
                <thead class="bg-gray-100 text-gray-700 text-sm">
                    <tr>
                        <th class="px-4 py-2 border-b text-left hidden md:table-cell">#</th>
                        <th class="px-4 py-2 border-b text-left">Título</th>
                        <th class="px-4 py-2 border-b text-left hidden md:table-cell">Agendamento</th>
                        <th class="px-4 py-2 border-b text-left hidden md:table-cell">Grupo</th>
                        <th class="px-4 py-2 border-b text-left hidden md:table-cell">Status</th>
                        <th class="px-4 py-2 border-b text-left hidden md:table-cell">Usuário</th>
                        <th class="px-4 py-2 border-b text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-gray-800">
                    <tr v-for="report in reports.data" :key="report.id"
                        class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-2 border-b hidden md:table-cell">{{ report.id }}</td>
                        <td class="px-4 py-2 border-b truncate max-w-[250px]">
                            {{ report.report_description.length > 40
                                ? report.report_description.slice(0, 40) + '...'
                                : report.report_description }}
                        </td>
                        <td class="px-4 py-2 border-b hidden md:table-cell">{{ report.scheduleType }}</td>
                        <td class="px-4 py-2 border-b hidden md:table-cell"
                            :class="report.group.status === 1 ? '' : 'text-red-500'">
                            {{ report.group.name }}
                        </td>
                        <td class="px-4 py-2 border-b hidden md:table-cell">
                            <span :class="report.status == 1
                                ? 'bg-green-100 text-green-600'
                                : 'bg-red-100 text-red-600'" class="px-2 py-1 rounded-lg text-xs font-semibold">
                                {{ report.status == 1 ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border-b hidden md:table-cell">{{ report.user_id }}</td>
                        <td class="px-4 py-2 border-b text-right">
                            <DropdownItem :links="links" :dataId="report.id" :dataToken="report.report_token" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="flex justify-end mt-4">
            <Pagination :links="reports.links" />
        </div>
    </AppLayout>
</template>
