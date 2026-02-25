<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { data } from "autoprefixer";

const props = defineProps({
    options: { type: Object },
});
// Formulario
const form = useForm({
    finca: null,
    lote: null,
    codigolote: null,
    status: { name: "Activos", id: 1 },
});
const formLote = useForm({
    lote: null,
    status: { name: "Activos", id: 1 },
});
const datatable = ref(null);
//Crear

async function submitCrear() {
    await axios
        .post(route("costos.report.GastosPorLoteData"), form)
        .then(function (response) {
            datatable.value = response.data;
        });
}

const isLoadingOptionsLote = ref(false);
const isLoadingOptionsRegLote = ref(false);
const OptionsFinca = ref(props.options.get_finca); // Datos del Finca
const OptionsLote = ref(props.options.get_lote); // Datos del Lote
const OptionsCodigoLote = ref(props.options.get_reglote); // Datos del Lote
async function getOptionsLote() {
    isLoadingOptionsLote.value = true;
    isLoadingOptionsRegLote.value = true;
    form.lote = null;
    form.codigolote = null;

    await axios
        .post(route("Lote.getOptionsLote"), form.finca)
        .then(function (response) {
            OptionsLote.value = response.data;
            isLoadingOptionsLote.value = false;
            isLoadingOptionsRegLote.value = false;
        });
    //.catch((e) => console.log(e));
}

async function getOptionsRegLote() {
    formLote.lote = form.lote;
    formLote.status = form.status;
    form.codigolote = null;

    await axios
        .post(route("RegLote.getOptionsRegLote"), formLote)
        .then(function (response) {
            OptionsCodigoLote.value = response.data;
        });
}

const FilteredRegLote = ref(); // Constante para Filto
// Buscado de Auto completar
const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            FilteredRegLote.value = [...OptionsCodigoLote.value];
        } else {
            FilteredRegLote.value = OptionsCodigoLote.value.filter((reg) => {
                return reg.label
                    .toLowerCase()
                    .startsWith(event.query.toLowerCase());
            });
        }
    }, 250);
};

const optionsFiltroStatus = ref([
    { name: "Activos", id: 1 },
    { name: "Cerrados", id: 2 },
    { name: "Todos", id: 3 },
]);

const obs_cump = ref(null);
const op = ref();

const toggle = (event) => {
    op.value.toggle(event);

}
</script>

