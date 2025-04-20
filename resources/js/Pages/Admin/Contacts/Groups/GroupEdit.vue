<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavPage from '@/Components/NavPage.vue';
import { watch } from 'vue';
import NavPageContact from '@/Components/Contacts/NavPageContact.vue';

const props = defineProps({
    group: {
        type: Object,
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
    },
});

// Usar diretamente props.group para manter a reatividade
const form = useForm({
    id: props.group?.id || '',
    token: props.group?.token || '',
    name: props.group?.name || '',
    status: props.group?.status || '',
});

// Observar mudanças em props.group e atualizar form
watch(
    () => props.group,
    (newValue, oldValue) => {
        if (!newValue) return; // Evita erro se estiver indefinido

        // Atualiza o formulário com os novos dados
        form.id = newValue.id;
        form.token = newValue.token;
        form.name = newValue.name;
        form.status = newValue.status;
    },
    { deep: true, immediate: true } // 'immediate' força o watch a rodar na inicialização
);

const sendForm = () => {
    form.post(route('contactGroup.update'), {
        forceFormData: true,
        onSuccess: () => {
            console.log("Formulário enviado com sucesso!");
        },
        onError: (errors) => {
            console.error("Erro ao enviar:", errors);
        },
    });
};
</script>

<template>
    <Head title="Editar Grupo" />
    <AppLayout :success="success" :error="error">
        <!-- Componente NavPage, emitindo 'selectPage' -->
        <NavPage
            :headTitle="'Editar grupo'"
            :countData="countGroups"
            :routeSelect="'contactGroup.index'"
            :routeSearch="'contactGroup.search'"
            :routeCreate="'contactGroup.create'"
        />

        <NavPageContact :routeContact="2" />

        <form @submit.prevent="sendForm" class="bg-white p-4 border border-gray-300 shadow-lg">
            <div>
                <div class="grid md:grid-cols-2 md:gap-4 mb-4">
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Nome do grupo <span class="text-red-600">**</span></span>
                            <input v-model="form.name" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Nome do grupo">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Status <span class="text-red-600">**</span></span>
                            <select v-model="form.status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </label>
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group grid grid-cols-1 md:grid-cols-2 md:gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <button type="submit" class="w-full bg-red-700 text-white px-4 py-2 hover:bg-red-900"><i class="fa-solid fa-check"></i> Salvar</button>
                            <Link :href="route('contactGroup.index')" type="reset" class="w-full flex justify-center items-center bg-gray-700 text-white px-4 py-2 hover:bg-gray-900"><i class="fa-solid fa-trash-can me-2"></i> Cancelar</Link>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
