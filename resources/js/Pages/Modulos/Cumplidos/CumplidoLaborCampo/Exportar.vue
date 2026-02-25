<script setup>
// Importar librerias de Vue
import { ref, onMounted, computed, watch } from "vue";
// Importar componentes de la aplicacion
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// Importar hooks de useForm
import { useForm } from "@inertiajs/vue3";
// Importar funcion de Toast
import { useToast } from "primevue/usetoast";

import { FilterMatchMode } from "@primevue/core/api";

const toast = useToast();

const props = defineProps({
    get_finca: { type: Object },
});

const form = useForm({
    fecha_inicio: null,
    fecha_fin: null,
    finca :null,
    RegLote_id :null,
    lote :null,

});

async function exportCSV() {
    const response = await axios.post(
        route("CumpLaborCampo.Exportarxlsx"),
        {
            fecha_inicio: form.fecha_inicio,
            fecha_fin: form.fecha_fin,
        },
        { responseType: "blob" }
    );

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", `cumplido_labor_campo.xlsx`);
    document.body.appendChild(link);
    link.click();
}

</script>

<template>
    <Head title="Cumplidos Maquinaria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Exportar - Cumplido Labor Campo
            </h2>
        </template>
        <!-- Menu 2-->
        <Breadcrumb :model="items">
            <template #item="{ item, props }">
                <a :href="item.url">
                    <span class="text-surface-700 dark:text-surface-0">{{
                        item.label
                    }}</span>
                </a>
            </template>
        </Breadcrumb>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <form @submit.prevent="exportCSV">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols gap-4 mt-2">
                                   
                                    <!-- Fecha-->
                                </div>
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                   
                                    <div >
                                        <label for="fecha_inicio"
                                            >Fecha Inicio</label
                                        >
                                        <DatePicker
                                            v-model="form.fecha_inicio"
                                            showIcon
                                            fluid
                                            iconDisplay="input"
                                            dateFormat="dd/mm/yy"
                                        />
                                    </div>

                                    <div >
                                        <label for="fecha_fin">FechaFin</label>
                                        <DatePicker
                                            v-model="form.fecha_fin"
                                            showIcon
                                            fluid
                                            iconDisplay="input"
                                            dateFormat="dd/mm/yy"
                                        />
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <Fluid>
                                <div class="">
                                    <!-- :disabled="isInvalid"  -->
                                    <Button
                                        type="submit"
                                        label="Exportar"
                                        icon="pi pi-file-excel"
                                        class="p-button-success"
                                    />

                                </div>
                                </Fluid>
                            </template>
                        </Card>
                        <Card style="overflow: hidden">
                            <template #content>
                                
                            </template>
                        </Card>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
