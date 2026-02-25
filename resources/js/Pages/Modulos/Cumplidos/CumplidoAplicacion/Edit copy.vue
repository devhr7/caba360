<script setup>
import { ref, onMounted, computed, watch } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm, router } from "@inertiajs/vue3";
import { reactive } from "vue";

import { FilterMatchMode } from "@primevue/core/api";
import { useToast } from "primevue/usetoast";

import Edit_Servicios from "./Edit_Servicios.vue";
const toast = useToast();

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Cumplido Aplicacion", url: route("CumplidoAplicacion.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    datos: { type: Object },
    options: { type: Object },
    DataTableProductoCumplidoAplicacion: { type: Object },
    DataTableProductoCumplidoAplicacion_servicios: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    CodigoCumplido: props.datos.CodigoCumplido,
    FechaCumplido: new Date(props.datos.fecha),
    HoraInicio: props.datos.HoraInicio,
    HoraFin: props.datos.HoraFinal,
    distrito_id: props.datos.distrito_id,
    finca: props.datos.finca_id,
    lote: props.datos.lote_id,
    RegLote_id: props.datos.RegLote.id,
    Hectareas: props.datos.HectLote,
    RecordVisita: props.datos.RecordVisita_id,
    CodRecordVisita: props.datos.CodRecordVisita,
    SalidaAlmacen: props.datos.CodSalidaAlmacen,
    ResponsableAplicacion: props.datos.ResponsableAplicacion_id,
    RiesgoLluvia: props.datos.RiesgoLluvia == 1 ? true : false,
    Brisa: props.datos.Brisa == 1 ? true : false,
    HumedadLote: props.datos.HumedadLote == 1 ? true : false,
    Velocidad: props.datos.Velocidad,
    Seguridad: props.datos.Seguridad == 1 ? true : false,
    EnpaquesEntregados: props.datos.EnpaquesEntregados == 1 ? true : false,
    Observaciones: props.datos.Observaciones,
    Coordinador: props.datos.Coordinador,
    JefeCampo: props.datos.JefeCampo,
    labor: props.datos.get_labor,
});

const formAddProduct = useForm({
    CumplidoAplicacion: props.datos.slug,
    TipoProducto: null,
    Producto: null,
    CantxHect: null,
    Total: null,
});

// Constantes
const OptionsFinca = ref(props.options.get_finca); // Datos del Finca
const OptionsLote = ref(props.options.get_lote); // Datos del Lote
const RegLote = ref(false);
const OptionsEmpleado = ref(props.options.get_empleados);
const OptionsLabor = ref(props.options.get_labor);
const DataLote = ref(props.get_lote); // Datos del Finca
const costoHect = ref();
const reg_datatable = ref({});

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    RegistroLotes: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    distrito_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    finca_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

//Adicicionar Producto
function SubmitAddProduct() {
    formAddProduct.post(
        route("CumplidoAplicacionProducto.store", props.datos.slug)
    );
}

// Actualizar
function submitUpdate() {
    // Alerta
    Swal.fire({
        title: "Esta Seguro de Actualizar?",
        text: "Esta Accion es Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Acualizar!",
    }).then((result) => {
        // Confirmacion
        if (result.isConfirmed) {
            // Exitoso
            form.put(route("CumplidoAplicacion.update", props.datos.slug), {
                onSuccess: (page) => {},
                onFinish: (visit) => {
                    // This won't be called until doThing()
                    // and doAnotherThing() have finished.
                    router.reload({ props: true });
                },
            });

            // Mensaje Final
            Swal.fire({
                title: "Alcualizado!",
                text: "Ha sido Actualizado Correctamente.",
                icon: "success",
            });
        }
    });
}

/**
 * Elimina un registro de un producto de un cumplido.
 *
 * @param {object} prod - Objeto con los datos del registro a eliminar.
 */
