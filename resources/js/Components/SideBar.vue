<script setup>
    import { Link } from '@inertiajs/vue3';
    import { ref, computed, onMounted, watch } from 'vue';

    const maxStorage = ref(0);
    const usedStorage = ref(0);
    const percentStorage = ref(0);
    const percentStyle = ref('bg-blue-600');

    onMounted(async () => {
        try {
            const response = await fetch(route('data.totalStorage'));
            if (!response.ok) {
                throw new Error('Erro ao buscar dados: ' + response.statusText);
            }
            const data = await response.json();
            maxStorage.value = data.maxStorage || 0;
            usedStorage.value = data.usedStorage || 0;
            percentStorage.value = maxStorage.value > 0 ? (usedStorage.value * 100) / maxStorage.value : 0;

            if (percentStorage.value >= 50 && percentStorage.value < 75) {
                percentStyle.value = 'bg-yellow-600';
            } else if (percentStorage.value >= 75) {
                percentStyle.value = 'bg-red-600';
            }
        } catch (error) {
            console.error('Erro:', error);
        }
    });

    const isWide = ref(JSON.parse(localStorage.getItem('isWide')) ?? true);
    const rotationAngle = ref(isWide.value ? 180 : 0);
    const sideBarWidth = computed(() => (isWide.value ? 'w-72' : 'w-14'));
    const contentMargin = computed(() => (isWide.value ? 'ml-72' : 'ml-14'));
    const textVisibility = computed(() => (isWide.value ? 'opacity-100' : 'opacity-0 hidden'));
    const rotateButtonStyle = computed(() => ({ transform: `rotate(${rotationAngle.value}deg)` }));

    const toggleAction = () => {
        isWide.value = !isWide.value;
        rotationAngle.value = rotationAngle.value === 180 ? 0 : 180;
    };

    // Salva o estado de `isWide` no localStorage sempre que ele for atualizado
    watch(isWide, (newVal) => {
        localStorage.setItem('isWide', JSON.stringify(newVal));
    });

</script>


<template>
    <div :class="`bg-slate-900 h-full fixed top-0 left-0 ${sideBarWidth} transition-all duration-300`">
        <div class="h-16 flex justify-center items-center relative">
            <div class="flex items-center text-white">
                <Link :href="route('dashboard')">
                    <img :src="isWide ? '/images/kristtaHorizontalBranco.png' : '/images/kristtaBranco.png'" :width="isWide ? '150' : '40'" alt="Logo">
                </Link>
            </div>
            <div :style="rotateButtonStyle" @click="toggleAction"
                class="bg-white rounded-full w-10 h-10 mt-16 absolute -right-4 border border-slate-900 flex justify-center items-center cursor-pointer transition-transform duration-300">
                <i class="fa-solid fa-arrow-right text-slate-900"></i>
            </div>
        </div>
        <div class="mt-6 flex flex-col items-center space-y-4">
            <div :class="`bg-slate-500 rounded-full bg-cover bg-center border-2 border-zinc-50 ${isWide ? 'w-24 h-24' : 'w-10 h-10'}`" :style="'background-image: url(\'/storage/' + $page.props.auth.user.profile_photo_path + '\');'"></div>
            <div v-if="isWide" class="text-center text-white">
                <h1 class="text-xl font-semibold">{{ $page.props.auth.user.name }}</h1>
                <h2 class="text-sm text-slate-500 mb-3">{{ $page.props.auth.user.email }}</h2>
                <Link :href="route('logout')" method="post" as="button" class="w-full border border-slate-400 py-2 rounded-lg hover:bg-slate-700 transition-all duration-300">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </Link>
            </div>
        </div>
        <nav class="mt-10">
            <ul class="space-y-2">
                <li>
                    <Link :href="route('dashboard')"
                        :class="isWide ? 'px-6' : ''"
                        class="flex items-center p-2 text-white hover:bg-gray-600 dark:hover:bg-gray-700 group">
                        <i :class="isWide ? '' : 'mx-auto'" class="fa-solid fa-chart-pie text-xl"></i>
                        <span :class="`ml-3 ${textVisibility} transition-opacity duration-300`">Dashboard</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('report.index')"
                        :class="isWide ? 'px-6' : ''"
                        class="flex items-center p-2 text-white hover:bg-gray-600 dark:hover:bg-gray-700 group">
                        <i :class="isWide ? '' : 'mx-auto'" class="fa-solid fa-list text-xl"></i>
                        <span :class="`ml-3 ${textVisibility} transition-opacity duration-300`">Relatórios</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('email.status')"
                        :class="isWide ? 'px-6' : ''"
                        class="flex items-center p-2 text-white hover:bg-gray-600 dark:hover:bg-gray-700 group">
                        <i :class="isWide ? '' : 'mx-auto'" class="fa-solid fa-envelopes-bulk"></i>
                        <span :class="`ml-3 ${textVisibility} transition-opacity duration-300`">Lotes</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('contact.index')"
                        :class="isWide ? 'px-6' : ''"
                        class="flex items-center p-2 text-white hover:bg-gray-600 dark:hover:bg-gray-700 group">
                        <i :class="isWide ? '' : 'mx-auto'" class="fa-solid fa-users-line text-xl"></i>
                        <span :class="`ml-3 ${textVisibility} transition-opacity duration-300`">Contatos</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('profile.show')"
                        :class="isWide ? 'px-6' : ''"
                        class="flex items-center p-2 text-white hover:bg-gray-600 dark:hover:bg-gray-700 group">
                        <i :class="isWide ? '' : 'mx-auto'" class="fa-solid fa-id-card"></i>
                        <span :class="`ml-3 ${textVisibility} transition-opacity duration-300`">Minha Conta</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('connection.edit')"
                        :class="isWide ? 'px-6' : ''"
                        class="flex items-center p-2 text-white hover:bg-gray-600 dark:hover:bg-gray-700 group">
                        <i :class="isWide ? '' : 'mx-auto'" class="fa-solid fa-gears text-xl"></i>
                        <span :class="`ml-3 ${textVisibility} transition-opacity duration-300`">Configurações</span>
                    </Link>
                </li>
            </ul>
        </nav>
        <div v-if="isWide" class="px-5 mt-6">
            <p class="text-white mb-2 text-center"><i class="fa-solid fa-hard-drive"></i> {{ percentStorage.toFixed(2) }}%</p>
            <div class="relative w-full bg-gray-200 h-4 shadow">
                <div :class="percentStyle" class="absolute h-full transition-all duration-300" :style="{ width: `${percentStorage}%` }"></div>
                <div class="absolute inset-0 flex items-center justify-center text-sm text-slate-900 font-semibold">
                    {{ usedStorage }} / {{ maxStorage }} GB
                </div>
            </div>
        </div>
    </div>
    <div :class="`transition-all duration-300 ${contentMargin}`">
        <slot></slot>
    </div>
</template>
