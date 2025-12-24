<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

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
const OptionsCodigoLote = ref(null); // Datos del Lote
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
</script>

<template>
    <Head title="Consolidado Gastos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Consolidado Ventas por lote
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submitCrear">
                        <div class="grid grid-cols-4 gap-4">
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
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                         

                            <table
                                v-if="datatable"
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
                                            {{ datatable.distrito }}
                                        </td>
                                        <td
                                            class="border border-gray-300 px-2 py-1"
                                        >
                                            {{ datatable.finca }}
                                        </td>
                                        <td
                                            class="border border-gray-300 px-2 py-1"
                                        >
                                            {{ datatable.lote }}
                                        </td>
                                        <td
                                            class="border border-gray-300 px-2 py-1"
                                        >
                                            {{ datatable.codigo }}
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
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th
                                                            colspan="9"
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Costo Total
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Arriendo
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Financiero
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Finca
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Grupo
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Maquinaria
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Materia Prima
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Oficina
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Servicio
                                                            Agropecuario
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Arriendos"
                                                                )
                                                                    ?.gasto_total ||
                                                                "N/A"
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Financiero"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Finca"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Grupo"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Maquinaria"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Materia Prima"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Oficina"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Servicios Agropecuarios"
                                                                )
                                                                    ?.gasto_total ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
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
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th
                                                            colspan="9"
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Costo X Hect
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Arriendo
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Financiero
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Finca
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Grupo
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Maquinaria
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Materia Prima
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Oficina
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Servicio
                                                            Agropecuario
                                                        </th>
                                                        <th
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Arriendos"
                                                                )?.gastoxhect ||
                                                                "N/A"
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Financiero"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Finca"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Grupo"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Maquinaria"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Materia Prima"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Oficina"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastos.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        "Servicios Agropecuarios"
                                                                )?.gastoxhect ||
                                                                ""
                                                            }}
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1"
                                                        >
                                                            {{
                                                                datatable.gastoxhect
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!----->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
