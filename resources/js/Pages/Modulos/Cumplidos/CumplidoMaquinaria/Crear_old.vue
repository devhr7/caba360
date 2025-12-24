<script setup>
// Importar librerias de Vue
import { ref, onMounted, computed, watch } from "vue";
// Importar componentes de la aplicacion
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// Importar componentes de Inertia
import { Head } from "@inertiajs/vue3";
// Importar hooks de useForm
import { useForm } from "@inertiajs/vue3";
// Importar funcion de PrimeVue
import { FilterMatchMode } from "@primevue/core/api";
// Importar funcion de Toast
import { useToast } from 'primevue/usetoast';
const toast = useToast();

// Menu 2
const items = ref([
    // Inicio
    { label: "Inicio", url: route("dashboard") },
    // Cumplido Maquinaria
    { label: "Cumplido Maquinaria", url: route("CumpMaquinaria.Explorar") },
]);

/**
 * Definiendo Props
 */
// Recibimos los datos del componente
const props = defineProps({
    // Finca
    get_finca: { type: Object },
    // Lote
    get_lote: { type: Object },
    // Empleado
    get_empleado: { type: Object },
    // Labor
    get_labor: { type: Object },
    // Maquinaria
    get_maquina: { type: Object },
    // Errores
    errors: { type: Object },
});

// Formulario
// Creamos el formulario con los datos a enviar
const form = useForm({
    // Codigo de cumplido
    CodigoCumplido: null,
    // Fecha de cumplido
    FechaCumplido: null,
    // Finca
    finca: null,
    // Lote
    lote: null,
    // Id de registro lote
    RegLote_id: null,
    // Hectareas
    Hectareas: null,
    // Empleado
    empleado: null,
    // Maquina
    Maquina_id: null,
    // Horometro inicio
    horometro_inicio: null,
    // Horometro fin
    horometro_fin: null,
    // Galones por hectarea
    GalACPM: null,
    // Labor
    labor: null,
    // Fecha inicio
    FechaInicio: null,
    // Cantidad
    Cant: 0,
});

// Constantes
// Datos del Finca
const OptionsFinca = ref(props.get_finca);

// Datos del Lote
const OptionsLote = ref(props.get_lote);

// Datos del registro lote
const RegLote = ref(false);

// Datos del Empleado
const OptionsEmpleado = ref(props.get_empleado);


// Datos de la maquinaria
const OptionsMaquinaria = ref(props.get_maquina);

// Datos del Labor
const OptionsLabor = ref(props.get_labor);

// Costo por hectarea
const costoHect = ref();

// Verifica si hay un error en el formulario
const isInvalid = ref(false);


//Crear
function submitCrear() {
    form.post(route("CumpMaquinaria.store"), form);
}

/**
 * Opciones Lote Cambia Segun el Finca
 *
 * Esta funcion se encarga de cargar los datos de los Lotes segun el Finca seleccionado
 * y limpiar los campos de Lote y Hectareas
 *
 * @return {void}
 */
async function getOptionsLote() {
    form.lote = null;
    form.RegLote_id = null;
    form.Hectareas = null;
    form.Cant = 0;
    // Carga los datos de los Lotes
    await axios
        .post(route("Lote.getOptionsLote"), form.finca)
        .then(function (response) {
            OptionsLote.value = response.data;
        });
    //.catch((e) => console.log(e));
}

/**
 * Carga los Datos de los Lotes en los campos nombre lote y hect
 *
 * Esta funcion se encarga de cargar los datos de los Lotes en los campos nombre lote y hect
 * cuando se selecciona un Lote
 *
 * @return {void}
 */
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
 * Calcula el valor total del cumplido
 *
 * Esta funcion se encarga de calcular el valor total del cumplido
 * segun el costo por hectarea y la cantidad de hectareas
 *
 * @return {string}
 */
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

/**
 * Verifica si el formulario es invalido
 *
 * Esta funcion se encarga de verificar si el formulario es invalido
 * segun la cantidad de hectareas y el costo por hectarea
 *
 * @return {boolean}
 */
const isFormInvalid = ref(true);

watch(
    () => form.Cant,
    (newVal, oldVal) => {
        if (newVal !== oldVal && form.labor !== null) {
            if (form.labor.id !== 28 && form.labor.id !== 29 && form.labor.id !== 21 && form.labor.id !== 23 && form.labor.id !== 24 && form.labor.id !== 25 && form.labor.id !== 26  && form.labor.id !== 17  ) {
                if (newVal > form.Hectareas) {
                    isInvalid.value = true;
                } else {
                    isInvalid.value = false;
                }
            } else {
                isInvalid.value = false;
            }
        }
    }
);
</script>

