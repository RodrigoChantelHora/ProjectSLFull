<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="bg-white shadow-md border border-gray-200 p-6 w-full mx-auto">
        <header class="mb-6 text-center">
            <h2 class="text-xl font-bold text-gray-800">Alterar Senha</h2>
            <p class="text-sm text-gray-500 mt-1">
                Mantenha sua conta segura com uma nova senha forte.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Senha atual -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Senha Atual
                    </label>
                    <input
                        id="current_password"
                        type="password"
                        v-model="form.current_password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        autocomplete="current-password"
                        placeholder="Digite sua senha atual"
                    />
                    <InputError :message="form.errors.current_password" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Nova senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Nova Senha
                    </label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        autocomplete="new-password"
                        placeholder="Digite sua nova senha"
                        ref="passwordInput"
                    />
                    <InputError :message="form.errors.password" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Confirmar nova senha -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar Nova Senha
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        autocomplete="new-password"
                        placeholder="Confirme sua nova senha"
                    />
                    <InputError :message="form.errors.password_confirmation" class="mt-1 text-xs text-red-500" />
                </div>
            </div>

            <!-- Botão de salvar -->
            <div class="flex justify-end items-center gap-4 mt-4">
                <ActionMessage :on="form.recentlySuccessful" class="text-sm text-green-600">
                    Senha atualizada com sucesso.
                </ActionMessage>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-md transition disabled:opacity-50"
                >
                    Salvar Alterações
                </button>
            </div>
        </form>
    </section>
</template>
