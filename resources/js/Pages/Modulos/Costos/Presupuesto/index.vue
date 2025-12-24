<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";

const props = defineProps({
    datatable: { type: Object },
    errors: { type: Object },
});

// Constante de los Filtros de la tabla
const filters = ref();
const reg_datatable = ref();
const size = ref({ label: "Small", value: "small" });
//  Funcion para Filtros
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        fecha_ini: { value: null, matchMode: FilterMatchMode.CONTAINS },
        fecha_fin: { value: null, matchMode: FilterMatchMode.CONTAINS },
        codigo: { value: null, matchMode: FilterMatchMode.CONTAINS },
        nombre: { value: null, matchMode: FilterMatchMode.CONTAINS },
    };
};

initFilters(); // Ejecutar Filtros

// Funcion para Limpiar Filtros
const clearFilter = () => {
    initFilters();
};

const modal = (prod) => {
    if (prod) {
        reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
        reg_datatable.value.id;
        router.get(route("costos.ppt.edit", reg_datatable.value.id)); // Redirecciona a la Vista Editar
    } else {
        router.get(route("costos.ppt.create")); // Redirecciona a la Vista Crear
    }
};
</script>

<template>
    <Head title="Presupuesto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Presupuesto | Explorar
            </h2>
        </template>

        <div class="py-12">
            <div class="table-fixed mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    {{ props.datatable }}
                    <DataTable
                        v-model:filters="filters"
                        :value="props.datatable"
                        tableStyle="min-width: 50rem"
                        filterDisplay="menu"
                        :globalFilterFields="[
                            'status',
                            'fecha_ini',
                            'fecha_fin',
                        ]"
                        class="text-sm"
                        showGridlines
                        :size="size.value"
                    >
                        <template #header>
                            <div
                                class="flex flex-wrap gap-2 items-center justify-between"
                            >
                                <Button
                                    type="button"
                                    icon="pi pi-filter-slash"
                                    label="Clear"
                                    outlined
                                    @click="clearFilter()"
                                />
                                <Button
                                    label="Crear"
                                    icon="pi pi-plus"
                                    severity="success"
                                    class="mr-2"
                                    @click="modal(false)"
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

                        <Column
                            header="Nombre"
                            field="slug"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.slug }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Buscar"
                                />
                            </template>
                        </Column>

                        <Column
                            header="Status"
                            field="status"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.status }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Buscar"
                                />
                            </template>
                        </Column>

                        <Column
                            header="Fecha Inicio"
                            field="fecha_ini"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.fecha_ini }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Buscar"
                                />
                            </template>
                        </Column>

                        <Column
                            header="Fecha Fin"
                            field="fecha_fin"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.fecha_fin }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Buscar"
                                />
                            </template>
                        </Column>

                        <Column :exportable="false" style="max-width: 3rem">
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-pencil"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="modal(slotProps.data)"
                                />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
