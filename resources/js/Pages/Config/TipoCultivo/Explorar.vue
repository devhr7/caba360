<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

import { FilterMatchMode } from "@primevue/core/api";
const props = defineProps(["data"]);
const reg_datatable = ref({});

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    TipoCultivo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
// Funcion del Boton Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.edit_url); // redirecciona a la vista Editar
};

// boton de Crear
function crear() {
    router.get(route("TipoCultivo.Crear")); // Redirecciona a la Vista Crear
}

</script>

<template>
    <Head title="Tipo Cultivo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar - Tipo Cultivo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        v-model:filters="filters"
                        :value="data"
                        showGridlines
                        paginator
                        :rows="5"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem"
                        filterDisplay="row"
                        :loading="loading"
                        :globalFilterFields="['TipoCultivo']"
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
                        <Column field="TipoCultivo" header="TipoCultivo"></Column>


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
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
