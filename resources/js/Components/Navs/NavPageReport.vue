<script setup>
import { Link } from '@inertiajs/vue3';


const props = defineProps({
    routeConfig: {
        type: Number
    }
});
</script>

<template>
    <nav class="z-10 h-8 mt-8">
        <ul class="flex items-center gap-1 md:gap-4">
            <li>
                <Link :href="route('connection.edit')" class="text-xs md:text-base"
                    :class="props.routeConfig === 1 ? 'px-5 py-3 bg-white border border-b-0 border-gray-300' : 'px-5 py-3 bg-gray-300 text-white'">
                    <i class="fa-solid fa-calendar-days"></i> Agendamento
                </Link>
            </li>
            <li>
                <span class="text-xs md:text-base"
                    :class="props.routeConfig === 3 ? 'px-5 py-3 bg-white border border-b-0 border-gray-300' : 'px-5 py-3 bg-gray-300 text-white cursor-not-allowed'">
                    <i class="fa-solid fa-database"></i> Consulta SQL
                </span>
            </li>
            <li>
                <span class="text-xs md:text-base"
                    :class="props.routeConfig === 3 ? 'px-5 py-3 bg-white border border-b-0 border-gray-300' : 'px-5 py-3 bg-gray-300 text-white cursor-not-allowed'">
                    <i class="fa-solid fa-envelope-open-text"></i> E-mail
                </span>
            </li>
        </ul>
    </nav>
</template>
