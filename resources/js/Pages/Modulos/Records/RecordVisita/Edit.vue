<script setup>
import { ref, onMounted, computed, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";
import Card from "primevue/card";
import ppt from "./ppt.vue";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Record Visita", url: route("RecordVisita.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    //
    datos: { type: Object },
    // Datos para los select
    get_finca: { type: Object }, // Finca
    get_lote: { type: Object }, // Lote
    get_GrupoMateriaPrima: { type: Object },
    //get_MateriaPrima: { type: Object },
    get_MateriaPrima: { type: Object },
    //
    DatosProductoVisita: { type: Object },
    // Errores
    errors: { type: Object },
});

//  Definiendo el Formulario
const form = useForm({
    CodigoRecord: props.datos.Codigo,
    FechaRecord: props.datos.fecha,
    finca: props.datos.Finca,
    lote: props.datos.lote,
    RegLote_id: props.datos.RegLote.id,
    Hectareas: props.datos.hect_aplicacion,
    Diagnostico: props.datos.diagnostico,
    DiaGerminacion: props.datos.dias_emergencia,
    Observaciones: props.datos.observaciones,
    AgricultorEncargado: props.datos.AgricultorEncargado,
    IngenieroAgronomo: props.datos.IngenieroAgronomo,
    TipoProducto: null,
    Producto: null,
    CantxHect: null,
    Total: null,
});

const formAddProduct = useForm({
    TipoProducto: null,
    Producto: null,
    CantxHect: null,
    Total: null,
    FechaAplicacion: null,
});

// Definiendo las Constantes
const OptionsFinca = ref(props.get_finca); // Datos del Finca
const OptionsLote = ref(props.get_lote); // Datos del Lote
const OptionsRegLote = ref([]);
const OptionsEmpleado = ref(props.get_empleado);
const DataLote = ref(props.get_lote); // Datos del Finca
const RegistroLotes = ref([]); // Datos del Finca
const reg_datatable = ref({});
const RegLote = ref(props.datos.RegLote);
const OptionsTipoProducto = ref(props.get_GrupoMateriaPrima);
const OptionsProducto = ref(props.get_MateriaPrima);
const costoHect = ref();

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    RegistroLotes: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    distrito_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    finca_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

//Adicicionar Producto
function SubmitAddProduct() {
    formAddProduct.post(props.datos.UrlAddProduct).then(function (resoonse) {
        formAddProduct.reset();
    });
}

function calcularDiasGerminacion() {
    console.log("calcular los Dias de Germinacion");
    if (form.FechaRecord && RegLote.value.FechaGerminacion) {
        const fechaRecord = new Date(form.FechaRecord);
        const fechaGerminacion = new Date(RegLote.value.FechaGerminacion);
        const diffTime = Math.abs(fechaRecord - fechaGerminacion);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        form.DiaGerminacion = diffDays;
    } else {
        form.DiaGerminacion = 0;
    }
}

watch(
    () => form.FechaRecord,
    () => calcularDiasGerminacion()
);
/**
 * Opciones Lote Cambia Segun el Finca
 */
