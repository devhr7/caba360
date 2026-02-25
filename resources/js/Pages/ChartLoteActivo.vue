<script setup>
import { ref, onMounted, watch } from 'vue';

import { Chart, registerables } from 'chart.js';


Chart.register(...registerables);

const props = defineProps({
    PreparacionTerreno: {
        type: Object,
        required: true,
    },
    Caballoneo: {
        type: Object,
        required: true,
    },
    Siembra: {
        type: Object,
        required: true,
    },
    CrecimientoCuidado: {
        type: Object,
        required: true,
    },
    Tamero: {
        type: Object,
        required: true,
    },
});

const chartCanvasLoteActivo = ref(null);
let chartInstance = null;
let delayed;
const renderChart = () => {
    const ctx = chartCanvasLoteActivo.value.getContext('2d');

    // Si ya existe una instancia del gráfico, la destruye
    if (chartInstance) {
        chartInstance.destroy();
    }

    chartInstance = new Chart(ctx, {
        type: 'bar', // Cambia el tipo de gráfico según tus necesidades
        data: {
            labels: props.PreparacionTerreno.labels,
            datasets: [
            {
                label: 'Tamero',
                data: props.Tamero.values,
                backgroundColor: 'rgba(64, 192, 73, 1)',
                borderColor: 'rgba(64, 192, 73, 1)',
                borderWidth: 1,
            },
            {
                label: 'Crecimiento y Cuidado',
                data: props.CrecimientoCuidado.values,
                backgroundColor: 'rgba(255, 159, 64, 1)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1,
            },
            {
                label: 'Siembra',
                data: props.Siembra.values,
                backgroundColor: 'rgba(54, 162, 235, 1)',
                borderColor: 'gba(54, 162, 235, 1)',
                borderWidth: 1,
            },
            {
                label: 'Preparacion del terreno',
                data: props.PreparacionTerreno.values,
                backgroundColor: 'rgba(192, 192, 192, 1)',
                borderColor: 'rgba(192, 192, 192, 1)',
                borderWidth: 1,
            },

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
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Lotes Activos'
                }
            }
        },
    });
};

// Observa los cambios en chartData
watch(() => props.PreparacionTerreno, (newData) => {
    if (newData.labels.length > 0 && newData.values.length > 0) {
        renderChart();
    }
}, { immediate: true });

</script>

<template>

    <canvas ref="chartCanvasLoteActivo"></canvas>
</template>
