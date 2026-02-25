<script setup>
import { ref, onMounted, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";
import { useToast } from "primevue/usetoast";

const toast = useToast();

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Cumplido Aplicacion", url: route("CumplidoAplicacion.Explorar") },
]);

const isLoadingOptionsLote = ref(false);
const isLoadingOptionsRegLote = ref(false);

/**
 * Definiendo Props
 */
const props = defineProps({
    get_finca: { type: Object },
    get_lote: { type: Object },
    get_empleado: { type: Object },
    get_labor: { type: Object },
    get_maquina: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    CodigoCumplido: null,
    FechaCumplido: null,
    finca: null,
    lote: null,
    labor:null,
    RegLote_id: null,
    Hectareas: null,
    HoraInicio: null,
    HoraFin: null,
    RecordVisita: null,
    SalidaAlmacen: null,
    RiesgoLluvia: true,
    Brisa: true,
    Humedad: true,
    Seguridad: true,
    Empaques: true,
    Velocidad: null,
    ResponsableAplicacion: null,
    Observaciones: null,
});

// Constantes
const OptionsFinca = ref(props.get_finca); // Datos del Finca
const OptionsLote = ref(props.get_lote); // Datos del Lote
const RegLote = ref(false);
const OptionsEmpleado = ref(props.get_empleado);
const DataLote = ref(props.get_lote); // Datos del Finca
const OptionsMaquinaria = ref(props.get_maquina);
const OptionsLabor = ref(props.get_labor);
const costoHect = ref();
const recordNumber = ref(null);

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    RegistroLotes: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    distrito_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    finca_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

//Crear
function submitCrear() {
    form.post(route("CumplidoAplicacion.store"), form);
}

/**
 * Opciones Lote Cambia Segun el Finca
 */
async function getOptionsLote() {
    isLoadingOptionsLote.value = true;
    form.lote = null;

    await axios
        .post(route("Lote.getOptionsLote"), form.finca)
        .then(function (response) {
            OptionsLote.value = response.data;
            isLoadingOptionsLote.value = false;
        });
    //.catch((e) => console.log(e));
}
// Carga los Datos de los REgLotes en los campos nombre lote y hect
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
// Carga Los Datos de los Codigos Lote

// Crea una función que se ejecute cuando el usuario digite un número de record

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
        })
        .catch((e) => console.log(e));
}

// Carga los Datos de los Lotes en los campos nombre lote y hect


</script>

<template>
    <Head title="Cumplidos Aplicacion" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - <a
                    :href="route('CumplidoAplicacion.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Cumplido Aplicacion</a
                >
            </h2>
        </template>
        <!-- Menu 2-->
       
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
                                        <label for="CodigoCumplido"
                                            >Codigo Cumplido</label
                                        >
                                        <InputText
                                            id="CodigoCumplido"
                                            name="CodigoCumplido"
                                            v-model="form.CodigoCumplido"
                                            aria-describedby="CodigoCumplido-help"
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
                                                :loading="isLoadingOptionsLote"
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
                                            v-model="form.Humedad"
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
                                            v-model="form.Empaques"
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
                                        <label for="ResponsableAplicacion"
                                            > Tipo Aplicacion</label
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
                                            v-if="errors.labor"
                                            id="labor-help"
                                            class="text-red-700"
                                            >{{
                                                errors.labor
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
                                        label="Crear y Seguir"
                                        class="w-full"
                                        v-if="RegLote"
                                        :disabled="form.processing"
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
