<script setup>
import { ref, onMounted, watch } from 'vue';

import { Chart, registerables } from 'chart.js';


Chart.register(...registerables);

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
    chartDataMeta: {
        type: Object,
        required: true,
    }
});

const chartCanvas = ref(null);
let chartInstance = null;
let delayed;
const renderChart = () => {
    const ctx = chartCanvas.value.getContext('2d');

    // Si ya existe una instancia del gráfico, la destruye
    if (chartInstance) {
        chartInstance.destroy();
    }

    chartInstance = new Chart(ctx, {
        type: 'bar', // Cambia el tipo de gráfico según tus necesidades
        data: {
            labels: props.chartDataMeta.labels,
            datasets: [{
                label: 'Siembra (Hect)',
                data: props.chartData.values,
                backgroundColor: 'rgba(41, 128, 185, 1)',
                borderColor: 'rgba(41, 128, 185, 1)',
                borderWidth: 1,
            },
            {
                label: 'Meta',
                data: props.chartDataMeta.values,
                backgroundColor: 'rgba(66, 176, 42, 1)',
                borderColor: 'rgba(66, 176, 42, 1)',
                type: 'line',
                order: 0
            }
            ],
        },
        options: {
            responsive: true,
            animation: {
      onComplete: () => {
        delayed = true;
      },
      delay: (context) => {
        let delay = 0;
        if (context.type === 'data' && context.mode === 'default' && !delayed) {
          delay = context.dataIndex * 300 + context.datasetIndex * 100;
        }
        return delay;
      },
    },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Metas De Siembras Del mes Actual'
                }
            }
        },
    });
};

// Observa los cambios en chartData
watch(() => props.chartData, (newData) => {
    if (newData.labels.length > 0 && newData.values.length > 0) {
        renderChart();
    }
}, { immediate: true });

</script>

<template>

    <canvas ref="chartCanvas"></canvas>
</template>
