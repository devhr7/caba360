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
import { useToast } from "primevue/usetoast";
const toast = useToast();

/**
 * Definiendo Props
 */
// Recibimos los datos del componente
const props = defineProps({
    datos: { type: Object },
    options: { type: Object },
    get_finca: { type: Object },
    get_lote: { type: Object },
    get_empleado: { type: Object },
    get_labor: { type: Object },
    get_maquina: { type: Object },
    errors: { type: Object },
});

// Formulario
// Creamos el formulario con los datos a enviar
const form = useForm({
    // Codigo de cumplido
    CodigoCumplido: props.datos.codigo,
    // Fecha de cumplido
    FechaCumplido: props.datos.fecha,
    // Finca
    finca: props.datos.Finca,
    // Lote
    lote: props.datos.lote,
    // Id de registro lote
    RegLote_id: props.datos.Codigolote.id,
    // Hectareas
    Hectareas: props.datos.Codigolote.Hect,
    // Empleado
    empleado: props.datos.Operario,
    // Maquina
    Maquina_id: props.datos.Cod_Maquina,
    // Horometro inicio
    horometro_inicio: props.datos.HorometroInicial,
    // Horometro fin
    horometro_fin: props.datos.HorometroFinal,
    // Galones por hectarea
    GalACPM: props.datos.GalACPM,
    // Labor
    labor: props.datos.Labor,
    // Fecha inicio
    FechaInicio: props.datos.Codigolote.FechaInicio,
    // Cantidad
    Cant: props.datos.Cant,

    Externo: props.datos.Externo,
    fincaExt: props.datos.fincaExt,
    loteExt: props.datos.loteExt,
});

// Constantes
// Datos del Finca
const OptionsFinca = ref(props.options.get_finca);

// Datos del Lote
const OptionsLote = ref(props.options.get_lote);

// Datos del registro lote
const RegLote = ref(props.datos.Codigolote);

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

costoHect.value = props.datos.total;

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

valorTotal.value = props.datos.total;
/**
 * Verifica si el formulario es invalido
 *
 * Esta funcion se encarga de verificar si el formulario es invalido
 * segun la cantidad de hectareas y el costo por hectarea
 *
 * @return {boolean}
 */

/***
 *
 */
// Exportar PDF
function submitExportPDF() {
    window.open(
        route("CumpMaquinaria.ExportPDFindiv", props.datos.slug),
        "_blank"
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
            form.put(route("CumpMaquinaria.update", props.datos.slug), {
                onSuccess: () => {
                    // Mensaje Final
                    Swal.fire({
                        title: "Alcualizado!",
                        text: "Ha sido Actualizado Correctamente.",
                        icon: "success",
                    });
                },
                onError: () => {
                    // Mostramos un mensaje de error
                    Swal.fire({
                        title: "Error!",
                        text: "",
                        icon: "error",
                    });
                },
            });
        }
    });
}

