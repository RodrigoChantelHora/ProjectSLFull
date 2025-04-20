<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
    icon: String,
});

const classes = computed(() => {
    return props.active
        ? 'flex items-center w-full ps-4 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-semibold text-indigo-600 bg-indigo-100 dark:bg-slate-800 dark:text-indigo-400 focus:outline-none transition duration-150 ease-in-out'
        : 'flex items-center w-full ps-4 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-indigo-400 dark:hover:bg-slate-800 transition duration-150 ease-in-out';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-start">
            <i v-if="icon" :class="icon" class="mr-3 text-lg"></i>
            <slot />
        </button>

        <a v-else-if="as == 'a'" :class="classes" class="w-full text-start" :href="href">
            <i v-if="icon" :class="icon" class="mr-3 text-lg"></i>
            <slot />
        </a>

        <Link v-else :href="href" :class="classes">
            <i v-if="icon" :class="icon" class="mr-3 text-lg"></i>
            <slot />
        </Link>
    </div>
</template>
