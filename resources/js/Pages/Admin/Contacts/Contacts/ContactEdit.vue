<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavPage from '@/Components/NavPage.vue';
import { watch, ref } from 'vue';
import NavPageContact from '@/Components/Contacts/NavPageContact.vue';
import SpinnerWhite from '@/Components/Loaders/SpinnerWhite.vue';

const saveOn = ref(null);

const props = defineProps({
    contact: {
        type: [Object, Array],
        required: true
    },
    countContacts: {
        type: String,
    },
    groups: {
        type: [Object, Array],
    },
    success: {
        type: String,
    },
    error: {
        type: String,
    }
});

const form = useForm({
    id: props.contact?.id || '',
    token: props.contact?.token || '',
    name: props.contact?.name || '',
    status: props.contact?.status || '',
    email: props.contact?.email || '',
    contact: props.contact?.whatsapp || '',
    group: props.contact?.group_id || '',
    description: props.contact?.description || '',
});

watch(
    () => props.contact,
    (newValue, oldValue) => {
        if (!newValue) return;

        form.id = newValue.id;
        form.token = newValue.token;
        form.name = newValue.name;
        form.status = newValue.status;
        form.description = newValue.description;
    },
    { deep: true, immediate: true }
);

const sendForm = () => {
    saveOn.value = true;
    form.post(route('contact.update'), {
        forceFormData: true,
        onSuccess: () => {
            console.log("Formulário enviado com sucesso!");
            saveOn.value = null;
        },
        onError: (errors) => {
            console.error("Erro ao enviar:", errors);
            saveOn.value = null;
        },
    });
};
</script>

<template>

    <Head title="Editar contato" />
    <AppLayout :success="success" :error="error">

        <NavPage :headTitle="'Contatos'" :countData="countContacts" :routeSelect="'contact.index'"
            :routeSearch="'contact.search'" :routeCreate="'contact.create'" />

        <NavPageContact :routeContact="1" />

        <form @submit.prevent="sendForm" class="bg-white p-4 border border-gray-300 shadow-lg rounded-md">
            <div class="space-y-6">
                <!-- Nome e Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Nome <span class="text-red-600">**</span></span>
                            <input v-model="form.name" type="text" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="Nome" />
                        </label>
                    </div>

                    <div>
                        <label class="block">
                            <span class="text-gray-700">Status <span class="text-red-600">**</span></span>
                            <select v-model="form.status" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </label>
                    </div>
                </div>

                <!-- Email, Contato, Grupo -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block">
                            <span class="text-gray-700">E-mail</span>
                            <input v-model="form.email" type="email" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="exemplo@exemplo.com" />
                        </label>
                    </div>

                    <div>
                        <label class="block">
                            <span class="text-gray-700">Contato</span>
                            <input v-model="form.contact" type="number" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="Whatsapp ou Telegram" />
                        </label>
                    </div>

                    <div>
                        <label class="block">
                            <span class="text-gray-700">Grupo <span class="text-red-600">**</span></span>
                            <select v-model="form.group" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500">
                                <option v-for="(group, index) in groups" :key="index" :value="group.id">
                                    {{ group.name }}
                                </option>
                            </select>
                        </label>
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block">
                        <span class="text-gray-700">Descrição</span>
                        <textarea v-model="form.description" rows="4"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500"
                            placeholder="Descrição"></textarea>
                    </label>
                </div>

                <!-- Ações -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                        class="w-full flex justify-center items-center bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-900 transition duration-200 sm:w-48">
                        <i v-if="!saveOn" class="fa-solid fa-check me-2"></i>
                        <SpinnerWhite v-if="saveOn" :status="saveOn" class="me-2" />
                        {{ saveOn ? 'Salvando...' : 'Salvar' }}
                    </button>

                    <Link :href="route('contact.index')"
                        class="w-full flex justify-center items-center bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-900 transition duration-200 sm:w-48">
                    <i class="fa-solid fa-trash-can me-2"></i> Cancelar
                    </Link>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