const submitDelete = () => {
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
            formAddProduct.delete(
                route("CumplidoAplicacion.delete", props.datos.slug)
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

const submitDeleteProducto = (prod) => {
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
            formAddProduct
                .delete(
                    route(
                        "CumplidoAplicacionProducto.delete",
                        reg_datatable.value.id
                    )
                )
                .then();

            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
};

/**
 * Opciones Lote Cambia Segun el Finca
 */
async function getOptionsLote() {
    form.lote = null;
    //OptionsLote.value=null;
    await axios
        .post(route("Lote.getOptionsLote"), form.finca)
        .then(function (response) {
            OptionsLote.value = response.data;
        });
    //.catch((e) => console.log(e));
}

// Carga los Datos de los REgLotes en los campos nombre lote y hect
RegLote.value = props.datos.RegLote;
async function getdataRegLote() {
    await axios
        .post(route("RegLote.getRegLoteActivo"), form.lote)
        .then(function (response) {
            console.error(response.data);
            if (response.data) {
                RegLote.value = response.data;
                form.Hectareas = RegLote.value.Hect;
                form.RegLote_id = RegLote.value.id;
            } else {
                RegLote.value = false;
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "Verificar en Módulo Registro Lote",
                    life: 8000,
                });
            }
        })
        .catch((e) => console.log(e));
}

// Carga las opciones de productos segun el grupo de materia prima seleccionado
async function getdataProducto() {
    await axios
        .post(route("MateriaPrima.getOptionsMateriaPrimaScope"), {
            GrupoMPrima_id: formAddProduct.TipoProducto,
        })
        .then(function (response) {
            props.options.get_MPrima = response.data;
        });
}

watch(
    () => formAddProduct.CantxHect,
    (newVal, oldVal) => {
        if (newVal !== oldVal) {
            formAddProduct.Total = form.Hectareas * formAddProduct.CantxHect;
        }
    }
);

watch(
    () => formAddProduct.Total,
    (newVal, oldVal) => {
        if (newVal !== oldVal) {
            formAddProduct.CantxHect = formAddProduct.Total / form.Hectareas;
        }
    }
);

const formatCurrency = (value) => {
    return value.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
    });
};

const Total = computed(() => {
    let total = 0;
    for (let DataTableProducto of props.DataTableProductoCumplidoAplicacion) {
        total += DataTableProducto.PrecioTotal;
    }

    return formatCurrency(total);
});

function getdataGrupoProducto() {
    formAddProduct.TipoProducto = formAddProduct.Producto.GrupoMPrima;
}

async function searchRecord() {
    // Haz una solicitud AJAX al endpoint que devuelve la información
    await axios
        .post(route("RecordVisita.getrecordinfo"), {
            CodigoRecord: form.RecordVisita,
        })
        .then(function (response) {
            // Obtiene la información de la respuesta
            const recordInfo = response.data;
            console.log(recordInfo.Finca);
            // Asigna la información a las variables correspondientes
            form.finca = recordInfo.Finca;
            form.lote = recordInfo.lote;
            form.RegLote_id = recordInfo.RegLote.id;
            RegLote.value = recordInfo.RegLote;
            form.Hectareas = recordInfo.RegLote.Hect;
            AddProductosPorRecord();
        })
        .catch((e) => console.log(e));
}

async function AddProductosPorRecord() {
    await axios
        .post(route("CumplidoAplicacion.AddProductosPorRecord"), {
            CumplidoAplicacion: props.datos.slug,
            Record: form.RecordVisita,
            labor: form.labor,
        })
        .then(function (response) {
            console.log(response.data);
        })
        .catch((e) => console.log(e));
}
</script>