async function getOptionsLote() {
    await axios
        .post(route("Lote.getOptionsLote"), form.finca)
        .then(function (response) {
            OptionsLote.value = response.data;
        });
    //.catch((e) => console.log(e));
}
async function getdataRegLote() {
    // Carga los datos de los Lotes
    form.RegLote_id = null;
    form.Hectareas = null;
    form.Cant = 0;

    await axios
        .post(route("RegLote.getRegLoteActivo"), form.lote)
        .then(function (response) {
            if (response.data) {
                RegLote.value = response.data;
                form.Hectareas = RegLote.value.Hect;
                form.RegLote_id = RegLote.value.id;
                form.Cant = RegLote.value.Hect;
                form.FechaInicio = RegLote.value.FechaInicio;
                calcularDiasGerminacion();
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

/**
 * Consulta los productos que se pueden aplicar segun el grupo de materia prima
 * y el tipo de producto seleccionado
 *
 * @param {Number} GrupoMPrima_id El id del grupo de materia prima
 */

async function getdataProducto() {
    await axios
        .post(route("MateriaPrima.getOptionsMateriaPrimaScope"), {
            GrupoMPrima_id: formAddProduct.TipoProducto,
        })
        .then(function (response) {
            OptionsProducto.value = response.data;
            console.log(OptionsProducto.value);
        });
}

function getdataGrupoProducto() {
    formAddProduct.TipoProducto = formAddProduct.Producto.GrupoMPrima;
}

function resetformAddProduct() {
    formAddProduct.reset();
}

const valorTotal = computed(() => {
    const cantidad = form.Hectareas * formAddProduct.CantxHect;
    formAddProduct.Total = form.Hectareas * formAddProduct.CantxHect;
    return new Intl.NumberFormat("es-ES", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
    }).format(cantidad);
});

// Eliminar

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
            form.put(route("RecordVisita.update", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Alcualizado!",
                text: "Ha sido Actualizado Correctamente.",
                icon: "success",
            });
        }
    });
}

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
            formAddProduct.delete(
                route("RecordVisita.Producto.delete", reg_datatable.value.id),
                formAddProduct
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

function submitDelete() {
    // Alerta
    Swal.fire({
        title: "Esta Seguro de Eliminar?",
        text: "Esta Accion es Irreversible, Los productos y el Record se Eliminaran.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        // Confirmacion
        if (result.isConfirmed) {
            // Exitoso
            form.delete(route("RecordVisita.delete", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}
const confirm = useConfirm();
const toast = useToast();
const requireConfirmation = (event) => {
    confirm.require({
        target: event.currentTarget,
        group: 'headless',
        message: 'Estas Seguro de Duplicar este record?',
        accept: () => {
            axios
                .post(route("RecordVisita.duplicar", props.datos.slug))
                .then(function (response) {
                    router.visit(route("RecordVisita.edit", response.data));
                    toast.add({severity:'info', summary:'Confirmed', detail:'Duplicar Record', life: 3000});
                })
                .catch(function (error) {
                    toast.add({severity:'error', summary:'Error', detail:'Error Al Duplicar', life: 3000});
                    console.error(error);
                });
        },
        reject: () => {
            toast.add({severity:'error', summary:'Rejected', detail:'Error Al Duplicar', life: 3000});
        }
    });
}
</script>

<template>
    <Head title="Record Visita" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar - Record Visita
            </h2>
        </template>
        <!-- Menu 2-->

        <div class="py-12">
            <div class=" mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <form @submit.prevent="submitUpdate">
                        <Card style="overflow: hidden">
                             <template #header>
 
<Toolbar>


    <template #end> <Button icon="pi pi-clone" @click="requireConfirmation($event)" aria-label="Duplicar" /> 
        <Toast />
    <ConfirmPopup group="headless">
        <template #container="{ message, acceptCallback, rejectCallback }">
            <div class="rounded p-4">
                <span>{{ message.message }}</span>
                <div class="flex items-center gap-2 mt-4">
                    <Button label="Si" @click="acceptCallback" size="small"></Button>
                    <Button label="No" outlined @click="rejectCallback" severity="secondary" size="small" text></Button>
                </div>
            </div>
        </template>
    </ConfirmPopup>
</template>
</Toolbar>
    </template>
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <!-- Codigo-->
                                    <div class="flex flex-col gap-2">
                                        <label for="CodigoRecord"
                                            >Codigo Record</label
                                        >
                                        <InputText
                                            id="CodigoRecord"
                                            name="CodigoRecord"
                                            v-model="form.CodigoRecord"
                                            aria-describedby="CodigoRecord-help"
                                        />
                                        <small
                                            v-if="errors.CodigoRecord"
                                            id="CodigoRecord-help"
                                            class="text-red-700"
                                            >{{ errors.CodigoRecord }}</small
                                        >
                                    </div>
                                    <!-- Fecha-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="FechaRecord"
                                                >Fecha</label
                                            >
                                            <DatePicker
                                                v-model="form.FechaRecord"
                                                showIcon
                                                fluid
                                                iconDisplay="input"
                                                inputId="FechaRecord"
                                                dateFormat="dd/mm/yy"
                                            />
                                            <small
                                                v-if="errors.FechaRecord"
                                                id="FechaRecord-help"
                                                class="text-red-700"
                                                >{{ errors.FechaRecord }}</small
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
                                            <label for="lote">Lote</label>

                                            <Select
                                                v-model="form.lote"
                                                :options="OptionsLote"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getdataRegLote"
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

                                            <p class="text-base" v-if="RegLote">
                                                {{ props.datos.RegLote.Codigo }}
                                                | {{ props.datos.RegLote.Hect }}
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

                                <!-- Fila 2-->
                                <div class="grid grid-cols-5 gap-4">
                                    <!-- Columna 1-->
                                    <div class="gap-2">
                                        <label for="Hectareas"
                                            >Hectareas Aplicacion Lote</label
                                        >
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
                                    <!-- Columna 2-->
                                    <div class="gap-2">
                                        <label for="DiasGerminacion"
                                            >Dias Germinacion</label
                                        >
                                        <p class="text-base" v-if="RegLote">
                                            {{ RegLote.FechaGerminacion }} |
                                            {{ form.DiaGerminacion }} Dias
                                        </p>
                                    </div>
                                    <!---->
                                    <div class="col-start-3 col-end-6 gap-2">
                                        <label for="Diagnostico"
                                            >Diagnostico</label
                                        >
                                        <InputText
                                            id="Diagnostico"
                                            name="Diagnostico"
                                            v-model="form.Diagnostico"
                                            size="large"
                                            style="width: 100%"
                                            aria-describedby="Diagnostico-help"
                                        />
                                        <small
                                            v-if="errors.Diagnostico"
                                            id="Diagnostico-help"
                                            class="text-red-700"
                                            >{{ errors.Diagnostico }}</small
                                        >
                                    </div>
                                </div>
                                <!-- Fila 3-->
                                <div class="grid grid-cols-1 gap-4 mt-2">
                                    <!-- Columna 1-->
                                    <div class="flex flex-col gap-2">
                                        <label for="Observaciones"
                                            >Observaciones</label
                                        >
                                        <InputText
                                            id="Observaciones"
                                            name="Observaciones"
                                            v-model="form.Observaciones"
                                            aria-describedby="Observaciones-help"
                                        />
                                        <small
                                            v-if="errors.Observaciones"
                                            id="Observaciones-help"
                                            class="text-red-700"
                                            >{{ errors.Observaciones }}</small
                                        >
                                    </div>
                                    <!---->
                                </div>

                                <!-- Fila 4-->
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
                                    <!-- Boton Eliminar-->
                                    <Button
                                        v-if="
                                            $page.props.Permission.user_permision.includes(
                                                'mod.recordvisita.delete'
                                            )
                                        "
                                        type="button"
                                        label="Eliminar"
                                        severity="danger"
                                        class="w-full"
                                        @click="submitDelete"
                                    />
                                </div>
                            </template>
                        </Card>
                        <Card>
                            <template #title> </template>
                            <template #content>
                                <div class="grid grid-cols-1 gap-4 mt-2">
                                    <div class="flex flex-col-1 gap-2">
                                        <Panel
                                            header="Adicionar Productos"
                                            toggleable
                                        >
                                            <form
                                                @submit.prevent="
                                                    SubmitAddProduct
                                                "
                                            >
                                                <!-- Fila 1 Adicionar Producto-->
                                                <div
                                                    class="grid grid-cols-5 gap-4 mt-2"
                                                >
                                                    <!-- Tipo Producto-->
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
                                                        <label
                                                            for="TipoProducto"
                                                            >Tipo
                                                            Producto</label
                                                        >
                                                        <Select
                                                            v-model="
                                                                formAddProduct.TipoProducto
                                                            "
                                                            :options="
                                                                OptionsTipoProducto
                                                            "
                                                            filter
                                                            showClear
                                                            optionLabel="label"
                                                            placeholder="Seleccionar"
                                                            v-on:change="
                                                                getdataProducto
                                                            "
                                                            class="w-full md:w-56"
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
                                                                        {{
                                                                            slotProps
                                                                                .value
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
                                                            <template
                                                                #option="slotProps"
                                                            >
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <div>
                                                                        {{
                                                                            slotProps
                                                                                .option
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
                                                            >{{
                                                                errors.finca
                                                            }}</small
                                                        >
                                                    </div>
                                                    <!--- Columna 2-->
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
                                                        <label for="Producto"
                                                            >Producto</label
                                                        >
                                                        <Select
                                                            v-model="
                                                                formAddProduct.Producto
                                                            "
                                                            :options="
                                                                OptionsProducto
                                                            "
                                                            filter
                                                            optionLabel="label"
                                                            placeholder="Seleccionar"
                                                            v-on:change="
                                                                getdataGrupoProducto
                                                            "
                                                            class="w-full md:w-56"
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
                                                                        {{
                                                                            slotProps
                                                                                .value
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
                                                            <template
                                                                #option="slotProps"
                                                            >
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <div>
                                                                        {{
                                                                            slotProps
                                                                                .option
                                                                                .label
                                                                        }}
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </Select>

                                                        <small
                                                            v-if="
                                                                errors.Producto
                                                            "
                                                            id="finca-help"
                                                            class="text-red-700"
                                                            >{{
                                                                errors.Producto
                                                            }}</small
                                                        >
                                                    </div>
                                                    <!-- Columna 3-->
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
                                                        <label for="CantxHect"
                                                            >Cantidad Por
                                                            Hect</label
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
                                                            v-if="
                                                                errors.CantxHect
                                                            "
                                                            id="CantxHect-help"
                                                            class="text-red-700"
                                                            >{{
                                                                errors.CantxHect
                                                            }}</small
                                                        >
                                                    </div>
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
                                                        <label
                                                            for="FechaAplicacion"
                                                            >Fecha Sugerida de
                                                            Aplicacion</label
                                                        >
                                                        <DatePicker
                                                            v-model="
                                                                formAddProduct.FechaAplicacion
                                                            "
                                                            showIcon
                                                            fluid
                                                            iconDisplay="input"
                                                            inputId="FechaAplicacion"
                                                            dateFormat="dd/mm/yy"
                                                        />
                                                        <small
                                                            v-if="
                                                                errors.FechaAplicacion
                                                            "
                                                            id="FechaAplicacion-help"
                                                            class="text-red-700"
                                                            >{{
                                                                errors.FechaAplicacion
                                                            }}</small
                                                        >
                                                    </div>
                                                    <!-- Columna 4-->
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
                                                        <label for="CantTotal"
                                                            >Cantidad
                                                            Total</label
                                                        >
                                                        <p
                                                            class="text-xl font-bold"
                                                        >
                                                            {{ valorTotal }}
                                                        </p>
                                                    </div>
                                                    <!-- Columna 5-->

                                                    <!-- Columna 5-->
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
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
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 mt-2">
                                    <!--  Tabla de producto-->
                                    <div class="flex flex-col gap-2">
                                        <DataTable
                                            :value="DatosProductoVisita"
                                            tableStyle="min-width: 50rem"
                                        >
                                            <Column
                                                field="GrupoMateriaPrima"
                                                header="Tipo Producto"
                                            ></Column>
                                            <Column
                                                field="Producto"
                                                header="Producto"
                                            ></Column>
                                            <Column
                                                field="Dosis_por_Ha"
                                                header="Dosis x Hect"
                                            ></Column>
                                            <Column
                                                field="cantidad_Total"
                                                header="Cant Total"
                                            ></Column>
                                            <Column
                                                field="fecha_estimada_aplicacion"
                                                header="Fecha sugerida de Aplicacion"
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
                                        </DataTable>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </form>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ppt :Reglote="props.datos.RegLote.id"></ppt>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