// Eliminar
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
            form.delete(route("CumpMaquinaria.delete", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}
</script>

<template>
    <Head title="Cumplidos Maquinaria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar -
                <a
                    :href="route('CumpMaquinaria.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Cumplido Maquinaria</a
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
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label for="CodigoCumplido"
                                            >Codigo Cumplido</label
                                        >
                                        <!-- input del Codigo del Cumplido-->
                                        <InputText
                                            id="CodigoCumplido"
                                            name="CodigoCumplido"
                                            class="w-full"
                                            v-model="form.CodigoCumplido"
                                            aria-describedby="CodigoCumplido-help"
                                            disabled
                                        />
                                        <!-- Mensaje de Error del Codigo del Cumplido-->
                                        <small
                                            v-if="errors.CodigoCumplido"
                                            id="CodigoCumplido-help"
                                            class="text-red-700"
                                            >{{ errors.CodigoCumplido }}</small
                                        >
                                    </div>
                                    <div>
                                        <label for="FechaCumplido">Fecha</label>
                                        <DatePicker
                                            class="w-full"
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
                                            >{{ errors.FechaCumplido }}</small
                                        >
                                    </div>
                                    <div>
                                        <label fluid for="ExtInterno"
                                            >Externo/Interno</label
                                        >

                                        <ToggleButton
                                            v-model="form.Externo"
                                            onLabel="Externo"
                                            offLabel="Interno"
                                            aria-label="Do you confirm"
                                            class="w-full"
                                            fluid
                                        />
                                    </div>
                                </div>
                                <div
                                    class="grid grid-cols-3 gap-4"
                                    v-show="form.Externo == false"
                                >
                                    <div>
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
                                            class="w-full"
                                            fluid
                                        >
                                            <template #value="slotProps">
                                                <div
                                                    v-if="slotProps.value"
                                                    class="flex items-center"
                                                >
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

                                        <!-- Mensaje de Error de la Finca-->
                                        <small
                                            v-if="errors.finca"
                                            id="finca-help"
                                            class="text-red-700"
                                            >{{ errors.finca }}</small
                                        >
                                    </div>
                                    <div>
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
                                            fluid
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
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex items-center">
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
                                    <div>
                                        <label for="RegLote_id"
                                            >Codigo Lote</label
                                        >
                                        <!-- Mostrar el Codigo Lote-->
                                        <Toast />
                                        <Fluid>
                                            <p class="text-base" v-if="RegLote">
                                                {{ RegLote.Codigo }} |
                                                {{ RegLote.Hect }}
                                            </p>
                                        </Fluid>

                                        <!-- Mensaje de Error del Codigo Lote-->
                                        <small
                                            v-if="errors.RegLote_id"
                                            id="RegLote_id-help"
                                            class="text-red-700"
                                            >{{ errors.RegLote_id }}</small
                                        >
                                    </div>
                                </div>

                                <!--------------------------------------------------------------------------------------------->
                                <div
                                    class="grid grid-cols-2 gap-4"
                                    v-show="form.Externo == true"
                                >
                                    <div>
                                        <label for="fincaExt"
                                            >Finca Externa</label
                                        >
                                        <!-- input del Codigo del Cumplido-->
                                        <InputText
                                            id="fincaExt"
                                            name="fincaExt"
                                            class="w-full"
                                            v-model="form.fincaExt"
                                            aria-describedby="fincaExt-help"
                                        />
                                        <!-- Mensaje de Error del Codigo del Cumplido-->
                                        <small
                                            v-if="errors.fincaExt"
                                            id="fincaExt-help"
                                            class="text-red-700"
                                            >{{ errors.fincaExt }}</small
                                        >
                                    </div>
                                    <div>
                                        <label for="loteExt"
                                            >Lote Externo</label
                                        >
                                        <!-- input del Codigo del Cumplido-->
                                        <InputText
                                            id="loteExt"
                                            name="loteExt"
                                            class="w-full"
                                            v-model="form.loteExt"
                                            aria-describedby="loteExt-help"
                                        />
                                        <!-- Mensaje de Error del Codigo del Cumplido-->
                                        <small
                                            v-if="errors.loteExt"
                                            id="loteExt-help"
                                            class="text-red-700"
                                            >{{ errors.loteExt }}</small
                                        >
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
                                            <label for="hect">Labor:</label>
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
                                <div class="grid grid-cols gap-4 mt-2">
                                    <div>
                                        <label for="Observaciones"
                                            >Observaciones</label
                                        >
                                        <br />
                                        <InputText
                                            id="Observaciones"
                                            name="Observaciones"
                                            v-model="form.Observaciones"
                                            aria-describedby="Codigo-help"
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
                                    <!-- :disabled="isInvalid"  -->
                                    <Button
                                        type="submit"
                                        label="Guardar"
                                        class="w-full"
                                        :disabled="
                                            form.labor === null ||
                                            form.labor === false
                                        "
                                    />

                                    <Button
                                        type="button"
                                        label="Eliminar"
                                        severity="danger"
                                        class="w-full"
                                        @click="submitDelete"
                                        :disabled="
                                            form.labor === null ||
                                            form.labor === false
                                        "
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
