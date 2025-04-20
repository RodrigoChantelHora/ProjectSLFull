<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Fazer login" />

    <div class="flex h-screen">
        <!-- Lado esquerdo: Formulário -->
        <div class="w-full md:w-1/3 flex flex-col justify-center items-center bg-white px-8">
            <AuthenticationCardLogo class="mb-6" />

            <h2 class="text-2xl font-semibold mb-6">Fazer login</h2>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="w-full max-w-sm">
                <div class="mb-4">
                    <InputLabel for="email" value="Nome de usuário" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mb-4">
                    <InputLabel for="password" value="Senha" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center mb-6">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Manter login</span>
                </div>

                <div class="flex justify-between items-center">
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-500 hover:text-gray-800">
                        Não consegue iniciar a sessão?
                    </Link>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="flex justify-center items-center bg-red-600 hover:bg-red-700 text-white h-14 w-14">
                        <i class="fa-solid fa-arrow-right text-lg"></i>
                    </PrimaryButton>
                </div>
            </form>

            <div class="mt-4 text-sm text-gray-500">
                <a href="https://kristta.com.br/" target="_blank" class="underline">Está com problemas?</a>
            </div>
        </div>

        <!-- Lado direito: Imagem -->
        <div class="hidden md:block md:w-2/3 bg-cover bg-center" style="background-image: url('/images/new-bg-section-01.png');"></div>
    </div>
</template>
