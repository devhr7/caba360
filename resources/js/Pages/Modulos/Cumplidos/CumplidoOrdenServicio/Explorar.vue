<script setup>
import { ref, onMounted, computed,nextTick } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

import { FilterMatchMode } from "@primevue/core/api";

// Definiendo Props
const props = defineProps(["CumplidoOrdenServicio"]);
import { useToast } from "primevue/usetoast";
const toast = useToast();
/**
 * Redireccionar Vista Crear
 */

// boton de Crear
function crear() {
    router.get(route("CumplidoOrdenServicio.crear")); // Redirecciona a la Vista Crear
}

/**
 * Tabla
 */
const filters = ref({});

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        codigo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        contratista: { value: null, matchMode: FilterMatchMode.CONTAINS },
        autorizado: { value: null, matchMode: FilterMatchMode.CONTAINS },
        factura: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        verificado: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    };
};
// Filtros de busqueda
initFilters(); // Ejecutar Filtros
const clearFilter = () => {
    initFilters();
};
// Constante para la Funcion
const reg_datatable = ref({});

// Redireccionar Vista Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(route("CumplidoOrdenServicio.edit", reg_datatable.value.slug)); // redirecciona a la vista Editar
};

/**  Verificar * */

// Formulario
const form = useForm({
    CumplidoOrdenServicio: "",
    selectedCumplidos: null,
    factura: "",
});

// Constante para modal
const visible = ref(false);
// Abrir Modal
const AbrirModal = (prod) => {
    reg_datatable.value = { ...prod };
    form.CumplidoOrdenServicio = reg_datatable.value.id;
    form.factura = reg_datatable.value.factura;
    visible.value = true;
};
const size = ref({ label: "Small", value: "small" });
// Funcion Ferificar

const formatCurrency = (value) => {
    return value.toLocaleString("es-ES");
};
const selectedCumplidos = ref(null);
const thisTotalSeleccionados = computed(() => {
    let total = 0;
    if (selectedCumplidos.value == null) {
        return 0;
    }
    for (let selectedCumplido of selectedCumplidos.value) {
        total += selectedCumplido.total;
    }

    return formatCurrency(total);
});

function verificar() {
    visible.value = false;
    form.selectedCumplidos = selectedCumplidos.value;
    form.post(route("CumplidoOrdenServicio.verificar"), {
        preserveScroll: true,
        onSuccess: () => {
            form.selectedCumplidos = null;
            selectedCumplidos.value = null;

            toast.add({
                severity: "success",
                summary: "Verificado",
                detail: "Ha Sido Verificado Correctamente.",
            });
        },
    });
}
const ObsCumpApliDialog = ref(false);
const datatable_detalle = ref({});
const AbrirModalObs = (prod) => {
    reg_datatable.value = { ...prod };
    datatable_detalle.value = reg_datatable.value.detalle;
    ObsCumpApliDialog.value = true;
};

const op = ref();
const datatable_detalle_record = ref({});

const displayRecord = (event, prod) => {
    op.value.hide();
    datatable_detalle_record.value = { ...prod };
    nextTick(() => {
        op.value.show(event);
    });
};

const hidePopover = () => {
    op.value.hide();
};
</script>