<template>
    <Head title="Cumplidos Aplicacion" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar - Cumplido Aplicacion
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
                    <form @submit.prevent="submitUpdate">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <!-- Codigo-->
                                    <div class="flex flex-col gap-2">
                                        <label for="CodigoCumplido"
                                            >Codigo Cumplido</label
                                        >
                                        <InputText
                                            id="CodigoCumplido"
                                            name="CodigoCumplido"
                                            v-model="form.CodigoCumplido"
                                            aria-describedby="CodigoCumplido-help"
                                            disabled
                                        />
                                        <small
                                            v-if="errors.CodigoCumplido"
                                            id="CodigoCumplido-help"
                                            class="text-red-700"
                                            >{{ errors.CodigoCumplido }}</small
                                        >
                                    </div>
                                    <!-- Fecha-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="FechaCumplido"
                                                >Fecha</label
                                            >
                                            <DatePicker
                                                v-model="form.FechaCumplido"
                                                showIcon
                                                fluid
                                                iconDisplay="input"
                                                inputId="FechaCumplido"
                                                dateFormat="dd/mm/yy"
                                            />
                                            <small
                                                v-if="errors.FechaCumplido"
                                                id="FechaCumplido-help"
                                                class="text-red-700"
                                                >{{
                                                    errors.FechaCumplido
                                                }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="finca">Finca</label>

                                            <Select
                                                v-model="form.finca"
                                                :options="OptionsFinca"
                                                filter
                                                showClear
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getOptionsLote"
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
                                                v-if="errors.finca"
                                                id="finca-help"
                                                class="text-red-700"
                                                >{{ errors.finca }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="finca">Lote</label>

                                            <Select
                                                v-model="form.lote"
                                                :options="OptionsLote"
                                                filter
                                                showClear
                                                v-on:change="getdataRegLote"
                                                optionLabel="label"
                                                placeholder="Seleccionar"
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
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="RegLote_id"
                                                >Codigo Lote</label
                                            >

                                            <Toast />

                                            <p class="text-base" v-if="RegLote">
                                                {{ RegLote.Codigo }} |
                                                {{ RegLote.Hect }}
                                            </p>

                                            <small
                                                v-if="errors.RegLote_id"
                                                id="RegLote_id-help"
                                                class="text-red-700"
                                                >{{ errors.RegLote_id }}</small
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Fila 2 -->
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <!-- Hectarea-->
                                    <div class="flex flex-col gap-2">
                                        <label for="Hectareas">Hectareas</label>
                                        <InputText
                                            id="Hectareas"
                                            name="Hectareas"
                                            v-model="form.Hectareas"
                                            aria-describedby="Hectareas-help"
                                        />
                                        <small
                                            v-if="errors.Hectareas"
                                            id="Hectareas-help"
                                            class="text-red-700"
                                            >{{ errors.Hectareas }}</small
                                        >
                                    </div>
                                    <!--Hora Inicio-->
                                    <div class="flex flex-col gap-2">
                                        <label for="HoraInicio"
                                            >Hora Inicio</label
                                        >
                                        <InputMask
                                            v-model="form.HoraInicio"
                                            mask="99:99"
                                            placeholder="99:99"
                                            fluid
                                        />
                                    </div>
                                    <!--Hora Fin-->
                                    <div class="flex flex-col gap-2">
                                        <label for="HoraFin">Hora Fin</label>

                                        <InputMask
                                            v-model="form.HoraFin"
                                            mask="99:99"
                                            placeholder="99:99"
                                            fluid
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="RecordVisita"
                                            >Record Visita</label
                                        >
                                        <InputGroup>
                                            <InputText
                                                id="RecordVisita"
                                                name="RecordVisita"
                                                v-model="form.RecordVisita"
                                            />
                                            <Button
                                                icon="pi pi-search"
                                                @click="searchRecord()"
                                            />
                                        </InputGroup>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="SalidaAlmacen"
                                            >Salida Almacen</label
                                        >
                                        <InputText
                                            id="SalidaAlmacen"
                                            name="SalidaAlmacen"
                                            v-model="form.SalidaAlmacen"
                                            aria-describedby="SalidaAlmacen-help"
                                        />
                                    </div>
                                </div>

                                <!-- Fila 3 -->
                                <div class="grid grid-cols-6 gap-4 mt-2">
                                    <div class="flex flex-col gap-2">
                                        <label for="RiesgoLluvia"
                                            >Riesgo lluvia</label
                                        >
                                        <ToggleButton
                                            v-model="form.RiesgoLluvia"
                                            onIcon="pi pi-check"
                                            offIcon="pi pi-times"
                                            class="w-full sm:w-40"
                                            aria-label="Confirmation"
                                            onLabel="Si"
                                            offLabel="No"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="Brisa">Brisa</label>
                                        <ToggleButton
                                            v-model="form.Brisa"
                                            onIcon="pi pi-check"
                                            offIcon="pi pi-times"
                                            class="w-full sm:w-40"
                                            aria-label="Confirmation"
                                            onLabel="Si"
                                            offLabel="No"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="Humedad"
                                            >Humedad Lote</label
                                        >
                                        <ToggleButton
                                            v-model="form.HumedadLote"
                                            onIcon="pi pi-check"
                                            offIcon="pi pi-times"
                                            class="w-full sm:w-40"
                                            aria-label="Confirmation"
                                            onLabel="Si"
                                            offLabel="No"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="Seguridad">Seguridad</label>
                                        <ToggleButton
                                            v-model="form.Seguridad"
                                            onIcon="pi pi-check"
                                            offIcon="pi pi-times"
                                            class="w-full sm:w-40"
                                            aria-label="Confirmation"
                                            onLabel="Si"
                                            offLabel="No"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="Empaques"
                                            >Empaques Entregados</label
                                        >
                                        <ToggleButton
                                            v-model="form.EnpaquesEntregados"
                                            onIcon="pi pi-check"
                                            offIcon="pi pi-times"
                                            class="w-full sm:w-40"
                                            aria-label="Confirmation"
                                            onLabel="Si"
                                            offLabel="No"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="Velocidad">Velocidad</label>
                                        <InputText
                                            id="Velocidad"
                                            name="Velocidad"
                                            v-model="form.Velocidad"
                                            aria-describedby="SalidaAlmacen-help"
                                        />
                                    </div>
                                </div>

                                <!-- Fila 2-->
                                <!-- Fila 2-->
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <div>
                                        <label for="ResponsableAplicacion"
                                            >Responsable Aplicacion</label
                                        >
                                        <Select
                                            v-model="form.ResponsableAplicacion"
                                            :options="OptionsEmpleado"
                                            filter
                                            optionLabel="label"
                                            placeholder="Seleccionar"
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
                                            v-if="errors.ResponsableAplicacion"
                                            id="ResponsableAplicacion-help"
                                            class="text-red-700"
                                            >{{
                                                errors.ResponsableAplicacion
                                            }}</small
                                        >
                                    </div>

                                    <div>
                                        <label for="ResponsableAplicacion">
                                            Tipo Aplicacion</label
                                        >
                                        <Select
                                            v-model="form.labor"
                                            :options="OptionsLabor"
                                            filter
                                            optionLabel="label"
                                            placeholder="Seleccionar"
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
                                            v-if="errors.ResponsableAplicacion"
                                            id="ResponsableAplicacion-help"
                                            class="text-red-700"
                                            >{{
                                                errors.ResponsableAplicacion
                                            }}</small
                                        >
                                    </div>

                                    <div class="col-span-4 gap-2">
                                        <label for="Observaciones"
                                            >Observaciones</label
                                        >

                                        <InputText
                                            id="Observaciones"
                                            name="Observaciones"
                                            v-model="form.Observaciones"
                                            aria-describedby="Observaciones-help"
                                            fluid
                                        />

                                        <small
                                            v-if="errors.Observaciones"
                                            id="Observaciones-help"
                                            class="text-red-700"
                                            >{{ errors.Observaciones }}</small
                                        >
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div class="flex gap-4 mt-1">
                                    <Button
                                        type="submit"
                                        label="Guardar"
                                        class="w-full"
                                        :disabled="form.processing"
                                    />
                                    <Button
                                        type="button"
                                        v-if="
                                            $page.props.Permission.user_permision.includes(
                                                'mod.cump_aplicacion.delete'
                                            )
                                        "
                                        severity="danger"
                                        label="Eliminar Cumplido"
                                        class="w-full"
                                        @click="submitDelete"
                                    />
                                </div>
                            </template>
                        </Card>
                    </form>
                    <Card>
                        <template #content>
                            <Panel header="Adicionar Productos" toggleable>
                                <form @submit.prevent="SubmitAddProduct">
                                    <!-- Fila 1 Adicionar Producto-->
                                    <div class="grid grid-cols-5 gap-4 mt-2">
                                        <!-- Tipo Producto-->
                                        <div class="flex flex-col gap-2">
                                            <label for="TipoProducto"
                                                >Tipo Producto</label
                                            >
                                            <Select
                                                v-model="
                                                    formAddProduct.TipoProducto
                                                "
                                                :options="
                                                    props.options
                                                        .get_GrupoMPrima
                                                "
                                                filter
                                                showClear
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getdataProducto"
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
                                                v-if="errors.TipoProducto"
                                                id="finca-help"
                                                class="text-red-700"
                                                >{{
                                                    errors.TipoProducto
                                                }}</small
                                            >
                                        </div>
                                        <!--- Columna 2-->
                                        <div class="flex flex-col gap-2">
                                            <label for="Producto"
                                                >Producto</label
                                            >
                                            <Select
                                                v-model="
                                                    formAddProduct.Producto
                                                "
                                                v-on:change="
                                                    getdataGrupoProducto
                                                "
                                                :options="
                                                    props.options.get_MPrima
                                                "
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
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
                                                v-if="errors.Producto"
                                                id="finca-help"
                                                class="text-red-700"
                                                >{{ errors.Producto }}</small
                                            >
                                        </div>
                                        <!-- Columna 3-->
                                        <div class="flex flex-col gap-2">
                                            <label for="CantxHect"
                                                >Cantidad Por Hect</label
                                            >
                                            <InputText
                                                id="CantxHect"
                                                name="CantxHect"
                                                v-model="
                                                    formAddProduct.CantxHect
                                                "
                                                aria-describedby="CantxHect-help"
                                            />
                                            <small
                                                v-if="errors.CantxHect"
                                                id="CantxHect-help"
                                                class="text-red-700"
                                                >{{ errors.CantxHect }}</small
                                            >
                                        </div>

                                        <!-- Columna 4-->
                                        <div class="flex flex-col gap-2">
                                            <label for="CantTotal"
                                                >Cantidad Total</label
                                            >
                                            <InputText
                                                id="CantTotal"
                                                name="CantTotal"
                                                v-model="formAddProduct.Total"
                                                aria-describedby="CantTotal-help"
                                            />
                                        </div>
                                        <!-- Columna 5-->

                                        <!-- Columna 5-->
                                        <div class="flex flex-col gap-2">
                                            <Button
                                                type="submit"
                                                label="Adicionar Producto"
                                                class="w-full"
                                                :disabled="
                                                    formAddProduct.processing
                                                "
                                                severity="info"
                                            />
                                        </div>
                                    </div>
                                </form>
                            </Panel>
                        </template>
                    </Card>
                    <Card>
                        <template #title>
                            <div>Productos</div>
                        </template>
                        <template #content>
                            <div>
                                {{
                                    props.datos
                                        .DataTableProductoCumplidoAplicacion
                                }}
                                <DataTable
                                    v-model:filters="filters"
                                    :value="DataTableProductoCumplidoAplicacion"
                                    :size="{ label: 'Small', value: 'small' }"
                                    :globalFilterFields="[
                                        'GrupoMateriaPrima',
                                        'NombreProducto',
                                    ]"
                                    tableStyle="min-width: 50rem"
                                >
                                    <Column
                                        field="GrupoMateriaPrima"
                                        header="Grupo"
                                    ></Column>
                                    <Column
                                        field="NombreProducto"
                                        header="Producto"
                                    ></Column>
                                    <Column
                                        field="PrecioUnit"
                                        header="Precio Unit"
                                    ></Column>
                                    <Column
                                        field="Dosis_por_Ha"
                                        header="Dosis xHa"
                                    ></Column>
                                    <Column
                                        field="cantidad_Total"
                                        header="cantidad_Total"
                                    ></Column>
                                    <Column
                                        field="PrecioTotalFormat"
                                        header="Total"
                                    ></Column>
                                    <Column
                                        :exportable="false"
                                        style="min-width: 12rem"
                                    >
                                        <!-- Boton Editar -->
                                        <template #body="slotProps">
                                            <Button
                                                icon="pi pi-trash"
                                                outlined
                                                rounded
                                                class="mr-2"
                                                @click="
                                                    submitDeleteProducto(
                                                        slotProps.data
                                                    )
                                                "
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
                                            <Column :footer="Total" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </div>
                        </template>
                    </Card>

                    <hr />

                    <Edit_Servicios
                        :datatable="
                            DataTableProductoCumplidoAplicacion_servicios
                        "
                        :CumplidoAplicacion="props.datos.id"
                    >
                    </Edit_Servicios>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
