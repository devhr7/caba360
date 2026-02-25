<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Bienvenido, {{ $page.props.auth.user.name }}
                        <div></div>

                        <div class="grid grid-cols-2 gap-4">
                            <ChartComponent
                                :chartData="chartData"
                                :chartDataMeta="chartDataMeta"
                            ></ChartComponent>
                            <!-- ... -->
                            <ChartLoteActivo
                                :PreparacionTerreno="PreparacionTerreno"
                                :Siembra="Siembra"
                                :CrecimientoCuidado="CrecimientoCuidado"
                                :Tamero="Tamero"
                            ></ChartLoteActivo>
                            <ChartLoteActivoCosechas
                                :Plantula="Plantula"
                                :MaximoMacollamiento="MaximoMacollamiento"
                                :Primordio="Primordio"
                                :Antesis="Antesis"
                                :Floracion="Floracion"
                                :Llenado="Llenado"
                                :Maduracion="Maduracion"
                                :Cosecha="Cosecha"
                            ></ChartLoteActivoCosechas>
                        </div>

                        <!-- Dashboard Looker Studio-->

                        <!--
                        <iframe
                            class="w-full"
                            height="900"
                            src="https://lookerstudio.google.com/embed/reporting/dff56d42-1e14-4d6c-b627-c2bfdc698ff6/page/ehR4D"
                            frameborder="0"
                            style="border: 0"
                            allowfullscreen
                            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                        ></iframe>

                    --></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import ChartComponent from "./ChartComponent.vue";
import ChartLoteActivo from "./ChartLoteActivo.vue";
import ChartLoteActivoCosechas from "./ChartLoteActivoCosechas.vue";

const chartData = ref({ labels: [], values: [] });
const chartDataMeta = ref({ labels: [], values: [] });
const PreparacionTerreno = ref({ labels: [], values: [] });
const Siembra = ref({ labels: [], values: [] });
const CrecimientoCuidado = ref({ labels: [], values: [] });
const Tamero = ref({ labels: [], values: [] });

const Plantula = ref({ labels: [], values: [] });
const MaximoMacollamiento = ref({ labels: [], values: [] });
const Primordio = ref({ labels: [], values: [] });
const Antesis = ref({ labels: [], values: [] });
const Floracion = ref({ labels: [], values: [] });
const Llenado = ref({ labels: [], values: [] });
const Maduracion = ref({ labels: [], values: [] });
const Cosecha = ref({ labels: [], values: [] });

onMounted(async () => {
    await fetchChartData();
});

const fetchChartData = async () => {
    const response = await fetch(route("RegLote.dataconsultaSiembra")); // Cambia esto a tu endpoint
    const data = await response.json();
    const response_Meta = await fetch(route("Finca.HectMeta")); // Cambia esto a tu endpoint
    const data_Meta = await response_Meta.json();

    //console.log(data.labels);
    // Asegúrate de que la estructura de data sea correcta
    chartData.value = {
        labels: data.labels,
        values: data.values,
    };

    chartDataMeta.value = {
        labels: data_Meta.labels,
        values: data_Meta.values,
    };

    // Chart 2  = Estatus Lote

    const ResponseDataChartEstadosLotes = await fetch(
        route("RegLote.DataChartEstadosLotes")
    ); // Cambia esto a tu endpoint
    const DataChartEstadosLotes = await ResponseDataChartEstadosLotes.json();

    PreparacionTerreno.value = {
        labels: DataChartEstadosLotes.PreparacioTerreno.labels,
        values: DataChartEstadosLotes.PreparacioTerreno.values,
    };
    Siembra.value = {
        labels: DataChartEstadosLotes.Siembra.labels,
        values: DataChartEstadosLotes.Siembra.values,
    };
    CrecimientoCuidado.value = {
        labels: DataChartEstadosLotes.CrecimientoCuidado.labels,
        values: DataChartEstadosLotes.CrecimientoCuidado.values,
    };
    Tamero.value = {
        labels: DataChartEstadosLotes.Tamero.labels,
        values: DataChartEstadosLotes.Tamero.values,
    };

    // Chart 3 Estatus de la Cosecha

    const ResponseDataChartStatusLoteCrecimientoCuidado = await fetch(
        route("RegLote.DataChartStatusLoteCrecimientoCuidado")
    ); // Cambia esto a tu endpoint
    const DataChartStatusLoteCrecimientoCuidado =
        await ResponseDataChartStatusLoteCrecimientoCuidado.json();

    Plantula.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Plantula.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Plantula.values,
    };
    MaximoMacollamiento.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.MaximoMacollamiento
            .labels,
        values: DataChartStatusLoteCrecimientoCuidado.MaximoMacollamiento
            .values,
    };
    Primordio.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Primordio.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Primordio.values,
    };
    Antesis.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Antesis.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Antesis.values,
    };
    Floracion.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Floracion.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Floracion.values,
    };
    Llenado.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Llenado.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Llenado.values,
    };
    Maduracion.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Maduracion.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Maduracion.values,
    };
    Cosecha.value = {
        labels: DataChartStatusLoteCrecimientoCuidado.Cosecha.labels,
        values: DataChartStatusLoteCrecimientoCuidado.Cosecha.values,
    };
};
</script>
<style>
canvas {
    max-width: 550px;
    max-height: 500px;
}
</style>
