<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Pages/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <section class="bg-white shadow-md border border-gray-200 p-6 w-full mx-auto">
        <header class="mb-6 text-center">
            <h2 class="text-xl font-bold text-gray-800">Informações do Perfil</h2>
            <p class="text-sm text-gray-500 mt-1">
                Atualize seu nome e e-mail. É rápido e fácil.
            </p>
        </header>

        <form @submit.prevent="updateProfileInformation" class="space-y-6">
            <!-- Foto do perfil -->
            <div class="flex items-center flex-wrap justify-center gap-6">
                <div class="w-full">
                    <!-- Foto atual ou preview -->
                    <div class="relative group w-20 mx-auto">
                        <div
                        class="w-20 h-20 rounded-full border-2 border-indigo-200 overflow-hidden shadow-sm transition"
                        >
                            <template v-if="!photoPreview">
                                <img
                                :src="'/storage/' + user.profile_photo_path ?? user.profile_photo_url"
                                :alt="user.name"
                                class="w-full h-full object-cover"
                                />
                            </template>
                            <template v-else>
                                <div
                                class="w-full h-full bg-cover bg-center"
                                :style="'background-image: url(' + photoPreview + ')'"
                                />
                            </template>
                        </div>

                        <!-- Botão de edição sobre a imagem -->
                        <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition rounded-full cursor-pointer"
                        @click.prevent="selectNewPhoto"
                        >
                        <span class="text-white text-xs font-semibold">Alterar</span>
                        </div>
                    </div>
                </div>

                <!-- Input escondido -->
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                />

                <!-- Ações -->
                <div class="w-full flex flex-col gap-1">
                    <button
                    type="button"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
                    @click.prevent="selectNewPhoto"
                    >
                    Selecionar Nova Foto
                    </button>
                    <button
                    v-if="user.profile_photo_path"
                    type="button"
                    class="text-sm text-red-600 hover:text-red-700 font-medium"
                    @click.prevent="deletePhoto"
                    >
                    Remover Foto
                    </button>
                </div>
            </div>

            <InputError :message="form.errors.photo" class="text-xs text-red-500" />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        required
                        autocomplete="name"
                    />
                    <InputError :message="form.errors.name" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- E-mail -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        required
                        autocomplete="username"
                    />
                    <InputError :message="form.errors.email" class="mt-1 text-xs text-red-500" />

                    <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null" class="mt-2 text-sm text-gray-600">
                        Seu e-mail ainda não foi verificado.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="ml-1 text-indigo-600 hover:underline"
                            @click.prevent="sendEmailVerification"
                        >
                            Reenviar verificação
                        </Link>

                        <div v-show="verificationLinkSent" class="mt-1 text-green-600">
                            Link de verificação enviado.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex justify-end items-center gap-4">
                <ActionMessage :on="form.recentlySuccessful" class="text-sm text-green-600">
                    Alterações salvas com sucesso.
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
