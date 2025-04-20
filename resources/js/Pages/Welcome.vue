<template>
    <Head title="Bem-vindo" />

    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">
        <header class="flex items-center justify-between px-6 py-4 shadow-md bg-white dark:bg-gray-900">
            <h1 class="text-2xl font-bold text-blue-600 dark:text-white">RelatoSys</h1>
            <nav class="flex gap-4">
                <Link v-if="canLogin" href="/login" class="text-sm text-blue-600 hover:underline dark:text-blue-300">Login</Link>
                <Link v-if="canRegister" href="/register" class="text-sm text-blue-600 hover:underline dark:text-blue-300">Registrar</Link>
            </nav>
        </header>

        <main class="px-6 py-12 flex flex-col lg:flex-row items-center justify-between max-w-7xl mx-auto">
            <section class="lg:w-1/2 mb-10 lg:mb-0 text-center lg:text-left">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-800 dark:text-white mb-6 leading-tight">
                    Sistema de Criação de Relatórios
                </h2>
                <p class="text-gray-600 dark:text-gray-300 text-lg mb-6">
                    Organize, crie e compartilhe relatórios personalizados com facilidade. Ideal para empresas, equipes e profissionais.
                </p>
                <div class="flex justify-center lg:justify-start gap-4">
                    <Link href="/dashboard" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                        Acessar o sistema
                    </Link>
                    <a href="#saiba-mais" class="text-blue-600 dark:text-blue-300 font-medium hover:underline">
                        Saiba mais →
                    </a>
                </div>
            </section>

            <section class="lg:w-1/2 relative" id="screenshot-container">
                <img
                    src="/images/dashboard-preview.png"
                    alt="Dashboard do sistema"
                    class="rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-md mx-auto"
                    @error="handleImageError"
                />
            </section>
        </main>

        <footer class="text-center py-6 text-sm text-gray-500 dark:text-gray-400">
            Laravel {{ laravelVersion }} • PHP {{ phpVersion }}
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
}
</script>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
