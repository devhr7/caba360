<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";

const props = defineProps({
    datatable: { type: Object },
    options: { type: Object },
    errors: { type: Object },
});

const form = useForm({
    id: null,
    gasto: null,
    tipogasto: null,
    codigo: null,
    subtipogasto: null,
});

// Constante de los Filtros de la tabla
const filters = ref();
const reg_datatable = ref();
const size = ref({ label: "Small", value: "small" });
//  Funcion para Filtros
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        gasto: { value: null, matchMode: FilterMatchMode.CONTAINS },
        tipogasto: { value: null, matchMode: FilterMatchMode.CONTAINS },
        codigo: { value: null, matchMode: FilterMatchMode.CONTAINS },
        nombre: { value: null, matchMode: FilterMatchMode.CONTAINS },
    };
};

const OptionsGasto = props.options.optionsGasto;
const OptionsTipoGasto = props.options.optionsTipoGasto;

initFilters(); // Ejecutar Filtros

// Funcion para Limpiar Filtros
const clearFilter = () => {
    initFilters();
};

const visible = ref(false);

const modal = (prod) => {
    if (prod) {
        reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila

        form.id = reg_datatable.value.id;
        form.gasto = reg_datatable.value.optionsGasto;
        form.tipogasto = reg_datatable.value.optionsTipoGasto;
        form.codigo = reg_datatable.value.codigo;
        form.subtipogasto = reg_datatable.value.nombre;
    } else {
        form.id = null;
        form.gasto = null;
        form.tipogasto = null;
        form.codigo = null;
        form.subtipogasto = null;
    }
    visible.value = true;
};

function submit() {
    form.post(route("costos.param.subtipogasto.store"), {
        onSuccess: () => {
            form.id = null;
            form.gasto = null;
            form.tipogasto = null;
            form.codigo = null;
            form.subtipogasto = null;
            visible.value = false;
        },
    });
}

const submitDelete = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    // Alerta
    Swal.fire({
        title: "Esta Seguro de Eliminar?",
        text: "Esta Accion es Irreversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        // Confirmacion
        if (result.isConfirmed) {
            // Exitoso
            form.delete(
                route("costos.param.subtipogasto.destroy", reg_datatable.value.id)
            );
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
};
</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Parametros Costos | Productos
            </h2>
        </template>

        <div class="py-12">
            <div class="table-fixed mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        v-model:filters="filters"
                        :value="datatable"
                        tableStyle="min-width: 50rem"
                        filterDisplay="menu"
                        :globalFilterFields="[
                            'gasto',
                            'tipogasto',
                            'codigo',
                            'nombre',
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

                                <!-- Boton de Crear -->
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
                            header="Gasto"
                            field="gasto"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.gasto }}
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
                            header="Tipo Gasto"
                            field="tipogasto"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.tipogasto }}
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
                            header="Codigo"
                            field="codigo"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.codigo }}
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
                            header="Sub-Tipo Gasto"
                            field="nombre"
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.nombre }}
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
                            <!-- Boton Editar -->
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-pencil"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="modal(slotProps.data)"
                                />

                                <Button
                                    icon="pi pi-trash"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    severity="danger"
                                    @click="submitDelete(slotProps.data)"
                                />
                            </template>
                        </Column>
                    </DataTable>

                    <Dialog
                        v-model:visible="visible"
                        modal
                        header="Sub Tipo Gasto"
                        :style="{ width: '50rem' }"
                        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
                    >
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="gasto">Gasto</label>
                                        <Select
                                            v-model="form.gasto"
                                            :options="OptionsGasto"
                                            filter
                                            optionLabel="label"
                                            placeholder="Seleccionar"
                                            showClear
                                            class="w-full md:w-56"
                                        >
                                            <template #value="slotProps">
                                                <div
                                                    v-if="slotProps.value"
                                                    class="flex items-center"
                                                >
                                                    <div>
                                                        {{
                                                            slotProps.value
                                                                .label
                                                        }}
                                                    </div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex items-center">
                                                    <div>
                                                        {{
                                                            slotProps.option
                                                                .label
                                                        }}
                                                    </div>
                                                </div>
                                            </template>
                                        </Select>
                                        <small
                                            v-if="errors.gasto"
                                            id="gasto-help"
                                            class="text-red-700"
                                            >{{ errors.gasto }}</small
                                        >
                                    </div>
                                </div>
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="tipogasto"
                                            >Tipo Gasto</label
                                        >
                                        <Select
                                            v-model="form.tipogasto"
                                            :options="OptionsTipoGasto"
                                            filter
                                            optionLabel="label"
                                            placeholder="Seleccionar"
                                            showClear
                                            class="w-full md:w-56"
                                        >
                                            <template #value="slotProps">
                                                <div
                                                    v-if="slotProps.value"
                                                    class="flex items-center"
                                                >
                                                    <div>
                                                        {{
                                                            slotProps.value
                                                                .label
                                                        }}
                                                    </div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex items-center">
                                                    <div>
                                                        {{
                                                            slotProps.option
                                                                .label
                                                        }}
                                                    </div>
                                                </div>
                                            </template>
                                        </Select>
                                        <small
                                            v-if="errors.tipogasto"
                                            id="tipogasto-help"
                                            class="text-red-700"
                                            >{{ errors.tipogasto }}</small
                                        >
                                    </div>
                                </div>
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="codigo">Codigo</label>
                                        <InputText
                                            id="codigo"
                                            name="codigo"
                                            v-model="form.codigo"
                                            aria-describedby="codigo-help"
                                        />
                                        <small
                                            v-if="errors.codigo"
                                            id="codigo-help"
                                            class="text-red-700"
                                            >{{ errors.codigo }}</small
                                        >
                                    </div>
                                </div>
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="subtipogasto"
                                            >Sub TipoGasto</label
                                        >
                                        <InputText
                                            id="subtipogasto"
                                            name="subtipogasto"
                                            v-model="form.subtipogasto"
                                            aria-describedby="codigo-help"
                                        />
                                        <small
                                            v-if="errors.subtipogasto"
                                            id="subtipogasto-help"
                                            class="text-red-700"
                                            >{{ errors.subtipogasto }}</small
                                        >
                                    </div>
                                </div>

                                <div>
                                    <div class="flex flex-col gap-2">
                                        <Button label="Guardar" type="submit" />
                                    </div>
                                </div>
                                <!-- ... -->
                            </div>
                        </form>
                    </Dialog>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
