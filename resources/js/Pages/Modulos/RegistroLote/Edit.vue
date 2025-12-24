<script setup>
import { ref, onMounted } from "vue";
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
    { label: "Registro Lote", url: route("RegLote.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    get_distrito: { type: Object },
    get_finca: { type: Object },
    get_lote: { type: Object },
    get_tipocultivo: { type: Object },
    get_tipovariedad: { type: Object },
    datos: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    distrito: props.datos.distrito,
    finca: props.datos.finca,
    lote: props.datos.lote,
    hect: props.datos.hect,
    FechaInicio: new Date(props.datos.FechaInicio),
    FechaSiembra: props.datos.FechaSiembra
        ? new Date(props.datos.FechaSiembra)
        : null,
    FechaGerminacion: props.datos.FechaGerminacion
        ? new Date(props.datos.FechaGerminacion)
        : null,
    FechaRecoleccion: props.datos.FechaRecoleccion
        ? new Date(props.datos.FechaRecoleccion)
        : null,
    FechaVenta: props.datos.FechaVenta
        ? new Date(props.datos.FechaVenta)
        : null,
    Codigo: props.datos.Codigo,
    NombreLote: props.datos.NombreLote,
    TipoCultivo: props.datos.TipoCultivo,
    TipoVariedad: props.datos.TipoVariedad,
    status: props.datos.status,
    slug: props.datos.slug,
    update_status: false,
});

const minDate = ref(new Date());
// Constantes
const OptionsDistrito = ref(props.get_distrito); // Datos del Distrito
const OptionsFinca = ref(props.get_finca); // Datos del Finca
const OptionsLote = ref(props.get_lote); // Datos del Lote
const OptionsTipoCultivo = ref(props.get_tipocultivo); // Datos del Tipo Cultivo
const OptionsTipoVariedad = ref(props.get_tipovariedad); // Datos del Tipo Variedad
const DataLote = ref(props.get_lote); // Datos del Finca
const RegistroLotes = ref([]); // Datos del Finca

// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    RegistroLotes: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    distrito_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    finca_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

