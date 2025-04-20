<script setup>
    const props = defineProps({
        status: {
            type: String,
        }
    });
</script>

<template>
    <div v-if="status" class="w-4 h-4 border-2 border-t-transparent border-white rounded-full animate-spin"></div>
</template>
