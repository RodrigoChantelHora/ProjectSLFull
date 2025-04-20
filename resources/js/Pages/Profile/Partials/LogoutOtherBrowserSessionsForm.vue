<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Pages/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>

<template>
    <section class="bg-white shadow-md border border-gray-200 p-6 mx-auto">
        <header class="mb-6 text-center">
            <h2 class="text-xl font-bold text-gray-800">Sessões do Navegador</h2>
            <p class="text-sm text-gray-500 mt-1">
                Gerencie e encerre sessões ativas da sua conta em outros navegadores ou dispositivos.
            </p>
        </header>

        <!-- Lista de Sessões -->
        <div v-if="sessions.length > 0" class="space-y-5">
            <div v-for="(session, i) in sessions" :key="i"
                class="flex items-center p-3 border border-gray-100 rounded-lg bg-gray-50">
                <!-- Ícone do Dispositivo -->
                <div class="shrink-0">
                    <svg v-if="session.agent.is_desktop" class="w-6 h-6 text-gray-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25" />
                    </svg>
                    <svg v-else class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </div>

                <!-- Informações -->
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-700">
                        {{ session.agent.platform || 'Desconhecido' }} - {{ session.agent.browser || 'Desconhecido' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ session.ip_address }},
                        <span v-if="session.is_current_device" class="text-green-600 font-semibold">Este
                            dispositivo</span>
                        <span v-else>Último acesso: {{ session.last_active }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="flex items-center justify-between mt-6">
            <button @click="confirmLogout"
                class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-all">
                Encerrar Sessões em Outros Dispositivos
            </button>

            <ActionMessage :on="form.recentlySuccessful" class="text-sm text-green-600">
                Sessões encerradas.
            </ActionMessage>
        </div>

        <!-- Modal -->
        <DialogModal :show="confirmingLogout" @close="closeModal">
            <template #title>Encerrar Sessões</template>

            <template #content>
                <p class="text-sm text-gray-700 mb-4">
                    Por favor, insira sua senha para confirmar o encerramento de todas as outras sessões em outros
                    dispositivos.
                </p>
                <TextInput ref="passwordInput" v-model="form.password" type="password"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-violet-500 focus:outline-none"
                    placeholder="Senha" autocomplete="current-password" @keyup.enter="logoutOtherBrowserSessions" />
                <InputError :message="form.errors.password" class="mt-2 text-xs text-red-500" />
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                <PrimaryButton class="ml-3" :disabled="form.processing" :class="{ 'opacity-50': form.processing }"
                    @click="logoutOtherBrowserSessions">
                    Confirmar
                </PrimaryButton>
            </template>
        </DialogModal>
    </section>
</template>
