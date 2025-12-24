<script setup>
import { ref, onMounted, computed, nextTick } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

import { FilterMatchMode } from "@primevue/core/api";
const props = defineProps(["cumplidoAplicacion"]);
const reg_datatable = ref({});

import { useToast } from "primevue/usetoast";
const toast = useToast();
const size = ref({ label: "Small", value: "small" });

// Constante de los Filtros de la tabla
const filters = ref();

//  Funcion para Filtros
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        codigo: { value: null, matchMode: FilterMatchMode.CONTAINS },
        finca: { value: null, matchMode: FilterMatchMode.CONTAINS },
        lote: { value: null, matchMode: FilterMatchMode.CONTAINS },
        fincalote: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        ResponsableAplicacion_nombre: {
            value: null,
            matchMode: FilterMatchMode.CONTAINS,
        },
        Aplicacion: { value: null, matchMode: FilterMatchMode.CONTAINS },
        verificado: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        codigo_lote: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        factura: { value: null, matchMode: FilterMatchMode.CONTAINS },
    };
};

initFilters(); // Ejecutar Filtros

// Funcion para Limpiar Filtros
const clearFilter = () => {
    initFilters();
};

const formatCurrency = (value) => {
    return value.toLocaleString("es-ES");
};
const visible = ref(false);
const ObsCumpApliDialog = ref(false);
// Funcion del Boton Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.edit_url); // redirecciona a la vista Editar
};

// boton de Crear
function crear() {
    router.get(route("CumplidoAplicacion.crear")); // Redirecciona a la Vista Crear
}

const selectedCumplidos = ref(null);

const form = useForm({
    cumplidoAplicacion: "",
    selectedCumplidos: null,
    factura: "",
});

const AbrirModal = (prod) => {
    reg_datatable.value = { ...prod };

    form.cumplidoAplicacion = reg_datatable.value.id;
    form.selectedCumplidos = selectedCumplidos.value;
    form.factura = reg_datatable.value.factura;

    visible.value = true;
};
const ObsCumplido = ref(null);
const AbrirModalObs = (prod) => {
    reg_datatable.value = { ...prod };
    if (reg_datatable.value.observaciones == null) {
        ObsCumplido.value = "No tiene Observaciones";
    } else {
        ObsCumplido.value = reg_datatable.value.observaciones;
    }
    ObsCumpApliDialog.value = true;
};

function verificar() {
    visible.value = false;

    form.post(route("CumplidoAplicacion.verificar"), {
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

const thisTotalSeleccionados = computed(() => {
    let total = 0;
    if (selectedCumplidos.value == null) {
        return 0;
    }
    for (let selectedCumplido of selectedCumplidos.value) {
        total += selectedCumplido.Total;
    }

    return formatCurrency(total);
});

const op = ref();

const displayRecord = (event, prod) => {
    op.value.hide();
    reg_datatable.value = { ...prod };
    nextTick(() => {
        op.value.show(event);
    });
};

const hidePopover = () => {
    op.value.hide();
};
</script>

<template>
    <Head title="CumpAplicacion" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar -
                <a
                    :href="route('CumplidoAplicacion.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Cumplido Aplicacion</a
                >
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        v-model:filters="filters"
                        v-model:selection="selectedCumplidos"
                        :value="cumplidoAplicacion"
                        showGridlines
                        paginator
                        :rows="10"
                        :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                        tableStyle="min-width: 50rem"
                        filterDisplay="menu"
                        :loading="loading"
                        :globalFilterFields="[
                            'codigo',
                            'finca',
                            'lote',
                            'codigo_lote',
                            'fincalote',
                            'ResponsableAplicacion_nombre',
                            'Aplicacion',
                            'verificado',
                            'factura',
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
                            header="Finca"
                            field="finca"
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.finca }}
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
                            header="Lote"
                            field="lote"
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.lote }}
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
                            header="CodLote"
                            field="codigo_lote"
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.codigo_lote }}
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
                            header="Contratista"
                            field="ResponsableAplicacion_nombre"
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.ResponsableAplicacion_nombre }}
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
                            header="Servicio"
                            field="Aplicacion"
                            sortable
                            :filterMenuStyle="{ width: '14rem' }"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.Aplicacion }}
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
                            field="PrecioUnitFormat"
                            sortable
                            header="Precio Unit"
                        ></Column>
                        <Column
                            field="cantidad"
                            sortable
                            header="Cant"
                        ></Column>
                        <Column
                            field="TotalFormat"
                            sortable
                            header="Total"
                        ></Column>
                        <!-- Columna de Acciones-->
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
                                    <div>
                                        <Button
                                            type="button"
                                            @click="AbrirModalObs(data)"
                                            icon="pi pi-search"
                                            :severity="
                                                data.observaciones
                                                    ? 'info'
                                                    : 'secondary'
                                            "
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

                        <Column :exportable="false" style="max-width: 3rem">
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

                        <ColumnGroup type="footer">
                            <Row>
                                <Column
                                    footer="Totals:"
                                    :colspan="10"
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
                        header="Verificar Cumplido Aplicacion"
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
                        :style="{ width: '450px' }"
                        header="Observaciones"
                        :modal="true"
                    >
                        <div class="flex items-center gap-4">
                            <span> {{ ObsCumplido }} </span>
                        </div>
                    </Dialog>

                    <Popover ref="op">
                        <div
                            class="grid grid-cols-1 gap-2"
                            v-if="reg_datatable.record"
                        >
                            <div>
                                <span class="font-bold"> Record: </span> :
                                {{ reg_datatable.record.Codigo }}
                            </div>
                            <div>
                                <span class="font-medium"> Fecha: </span>:
                                {{ reg_datatable.record.fecha }}
                            </div>
                            <div>
                                {{ reg_datatable.record.finca }} ||
                                {{ reg_datatable.record.lote }}
                            </div>
                            <div>
                                <span class="font-medium"> Diagnostico: </span>
                                {{ reg_datatable.record.diagnostico }}
                            </div>
                            <div>
                                <span class="font-medium">
                                    Observaciones:
                                </span>
                                {{ reg_datatable.record.observaciones }}
                            </div>
                        </div>
                    </Popover>

                    <Toast />
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