<template>
    <Head title="Orden de Servicio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar -
                <a
                    :href="route('CumplidoOrdenServicio.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Orden de Servicio</a
                >
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        v-model:filters="filters"
                        v-model:selection="selectedCumplidos"
                        :value="CumplidoOrdenServicio"
                        showGridlines
                        paginator
                        :rows="10"
                        :rowsPerPageOptions="[10, 20, 50, 100]"
                        tableStyle="min-width: 50rem"
                        filterDisplay="menu"
                        :loading="loading"
                        :globalFilterFields="[
                            'codigo',
                            'contratista',
                            'autorizado',
                            'factura',
                            'verificado',
                        ]"
                        :size="size.value"
                        class="text-sm"
                        :class="$style.mydata"
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
                            header="Codigo"
                            field="codigo"
                            sortable
                            frozen
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
                        <Column field="fecha" sortable header="Fecha"></Column>

                        <Column
                            header="Contratista"
                            field="contratista"
                            sortable
                            frozen
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.contratista }}
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
                            field="Format_Total"
                            sortable
                            header="Total"
                        ></Column>

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
                                            slotProps.data.verificado,
                                        ' pi pi-times-circle':
                                            !slotProps.data.verificado,
                                    }"
                                    :class="{
                                        'text-green-500':
                                            slotProps.data.verificado,
                                        ' text-red-500':
                                            !slotProps.data.verificado,
                                    }"
                                    text
                                    rounded
                                    @click="AbrirModal(slotProps.data)"
                                >
                                </Button>
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

                        <Column
                            header="factura"
                            field="factura"
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>{{ data.factura }}</div>
                                    <!-- ... -->
                                </div>
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
                            header="Detalle"
                           
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="slotProps">
                                <div class="grid grid-cols-2 gap-2">
                                    
                                    <!-- ... -->
                                    <div>
                                        <Button
                                            type="button"
                                            @click="AbrirModalObs(slotProps.data)"
                                            icon="pi pi-search"
                                            severity="secondary"
                                            
                                            rounded
                                        ></Button>
                                    </div>
                                </div>
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
                        <ColumnGroup type="footer">
                            <Row>
                                <Column
                                    footer="Totals:"
                                    :colspan="5"
                                    footerStyle="text-align:right"
                                />

                                <Column :footer="thisTotalSeleccionados" />
                            </Row>
                        </ColumnGroup>
                        <template #footer>
                            {{
                                selectedCumplidos ? selectedCumplidos.length : 0
                            }}
                            Cumplidos Seleccionados.
                        </template>
                    </DataTable>

                    <Dialog
                        v-model:visible="visible"
                        modal
                        header="Verificar Orden Servicio"
                        :style="{ width: '25rem' }"
                    >
                        <div class="flex items-center gap-4 mb-4">
                            <label for="Factura" class="font-semibold w-24"
                                >Factura</label
                            >
                            <InputText
                                id="Factura"
                                v-model="form.factura"
                                class="flex-auto"
                                autocomplete="off"
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

                      <Dialog
                        v-model:visible="ObsCumpApliDialog"
                        :style="{ width: '1300px' }"
                        header="Observaciones"
                        :modal="true"
                    >
              
                        <div class="">
                            <DataTable :value="datatable_detalle" tableStyle="min-width: 50rem">
    <Column field="finca" header="Finca"></Column>
    <Column field="lote" header="Lote"></Column>
    <Column field="labor" header="Labor"></Column>
    <Column :exportable="false"  header="Record"  style="max-width: 3rem">
                            <!-- Boton Editar -->
                            <template #body="slotProps">
                              
                                <Button
                                    type="button"
                                    @click="
                                        displayRecord($event, slotProps.data)
                                    "
                                    icon="pi pi-id-card"
                                   
                                    :severity="
                                                slotProps.data.infrecord
                                                    ? 'info'
                                                    : 'secondary'
                                            "
                                    rounded
                                > </Button>
                           
                            </template>
                        </Column>
</DataTable>
                           

                              <Popover ref="op">
                        <div
                            class="grid grid-cols-1 gap-2"
                            v-if="datatable_detalle_record.record"
                        >
                            <div>
                                <span class="font-bold"> Record: </span> :
                                {{ datatable_detalle_record.record.Codigo }}
                            </div>
                            <div>
                                <span class="font-medium"> Fecha: </span>:
                                {{ datatable_detalle_record.record.fecha }}
                            </div>
                            <div>
                                {{ datatable_detalle_record.record.finca }} ||
                                {{ datatable_detalle_record.record.lote }}
                            </div>
                            <div>
                                <span class="font-medium"> Diagnostico: </span>
                                {{ datatable_detalle_record.record.diagnostico }}
                            </div>
                            <div>
                                <span class="font-medium">
                                    Observaciones:
                                </span>
                                {{ datatable_detalle_record.record.observaciones }}
                            </div>
                        </div>
                    </Popover>
                        </div>
                    </Dialog>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style lang="css" module>
.mydata tbody tr td {
    padding: 0.1rem 0.375rem;
}
.p-datatable-tbody {
    padding: 0.1rem 0.375rem;
}
</style>
