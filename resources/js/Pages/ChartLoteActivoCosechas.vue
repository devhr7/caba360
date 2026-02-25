<script setup>
import { ref, onMounted, watch } from 'vue';

import { Chart, registerables } from 'chart.js';


Chart.register(...registerables);

const props = defineProps({
Plantula : { type:Object , required:true},
MaximoMacollamiento : { type:Object , required:true},
Primordio : { type:Object , required:true},
Antesis : { type:Object , required:true},
Floracion : { type:Object , required:true},
Llenado : { type:Object , required:true},
Maduracion : { type:Object , required:true},
Cosecha : { type:Object , required:true},
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
            labels: props.Cosecha.labels,
            datasets: [
            {
                label: 'Cosecha',
                data: props.Cosecha.values,
                backgroundColor: 'rgba(75, 192, 192, 1',
                borderColor: 'rgba(75, 192, 192, 1',
                borderWidth: 1,
            },
            {
                label: 'Maduracion',
                data: props.Maduracion.values,
                backgroundColor: 'rgba(54, 162, 235, 1) ',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            },
            {
                label: 'Llenado',
                data: props.Llenado.values,
                backgroundColor: 'rgba(255, 99, 132, 1)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
            },
            {
                label: 'Floracion',
                data: props.Floracion.values,
                backgroundColor: 'rgba(255, 205, 86, 1)',
                borderColor: 'rgba(255, 205, 86, 1)',
                borderWidth: 1,
            },
            {
                label: 'Antesis',
                data: props.Antesis.values,
                backgroundColor: 'rgba(153, 102, 255, 1)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
            },
            {
                label: 'Primordio',
                data: props.Primordio.values,
                backgroundColor: 'rgba(255, 159, 64, 1)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1,
            },
            {
                label: 'MaximoMacollamiento',
                data: props.MaximoMacollamiento.values,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 0.5)',
                borderWidth: 1,
            },
            {
                label: 'Plantula',
                data: props.Plantula.values,
                backgroundColor: 'rgba(201, 203, 207, 1)',
                borderColor: 'rgba(201, 203, 207, 1)',
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
watch(() => props.Cosecha, (newData) => {
    if (newData.labels.length > 0 && newData.values.length > 0) {
        renderChart();
    }
}, { immediate: true });

</script>

<template>

    <canvas ref="chartCanvasLoteActivo"></canvas>
</template>