<template>
    <Head title="Cumplidos Maquinaria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Cumplido Maquinaria
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
                                    <!-- Codigo Cumplido-->
                                    <div class="flex flex-col gap-2">
                                        <!-- Label del Codigo Cumplido-->
                                        <label for="CodigoCumplido"
                                            >Codigo Cumplido</label
                                        >
                                        <!-- input del Codigo del Cumplido-->
                                        <InputText
                                            id="CodigoCumplido"
                                            name="CodigoCumplido"
                                            v-model="form.CodigoCumplido"
                                            aria-describedby="CodigoCumplido-help"
                                        />
                                        <!-- Mensaje de Error del Codigo del Cumplido-->
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
                                            <!-- Label de la Finca-->
                                            <label for="finca">Finca</label>

                                            <!-- Select de la Finca-->
                                            <Select
                                                v-model="form.finca"
                                                :options="OptionsFinca"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getOptionsLote"
                                                showClear
                                                class="w-full md:w-56"
                                            >
                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex items-center">
                                                        <div>
                                                            <!-- Mostrar el nombre de la finca-->
                                                            {{ slotProps.value.label }}
                                                        </div>
                                                    </div>
                                                    <span v-else>
                                                        <!-- Mostrar el placeholder-->
                                                        {{ slotProps.placeholder  }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div
                                                        class="flex items-center"
                                                    >
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

                                            <!-- Mensaje de Error de la Finca-->
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
                                            <!-- Label del Lote-->
                                            <label for="finca">Lote</label>

                                            <!-- Select del Lote-->
                                            <Select
                                                v-model="form.lote"
                                                :options="OptionsLote"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getdataRegLote"
                                                showClear
                                                class="w-full md:w-56"
                                            >
                                                <template #value="slotProps">
                                                    <div
                                                        v-if="slotProps.value"
                                                        class="flex items-center"
                                                    >
                                                        <div>
                                                            <!-- Mostrar el nombre del lote-->
                                                            {{
                                                                slotProps.value
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
                                                <template #option="slotProps">
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <div>
                                                            <!-- Mostrar el nombre del lote-->
                                                            {{
                                                                slotProps.option
                                                                    .label
                                                            }}
                                                        </div>
                                                    </div>
                                                </template>
                                            </Select>

                                            <!-- Mensaje de Error del Lote-->
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
                                            <!-- Label del Codigo Lote-->
                                            <label for="RegLote_id"
                                                >Codigo Lote</label
                                            >
                                            <!-- Mostrar el Codigo Lote-->
                                            <Toast />

                                            <p class="text-base" v-if="RegLote">
                                                {{ RegLote.Codigo }} |
                                                {{ RegLote.Hect }}
                                            </p>

                                            <!-- Mensaje de Error del Codigo Lote-->
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
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="empleado"
                                                >Operario</label
                                            >

                                            <Select
                                                v-model="form.empleado"
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
                                                v-if="errors.empleado"
                                                id="empleado-help"
                                                class="text-red-700"
                                                >{{ errors.empleado }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="finca">Maquina</label>

                                            <Select
                                                v-model="form.Maquina_id"
                                                :options="OptionsMaquinaria"
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
                                                v-if="errors.Maquina_id"
                                                id="Maquina_id-help"
                                                class="text-red-700"
                                                >{{ errors.Maquina_id }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="horometro_inicio"
                                                >Horometro Inicio</label
                                            >
                                            <InputText
                                                id="horometro_inicio"
                                                name="horometro_inicio"
                                                v-model="form.horometro_inicio"
                                                aria-describedby="horometro_inicio-help"
                                            />
                                            <small
                                                v-if="errors.horometro_inicio"
                                                id="horometro_inicio-help"
                                                class="text-red-700"
                                                >{{
                                                    errors.horometro_inicio
                                                }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="horometro_fin"
                                                >Horometro Fin</label
                                            >
                                            <InputText
                                                id="horometro_fin"
                                                name="horometro_fin"
                                                v-model="form.horometro_fin"
                                                aria-describedby="horometro_fin-help"
                                            />
                                            <small
                                                v-if="errors.horometro_fin"
                                                id="horometro_fin-help"
                                                class="text-red-700"
                                                >{{
                                                    errors.horometro_fin
                                                }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="GalACPM"
                                                >Gal ACPM</label
                                            >
                                            <InputText
                                                id="GalACPM"
                                                name="GalACPM"
                                                v-model="form.GalACPM"
                                                aria-describedby="GalACPM-help"
                                            />
                                            <small
                                                v-if="errors.GalACPM"
                                                id="GalACPM-help"
                                                class="text-red-700"
                                                >{{ errors.GalACPM }}</small
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mt-2">
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="hect">Labor</label>
                                            <Select
                                                v-model="form.labor"
                                                :options="OptionsLabor"
                                                showClear
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                class="w-full"
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
                                                v-if="errors.labor"
                                                id="labor-help"
                                                class="text-red-700"
                                                >{{ errors.labor }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="Cant"
                                                >Cant / Hect</label
                                            >
                                            <InputText
                                                id="Cant"
                                                name="Cant"
                                                v-model="form.Cant"
                                                aria-describedby="Codigo-help"
                                            />
                                            <small
                                                v-if="errors.Cant"
                                                id="Cant-help"
                                                class="text-red-700"
                                                >{{ errors.Cant }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <label for="hect">Total</label>
                                        <h1>${{ valorTotal }}</h1>
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div class="flex gap-4 mt-1">
                                    <!-- :disabled="isInvalid"  -->
                                    <Button
                                        type="submit"
                                        label="Guardar"
                                        class="w-full"
                                        :disabled="isInvalid || (form.RegLote_id === null || form.RegLote_id === false) || (form.labor === null || form.labor === false)"
                                    />

                                </div>
                            </template>
                        </Card>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

