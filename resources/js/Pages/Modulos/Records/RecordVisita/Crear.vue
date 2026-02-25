<script setup>
// Import required dependencies
import { ref, onMounted, computed,watch } from "vue";
// Import the authenticated layout component
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// Import the Head component to be used in the layout
import { Head } from "@inertiajs/vue3";
// Import the useForm hook from the Inertia library
import { useForm } from "@inertiajs/vue3";
// Importar funcion de Toast
import { useToast } from 'primevue/usetoast';
const toast = useToast();

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Record Visita", url: route("RecordVisita.Explorar") },
]);

/**
 * Definiendo Props
 *
 * Se definen las props que se pasan desde el controlador a la vista
 *
 * @typedef {Object} Props
 * @property {Object} get_finca - Datos para el select de finca
 * @property {Object} get_lote - Datos para el select de lote
 * @property {Object} errors - Errores que se pueden presentar en el formulario
 */
const props = defineProps({
    // Datos para los select
    get_finca: { type: Object },  // Finca
    get_lote: { type: Object },  // Lote
    // Errores
    errors: { type: Object },
});

//  Definiendo el Formulario
const form = useForm({
    CodigoRecord: null,
    FechaRecord: null,
    finca: null,
    lote: null,
    RegLote_id: null,
    Hectareas: null,
    DiaGerminacion:null,
    Diagnostico: null,
    Observaciones: null,
    AgricultorEncargado: null,
    IngenieroAgronomo: null,
    TipoProducto:null,
    Producto:null,
    CantxHect:null,
    Total:null,
});
/**
 * Definir las constantes para los select
 *
 * @type {Object} OptionsFinca - Options del select Finca
 * @type {Object} OptionsLote - Options del select Lote
 * @type {Object} OptionsRegLote - Options del select RegLote
 */
const OptionsFinca = ref(props.get_finca); // Options del Finca
const OptionsLote = ref(props.get_lote); // Options del Lote
const OptionsRegLote = ref([]); // Options Registro Lote
// Datos del registro lote
const RegLote = ref(false);
/**
 * Submit the form to create a new RecordVisita
 *
 * @return {void}
 */
function submitCrear() {
    // Send the form data to the server
    form.post(route("RecordVisita.store"), form);
}

/**
 * Opciones Lote Cambia Segun el Finca
 * @param {Number} finca_id Finca que se va a buscar
 * @return {Promise} Promise que se resuelve con los datos de la finca
 */
async function getOptionsLote() {
    await axios
        .post(route("Lote.getOptionsLote"), form.finca)
        .then(function (response) {
            /**
             * Asigna los datos de la finca a la variable OptionsLote
             * @type {Array} Array con los datos de la finca
             */
            OptionsLote.value = response.data;
        });
    //.catch((e) => console.log(e));
}

/**
 * Carga los Datos de los Lotes en los campos nombre lote y hect
 * @param {Number} lote_id Lote que se va a buscar
 * @return {Promise} Promise que se resuelve con los datos del lote
 */
/**
 * Calcula la cantidad de dias entre la fecha de germinacion y la fecha del Record
 * @return {void}
 */
