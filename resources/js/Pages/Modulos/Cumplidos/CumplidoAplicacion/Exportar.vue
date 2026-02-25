<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

const props = defineProps(["cumplidoAplicacion"]);

// Filtros de busqueda

// CumplidoAplicacion.DataReport

const form = useForm({
    fecha_inicio: null,
    fecha_fin: null,
});

const DTCumplidosAplicacion = ref(null);

async function getdataReport() {
    await axios
        .post(route("CumplidoAplicacion.DataReport"), {
            fecha_inicio: form.fecha_inicio,
            fecha_fin: form.fecha_fin,
        })
        .then(function (response) {
            console.info(response.data);
            DTCumplidosAplicacion.value = response.data;
        });
}




async function exportCSV() {

    const response = await axios.post(route("CumplidoAplicacion.Exportarxlsx"), {
        fecha_inicio: form.fecha_inicio,
        fecha_fin: form.fecha_fin,
    }, { responseType: 'blob' });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `cumplidos_aplicacion.xlsx`);
    document.body.appendChild(link);
    link.click();
}
</script>

<template>
    <Head title="CumpAplicacion" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reporte - Cumplido Aplicacion
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Panel header="Buscador" class="m-5" toggleable>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="fecha_inicio">Fecha Inicio</label>
                                <DatePicker
                                    v-model="form.fecha_inicio"
                                    showIcon
                                    fluid
                                    iconDisplay="input"
                                    dateFormat="dd/mm/yy"
                                />
                            </div>

                            <div>
                                <label for="fecha_fin">Fecha Fin</label>
                                <DatePicker
                                    v-model="form.fecha_fin"
                                    showIcon
                                    fluid
                                    iconDisplay="input"
                                    dateFormat="dd/mm/yy"
                                />
                            </div>
                        </div>

                        <Divider />
                        <div class="grid grid-cols-1 gap-9">
                            <Button
                                label="Buscar"
                                severity="secondary"
                                @click="getdataReport()"
                                raised
                            />
                        </div>
                    </Panel>

                    <Panel header="Reporte" class="m-5" toggleable >
                        <DataTable
                            :value="DTCumplidosAplicacion"
                            size="small"
                            tableStyle="min-width: 50rem"
                            showGridlines
                            scrollable
                        >
                        <template #header>
        <div class="text-end pb-4">
            <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />
        </div>
    </template>
                            <Column field="cumplido" header="Cumplido"></Column>
                            <Column field="fecha" header="Fecha"></Column>
                            <Column field="distrito" header="Distrito"></Column>
                            <Column field="finca" header="Finca"></Column>
                            <Column field="lote" header="Lote"></Column>
                            <Column field="codigo_lote" header="codigo_lote"></Column>
                            <Column field="hectareas" header="Hect"></Column>
                            <Column field="Aplicacion" header="Aplicacion"></Column>
                            <Column field="cantidad" header="cantidad"></Column>
                            <Column field="Total" header="Total"></Column>

                        </DataTable>
                    </Panel>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
