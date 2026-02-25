<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

import { FilterMatchMode } from "@primevue/core/api";
const props = defineProps(["RegistroLotes"]);
const reg_datatable = ref({});

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    RegistroLotes: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    distrito_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    finca_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
// Funcion del Boton Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.edit_url); // redirecciona a la vista Editar
};

// boton de Crear
function crear() {
    router.get(route("RegLote.Crear")); // Redirecciona a la Vista Crear
}

// boton de PDF
const GenerarPDF = (prod) => {
    // Obtener datos de la fila seleccionada (si es necesario)
    let datosFila = { ...prod };

    // Llamar a la ruta de Laravel para generar el PDF
    axios.get(route("RegLote.GenerarPDF", datosFila.slug), {
        responseType: 'blob' // Indicar que esperamos un blob (archivo) como respuesta
    })
    .then(response => {
        // Crear un objeto URL para el blob
        const url = window.URL.createObjectURL(new Blob([response.data]));

        // Crear un elemento <a> para descargar el PDF
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'example.pdf'); // Nombre del archivo para descargar

        // Agregar el elemento <a> al cuerpo del documento
        document.body.appendChild(link);

        // Simular clic en el enlace para iniciar la descarga
        link.click();

        // Limpiar el objeto URL creado
        window.URL.revokeObjectURL(url);

        console.log('PDF generado correctamente');
    })
    .catch(error => {
        console.error('Error al generar el PDF:', error);
    });
};


/**
 * Excel
 */

 const ExportarExcel = (prod) => {
    let datosFila = { ...prod };
    axios.get(route("RegLote.exportXLSX", datosFila.slug), {
        responseType: 'blob' // Indicar que esperamos un blob (archivo) como respuesta
    })
    .then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));

        // Crear un elemento <a> para descargar el archivo Excel
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'registro_lotes.xlsx'); // Nombre del archivo para descargar

        // Agregar el elemento <a> al cuerpo del documento
        document.body.appendChild(link);

        // Simular clic en el enlace para iniciar la descarga
        link.click();

        // Limpiar el objeto URL creado
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error al exportar a Excel:', error);
    });
};

</script>

<template>
    <Head title="RegLote" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar - RegLote
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    {{  red }}
                    <DataTable
                        v-model:filters="filters"
                        :value="RegistroLotes"
                        showGridlines
                        paginator
                        :rows="5"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem"
                        filterDisplay="row"
                        :loading="loading"
                        :globalFilterFields="['finca_id','distrito_id']"
                    >
                        <template #header>
                            <div
                                class="flex flex-wrap gap-2 items-center justify-between"
                            >
                            <!-- Boton de Crear -->
                                <Button
                                    label="Crear"
                                    icon="pi pi-plus"
                                    severity="success"
                                    class="mr-2"
                                    @click="crear"
                                />
                                <IconField>
                                    <InputIcon>
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <InputText
                                        v-model="filters['global'].value"
                                        placeholder="Buscar..."
                                    />
                                </IconField>
                            </div>
                        </template>
                        <Column field="id" header="#"></Column>
                        <Column field="distrito_id" header="Distrito"></Column>
                        <Column field="finca_id" header="Finca"></Column>
                        <Column field="Codigo" header="Codigo"></Column>
                        <Column field="NombreLote" header="Lote"></Column>
                        <Column field="TipoCultivo_id" header="Cultivo"></Column>
                        <Column field="TipoVariedad_id" header="Variedad"></Column>
                        <Column field="FechaSiembra" header="Fecha Siembra"></Column>
                        <Column field="FechaRecoleccion" header="Fecha Recoleccion"></Column>
                        <Column field="Hect" header="Hect"></Column>

                        <!-- Columna de Acciones-->
                        <Column :exportable="false" style="min-width: 12rem">
                            <!-- Boton Editar -->
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-pencil"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="edit(slotProps.data)"
                                />
                                <Button
                                    icon="pi pi-file-pdf"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="GenerarPDF(slotProps.data)"
                                />
                                <Button
                                    icon="pi pi-file-pdf"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="ExportarExcel(slotProps.data)"
                                />



                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