function calcularDiasGerminacion() {
    console.log("calcular los Dias de Germinacion")
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

const valorTotal = computed(() => {
    if (form.labor === null) {
        costoHect.value = 0;
    } else {
        costoHect.value = form.labor.CostoHect ?? 0;
    }
    const cantidad = form.Cant ?? 0;

    return new Intl.NumberFormat("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(costoHect.value * cantidad);
});
</script>

<template>

    <Head title="Record Visita" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Record Visita
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
                    <form @submit.prevent="submitCrear">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <!-- Codigo-->
                                    <div class="flex flex-col gap-2">
                                        <label for="CodigoRecord">Codigo Record</label>
                                        <InputText id="CodigoRecord" name="CodigoRecord" v-model="form.CodigoRecord"
                                            aria-describedby="CodigoRecord-help" />
                                        <small v-if="errors.CodigoRecord" id="CodigoRecord-help" class="text-red-700">{{
                                            errors.CodigoRecord }}</small>
                                    </div>
                                    <!-- Fecha-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="FechaRecord">Fecha</label>
                                            <DatePicker v-model="form.FechaRecord" showIcon fluid iconDisplay="input"
                                                inputId="FechaRecord" dateFormat="dd/mm/yy" v-on:change="calcularDiasGerminacion"  />
                                            <small v-if="errors.FechaRecord" id="FechaRecord-help" class="text-red-700">{{
                                                errors.FechaRecord }}</small>
                                        </div>
                                    </div>
                                    <!-- Finca-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="finca">Finca</label>
                                            <Select v-model="form.finca" :options="OptionsFinca" filter
                                                optionLabel="label" placeholder="Seleccionar"
                                                v-on:change="getOptionsLote" class="w-full md:w-56">
                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex items-center">
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

                                            <small v-if="errors.finca" id="finca-help" class="text-red-700">{{
                                                errors.finca }}</small>
                                        </div>
                                    </div>
                                    <!-- Lote-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="lote">Lote</label>

                                            <Select v-model="form.lote" :options="OptionsLote" filter
                                                optionLabel="label" placeholder="Seleccionar" v-on:change="getdataRegLote"
                                                class="w-full md:w-56">
                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex items-center">
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

                                            <small v-if="errors.lote" id="lote-help" class="text-red-700">{{ errors.lote
                                                }}</small>
                                        </div>
                                    </div>
                                    <!-- Codigo Lote-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="RegLote_id">Codigo Lote</label>
                                            <Toast />

<p class="text-base" v-if="RegLote">
    {{ RegLote.Codigo }} |
    {{ RegLote.Hect }}
</p>

                                            <small v-if="errors.RegLote_id" id="RegLote_id-help" class="text-red-700">{{
                                                errors.RegLote_id }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fila 2-->
                                <div class="grid grid-cols-5 gap-4">
                                    <!-- Columna 1-->
                                    <div class=" gap-2">
                                        <label for="Hectareas">Hectareas Aplicacion Lote</label>
                                        <InputText id="Hectareas" name="Hectareas" v-model="form.Hectareas"
                                            aria-describedby="Hectareas-help" />
                                        <small v-if="errors.Hectareas" id="Hectareas-help"
                                            class="text-red-700">{{ errors.Hectareas
                                            }}</small>
                                    </div>
                                    <!-- Columna 2-->
                                    <div class=" gap-2">
                                        <label for="DiasGerminacion">Dias Germinacion</label>
                                        <p class="text-base" v-if="RegLote">
                                             {{ RegLote.FechaGerminacion }} |
                                             {{ form.DiaGerminacion }} Dias
                                        </p>

                                    </div>
                                    <!---->
                                    <div class="col-start-3 col-end-6 gap-2">
                                        <label for="Diagnostico">Diagnostico</label>
                                        <InputText id="Diagnostico" name="Diagnostico" v-model="form.Diagnostico"
                                            size="large" style="width: 100%" aria-describedby="Diagnostico-help" />
                                        <small v-if="errors.Diagnostico" id="Diagnostico-help" class="text-red-700">{{
                                            errors.Diagnostico }}</small>
                                    </div>
                                </div>
                                <!-- Fila 3-->
                                <div class="grid grid-cols-1 gap-4 mt-2">
                                    <!-- Columna 1-->
                                    <div class="flex flex-col gap-2">
                                        <label for="Observaciones">Observaciones</label>
                                        <InputText id="Observaciones" name="Observaciones" v-model="form.Observaciones"
                                            aria-describedby="Observaciones-help" />
                                        <small v-if="errors.Observaciones" id="Observaciones-help"
                                            class="text-red-700">{{ errors.Observaciones
                                            }}</small>
                                    </div>
                                    <!---->
                                </div>

                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div class="flex gap-4 mt-1">
                                    <Button type="submit" label="Crear y Seguir" class="w-full" :disabled="form.processing" />
                                </div>
                            </template>
                        </Card>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