<template>
    <Head title="Consolidado Gastos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Consolidado Gastos Por Lote
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submitCrear">
                        <div class="grid grid-cols-5 gap-5 m-5">
                            <div>
                                <label
                                    for="lote"
                                    class="block text-sm font-medium text-gray-700"
                                    >Finca</label
                                >
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
                                                {{ slotProps.value.label }}
                                            </div>
                                        </div>
                                        <span v-else>
                                            {{ slotProps.placeholder }}
                                        </span>
                                    </template>
                                    <template #option="slotProps">
                                        <div class="flex items-center">
                                            <div>
                                                {{ slotProps.option.label }}
                                            </div>
                                        </div>
                                    </template>
                                </Select>
                            </div>
                            <div>
                                <label
                                    for="lote"
                                    class="block text-sm font-medium text-gray-700"
                                    >Lote</label
                                >
                                <Select
                                    v-model="form.lote"
                                    :options="OptionsLote"
                                    filter
                                    :loading="isLoadingOptionsLote"
                                    optionLabel="label"
                                    placeholder="Seleccionar"
                                    v-on:change="getOptionsRegLote"
                                    showClear
                                    class="w-full md:w-56"
                                >
                                    <template #value="slotProps">
                                        <div
                                            v-if="slotProps.value"
                                            class="flex items-center"
                                        >
                                            <div>
                                                {{ slotProps.value.label }}
                                            </div>
                                        </div>
                                        <span v-else>
                                            {{ slotProps.placeholder }}
                                        </span>
                                    </template>
                                    <template #option="slotProps">
                                        <div class="flex items-center">
                                            <div>
                                                {{ slotProps.option.label }}
                                            </div>
                                        </div>
                                    </template>
                                </Select>
                            </div>
                            <div>
                                <label
                                    for="lote"
                                    class="block text-sm font-medium text-gray-700"
                                    >Seleccionar Estado Lotes</label
                                >
                                <SelectButton
                                    v-model="form.status"
                                    :options="optionsFiltroStatus"
                                    optionLabel="name"
                                    v-on:change="getOptionsRegLote"
                                    aria-labelledby="multiple"
                                />
                            </div>
                            <div>
                                <label
                                    for="lote"
                                    class="block text-sm font-medium text-gray-700"
                                    >Codigo Lote</label
                                >

                                <AutoComplete
                                    v-model="form.codigolote"
                                    :suggestions="FilteredRegLote"
                                    dataKey="id"
                                    @complete="search"
                                    :loading="isLoadingOptionsRegLote"
                                    optionLabel="Codigo"
                                    optionV="Codigo"
                                    dropdown
                                />
                            </div>
                            <div>
                                <Button
                                    type="submit"
                                    label="Buscar"
                                    severity="info"
                                />
                            </div>
                        </div>
                    </form>
                    <br />
                    <hr />
                    <br />

                    <div class="gap-5">
                        <div>
                            <Tabs value="0">
                                <TabList>
                                    <Tab value="GastoGeneral"
                                        >Gastos General</Tab
                                    >
                                    <Tab value="GastoDetallado"
                                        >Gastos Detallado</Tab
                                    >
                                    <Tab value="Movimientos"
                                        >Historico Lote</Tab
                                    >
                                    <Tab value="ConsolidadoVentas"
                                        >Consolidado Ventas</Tab
                                    >
                                    <Tab value="MovImport"
                                        >Movimientos Importados</Tab
                                    >
                                </TabList>
                                <TabPanels>
                                    <TabPanel value="GastoGeneral">
                                        <Panel toggleable>
                                            <template #header>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    Gastos General
                                                </div>
                                            </template>
                                            <table
                                                v-if="datatable"
                                                class="table-auto w-full border-collapse border border-gray-200"
                                            >
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Finca
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Lote
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            CodigoLote
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Hect
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Detalle
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.finca
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{ datatable.lote }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.codigo
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{ datatable.hect }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            <table
                                                                class="table-auto w-full border-collapse border border-gray-200"
                                                            >
                                                                <thead
                                                                    class="bg-gray-100"
                                                                >
                                                                    <tr>
                                                                        <th
                                                                            colspan="9"
                                                                            class="border border-gray-300 px-2 py-1"
                                                                        >
                                                                            Costo
                                                                            Total
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Arriendo
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Financiero
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Finca
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Grupo
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Maquinaria
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Materia
                                                                            Prima
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Oficina
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Servicio
                                                                            Agropecuarios
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Total
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Arriendos"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                "N/A"
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Financiero"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Finca"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Grupo"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Maquinaria"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Materia Prima"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Oficina"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Servicios Agropecuarios"
                                                                                )
                                                                                    ?.gasto_total ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center font-semibold"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_total
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table
                                                                class="table-auto w-full border-collapse border border-gray-200 mt-4"
                                                            >
                                                                <thead
                                                                    class="bg-gray-100"
                                                                >
                                                                    <tr>
                                                                        <th
                                                                            colspan="9"
                                                                            class="border border-gray-300 px-2 py-1"
                                                                        >
                                                                            Costo
                                                                            X
                                                                            Hect
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Arriendo
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Financiero
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Finca
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Grupo
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Maquinaria
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Materia
                                                                            Prima
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Oficina
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Servicio
                                                                            Agropecuarios
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Total
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Arriendos"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                "N/A"
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Financiero"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Finca"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Grupo"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Maquinaria"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Materia Prima"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Oficina"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.data.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Servicios Agropecuarios"
                                                                                )
                                                                                    ?.gastoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center font-semibold"
                                                                        >
                                                                            {{
                                                                                datatable.gastoxhect
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <table
                                                                class="table-auto w-full border-collapse border border-gray-200 mt-4"
                                                            >
                                                                <thead
                                                                    class="bg-gray-100"
                                                                >
                                                                    <tr>
                                                                        <th
                                                                            colspan="9"
                                                                            class="border border-gray-300 px-2 py-1"
                                                                        >
                                                                            Presupuesto
                                                                            Costo
                                                                            X
                                                                            Hect
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Arriendo
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Financiero
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Finca
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Grupo
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Maquinaria
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Materia
                                                                            Prima
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Oficina
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Servicio
                                                                            Agropecuarios
                                                                        </th>
                                                                        <th
                                                                            class="border border-gray-300 px-2 py-1"
                                                                            style="
                                                                                width: 11%;
                                                                            "
                                                                        >
                                                                            Total
                                                                        </th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    <tr>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Arriendos"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                "N/A"
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Financieros"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Finca"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Grupo"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Maquinaria"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Materia Prima"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Oficina"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt.find(
                                                                                    (
                                                                                        gasto
                                                                                    ) =>
                                                                                        gasto.nombre_gasto ===
                                                                                        "Servicios Agropecuarios"
                                                                                )
                                                                                    ?.costoxhect ||
                                                                                ""
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="border border-gray-300 px-2 py-1 text-center"
                                                                        >
                                                                            {{
                                                                                datatable.gasto_general.ppt
                                                                                    .reduce(
                                                                                        (
                                                                                            acc,
                                                                                            gasto
                                                                                        ) =>
                                                                                            acc +
                                                                                            (gasto.costoxhect_n ||
                                                                                                0),
                                                                                        0
                                                                                    )
                                                                                    .toLocaleString(
                                                                                        "es-ES"
                                                                                    )
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <template #icons>
                                                <Button
                                                    icon="pi pi-print"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />

                                                <Button
                                                    icon="pi pi-file-excel"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />
                                            </template>
                                            <div></div>
                                        </Panel>
                                    </TabPanel>
                                    <TabPanel value="GastoDetallado">
                                        <Panel toggleable>
                                            <template #header>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    Gastos Detallado
                                                </div>
                                            </template>

                                            <template #icons>
                                                <Button
                                                    icon="pi pi-print"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />

                                                <Button
                                                    icon="pi pi-file-excel"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />
                                            </template>
                                            <div>
                                                <div v-if="datatable">
                                                    <table
                                                        class="min-w-full divide-y divide-gray-200 border border-gray-300"
                                                    >
                                                        <thead
                                                            class="bg-gray-100"
                                                        >
                                                            <tr>
                                                                <th
                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                >
                                                                    Finca
                                                                </th>
                                                                <th
                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                >
                                                                    Lote
                                                                </th>
                                                                <th
                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                >
                                                                    Codigo
                                                                </th>
                                                                <th
                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                >
                                                                    Hect
                                                                </th>
                                                                <th
                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                    colspan="2"
                                                                >
                                                                    Detalle
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody
                                                            class="bg-white divide-y divide-gray-200"
                                                        >
                                                            <tr>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                >
                                                                    {{
                                                                        datatable.finca
                                                                    }}
                                                                </td>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                >
                                                                    {{
                                                                        datatable.lote
                                                                    }}
                                                                </td>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                >
                                                                    {{
                                                                        datatable.codigo
                                                                    }}
                                                                </td>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                >
                                                                    {{
                                                                        datatable.hect
                                                                    }}
                                                                </td>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                    colspan="2"
                                                                >
                                                                    <table>
                                                                        <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    Tipo
                                                                                </th>
                                                                                <th>
                                                                                    Detalle
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr
                                                                                v-for="(
                                                                                    gasto,
                                                                                    index
                                                                                ) in datatable.data"
                                                                                :key="
                                                                                    index
                                                                                "
                                                                            >
                                                                                <td
                                                                                    class="px-4"
                                                                                >
                                                                                    {{
                                                                                        gasto.nombre_gasto
                                                                                    }}
                                                                                </td>
                                                                                <td>
                                                                                    <table
                                                                                        class="min-w-full divide-y divide-gray-200 border border-gray-300"
                                                                                    >
                                                                                        <thead
                                                                                            class="bg-gray-100"
                                                                                        >
                                                                                            <tr>
                                                                                                <th
                                                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                                                    style="
                                                                                                        width: 300px;
                                                                                                    "
                                                                                                >
                                                                                                    Gasto
                                                                                                </th>
                                                                                                <th
                                                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                                                    colspan="2"
                                                                                                >
                                                                                                    Detalle
                                                                                                </th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody
                                                                                            class="bg-white divide-y divide-gray-200"
                                                                                        >
                                                                                            <tr
                                                                                                v-for="(
                                                                                                    tipogasto,
                                                                                                    index
                                                                                                ) in gasto.data"
                                                                                                :key="
                                                                                                    index
                                                                                                "
                                                                                            >
                                                                                                <td
                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                                                >
                                                                                                    {{
                                                                                                        tipogasto.nombre_tipogasto
                                                                                                    }}
                                                                                                </td>
                                                                                                <td
                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                                                    colspan="2"
                                                                                                >
                                                                                                    <table
                                                                                                        class="min-w-full divide-y divide-gray-200 border border-gray-300"
                                                                                                    >
                                                                                                        <thead
                                                                                                            class="bg-gray-100"
                                                                                                        >
                                                                                                            <tr>
                                                                                                                <th
                                                                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                                                                    style="
                                                                                                                        width: 200px;
                                                                                                                    "
                                                                                                                >
                                                                                                                    Descripcion
                                                                                                                </th>
                                                                                                                <th
                                                                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                                                                    style="
                                                                                                                        width: 150px;
                                                                                                                    "
                                                                                                                >
                                                                                                                    Gasto
                                                                                                                    x
                                                                                                                    Hect
                                                                                                                </th>
                                                                                                                <th
                                                                                                                    class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                                                                    style="
                                                                                                                        width: 150px;
                                                                                                                    "
                                                                                                                >
                                                                                                                    Total
                                                                                                                    Gasto
                                                                                                                </th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody
                                                                                                            class="bg-white divide-y divide-gray-200"
                                                                                                        >
                                                                                                            <tr
                                                                                                                v-for="(
                                                                                                                    subtipogasto,
                                                                                                                    index
                                                                                                                ) in tipogasto.data"
                                                                                                                :key="
                                                                                                                    index
                                                                                                                "
                                                                                                            >
                                                                                                                <td
                                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                                                                >
                                                                                                                    {{
                                                                                                                        subtipogasto.nombre_subtipogasto
                                                                                                                    }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 text-right"
                                                                                                                >
                                                                                                                    {{
                                                                                                                        subtipogasto.gastoxhect
                                                                                                                    }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 text-right"
                                                                                                                >
                                                                                                                    {{
                                                                                                                        subtipogasto.gasto_total
                                                                                                                    }}
                                                                                                                </td>
                                                                                                            </tr>

                                                                                                            <tr
                                                                                                                class="bg-gray-200"
                                                                                                            >
                                                                                                                <td
                                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold"
                                                                                                                >
                                                                                                                    Subtotal
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold text-right"
                                                                                                                >
                                                                                                                    {{
                                                                                                                        tipogasto.gastoxhect
                                                                                                                    }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold text-right"
                                                                                                                >
                                                                                                                    {{
                                                                                                                        tipogasto.gasto_total
                                                                                                                    }}
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr
                                                                                                class="bg-gray-200"
                                                                                            >
                                                                                                <td
                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold"
                                                                                                    colspan="2"
                                                                                                >
                                                                                                    Subtotal
                                                                                                    {{
                                                                                                        gasto.nombre_gasto
                                                                                                    }}
                                                                                                </td>

                                                                                                <td
                                                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold text-right"
                                                                                                >
                                                                                                    <div
                                                                                                        class="grid grid-cols-3 gap-4"
                                                                                                    >
                                                                                                        <div></div>
                                                                                                        <div>
                                                                                                            {{
                                                                                                                gasto.gastoxhect
                                                                                                            }}
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class=""
                                                                                                        >
                                                                                                            {{
                                                                                                                gasto.gasto_total
                                                                                                            }}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold"
                                                                    colspan="4"
                                                                >
                                                                    Total
                                                                </td>
                                                                <td
                                                                    class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold"
                                                                >
                                                                    <div
                                                                        class="grid grid-cols-2 gap-4"
                                                                    >
                                                                        <div
                                                                            class="gap-4"
                                                                        >
                                                                            Costo
                                                                            X
                                                                            Hect
                                                                            :
                                                                            {{
                                                                                datatable.gastoxhect
                                                                            }}
                                                                        </div>
                                                                        <div
                                                                            class="gap-4"
                                                                        >
                                                                            Costo
                                                                            Total
                                                                            :
                                                                            {{
                                                                                datatable.gasto_total
                                                                            }}
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </Panel>
                                    </TabPanel>

                                    <TabPanel value="Movimientos">
                                        <Panel toggleable>
                                            <template #header>
                                                <div
                                                    class="flex items-center gap-2 text-lg font-semibold text-gray-800"
                                                    v-if="datatable"
                                                >
                                                    Movimientos
                                                    <span
                                                        class="ml-2 text-blue-600"
                                                        >Finca:
                                                        {{
                                                            datatable.finca
                                                        }}</span
                                                    >
                                                    <span
                                                        class="ml-2 text-green-600"
                                                        >Lote:
                                                        {{
                                                            datatable.lote
                                                        }}</span
                                                    >
                                                    <span
                                                        class="ml-2 text-blue-600"
                                                        >Codigo:
                                                        {{
                                                            datatable.codigo
                                                        }}</span
                                                    >
                                                    <span
                                                        class="ml-2 text-green-600"
                                                        >Hect:
                                                        {{
                                                            datatable.hect
                                                        }}</span
                                                    >
                                                </div>
                                            </template>

                                            <template #icons>
                                                <Button
                                                    icon="pi pi-print"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />

                                                <Button
                                                    icon="pi pi-file-excel"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />
                                            </template>
                                            <div>
                                                <table
                                                    v-if="datatable"
                                                    class="min-w-full divide-y divide-gray-200 border border-gray-300"
                                                >
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Fecha
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Tipo
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Codigo
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Labor
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Grupo
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Producto
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Cantidad
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Valor Unit
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                            >
                                                                Total
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody
                                                        class="bg-white divide-y divide-gray-200"
                                                    >
                                                        <tr
                                                            v-for="(
                                                                item, index
                                                            ) in datatable.movcumplidos"
                                                            :key="index"
                                                        >
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{ item.fecha }}
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{ item.tipo }}
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >

                                                            <Chip :label="item.codigo"   v-tooltip.bottom="{value: item.obs, }"/>
                                                                
                                                     

                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{ item.labor }}
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{
                                                                    item.GrupoProducto
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{
                                                                    item.producto
                                                                }}
                                                            </td>

                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{
                                                                    item.cantidad
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{
                                                                    item.valor_unit
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                            >
                                                                {{
                                                                    item.valor_total
                                                                }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </Panel>
                                    </TabPanel>
                                    <TabPanel value="ConsolidadoVentas">
                                        <Panel toggleable>
                                            <template #header>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    Consolidado Ventas
                                                </div>
                                            </template>
                                            <div
                                                class="flex items-center gap-2 text-lg font-semibold text-gray-800"
                                                v-if="datatable"
                                            >
                                                <span class="ml-2 text-blue-600"
                                                    >Finca:
                                                    {{ datatable.finca }}</span
                                                >
                                                <span
                                                    class="ml-2 text-green-600"
                                                    >Lote:
                                                    {{ datatable.lote }}</span
                                                >
                                                <span class="ml-2 text-blue-600"
                                                    >Codigo:
                                                    {{ datatable.codigo }}</span
                                                >
                                                <span
                                                    class="ml-2 text-green-600"
                                                    >Hect:
                                                    {{ datatable.hect }}</span
                                                >
                                            </div>
                                            <template #icons>
                                                <Button
                                                    icon="pi pi-print"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />

                                                <Button
                                                    icon="pi pi-file-excel"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />
                                            </template>
                                            <div v-if="datatable">
                                                

                                                <table
                                                    class="table-auto w-full border-collapse border border-gray-200"
                                                >
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Distrito
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Finca
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Lote
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Codigo
                                                            </th>

                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Hectareas
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Kg
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Bultos/Hect
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Total Costo
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Costo/Hect
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Ingreso/Hect
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Total Ingreso
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Utilidad
                                                            </th>
                                                            <th
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                Utilidad/Hect
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .distrito
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .finca
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .lote
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .codigo
                                                                }}
                                                            </td>

                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .hect
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .total_kilos
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .Bultos
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .total_gasto
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .gasto_x_hect
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .ingresos_x_hect
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .total_ingresos
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .utilidad
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1"
                                                            >
                                                                {{
                                                                    datatable
                                                                        .consolidadoventas
                                                                        .utilidad_x_hect
                                                                }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </Panel>
                                    </TabPanel>

                                    <TabPanel value="MovImport">
                                        <Panel toggleable>
                                            <template #header>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    Movimientos Importados
                                                </div>
                                            </template>
                                            <div
                                                class="flex items-center gap-2 text-lg font-semibold text-gray-800"
                                                v-if="datatable"
                                            >
                                                <span class="ml-2 text-blue-600"
                                                    >Finca:
                                                    {{ datatable.finca }}</span
                                                >
                                                <span
                                                    class="ml-2 text-green-600"
                                                    >Lote:
                                                    {{ datatable.lote }}</span
                                                >
                                                <span class="ml-2 text-blue-600"
                                                    >Codigo:
                                                    {{ datatable.codigo }}</span
                                                >
                                                <span
                                                    class="ml-2 text-green-600"
                                                    >Hect:
                                                    {{ datatable.hect }}</span
                                                >
                                            </div>
                                            <table
                                                class="min-w-full divide-y divide-gray-200 border border-gray-300"
                                                v-if="datatable"
                                            >
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Fecha Subida
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Gasto
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Tipo Gasto
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Codigo
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Sub Tipo Gasto
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Cantidad
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Valor Unit
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                        >
                                                            Valor Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white divide-y divide-gray-200"
                                                >
                                                    <tr
                                                        v-for="(
                                                            item, index
                                                        ) in datatable.mov_importados"
                                                        :key="index"
                                                    >
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300"
                                                        >
                                                            {{ item.fecha }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300"
                                                        >
                                                            {{ item.gasto }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300"
                                                        >
                                                            {{ item.tipogasto }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300"
                                                        >
                                                            {{ item.codigo }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300"
                                                        >
                                                            {{
                                                                item.subtipogasto
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                        >
                                                            {{
                                                                item.cantidad_f
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                        >
                                                            {{
                                                                item.preciounitario_f
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap border border-gray-300 text-right"
                                                        >
                                                            {{ item.total_f }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                         <Popover ref="op">
            <div class="flex flex-col gap-4 w-[25rem]">
                hola
             
            </div>
        </Popover>
                    
                                            <template #icons>
                                                <Button
                                                    icon="pi pi-print"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />

                                                <Button
                                                    icon="pi pi-file-excel"
                                                    class="mr-2"
                                                    severity="secondary"
                                                    text
                                                />
                                            </template>
                                            <div></div>
                                        </Panel>
                                    </TabPanel>
                                </TabPanels>
                            </Tabs>

                            <!----->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
