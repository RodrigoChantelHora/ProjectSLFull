<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from 'vue';
import NavPage from '@/Components/NavPage.vue';
import CodeEditor from '@/Components/Textarea/CodeEditor.vue';
import QuillEditor from '@/Components/Textarea/QuillEditor.vue';

const props = defineProps({
    report: {
        type: [Object, Array],
        required: true
    },
    countReports: {
        type: String,
    },
    query: {
        type: Array,
    },
    groups: {
        type: [Object, Array],
    },
    success: {
        type: String,
    },
    error: {
        type: String,
    }
});

const form = useForm({
    report_id: props.report?.id || '',
    report_token: props.report?.report_token || '',
    report_description: props.report?.report_description || '',
    status: props.report?.status || '',
    group_id: props.report?.group_id || '',
    message: props.report?.message || '',
    scheduleType: props.report?.scheduleType || '',
    selectedTime: props.report?.selectedTime || '',
    selectedWeekday: props.report?.selectedWeekday || '',
    selectedDay: props.report?.selectedDay || '',
    selectedMonth: props.report?.selectedMonth || '',

    //
    query_id: props.query?.id || '',
    query_token: props.query?.query_token || '',
    query_name: props.query?.query_name || '',
    query: props.query?.query || '',
});

const sendForm = () => {
    form.post(route('report.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            console.log("Salvo com sucesso!");
        },
        onError: (errors) => {
            console.error("Erro ao enviar:", errors);
        },
    });
};

const weekdays = [
    { value: "0", label: "Domingo" },
    { value: "1", label: "Segunda-feira" },
    { value: "2", label: "Terça-feira" },
    { value: "3", label: "Quarta-feira" },
    { value: "4", label: "Quinta-feira" },
    { value: "5", label: "Sexta-feira" },
    { value: "6", label: "Sábado" },
];

const months = [
    { value: "1", label: "Janeiro" },
    { value: "2", label: "Fevereiro" },
    { value: "3", label: "Março" },
    { value: "4", label: "Abril" },
    { value: "5", label: "Maio" },
    { value: "6", label: "Junho" },
    { value: "7", label: "Julho" },
    { value: "8", label: "Agosto" },
    { value: "9", label: "Setembro" },
    { value: "10", label: "Outubro" },
    { value: "11", label: "Novembro" },
    { value: "12", label: "Dezembro" },
];

const days = [...Array(31).keys()].map(i => i + 1); // Gera os dias de 1 a 31

// Computed para gerar a expressão CRON
const cronExpression = computed(() => {
    if (!form.selectedTime) return "* * * * *";

    const [hour, minute] = form.selectedTime.split(":");

    if (form.scheduleType === "daily") {
        return `${minute} ${hour} * * *`; // Executa todo dia no horário definido
    }

    if (form.scheduleType === "weekly") {
        return `${minute} ${hour} * * ${form.selectedWeekday}`; // Executa no dia e horário definido
    }

    if (form.scheduleType === "monthly") {
        return `${minute} ${hour} ${form.selectedDay} * *`; // Executa no dia do mês escolhido
    }

    if (form.scheduleType === "yearly") {
        return `${minute} ${hour} ${form.selectedDay} ${form.selectedMonth} *`; // Executa no mês e dia escolhido
    }

    return "* * * * *"; // Caso algo dê errado
});

const pageSelected = ref(1);

const pageSelect = (selected) => {
    pageSelected.value = selected;
}

const nextPage = () => {
    if (pageSelected.value < 3) {
        pageSelected.value = pageSelected.value + 1;
    }
}

const formTestQuery = ref({
    query: form?.query,
});
const errorTestQuery = ref('');
const loadingTest = ref(false);
const responseData = ref([]);

const testQuery = () => {
    loadingTest.value = true;
    errorTestQuery.value = '';

    if (!formTestQuery.value.query.trim()) {
        console.warn("A query está vazia.");
        loadingTest.value = false;
        return;
    }

    fetch(route('test.query'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ query: form.query })
    })
    .then(async (response) => {
        const data = await response.json();

        if (!response.ok) {
            // Captura o erro vindo do Laravel
            const errorMsg =
                data.query_error ||
                data.connection_error ||
                data.message || // fallback genérico
                'Erro desconhecido.';
            throw new Error(errorMsg);
        }

        responseData.value = data.result;
        loadingTest.value = false;
    })
    .catch((error) => {
        errorTestQuery.value = error.message;
        loadingTest.value = false;
        console.error('Erro:', error.message);
    });
};
</script>

