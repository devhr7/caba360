<script setup>
import { ref, onMounted, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import ppt from "./ppt.vue";

const toast = useToast();

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

/**
 * Definiendo Props
 */
const props = defineProps({
    options: { type: Object },
    datos: { type: Object },
    datatable: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    codigo: props.datos.codigo,
    fecha: props.datos.fecha,
    contratista: props.datos.contratista,
    autorizado: props.datos.autorizado,
    
});

const formDetalle = useForm({
    id: null,
    claseCentroCosto: null,
    finca: null,
    lote: null,
    RegLote_id: null,
    destino: null,
    labor: null,
    DetalleLabor: null,
    cantidad: 0,
    unidadMedida: null,
    PrecioUnit: 0,
    total: 0,
    observaciones: null,
    record : null,
});

const AddServicioDialog = ref(false);

// Options
const OptionsContratista = ref(props.options.optionsContratista); // Datos del Finca
const OptionsAutorizado = ref(props.options.optionsEmpleado); // Datos del Lote
const OptionsFinca = ref(props.options.optionsFinca); // Datos del Lote
const OptionsLote = ref(props.options.optionsLote); // Datos del Lote
const OptionsLabor = ref(props.options.optionsLabor); // Datos del Lote
const OptionsUnidadMedida = ref(props.options.optionsUnidadMedida); // Datos del Lote
const OptionsClaseCentroCosto = ref(props.options.optionsTipoCentroCosto);
const reg_datatable = ref({});

// Datos del registro lote
const RegLote = ref(false);
//Crear
function submitUpdate() {
    form.put(route("CumplidoOrdenServicio.update", props.datos.slug), form);
}

function submitDelete() {
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
                route("CumplidoOrdenServicio.delete", props.datos.slug),
                form
            );
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}

function submitAddDetalle() {
    if (formDetalle.id) {
        formDetalle.put(
            route("CumplidoOrdenServicioDetalle.update", formDetalle.id),
            formDetalle
        );
    } else {
        formDetalle.put(
            route("CumplidoOrdenServicioDetalle.store", props.datos.slug),
            formDetalle
        );
    }

    AddServicioDialog.value = false;
    formDetalle.reset();
    RegLote.value = false;
}

const submitDeleteDetalle = (prod) => {
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
                route(
                    "CumplidoOrdenServicioDetalle.delete",
                    reg_datatable.value.id
                ),
                form
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

const openNew = (prod) => {
    AddServicioDialog.value = true;
    formDetalle.id = null;
    formDetalle.reset();
    RegLote.value = false;
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    if (prod) {
        formDetalle.id = reg_datatable.value.id;
        formDetalle.claseCentroCosto =
            reg_datatable.value.Options_tipo_centro_costo;
        formDetalle.finca = reg_datatable.value.Optionsfinca;
        formDetalle.lote = reg_datatable.value.Optionslote;

        formDetalle.RegLote_id = reg_datatable.RegLote_id;
        formDetalle.destino = reg_datatable.value.destino;

        formDetalle.labor = reg_datatable.value.Optionslabor;

        formDetalle.DetalleLabor = reg_datatable.value.DetalleLabor;
        formDetalle.cantidad = reg_datatable.value.cantidad;
        formDetalle.unidadMedida = reg_datatable.value.Options_unidad_medida;
        formDetalle.PrecioUnit = reg_datatable.value.PrecioUnitario;
        formDetalle.total = reg_datatable.value.Total;
        formDetalle.observaciones = reg_datatable.value.Observaciones;
        formDetalle.record = reg_datatable.value.record;

        getdataRegLote();
    }
};

// Valor Total
const Total = computed(() => {
    formDetalle.cantidad = formDetalle.cantidad ?? 0;
    formDetalle.total = formDetalle.PrecioUnit * formDetalle.cantidad;
    return new Intl.NumberFormat("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(formDetalle.PrecioUnit * formDetalle.cantidad);
});

function PrecioUnitporLabor() {
    if (formDetalle.labor === null) {
    } else {
        formDetalle.PrecioUnit = formDetalle.labor.CostoHect ?? 0;
    }
    formDetalle.total = Total.value;
}

async function getOptionsLote() {
    formDetalle.lote = null;
    formDetalle.RegLote_id = null;
    RegLote.value = false;

    // Carga los datos de los Lotes
    await axios
        .post(route("Lote.getOptionsLote"), formDetalle.finca)
        .then(function (response) {
            OptionsLote.value = response.data;
        });
    //.catch((e) => console.log(e));
}

async function getdataRegLote() {
    // Carga los datos de los Lotes
    formDetalle.RegLote_id = null;

    await axios
        .post(route("RegLote.getRegLoteActivo"), formDetalle.lote)
        .then(function (response) {
            if (response.data) {
                RegLote.value = response.data;

                formDetalle.RegLote_id = RegLote.value.id;

                // form.FechaInicio = RegLote.value.FechaInicio;
            } else {
                RegLote.value = false;
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "No hay lotes Activos, Verificar en Módulo Registro Lote",
                    life: 8000,
                });
            }
        })
        .catch((e) => console.log(e));
}

