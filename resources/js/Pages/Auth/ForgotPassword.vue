<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Recuperar Senha" />

    <div class="flex h-screen">
        <!-- Lado esquerdo: formulário -->
        <div class="w-full md:w-1/3 flex flex-col justify-center items-center bg-white px-8">
            <AuthenticationCardLogo class="mb-6" />

            <h2 class="text-2xl font-semibold mb-4">Esqueceu sua senha?</h2>

            <p class="mb-6 text-sm text-gray-600 text-center">
                Sem problemas! Informe o seu endereço de e-mail abaixo e enviaremos um link para redefinir sua senha.
            </p>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="w-full max-w-sm">
                <div class="mb-4">
                    <InputLabel for="email" value="Endereço de E-mail" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex justify-end">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="bg-red-600 hover:bg-red-700 text-white">
                        Enviar link de redefinição
                    </PrimaryButton>
                </div>
            </form>
        </div>

        <!-- Lado direito: imagem de fundo -->
        <div class="hidden md:block md:w-2/3 bg-cover bg-center" style="background-image: url('/images/new-bg-section-01.png');"></div>
    </div>
</template>
