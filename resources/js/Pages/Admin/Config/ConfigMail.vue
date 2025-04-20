<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavPage from '@/Components/NavPage.vue';
import QuillEditor from '@/Components/Textarea/QuillEditor.vue';
import { watch } from 'vue';
import NavPageConfig from '@/Components/Config/NavPageConfig.vue';

const props = defineProps({
    mailConfig: {
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
    id: props.mailConfig?.id || '',
    mailer: props.mailConfig?.mailer || '',
    host: props.mailConfig?.host || '',
    port: props.mailConfig?.port || '',
    username: props.mailConfig?.username || '',
    password: props.mailConfig?.password || '',
    encryption: props.mailConfig?.encryption || '',
    from_adress: props.mailConfig?.from_adress || '',
    from_name: props.mailConfig?.from_name || '',
    signature: props.mailConfig?.signature || '',
});

watch(
    () => props.mailConfig,
    (newValue, oldValue) => {
        if (!newValue) return;

        form.id = newValue.id;
        form.mailer = newValue.mailer;
        form.host = newValue.host;
        form.port = newValue.port;
        form.username = newValue.username;
        form.password = newValue.password;
        form.encryption = newValue.encryption;
        form.from_adress = newValue.from_adress;
        form.from_name = newValue.from_name;
        form.signature = newValue.signature
    },
    { deep: true, immediate: true }
);

const sendForm = () => {
    form.post(route('mail.update'), {
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
    <Head title="Configurações de e-mail" />
    <AppLayout :success="success" :error="error">

        <NavPageConfig :routeConfig="2" />

        <form @submit.prevent="sendForm" class="bg-white p-4 border border-gray-300 shadow-lg mt-1 md:mt-2 lg:mt-4">
            <div>
                <div class="grid lg:grid-cols-3 md:gap-4 mb-4">
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Mailer <span class="text-red-600">**</span></span>
                            <input v-model="form.mailer" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: smtp">
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
                            <span class="text-gray-700">Usuário <span class="text-red-600">**</span></span>
                            <input v-model="form.username" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: root">
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
                            <span class="text-gray-700">Encryption</span>
                            <input v-model="form.encryption" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Opcional">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">E-mail de envio</span>
                            <input v-model="form.from_adress" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="exemplo@exemplo.com">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block">
                            <span class="text-gray-700">Nome do remetente</span>
                            <input v-model="form.from_name" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-gray-500 focus:border-gray-500" placeholder="Ex: 'utf8mb4_unicode_ci'">
                        </label>
                    </div>
                    <div class="col-span-3 pt-6">
                        <label class="block">
                            <span class="text-gray-700">Assinatura do Email</span>

                        </label>
                        <QuillEditor v-model="form.signature" />
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="form-group grid grid-cols-1 md:grid-cols-2 md:gap-4">
                    <div class="w-full grid grid-cols-2 gap-4">
                        <button type="submit" class="w-full bg-red-700 text-white px-4 py-2 hover:bg-red-900"><i class="fa-solid fa-check"></i> Salvar</button>
                        <Link :href="route('mail.edit')" type="reset" class="w-full flex justify-center items-center bg-gray-700 text-white px-4 py-2 hover:bg-gray-900"><i class="fa-solid fa-trash-can me-2"></i> Cancelar</Link>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