// Actualizar
/**
 * Funcion para Actualizar un registro en la base de datos
 * Realiza una peticion HTTP PUT a la ruta especificada en el objeto form
 * y envia los datos del formulario en el objeto form
 * Si el formulario se envia correctamente, se mostrara un mensaje de exito
 * y se preguntara al usuario si desea crear un nuevo codigo de lote.
 * Si el usuario confirma, se redirigira al formulario de crear.
 * Si el usuario cancela, no se realizara ninguna accion.
 */
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
            const response = form.put(
                route("RegLote.update", props.datos.slug),
                {
                    /**
                     * Si el formulario se envia correctamente, se mostrara un mensaje de exito
                     * y se preguntara al usuario si desea crear un nuevo codigo de lote.
                     * Si el usuario confirma, se redirigira al formulario de crear.
                     * Si el usuario cancela, no se realizara ninguna accion.
                     */
                    onSuccess: () => {
                        // Mostramos un mensaje de exito
                        toast.add({
                            severity: "success",
                            summary: "Actualizado Correctamente",
                            detail: "",
                            life: 3000,
                        });
                        // Preguntamos si el usuario desea crear un nuevo codigo
                        if (form.FechaRecoleccion === null) {
                            // Si no se ha especificado una fecha de recoleccion, no se hace nada
                        } else {
                            Swal.fire({
                                title: "Desea Crear un nuevo Codigo?",
                                text: "Si confirma, sera redirigido al formulario de Crear",
                                icon: "info",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Si, Crear!",
                            }).then((result2) => {
                                // Confirmacion
                                if (result2.isConfirmed) {
                                    // Envio al formulario a la ruta crear
                                    const response2 = form.post(
                                        route("RegLote.store_auto"),
                                        {
                                            onSuccess: () => {
                                                // Mostramos un mensaje de exito
                                                toast.add({
                                                    severity: "success",
                                                    summary:
                                                        "Codigo Creado Correctamente",
                                                    detail: "",
                                                    life: 3000,
                                                });
                                            },
                                            onError: () => {
                                                // Mostramos un mensaje de error
                                                toast.add({
                                                    severity: "error",
                                                    summary:
                                                        "Error al Crear el codigo",
                                                    detail: "",
                                                    life: 3000,
                                                });
                                            },
                                        }
                                    );
                                }
                            });
                        }
                    },
                    onError: () => {
                        // Mostramos un mensaje de error
                        toast.add({
                            severity: "error",
                            summary: "Error al actualizar",
                            detail: "",
                            life: 3000,
                        });
                    },
                }
            );

            // Mensaje Final
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
            form.delete(route("RegLote.delete", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}
/**
 * Opciones Finca Cambia Segun el Distrito
 */
async function getOptionsFinca() {
    await axios
        .post(route("Finca.getOptionsFinca"), form.distrito)
        .then(function (response) {
            OptionsFinca.value = response.data;
        });
    //.catch((e) => console.log(e));
}
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
// Carga los Datos de los Lotes en los campos nombre lote y hect
async function getdataLote() {
    await axios
        .post(route("Lote.getDataLote"), form.lote)
        .then(function (response) {
            form.hect = response.data.hect;
            form.NombreLote = response.data.lote;
            get_data_RegLote();
        });
}
// Carga La Tabla Historico de los Lotes, Cuando se selecciona
async function get_data_RegLote() {
    await axios
        .post(route("RegLote.get_data_RegLote"), form.lote)
        .then(function (response) {
            RegistroLotes.value = response.data;
            console.log(response.data);
            console.log(RegistroLotes);
        })
        .catch((e) => console.log(e));
}

const visible = ref(false);

const selectedstatus = ref();
const statusOptions = ref([
    { label: "Activo", id: 1 },
    { label: "Bloqueado", id: 2 },
    { label: "Cerrado", id: 3 },
]);
</script>

<template>
    <Head title="Registros Lotes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar -
                <a
                    :href="route('RegLote.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Registro Lote</a
                >
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <BlockUI :blocked="blocked">
                        <form @submit.prevent="submitUpdate">
                            <Card style="overflow: hidden">
                                <template #content>
                                    <div class="grid grid-cols-4 gap-4 mt-2">
                                        <div>
                                            <div class="flex flex-col gap-2">
                                                <label for="distrito"
                                                    >Distrito</label
                                                >

                                                <Select
                                                    v-model="form.distrito"
                                                    :options="OptionsDistrito"
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    v-on:change="
                                                        getOptionsFinca
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
                                                    v-if="errors.distrito"
                                                    id="distrito-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.distrito
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
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    v-on:change="getOptionsLote"
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
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    v-on:change="getdataLote"
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
                                                    v-if="errors.lote"
                                                    id="lote-help"
                                                    class="text-red-700"
                                                    >{{ errors.lote }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-2">
                                                <label for="hect">hect</label>
                                                <InputText
                                                    id="hect"
                                                    name="hect"
                                                    v-model="form.hect"
                                                    aria-describedby="hect-help"
                                                />
                                                <small
                                                    v-if="errors.hect"
                                                    id="hect-help"
                                                    class="text-red-700"
                                                    >{{ errors.hect }}</small
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fechas -->
                                    <div class="grid grid-cols-5 gap-3 mt-2">
                                        <div>
                                            <div class="flex flex-col gap-3">
                                                <label for="FechaInicio"
                                                    >Fecha Inicio</label
                                                >

                                                <DatePicker
                                                    v-model="form.FechaInicio"
                                                    showIcon
                                                    fluid
                                                    iconDisplay="input"
                                                    inputId="FechaInicio"
                                                    dateFormat="dd/mm/yy"
                                                />

                                                <small
                                                    v-if="errors.FechaInicio"
                                                    id="FechaInicio-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.FechaInicio
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-3">
                                                <label for="FechaSiembra"
                                                    >Fecha Siembra</label
                                                >

                                                <DatePicker
                                                    v-model="form.FechaSiembra"
                                                    showIcon
                                                    fluid
                                                    :minDate="form.FechaInicio"
                                                    iconDisplay="input"
                                                    inputId="FechaSiembra"
                                                    dateFormat="dd/mm/yy"
                                                />
                                                <small
                                                    v-if="errors.FechaSiembra"
                                                    id="FechaSiembra-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.FechaSiembra
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-3">
                                                <label for="FechaGerminacion"
                                                    >Fecha Germinacion</label
                                                >

                                                <DatePicker
                                                    v-model="
                                                        form.FechaGerminacion
                                                    "
                                                    showIcon
                                                    fluid
                                                    :minDate="form.FechaSiembra"
                                                    iconDisplay="input"
                                                    inputId="FechaGerminacion"
                                                    dateFormat="dd/mm/yy"
                                                />
                                                <small
                                                    v-if="
                                                        errors.FechaGerminacion
                                                    "
                                                    id="FechaGerminacion-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.FechaGerminacion
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-3">
                                                <label for="FechaRecoleccion"
                                                    >Fecha Recoleccion</label
                                                >
                                                <DatePicker
                                                    v-model="
                                                        form.FechaRecoleccion
                                                    "
                                                    showIcon
                                                    fluid
                                                    :minDate="form.FechaSiembra"
                                                    iconDisplay="input"
                                                    inputId="FechaRecoleccion"
                                                    dateFormat="dd/mm/yy"
                                                />
                                                <small
                                                    v-if="
                                                        errors.FechaRecoleccion
                                                    "
                                                    id="FechaRecoleccion-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.FechaRecoleccion
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-3">
                                                <label for="FechaVenta"
                                                    >Fecha Venta</label
                                                >
                                                <DatePicker
                                                    v-model="form.FechaVenta"
                                                    showIcon
                                                    fluid
                                                    :minDate="
                                                        form.FechaRecoleccion
                                                    "
                                                    iconDisplay="input"
                                                    inputId="FechaVenta"
                                                    dateFormat="dd/mm/yy"
                                                />
                                                <small
                                                    v-if="errors.FechaVenta"
                                                    id="FechaVenta-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.FechaVenta
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Informacion Lote-->
                                    <div class="grid grid-cols-5 gap-4 mt-2">
                                        <div>
                                            <div class="flex flex-col gap-2">
                                                <label for="Codigo"
                                                    >Codigo Lote</label
                                                >
                                                <InputText
                                                    id="Codigo"
                                                    name="Codigo"
                                                    v-model="form.Codigo"
                                                    aria-describedby="Codigo-help"
                                                />
                                                <small
                                                    v-if="errors.Codigo"
                                                    id="Codigo-help"
                                                    class="text-red-700"
                                                    >{{ errors.Codigo }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-2">
                                                <label for="NombreLote"
                                                    >Nombre Lote</label
                                                >
                                                <InputText
                                                    id="NombreLote"
                                                    name="NombreLote"
                                                    v-model="form.NombreLote"
                                                    aria-describedby="NombreLote-help"
                                                />
                                                <small
                                                    v-if="errors.NombreLote"
                                                    id="NombreLote-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.NombreLote
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-2">
                                                <label for="TipoCultivo"
                                                    >Tipo Cultivo</label
                                                >

                                                <Select
                                                    v-model="form.TipoCultivo"
                                                    :options="
                                                        OptionsTipoCultivo
                                                    "
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    v-on:change="getOptionsLote"
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
                                                    v-if="errors.TipoCultivo"
                                                    id="TipoCultivo-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.TipoCultivo
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex flex-col gap-2">
                                                <label for="TipoVariedad"
                                                    >Tipo Variedad</label
                                                >

                                                <Select
                                                    v-model="form.TipoVariedad"
                                                    :options="
                                                        OptionsTipoVariedad
                                                    "
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
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
                                                    v-if="errors.TipoVariedad"
                                                    id="TipoVariedad-help"
                                                    class="text-red-700"
                                                    >{{
                                                        errors.TipoVariedad
                                                    }}</small
                                                >
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label
                                                for="Seleccione"
                                                class="font-semibold w-24"
                                                >Estado</label
                                            >

                                            <InputGroup>
                                                <InputGroupAddon>
                                                    <Checkbox
                                                        v-model="
                                                            form.update_status
                                                        "
                                                        :binary="true"
                                                        v-tooltip.bottom="
                                                            'Marcar para actualizar el estado'
                                                        "
                                                    />
                                                </InputGroupAddon>
                                                <Select
                                                    v-model="form.status"
                                                    :options="statusOptions"
                                                    filter
                                                    optionLabel="label"
                                                    placeholder="Seleccionar"
                                                    class="w-full md:w-56"
                                                    v-tooltip.bottom="
                                                        'Marcar para actualizar el estado'
                                                    "
                                                >
                                                </Select>
                                            </InputGroup>
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
                                            v-if="
                                                $page.props.Permission.user_permision.includes(
                                                    'mod.reglote.edit'
                                                )
                                            "
                                            :disabled="form.processing"
                                        />
                                        <Toast />
                                        <!-- Boton Eliminar-->
                                        <Button
                                            v-if="
                                                $page.props.Permission.user_permision.includes(
                                                    'mod.reglote.delete'
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
                        </form>
                    </BlockUI>
                    <Card style="overflow: hidden">
                        <template #content>
                            <div class="grid grid-cols gap-4 mt-2">
                                <table></table>
                            </div>

                            <div class="grid grid-cols gap-4 mt-2"></div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
