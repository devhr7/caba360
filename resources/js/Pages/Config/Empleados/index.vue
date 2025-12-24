<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { useForm } from '@inertiajs/vue3';

import { FilterMatchMode } from "@primevue/core/api";
//
const props = defineProps(["empleados", "options"]);
const reg_datatable = ref({});


// Constante de los Filtros de la tabla
const filters = ref();

//  Funcion para Filtros
const initFilters = () => {
    filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    identificacion: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    nombre1: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    cargo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    };
};

initFilters(); // Ejecutar Filtros


const visible = ref(false);

const form = useForm({
    id: null,
    identificacion: null,
    nombre: null,
    cargo: null,
});
// Funcion del Boton Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    form.id = reg_datatable.value.id;
    form.identificacion = reg_datatable.value.identificacion;
    form.nombre = reg_datatable.value.nombre1;
    form.cargo = reg_datatable.value.selectedCargo;
    visible.value = true;

};


function resetForm() {

    form.reset();
}
function closeDialog() {
    visible.value = false;
    resetForm();
}
function openNew() {
    resetForm();
    visible.value = true;
}


// boton de Crear
function crear() {
    form.post(route("empleados.store"), form);
    closeDialog();
}

function actualizar() {
    form.put(route("empleados.update", form.id), form);
    closeDialog();
}

function eliminar() {
    form.delete(route("empleados.delete", form.id), form);
    closeDialog();
}







</script>

<template>

    <Head title="Empleados" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar - Empleados
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable v-model:filters="filters" :value="empleados" showGridlines paginator :rows="5"
                        :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" filterDisplay="row"
                        :loading="loading" :globalFilterFields="['nombre1', 'identificacion', 'cargo']">
                        <template #header>
                            <div class="flex flex-wrap gap-2 items-center justify-between">
                                <Button label="Crear" icon="pi pi-plus" severity="success" @click="openNew" />

                                <IconField>
                                    <InputIcon>
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <InputText v-model="filters['global'].value" placeholder="Buscar..." />
                                </IconField>
                            </div>
                        </template>
                        <Column field="id" header="#"></Column>
                        <Column field="estado" header="Estado"></Column>
                        <Column field="identificacion" header="Identificacion"></Column>
                        <Column field="nombre1" header="Nombre"></Column>
                        <Column field="cargo" header="Cargo"></Column>

                        <Column :exportable="false" style="min-width: 12rem">
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2"
                                    @click="edit(slotProps.data)" />

                            </template>
                        </Column>
                    </DataTable>

                    <Dialog v-model:visible="visible" modal :header="form.id ? 'Editar Empleado' : 'Crear Empleado'" :style="{ width: '25rem' }">

                        <div class="flex items-center gap-4 mb-4">
                            <label for="identificacion" class="font-semibold w-24">Identificacion</label>
                            <InputText id="identificacion" v-model="form.identificacion" class="flex-auto"
                                autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nombre" class="font-semibold w-24">Nombre</label>
                            <InputText id="nombre" v-model="form.nombre" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="cargo" class="font-semibold w-24">Cargo</label>

                            <Select v-model="form.cargo" :options="props.options.cargos" filter optionLabel="label"
                                placeholder="Seleccionar" showClear class="w-full" fluid>
                                <template #value="slotProps">
                                    <div v-if="slotProps.value" class="flex items-center">
                                        <div>
                                            <!-- Mostrar el nombre de la finca-->
                                            {{
                                                slotProps.value
                                                    .label
                                            }}
                                        </div>
                                    </div>
                                    <span v-else>
                                        <!-- Mostrar el placeholder-->
                                        {{ slotProps.placeholder }}
                                    </span>
                                </template>
                                <template #option="slotProps">
                                    <div class="flex items-center">
                                        <div>
                                            <!-- Mostrar el nombre de la finca-->
                                            {{
                                                slotProps.option
                                                    .label
                                            }}
                                        </div>
                                    </div>
                                </template>
                            </Select>
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" v-show="form.id" label="Eliminar" severity="danger" @click="eliminar"
                                outlined></Button>

                            <Button type="button" v-show="!form.id" label="Guardar" @click="crear"></Button>
                            <Button type="button" v-show="form.id" label="Actualizar" @click="actualizar"></Button>
                        </div>
                    </Dialog>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
