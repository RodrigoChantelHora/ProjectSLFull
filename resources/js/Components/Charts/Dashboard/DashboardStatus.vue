<template>
    <div class="lg:mt-8 max-w-full mx-auto">
        <canvas ref="chartCanvas" class="w-full h-auto" style="min-height: 300px;"></canvas>
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    contacts: Array,
    contactActiveCount: Number,
    contactInactiveCount: Number,
    groups: Array,
    groupActiveCount: Number,
    groupInactiveCount: Number,
    reports: Array,
    reportActiveCount: Number,
    reportInactiveCount: Number,
});

const chartCanvas = ref(null)
let chartInstance = null

const createChart = () => {
    if (chartInstance) {
        chartInstance.destroy()
    }

    const isDark = document.documentElement.classList.contains('dark')
    const isMobile = window.innerWidth < 640

    chartInstance = new Chart(chartCanvas.value, {
        type: 'polarArea',
        data: {
            labels: [
                'Contatos Ativos',
                'Contatos Inativos',
                'Grupos Ativos',
                'Grupos Inativos',
                'Relatórios Ativos',
                'Relatórios Inativos'
            ],
            datasets: [{
                label: 'Totais',
                data: [
                    props.contactActiveCount,
                    props.contactInactiveCount,
                    props.groupActiveCount,
                    props.groupInactiveCount,
                    props.reportActiveCount,
                    props.reportInactiveCount
                ],
                backgroundColor: [
                    '#10B981', // verde
                    '#6B7280', // cinza
                    '#3B82F6', // azul
                    '#9CA3AF', // cinza claro
                    '#F59E0B', // laranja
                    '#EF4444'  // vermelho
                ],
                borderColor: '#ffffff',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: {
                        display: true
                    },
                    ticks: {
                        color: isDark ? '#e5e7eb' : '#374151',
                        beginAtZero: true
                    },
                    pointLabels: {
                        centerPointLabels: true,
                        display: true,
                        color: isDark ? '#e5e7eb' : '#374151',
                        font: {
                            size: isMobile ? 10 : 14
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'top',
                    align: isMobile ? 'start' : 'center',
                    labels: {
                        color: isDark ? '#e5e7eb' : '#374151',
                        font: {
                            size: isMobile ? 10 : 12
                        },
                        boxWidth: 12
                    }
                }
            }
        }
    })
}

onMounted(() => {
    createChart()
})

onBeforeUnmount(() => {
    if (chartInstance) {
        chartInstance.destroy()
    }
})
</script>