<template>
    <Head :title="`Editar relatório ${report.id}`" />
    <AppLayout :success="props.success" :error="props.error">

        <NavPage
            :headTitle="`Editar relatório ${report.id}`"
            :countData="countReports"
            :routeIndex="'report.index'"
            :routeSelect="'report.index'"
            :routeSearch="'report.search'"
            :routeCreate="'report.create'"
        />

        <!-- Tabs -->
        <div>
            <nav class="h-8 mt-8">
                <ul class="flex items-center gap-1 md:gap-4">
                    <li>
                        <span class="text-xs md:text-base flex items-center"
                            @click="pageSelect(1)"
                            :class="pageSelected === 1 ? 'px-5 py-3 bg-white border border-b-0 border-gray-300 cursor-default' : 'px-5 py-3 bg-gray-300 text-white cursor-pointer hover:bg-gray-200 hover:text-gray-600'">
                            <i class="fa-solid fa-calendar-days"></i> <span class="hidden md:block md:ms-4">Agendamento</span>
                        </span>
                    </li>
                    <li>
                        <span class="text-xs md:text-base flex items-center"
                            @click="pageSelect(2)"
                            :class="pageSelected === 2 ? 'px-5 py-3 bg-white border border-b-0 border-gray-300 cursor-default' : 'px-5 py-3 bg-gray-300 text-white cursor-pointer hover:bg-gray-200 hover:text-gray-600'">
                            <i class="fa-solid fa-database"></i> <span class="hidden md:block md:ms-4">Consulta SQL</span>
                        </span>
                    </li>
                    <li>
                        <span class="text-xs md:text-base flex items-center"
                            @click="pageSelect(3)"
                            :class="pageSelected === 3 ? 'px-5 py-3 bg-white border border-b-0 border-gray-300 cursor-default' : 'px-5 py-3 bg-gray-300 text-white cursor-pointer hover:bg-gray-200 hover:text-gray-600'">
                            <i class="fa-solid fa-envelope-open-text"></i> <span class="hidden md:block md:ms-4">E-mail</span>
                        </span>
                    </li>
                </ul>
            </nav>
        </div>


        <!-- Formulário -->
        <form @submit.prevent="sendForm" id="report" class="bg-white p-4 border border-gray-300 rounded-t-none shadow-lg rounded-md mt-1 md:mt-4">
            <!-- Página 1 -->
            <div v-if="pageSelected === 1">
                <h1 class="py-6 font-extrabold text-lg">Geral</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Título <span class="text-red-600">**</span></span>
                            <input v-model="form.report_description" type="text" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500" placeholder="Defina um título">
                            <p v-if="form.errors.report_description" class="text-red-600 text-sm">
                                {{ form.errors.report_description }}
                            </p>
                        </label>
                    </div>
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Status <span class="text-red-600">**</span></span>
                            <select v-model="form.status" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                            <p v-if="form.errors.status" class="text-red-600 text-sm">
                                {{ form.errors.status }}
                            </p>
                        </label>
                    </div>
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Grupo de contatos <span class="text-red-600">**</span></span>
                            <select v-model="form.group_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                                <option v-for="(group, index) in groups" :key="index" :value="group.id">{{ group.name }}</option>
                            </select>
                            <p v-if="form.errors.group_id" class="text-red-600 text-sm">
                                {{ form.errors.group_id }}
                            </p>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Tipo de Agendamento <span class="text-red-600">**</span></span>
                            <select v-model="form.scheduleType" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                                <option value="daily">Diariamente</option>
                                <option value="weekly">Semanalmente</option>
                                <option value="monthly">Mensalmente</option>
                                <option value="yearly">Anualmente</option>
                            </select>
                            <p v-if="form.errors.scheduleType" class="text-red-600 text-sm">
                                {{ form.errors.scheduleType }}
                            </p>
                        </label>
                    </div>
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Horário <span class="text-red-600">**</span></span>
                            <input v-model="form.selectedTime" type="time"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500" />
                        </label>
                    </div>
                    <div v-if="form.scheduleType === 'weekly'">
                        <label class="block">
                            <span class="text-gray-700">Dia da semana</span>
                            <select v-model="form.selectedWeekday"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                                <option v-for="(day, index) in weekdays" :key="index" :value="day.value">{{ day.label }}</option>
                            </select>
                        </label>
                    </div>
                    <div v-if="form.scheduleType === 'monthly' || form.scheduleType === 'yearly'">
                        <label class="block">
                            <span class="text-gray-700">Dia do mês</span>
                            <select v-model="form.selectedDay"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                                <option v-for="day in 31" :key="day" :value="day">{{ day }}</option>
                            </select>
                        </label>
                    </div>
                    <div v-if="form.scheduleType === 'yearly'">
                        <label class="block">
                            <span class="text-gray-700">Mês do ano</span>
                            <select v-model="form.selectedMonth"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                                <option v-for="(month, index) in months" :key="index" :value="month.value">{{ month.label }}</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SQL -->
            <div v-if="pageSelected === 2">
                <h1 class="py-6 font-extrabold text-lg">Consulta SQL</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block">
                            <span class="text-gray-700">Nome <span class="text-red-600">**</span></span>
                            <input v-model="form.query_name" type="text" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500" placeholder="Defina um nome">
                            <p v-if="form.errors.query_name" class="text-red-600 text-sm">
                                {{ form.errors.query_name }}
                            </p>
                        </label>
                    </div>
                    <div class="flex items-end">
                        <form id="query" @submit.prevent="testQuery" class="w-full">
                            <p class="text-gray-700 mb-1">Testar consulta SQL</p>
                            <button :class="loadingTest ? 'bg-indigo-900' : 'bg-indigo-600 hover:bg-indigo-700'"
                                form="query" type="submit"
                                class="w-full px-4 py-2 text-white rounded-md text-sm transition duration-300">
                                {{ loadingTest ? 'Consultando...' : 'Testar' }}
                            </button>
                        </form>
                    </div>
                    <div class="col-span-full">
                        <label class="block">
                            <span class="text-gray-700">Query SQL <span class="text-red-600">**</span></span>
                            <p v-if="form.errors.query" class="text-red-600 text-sm">
                                {{ form.errors.query }}
                            </p>
                        </label>
                        <CodeEditor v-model="form.query" class="cursor-text" />
                    </div>
                    <div class="col-span-full">
                        <div v-if="errorMsg">{{ errorMsg }}</div>
                        <div v-if="responseData" class="overflow-auto max-h-96 border border-gray-200 rounded-md shadow-md">
                            <table class="min-w-full table-auto text-sm text-gray-700">
                                <thead class="bg-gray-100 border-b border-gray-300">
                                    <tr>
                                        <th v-for="(value, key) in responseData[0]" :key="key" class="px-4 py-1 text-left text-xs uppercase text-gray-600">{{ key }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in responseData" :key="index" class="hover:bg-gray-50">
                                        <td v-for="(value, key) in item" :key="key" class="px-4 py-2 border-b">{{ value }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center text-gray-500 italic mt-2">Nenhum dado encontrado.</div>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div v-if="pageSelected === 3">
                <h1 class="py-6 font-extrabold text-lg">E-mail</h1>
                <div class="space-y-2 text-sm">
                    <p>Assunto: <span class="text-blue-500">{{ form.report_description }}</span></p>
                    <p>Tipo de agendamento: <span class="text-blue-500">{{ form.scheduleType }}</span></p>
                </div>
                <div class="pt-6">
                    <label class="block">
                        <span class="text-gray-700">Corpo do e-mail <span class="text-red-600">**</span></span>
                    </label>
                    <p v-if="form.errors.message" class="text-red-600 text-sm">
                        {{ form.errors.message }}
                    </p>
                    <QuillEditor v-model="form.message" />
                </div>
            </div>

            <!-- Botões -->
            <div v-if="pageSelected < 3" class="mt-8 flex flex-col sm:flex-row gap-4">
                <button @click="nextPage()" type="button" class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-900 w-full sm:w-48">
                    Próximo <i class="fa-solid fa-arrow-right"></i>
                </button>
                <Link :href="route('report.index')" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-900 flex justify-center items-center w-full sm:w-48">
                    <i class="fa-solid fa-trash-can me-2"></i> Cancelar
                </Link>
            </div>

            <div v-if="pageSelected === 3" class="mt-8 flex flex-col sm:flex-row gap-4">
                <button type="submit" form="report" class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-900 w-full sm:w-48">
                    <i class="fa-solid fa-check"></i> Salvar
                </button>
                <Link :href="route('report.index')" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-900 flex justify-center items-center w-full sm:w-48">
                    <i class="fa-solid fa-trash-can me-2"></i> Cancelar
                </Link>
            </div>
        </form>
    </AppLayout>
</template>
