<script setup>
import { Link } from '@inertiajs/vue3';


const props = defineProps({
    routeContact: {
        type: Number
    }
});
</script>

<template>
    <nav class="z-10 h-8 mt-8">
        <ul class="flex items-center gap-1 md:gap-4">
            <li>
                <Link :href="route('contact.index')" class="text-xs md:text-base"
                    :class="props.routeContact === 2 ? 'px-5 py-3 bg-gray-300 text-white' : 'px-5 py-3 bg-white border border-b-0 border-gray-300'">
                    <i class="fa-solid fa-user"></i> Contatos
                </Link>
            </li>
            <li>
                <Link :href="route('contactGroup.index')" class="text-xs md:text-base"
                    :class="props.routeContact === 1 ? 'px-5 py-3 bg-gray-300 text-white' : 'px-5 py-3 bg-white border border-b-0 border-gray-300'">
                    <i class="fa-solid fa-user-group"></i> Grupos
                </Link>
            </li>
        </ul>
    </nav>
</template>
