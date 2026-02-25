<script setup>
import { ref, onMounted, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";

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
    FechaInicio: props.datos.FechaInicio,
    FechaSiembra: props.datos.FechaSiembra,
    FechaRecoleccion: props.datos.FechaRecoleccion,
    Codigo: props.datos.Codigo,
    NombreLote: props.datos.NombreLote,
    TipoCultivo: props.datos.TipoCultivo,
    TipoVariedad: props.datos.TipoVariedad,
});

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
            form.put(route("RegLote.update", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Alcualizado!",
                text: "Ha sido Actualizado Correctamente.",
                icon: "success",
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

const formatCurrency = (value) => {
    return value.toLocaleString("es-ES");
};

const thisTotalCumplidosMaquinaria = computed(() => {
    let total = 0;
    for (let CumplidoMaquinaria of props.datos.CumplidoMaquinaria) {
        total += CumplidoMaquinaria.valor_total_sinformato;
    }
    return formatCurrency(total);
});

const thisTotalCantCumplidosMaquinaria = computed(() => {
    let cantTotal = 0;
    for (let CumplidoMaquinaria of props.datos.CumplidoMaquinaria) {
        cantTotal += CumplidoMaquinaria.cantidad;
    }
    return formatCurrency(cantTotal);
});
</script>

<template>
    <Head title="Registros Lotes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registro Lote
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
                    <Card style="overflow: hidden">
                        <template #content>
                            <!-- Fila 1 -->
                            <div class="grid grid-cols-4 gap-4 mt-2">
                                <!-- Distrito -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Distrito</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.distrito }}
                                    </div>
                                </div>
                                <!-- Finca -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Finca</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.finca }}
                                    </div>
                                </div>
                                <!-- Lote -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Lote</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.Codigo }} |
                                        {{ props.datos.lote }}
                                    </div>
                                </div>
                                <!-- Hect -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Hectareas</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.hect }}
                                    </div>
                                </div>
                            </div>
                            <!-- Fila 2 -->
                            <div class="grid grid-cols-5 gap-4 mt-2">
                                <!-- Fecha Inicio -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div class="">Fecha Inicio</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.FechaInicio }}
                                    </div>
                                </div>
                                <!-- Fecha Siembra -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Fecha Siembra</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.FechaSiembra }}
                                    </div>
                                </div>
                                <!-- Germinacion -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Fecha Germinacion</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.FechaGerminacion }}
                                    </div>
                                </div>

                                <!-- Fecha Recoleccion -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Fecha Recoleccion</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.FechaRecoleccion }}
                                    </div>
                                </div>
                            </div>

                            <!-- -->

                            <!-- Fila 3 Informacion General -->
                            <div class="grid grid-cols-5 gap-4 mt-2">
                                <!-- Fecha Inicio -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div class="">Dias Preparacion</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.DiasPreparacion }}
                                    </div>
                                </div>
                                <!-- Fecha Siembra -->
                                <div
                                    class="grid grid-rows-2 grid-flow-col gap-2"
                                >
                                    <div>Dias Cultivo</div>
                                    <div class="font-bold text-xl">
                                        {{ props.datos.DiasCultivo }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <Card style="overflow: hidden">
                        <template #content>
                            <div class="grid grid-cols gap-4 mt-2">
                                <div>Cumplidos de Maquinaria</div>
                            </div>

                            <div class="grid grid-cols gap-4 mt-2">
                                <DataTable
                                    :value="props.datos.CumplidoMaquinaria"
                                    resizableColumns
                                    columnResizeMode="fit"
                                    showGridlines
                                    tableStyle="min-width: 50rem"
                                >
                                    <Column
                                        field="codigo"
                                        header="Codigo"
                                    ></Column>
                                    <Column
                                        field="fecha"
                                        header="fecha"
                                    ></Column>
                                    <Column
                                        field="maquinaria"
                                        header="maquinaria"
                                    ></Column>
                                    <Column
                                        field="operario"
                                        header="Operario"
                                    ></Column>
                                    <Column
                                        field="labor"
                                        header="labor"
                                    ></Column>
                                    <Column
                                        field="cantidad"
                                        header="cantidad"
                                    ></Column>
                                    <Column
                                        field="valor_total"
                                        header="valor_total"
                                    ></Column>
                                    <ColumnGroup type="footer">
                                        <Row>
                                            <Column
                                                footer="Total:"
                                                :colspan="4"
                                                footerStyle="text-align:right"
                                            />
                                            <Column
                                                :footer="
                                                    thisTotalCantCumplidosMaquinaria
                                                "
                                            />
                                            <Column
                                                :footer="
                                                    thisTotalCumplidosMaquinaria
                                                "
                                            />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
