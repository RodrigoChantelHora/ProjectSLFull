<template>
    <div class="mt-8">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    batchesTotal: Number,
    pendingJobsTotal: Number,
    failedJobsTotal: Number,
})

const chartCanvas = ref(null)
let chartInstance = null

const createChart = () => {
    if (chartInstance) {
        chartInstance.destroy()
    }

    chartInstance = new Chart(chartCanvas.value, {
        type: 'bar',
        data: {
            labels: ['Em lote', 'Pendentes', 'Falhas'],
            datasets: [{
                label: 'Total de Jobs',
                data: [props.batchesTotal, props.pendingJobsTotal, props.failedJobsTotal],
                backgroundColor: ['#4f46e5', '#FBBF24', '#EF4444'],
                borderRadius: 8,
                borderWidth: 1,
            }]
        },
        options: {
            indexAxis: 'y', // ← Transforma em horizontal
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151',
                    },
                },
                y: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151',
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
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
