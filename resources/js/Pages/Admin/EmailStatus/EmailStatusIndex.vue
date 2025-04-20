<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import EmailStatusJob from '@/Components/Charts/EmailStatus/EmailStatusJob.vue';

defineProps({
    pendingJobs: Array,
    failedJobs: Array,
    batches: Array,
    pendingJobsTotal: Number,
    failedJobsTotal: Number,
    batchesTotal: Number,
    success: String,
    error: String,
});
</script>

<template>
    <Head title="Status dos E-mails" />

    <AppLayout :success="success" :error="error">
        <div class="p-6 space-y-8">

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl p-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">EM LOTES</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">{{ batchesTotal }}</p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl p-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">PENDENTES</p>
                    <p class="text-3xl font-bold text-yellow-500 dark:text-yellow-400 mt-2">{{ pendingJobsTotal }}</p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl p-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-300 font-medium">FALHAS</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-500 mt-2">{{ failedJobsTotal }}</p>
                </div>
            </div>

            <!-- Gráfico e ações -->
            <div class="flex items-center flex-wrap bg-white border border-gray-300 shadow-lg rounded-md p-4">
                <div class="lg:w-2/3 w-4/5">
                    <EmailStatusJob
                        class="lg:w-full mx-auto"
                        :batchesTotal="batchesTotal"
                        :pendingJobsTotal="pendingJobsTotal"
                        :failedJobsTotal="failedJobsTotal"
                        :batches="batches"
                        :pending="pendingJobs"
                        :failed="failedJobs"
                    />
                </div>

                <div class="space-y-4 lg:w-1/3 w-full">
                    <!-- Pendentes -->
                    <div class="p-4 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Pendentes</h3>
                        <p class="text-gray-500 text-sm mb-3">Escolha entre reenviar os lotes pendentes ou excluí-los.</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded transition duration-200"
                                aria-label="Reenviar pendentes"
                            >
                                Reenviar
                            </button>
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded transition duration-200"
                                aria-label="Excluir pendentes"
                            >
                                Excluir
                            </button>
                        </div>
                    </div>

                    <!-- Falhas -->
                    <div class="p-4 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Falhas</h3>
                        <p class="text-gray-500 text-sm mb-3">Escolha entre reenviar os lotes com falha ou excluí-los.</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded transition duration-200"
                                aria-label="Reenviar falhas"
                            >
                                Reenviar
                            </button>
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded transition duration-200"
                                aria-label="Excluir falhas"
                            >
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarefas Pendentes -->
            <div class="bg-white border border-gray-200 shadow rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Tarefas Pendentes</h2>
                <div v-if="!pendingJobs.length" class="text-gray-500">Nenhuma tarefa pendente.</div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-800">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold">ID</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Fila</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Dados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="job in pendingJobs" :key="job.id" class="border-t">
                                <td class="py-2 px-4">{{ job.id }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ job.queue }}</td>
                                <td class="py-2 px-4 break-all hidden lg:table-cell">
                                    {{ job.payload.substring(0, 100) + (job.payload.length > 100 ? '...' : '') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Falhas nos E-mails -->
            <div class="bg-white border border-red-200 shadow rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4 text-red-600">Falhas nos E-mails</h2>
                <div v-if="!failedJobs.length" class="text-gray-500">Nenhuma falha registrada.</div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-800">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold">ID</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">UUID</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Job</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Classe</th>
                                <th class="text-left py-3 px-4 font-semibold">Falhou em</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Falha principal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="fail in failedJobs" :key="fail.id" class="border-t align-top">
                                <td class="py-2 px-4">{{ fail.id }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ fail.uuid }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ fail.jobName || 'Desconhecido' }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ fail.displayName }}</td>
                                <td class="py-2 px-4">{{ fail.failed_at }}</td>
                                <td class="py-2 px-4 break-all hidden lg:table-cell">
                                    {{ fail.exception ? fail.exception.split('\n')[0] : 'Não especificado' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lotes de Envio -->
            <div class="bg-white border border-gray-200 shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Lotes de Envio</h2>
                <div v-if="!batches.length" class="text-gray-500">Nenhum lote encontrado.</div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-800">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold">Nome</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Total de Jobs</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Pendentes</th>
                                <th class="text-left py-3 px-4 font-semibold hidden lg:table-cell">Falhas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="batch in batches" :key="batch.id" class="border-t">
                                <td class="py-2 px-4">{{ batch.name }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ batch.total_jobs }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ batch.pending_jobs }}</td>
                                <td class="py-2 px-4 hidden lg:table-cell">{{ batch.failed_jobs }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
