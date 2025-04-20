<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useForm } from "@inertiajs/vue3";
import Alert from "./Alerts/Alert.vue";

// Gerenciar o estado do dropdown
const isDropdownOpen = ref(false);
const notifications = ref([]); // Armazena as notificações
const countNotify = ref([]);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
};

const handleClickOutside = (event) => {
    const dropdown = document.querySelector(".origin-top-right");
    if (dropdown && !dropdown.contains(event.target)) {
        closeDropdown();
    }
};

// Adicionar o evento de clique global ao montar o componente
onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

// Buscar notificações ao montar o componente
onMounted(async () => {
    try {
        const response = await fetch(route("notification.index"));
        if (!response.ok) {
            throw new Error("Erro ao buscar dados: " + response.statusText);
        }
        const data = await response.json();
        notifications.value = data.notifications; // Atribuir os dados recebidos corretamente
        countNotify.value = data.countNotify;

    } catch (error) {
        console.error("Erro:", error);
    }
});

// Remover o evento global ao desmontar o componente
onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

const formNotify = useForm({
    notifyStatus: "",
});

const submitNotifyStatus = () => {
    formNotify.post(route('notification.update'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            console.log("Formulário enviado com sucesso!");
        },
        onError: (errors) => {
            console.error("Erro ao enviar:", errors);
        },
    })
}
</script>


<template>
    <!-- Settings Dropdown -->
    <div class="w-full">
        <div class="flex">
            <button class="text-slate-900 text-xl me-4">
                <i class="fa-solid fa-envelope-open-text"></i>
            </button>

            <!-- Dropdown -->
            <div class="relative inline-block text-left">
                <form id="formNotify" @submit.prevent="submitNotifyStatus">
                    <input v-model="formNotify.notifyStatus" type="hidden" value="1">
                </form>
                <button class="relative text-slate-900 w-9 text-xl" @click.stop="toggleDropdown">
                    <i class="fa-solid fa-bell"></i>
                    <!-- Exibir o contador de notificações se countNotify for maior que 0 -->
                    <div v-if="countNotify > 0"
                        class="absolute flex justify-center items-center rounded-full top-0 right-0 w-4 h-4 bg-red-600">
                        <span class="text-xs font-bold text-white">{{ countNotify > 99 ? "+" : countNotify }}</span>
                    </div>
                </button>


                <!-- Dropdown Menu -->
                <div v-if="isDropdownOpen"
                    class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <div class="py-1 overflow-y-auto" role="menu" aria-orientation="vertical"
                        style="min-height: 100px; max-height: 350px;">
                        <!-- Listar notificações -->
                        <button form="formNotify" class="w-full flex justify-center items-center">
                            <i class="fa-solid fa-trash me-3"></i> <span>Limpar</span>
                        </button>
                        <hr>
                        <div v-for="notification in notifications" :key="notification.id"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <p><strong>Notification:</strong> {{ notification.notification }}</p>
                            <p><strong>Origin:</strong> {{ notification.origin }}</p>
                            <hr class="my-2 border-gray-300" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
