<script setup>
import { ref, watch, onMounted } from 'vue';

const ifSuccess = ref(false);
const ifError = ref(false);
const ifCustomMessage = ref(false);

const props = defineProps({
    success: {
        type: String,
    },
    error: {
        type: String,
    },
    customMessage: {
        type: String,
    }
});

onMounted(() => {
    if (props.success) {
        ifSuccess.value = true;
        setTimeout(() => {
            ifSuccess.value = false;
        }, 10000);
    }

    if (props.error) {
        ifError.value = true;
        setTimeout(() => {
            ifError.value = false;
        }, 10000);
    }

    if (props.customMessage) {
        ifCustomMessage.value = true;
        setTimeout(() => {
            ifCustomMessage.value = false;
        }, 10000);
    }
});

</script>

<template>
    <div v-if="ifSuccess" class="w-full text-green-500 font-extrabold text-center">
        <h2>{{ success }}</h2>
    </div>

    <div v-if="ifError" class="w-full text-red-500 font-extrabold text-center">
        <h2>{{ error }}</h2>
    </div>
</template>