const TotalOrdenServicio = computed(() => {
    let total = 0;
    for (let row of props.datatable) {
        total += row.Total;
    }

    return total.toLocaleString("es-ES");
});

const visible_ppt = ref(false);

const openPPT = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila

    formDetalle.RegLote_id = reg_datatable.value.RegLote_id;

    if (formDetalle.RegLote_id) {
        visible_ppt.value = true;
    } else {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "No hay Registros de Lote, Verificar en Módulo Registro Lote",
            life: 8000,
        });
    }
};

const DataRecord = ref(props.options.getRecord); // Datos del Distrito
const FilteredRecord = ref();  // Constante para Filto
// Buscado de Auto completar
const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            FilteredRecord.value = [...DataRecord.value];
        } else {
            FilteredRecord.value = DataRecord.value.filter((reg) => {
                return reg.Codigo
                    .toLowerCase()
                    .startsWith(event.query.toLowerCase());
            });
        }
    }, 250);
};
</script>

<template>
    <Head title="Cumplidos Orden Servicio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar -
                <a
                    :href="route('CumplidoOrdenServicio.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Orden de Servicio</a
                >
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <form @submit.prevent="submitUpdate">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols-4 gap-4 mt-2">
                                    <!-- Codigo-->
                                    <div class="flex flex-col gap-2">
                                        <label for="codigo"
                                            >Codigo Cumplido</label
                                        >
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
                                    <!-- Fecha-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="FechaCumplido"
                                                >Fecha</label
                                            >
                                            <DatePicker
                                                v-model="form.fecha"
                                                showIcon
                                                fluid
                                                iconDisplay="input"
                                                inputId="fecha"
                                                dateFormat="dd/mm/yy"
                                            />
                                            <small
                                                v-if="errors.fecha"
                                                id="fecha-help"
                                                class="text-red-700"
                                                >{{ errors.fecha }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="contratista"
                                                >Contratista</label
                                            >

                                            <Select
                                                v-model="form.contratista"
                                                :options="OptionsContratista"
                                                filter
                                                fluid
                                                showClear
                                                optionLabel="label"
                                                placeholder="Seleccionar"
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
                                                        {{
                                                            slotProps.placeholder
                                                        }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div
                                                        class="flex items-center"
                                                    >
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
                                                v-if="errors.contratista"
                                                id="contratista-help"
                                                class="text-red-700"
                                                >{{ errors.contratista }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="finca"
                                                >Autorizado</label
                                            >

                                            <Select
                                                v-model="form.autorizado"
                                                :options="OptionsAutorizado"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                fluid
                                                showClear
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
                                                        {{
                                                            slotProps.placeholder
                                                        }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div
                                                        class="flex items-center"
                                                    >
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
                                                v-if="errors.lote"
                                                id="lote-help"
                                                class="text-red-700"
                                                >{{ errors.lote }}</small
                                            >
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div class="flex gap-4 mt-1">
                                    <Button
                                        type="submit"
                                        label="Actualizar"
                                        class="w-full"
                                        :disabled="form.processing"
                                    />
                                    <Button
                                        type="button"
                                        severity="danger"
                                        label="Eliminar"
                                        @click="submitDelete()"
                                        class="w-full"
                                        :disabled="form.processing"
                                    />
                                </div>
                            </template>
                        </Card>
                    </form>
                </div>

                <!-- Detalle Cumplido Aplicacion-->

                <!-- Formulario-->

                <Card
                    style="overflow: hidden"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4"
                >
                    <template #content>
                        <div class="grid grid-col gap-4 mt-2"></div>
                        <!-- Crear-->

                        <!-- Tabla-->
                        <div class="grid grid-col gap-4 mt-2">
                            <DataTable
                                v-model:filters="filters"
                                :value="datatable"
                                showGridlines
                                paginator
                                :rows="10"
                                :rowsPerPageOptions="[5, 10, 20, 50]"
                                tableStyle="min-width: 50rem"
                                filterDisplay="row"
                                :loading="loading"
                                :globalFilterFields="['codigo', 'labor']"
                            >
                                <template #header>
                                    <div
                                        class="flex flex-wrap gap-2 items-center justify-between"
                                    >
                                        <!-- Boton de Crear -->
                                        <Button
                                            label="Añadir"
                                            icon="pi pi-plus"
                                            class="mr-2"
                                            @click="openNew(false)"
                                        />
                                        <IconField>
                                            <InputIcon>
                                                <i class="pi pi-search" />
                                            </InputIcon>
                                            <InputText
                                                v-model="
                                                    filters['global'].value
                                                "
                                                placeholder="Buscar..."
                                            />
                                        </IconField>
                                    </div>
                                </template>

                                <Column
                                    field="tipo_centro_costo"
                                    sortable
                                    header="CC"
                                ></Column>
                                <Column
                                    field="finca"
                                    sortable
                                    header="Finca"
                                ></Column>
                                <Column
                                    field="lote"
                                    sortable
                                    header="Lote"
                                ></Column>
                                <Column
                                    field="destino"
                                    sortable
                                    header="Destino"
                                ></Column>
                                <Column
                                    field="labor"
                                    sortable
                                    header="Labor"
                                ></Column>
                                <Column
                                    field="DetalleLabor"
                                    sortable
                                    header="Detalle"
                                ></Column>
                                <Column
                                    field="cantidad"
                                    sortable
                                    header="Cant"
                                ></Column>
                                <Column
                                    field="unidad_medida"
                                    sortable
                                    header="Unidad"
                                ></Column>
                                <Column
                                    field="FormatPrecioUnitario"
                                    sortable
                                    header="Precio Unit"
                                ></Column>
                                <Column
                                    field="FormatTotal"
                                    sortable
                                    header="Total"
                                ></Column>

                                <!-- Columna de Acciones-->

                                <Column
                                    :exportable="false"
                                    style="min-width: 12rem"
                                >
                                    <!-- Boton Editar -->
                                    <template #body="slotProps">
                                        <Button
                                            icon="pi pi-eye"
                                            outlined
                                            rounded
                                            severity="info"
                                            class="mr-2"
                                            @click="openPPT(slotProps.data)"
                                        />
                                        <Button
                                            icon="pi pi-pencil"
                                            outlined
                                            rounded
                                            class="mr-2"
                                            @click="openNew(slotProps.data)"
                                        />
                                        <Button
                                            icon="pi pi-trash"
                                            outlined
                                            rounded
                                            class="mr-2"
                                            @click="
                                                submitDeleteDetalle(
                                                    slotProps.data
                                                )
                                            "
                                        />
                                    </template>
                                </Column>
                                <ColumnGroup type="footer">
                                    <Row>
                                        <Column
                                            footer="Total:"
                                            :colspan="9"
                                            footerStyle="text-align:right"
                                        />
                                        <Column :footer="TotalOrdenServicio" />
                                    </Row>
                                </ColumnGroup>
                            </DataTable>
                        </div>
                        <!--Modal-->
                        <div class="grid grid-col gap-4 mt-2">
                            <Dialog
                                v-model:visible="AddServicioDialog"
                                :style="{ width: '50vw' }"
                                :breakpoints="{
                                    '1199px': '75vw',
                                    '575px': '90vw',
                                }"
                                header="Añadir Servicio"
                                :modal="true"
                            >
                                <form @submit.prevent="submitAddDetalle">
                                    <div class="flex flex-col gap-6">
                                        <div class="grid grid-cols-4 gap-4">
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Tipo Centro Costo</label
                                                >
                                                <Select
                                                    v-model="
                                                        formDetalle.claseCentroCosto
                                                    "
                                                    :options="
                                                        OptionsClaseCentroCosto
                                                    "
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    showClear
                                                    class="w-full"
                                                    fluid
                                                >
                                                    <template
                                                        #value="slotProps"
                                                    >
                                                        <div
                                                            v-if="
                                                                slotProps.value
                                                            "
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .value
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                        <span v-else>
                                                            <!-- Mostrar el placeholder-->
                                                            {{
                                                                slotProps.placeholder
                                                            }}
                                                        </span>
                                                    </template>
                                                    <template
                                                        #option="slotProps"
                                                    >
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .option
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                    </template>
                                                </Select>
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Finca</label
                                                >
                                                <Select
                                                    v-model="formDetalle.finca"
                                                    :options="OptionsFinca"
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    showClear
                                                    class="w-full"
                                                    fluid
                                                    v-on:change="getOptionsLote"
                                                >
                                                    <template
                                                        #value="slotProps"
                                                    >
                                                        <div
                                                            v-if="
                                                                slotProps.value
                                                            "
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .value
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                        <span v-else>
                                                            <!-- Mostrar el placeholder-->
                                                            {{
                                                                slotProps.placeholder
                                                            }}
                                                        </span>
                                                    </template>
                                                    <template
                                                        #option="slotProps"
                                                    >
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .option
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                    </template>
                                                </Select>
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Lote</label
                                                >
                                                <Select
                                                    v-model="formDetalle.lote"
                                                    :options="OptionsLote"
                                                    v-on:change="getdataRegLote"
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    showClear
                                                    class="w-full"
                                                    fluid
                                                >
                                                    <template
                                                        #value="slotProps"
                                                    >
                                                        <div
                                                            v-if="
                                                                slotProps.value
                                                            "
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .value
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                        <span v-else>
                                                            <!-- Mostrar el placeholder-->
                                                            {{
                                                                slotProps.placeholder
                                                            }}
                                                        </span>
                                                    </template>
                                                    <template
                                                        #option="slotProps"
                                                    >
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .option
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                    </template>
                                                </Select>
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Record</label
                                                >
                                        
                                                <AutoComplete
                                        v-model="formDetalle.record"
                                        :suggestions="FilteredRecord"
                                        optionLabel="Codigo"
                                        dataKey="id"
                                        @complete="search"
                                        dropdown
                                        
                                    />
                                               
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Reg Lote</label
                                                >
                                                <Toast />
                                                <Fluid>
                                                    <Message
                                                        severity="info"
                                                        variant="outlined"
                                                    >
                                                        <p
                                                            class="text-base"
                                                            v-if="RegLote"
                                                        >
                                                            {{ RegLote.Codigo }}
                                                            |
                                                            {{ RegLote.Hect }}
                                                        </p>
                                                    </Message>
                                                </Fluid>
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Destino</label
                                                >
                                                <InputText
                                                    name="destino"
                                                    v-model="
                                                        formDetalle.destino
                                                    "
                                                    type="text"
                                                    fluid
                                                />
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Labor</label
                                                >
                                                <Select
                                                    v-model="formDetalle.labor"
                                                    :options="OptionsLabor"
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    showClear
                                                    class="w-full"
                                                    fluid
                                                    v-on:change="
                                                        PrecioUnitporLabor()
                                                    "
                                                >
                                                    <template
                                                        #value="slotProps"
                                                    >
                                                        <div
                                                            v-if="
                                                                slotProps.value
                                                            "
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .value
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                        <span v-else>
                                                            <!-- Mostrar el placeholder-->
                                                            {{
                                                                slotProps.placeholder
                                                            }}
                                                        </span>
                                                    </template>
                                                    <template
                                                        #option="slotProps"
                                                    >
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .option
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                    </template>
                                                </Select>
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Detalle Labor</label
                                                >
                                                <InputText
                                                    name="DetalleLabor"
                                                    type="text"
                                                    v-model="
                                                        formDetalle.DetalleLabor
                                                    "
                                                    fluid
                                                />
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-4 gap-4">
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Cantidad</label
                                                >
                                                <InputText
                                                    name="Cantidad"
                                                    type="text"
                                                    v-model="
                                                        formDetalle.cantidad
                                                    "
                                                    fluid
                                                />
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Unidad Medida</label
                                                >
                                                <Select
                                                    v-model="
                                                        formDetalle.unidadMedida
                                                    "
                                                    :options="
                                                        OptionsUnidadMedida
                                                    "
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    showClear
                                                    class="w-full"
                                                    fluid
                                                >
                                                    <template
                                                        #value="slotProps"
                                                    >
                                                        <div
                                                            v-if="
                                                                slotProps.value
                                                            "
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .value
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                        <span v-else>
                                                            <!-- Mostrar el placeholder-->
                                                            {{
                                                                slotProps.placeholder
                                                            }}
                                                        </span>
                                                    </template>
                                                    <template
                                                        #option="slotProps"
                                                    >
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <div>
                                                                <!-- Mostrar el nombre de la finca-->
                                                                {{
                                                                    slotProps
                                                                        .option
                                                                        .label
                                                                }}
                                                            </div>
                                                        </div>
                                                    </template>
                                                </Select>
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Precio Unitario</label
                                                >
                                                <InputText
                                                    name=""
                                                    type="text"
                                                    v-model="
                                                        formDetalle.PrecioUnit
                                                    "
                                                    fluid
                                                />
                                            </div>
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Total</label
                                                >
                                                <Message severity="secondary">
                                                    {{ Total }}
                                                </Message>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label
                                                    for=""
                                                    class="block font-bold mb-3"
                                                    >Observaciones</label
                                                >
                                                <InputText
                                                    name=""
                                                    v-model="
                                                        formDetalle.observaciones
                                                    "
                                                    type="text"
                                                    fluid
                                                />
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="flex gap-4 mt-1">
                                                <Button
                                                    type="submit"
                                                    label="Actualizar"
                                                    class="w-full"
                                                    :disabled="form.processing"
                                                />
                                                <Button
                                                    type="button"
                                                    severity="danger"
                                                    label="Cancelar"
                                                    class="w-full"
                                                    :disabled="form.processing"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </Dialog>

                            <Dialog
                                v-model:visible="visible_ppt"
                                modal
                                header="Presupuesto"
                                :style="{ width: '100rem' }"
                            >
                                <ppt :Reglote="formDetalle.RegLote_id"></ppt>
                            </Dialog>
                        </div>
                    </template>
                    <!-- Botones Finales-->
                    <template #footer>
                        <!-- Boton Guardar-->
                        <div class="flex gap-4 mt-1"></div>
                    </template>
                </Card>

                <Divider></Divider>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
