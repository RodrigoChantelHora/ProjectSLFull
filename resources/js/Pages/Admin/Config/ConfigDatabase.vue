<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavPage from '@/Components/NavPage.vue';
import { watch } from 'vue';
import NavPageConfig from '@/Components/Config/NavPageConfig.vue';

const props = defineProps({
    connection: {
        type: [Object, Array],
        required: true
    },
    success: {
        type: String,
    },
    error: {
        type: String,
    }
});

const form = useForm({
    id: props.connection?.id || '',
    db_connection: props.connection?.db_connection || '',
    host: props.connection?.host || '',
    port: props.connection?.port || '',
    database: props.connection?.database || '',
    username: props.connection?.username || '',
    password: '',
    sid: props.connection?.sid || '',
    charset: props.connection?.charset || '',
    collation: props.connection?.collation || '',
});

watch(
    () => props.connection,
    (newValue, oldValue) => {
        if (!newValue) return;

        form.id = newValue.id;
        form.db_connection = newValue.db_connection;
        form.host = newValue.host;
        form.port = newValue.port;
        form.database = newValue.database;
        form.username = newValue.username;
        form.sid = newValue.sid;
        form.charset = newValue.charset;
        form.collation = newValue.collation;
    },
    { deep: true, immediate: true }
);

const sendForm = () => {
    form.post(route('connection.update'), {
        forceFormData: true,
        onSuccess: () => {
            console.log("Salvo com sucesso!");
        },
        onError: (errors) => {
            console.error("Erro ao enviar:", errors);
        },
    });
};
</script>

<template>
    <Head title="Registrar Database" />
    <AppLayout :success="success" :error="error">

        <NavPageConfig :routeConfig="1" />

        <form @submit.prevent="sendForm" class="bg-white p-4 border border-gray-300 shadow-lg mt-1 md:mt-2 lg:mt-4">
            <div>
                <div class="grid lg:grid-cols-3 md:gap-4 mb-4">

                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Database <span class="text-red-600">**</span></span>
                            <select v-model="form.db_connection" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500">
                                <option value="oci8">Oracle</option>
                                <option value="mysql">Mysql</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Host <span class="text-red-600">**</span></span>
                            <input v-model="form.host" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: 127.0.0.1">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Porta <span class="text-red-600">**</span></span>
                            <input v-model="form.port" type="number" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: 3306">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Database <span class="text-red-600">**</span></span>
                            <input v-model="form.database" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Nome da database">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Usuário <span class="text-red-600">**</span></span>
                            <input v-model="form.username" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Digite o usuário">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Senha <span class="text-red-600">**</span></span>
                            <input v-model="form.password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Digite a Senha">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">SID</span>
                            <input v-model="form.sid" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="SID">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Charset</span>
                            <input v-model="form.charset" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: 'utf8mb4'">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Collation</span>
                            <input v-model="form.collation" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: 'utf8mb4_unicode_ci'">
                        </label>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="form-group grid grid-cols-1 md:grid-cols-2 md:gap-4">
                    <div class="w-full grid grid-cols-2 gap-4">
                        <button type="submit" class="w-full bg-red-700 text-white px-4 py-2 hover:bg-red-900"><i class="fa-solid fa-check"></i> Salvar</button>
                        <Link :href="route('connection.edit')" type="reset" class="w-full flex justify-center items-center bg-gray-700 text-white px-4 py-2 hover:bg-gray-900"><i class="fa-solid fa-trash-can me-2"></i> Cancelar</Link>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
