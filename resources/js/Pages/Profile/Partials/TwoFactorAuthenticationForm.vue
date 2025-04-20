<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(() =>
    !enabling.value && page.props.auth.user?.two_factor_enabled
);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios.post(route('two-factor.recovery-codes')).then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <section class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 p-6 mx-auto">
        <header class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Autenticação em Duas Etapas</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Adicione uma camada extra de segurança à sua conta.
            </p>
        </header>

        <div class="text-sm mb-5">
            <p v-if="twoFactorEnabled && !confirming" class="text-green-600 font-medium">
                A autenticação em duas etapas está ativada.
            </p>
            <p v-else-if="twoFactorEnabled && confirming" class="text-yellow-500 font-medium">
                Finalize a ativação da autenticação em duas etapas.
            </p>
            <p v-else class="text-gray-500 dark:text-gray-400">
                A autenticação em duas etapas não está ativada.
            </p>
        </div>

        <div v-if="twoFactorEnabled" class="space-y-6">
            <div v-if="qrCode" class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-800 dark:text-gray-100">
                <p v-if="confirming" class="mb-2">
                    Escaneie o QR Code abaixo no seu aplicativo autenticador ou digite a chave de configuração.
                </p>
                <p v-else>
                    2FA ativado. Você pode adicionar este QR Code em um novo dispositivo se desejar.
                </p>

                <div class="flex justify-center mt-3" v-html="qrCode" />

                <p v-if="setupKey" class="mt-4 font-medium">
                    <span class="text-gray-800 dark:text-white">Chave de configuração:</span>
                    <span class="font-mono">{{ setupKey }}</span>
                </p>

                <div v-if="confirming" class="mt-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Código de Verificação
                    </label>
                    <input
                        id="code"
                        v-model="confirmationForm.code"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-violet-500 focus:outline-none text-gray-800 dark:text-white"
                        placeholder="Digite o código do aplicativo"
                        @keyup.enter="confirmTwoFactorAuthentication"
                    />
                    <InputError :message="confirmationForm.errors.code" class="mt-1 text-xs text-red-500" />
                </div>
            </div>

            <div v-if="recoveryCodes.length > 0 && !confirming" class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md border text-sm text-gray-700 dark:text-gray-200">
                <p class="mb-2 font-medium">
                    Guarde os códigos abaixo com segurança. Eles são sua última opção de acesso.
                </p>
                <div class="grid grid-cols-2 gap-2 font-mono">
                    <div v-for="code in recoveryCodes" :key="code">{{ code }}</div>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex flex-wrap justify-end gap-3 mt-8">
            <ConfirmsPassword @confirmed="enableTwoFactorAuthentication" v-if="!twoFactorEnabled">
                <button
                    type="button"
                    :disabled="enabling"
                    class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Ativar
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication" v-if="confirming">
                <button
                    type="button"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition"
                >
                    Confirmar
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="regenerateRecoveryCodes" v-if="recoveryCodes.length && !confirming">
                <button
                    type="button"
                    class="bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-sm text-gray-800 dark:text-white px-4 py-2 rounded-lg transition"
                >
                    Regenerar Códigos
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="showRecoveryCodes" v-if="!recoveryCodes.length && !confirming">
                <button
                    type="button"
                    class="bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-sm text-gray-800 dark:text-white px-4 py-2 rounded-lg transition"
                >
                    Mostrar Códigos
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="disableTwoFactorAuthentication" v-if="confirming">
                <button
                    type="button"
                    class="bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-sm text-gray-800 dark:text-white px-4 py-2 rounded-lg transition"
                >
                    Cancelar
                </button>
            </ConfirmsPassword>

            <ConfirmsPassword @confirmed="disableTwoFactorAuthentication" v-if="!confirming">
                <button
                    type="button"
                    :disabled="disabling"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Desativar
                </button>
            </ConfirmsPassword>
        </div>
    </section>
</template>
