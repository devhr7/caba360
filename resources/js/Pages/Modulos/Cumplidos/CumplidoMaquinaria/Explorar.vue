<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

import { FilterMatchMode } from "@primevue/core/api";
const props = defineProps(["CumplidoMaquinaria"]);
const reg_datatable = ref({});
import { useToast } from "primevue/usetoast";
const toast = useToast();

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    codigo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    codigo_lote: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    labor: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    fecha: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

// Funcion del Boton Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.edit_url); // redirecciona a la vista Editar
};

// boton de Crear
function crear() {
    router.get(route("CumpMaquinaria.Crear")); // Redirecciona a la Vista Crear
}

const selectedCumplidos = ref(null);
const size = ref({ label: "Small", value: "small" });
const visible = ref(false);

const form = useForm({
    fechaCierre: null,
    selectedCumplidos: null,
    cumplido: null,
});

const AbrirModal = (prod) => {
    reg_datatable.value = { ...prod };

    form.cumplido = reg_datatable.value.id;
    form.selectedCumplidos = selectedCumplidos.value;
    const fechaCierre = new Date(reg_datatable.value.fecha_cierre || new Date().toISOString());
    fechaCierre.setDate(fechaCierre.getDate() );
    form.fechaCierre = fechaCierre.toISOString();

    visible.value = true;
};

function verificar() {
    visible.value = false;

    form.post(route("CumpMaquinaria.verificar"), {
        preserveScroll: true,
        onSuccess: () => {
            form.selectedCumplidos = null;
            selectedCumplidos.value = null;

            toast.add({
                severity: "success",
                summary: "Verificado",
                detail: "Ha Sido Verificado Correctamente.",
                life: 3000,
            });
        },
    });
}
</script>

<template>
    <Head title="CumpMaq" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cumplido Maquinaria
            </h2>
        </template>

        <div class="py-12">
            <div class=" mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        v-model:filters="filters"
                        v-model:selection="selectedCumplidos"
                        :value="CumplidoMaquinaria"
                        showGridlines
                        paginator
                        :rows="10"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem"
                        filterDisplay="menu"
                        :loading="loading"
                        :globalFilterFields="[
                            'codigo',
                            'codigo_lote',
                            'labor',
                            'fecha',

                        ]"
                        :size="size.value"
                        class="text-sm"
                    >
                        <template #header>
                            <div
                                class="flex flex-wrap gap-2 items-center justify-between"
                            >
                                <Button
                                    label="Verificar"
                                    icon="pi pi-verified"
                                    severity="success"
                                    class="mr-2"
                                    @click="AbrirModal()"
                                    :disabled="
                                        !selectedCumplidos ||
                                        !selectedCumplidos.length
                                    "
                                />
                                <!-- Boton de Crear -->
                                <Button
                                    label="Crear"
                                    icon="pi pi-plus"
                                    severity="success"
                                    class="mr-2"
                                    @click="crear"
                                    v-if="
                                        $page.props.Permission.user_permision.includes(
                                            'mod.cump_maq.create' // DEfinir permiso
                                        )
                                    "
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
                            selectionMode="multiple"
                            headerStyle="width: 3rem"
                        ></Column>
                        <Column
                            field="codigo"
                            sortable
                            header="Codigo"
                        ></Column>
                        <Column field="fecha" sortable header="Fecha"></Column>
                        <Column field="finca" sortable header="Lote"></Column>
                        <Column
                            field="codigo_lote"
                            sortable
                            header="Cod Lote"
                        ></Column>
                        <Column field="maquinaria" header="Maquina"></Column>
                        <Column field="operario" header="Operario"></Column>
                        <Column field="labor" header="Labor"></Column>
                        <Column field="codigo_lote" header="Operario"></Column>
                        <Column field="cantidad" header="Cantidad"></Column>
                        <Column field="valor_total" header="Total"></Column>
                        <Column
                            field="verificado"
                            header=""
                            dataType="boolean"
                            bodyClass="text-center"
                        >
                            <template #body="slotProps">
                                <Button
                                    :icon="{
                                        ' pi pi-check-circle':
                                            slotProps.data.fecha_cierre,
                                        ' pi pi-times-circle':
                                            !slotProps.data.fecha_cierre,
                                    }"
                                    :class="{
                                        'text-green-500':
                                            slotProps.data.fecha_cierre,
                                        ' text-red-500':
                                            !slotProps.data.fecha_cierre,
                                    }"
                                    text
                                    rounded
                                    @click="AbrirModal(slotProps.data)"
                                >
                                </Button
                                >{{ slotProps.data.fecha_cierre_f }}
                            </template>
                            <template #filter="{ filterModel }">
                                <label for="verified-filter" class="font-bold">
                                    Verificar
                                </label>
                                <Checkbox
                                    v-model="filterModel.value"
                                    :indeterminate="filterModel.value === null"
                                    binary
                                    inputId="verified-filter"
                                />
                            </template>
                        </Column>
                        <!-- Columna de Acciones-->
                        <Column :exportable="false">
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

                    <Dialog
                        v-model:visible="visible"
                        modal
                        header="Verificar Cumplido Maquinaria"
                        :style="{ width: '25rem' }"
                    >
                        <div class="flex items-center gap-4 mb-4">
                            <label for="Fecha Cierre" class="font-semibold w-24"
                                >Fecha Cierre</label
                            >
                            <DatePicker
                                class="w-full"
                                v-model="form.fechaCierre"
                                showIcon
                                fluid
                                iconDisplay="input"
                                inputId="fechaCierre"
                                dateFormat="dd/mm/yy"
                            />
                        </div>

                        <div class="flex justify-end gap-2">
                            <Button
                                type="button"
                                label="Cerrar"
                                severity="secondary"
                                @click="visible = false"
                            ></Button>
                            <Button
                                type="button"
                                label="Guardar"
                                @click="verificar()"
                            ></Button>
                        </div>
                    </Dialog>

                    <Toast />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
