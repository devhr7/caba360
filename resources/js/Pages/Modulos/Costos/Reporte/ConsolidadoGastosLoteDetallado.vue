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
        .post(route("costos.report.DataMegahistoria"), form)
        .then(function (response) {
            datatable.value = response.data;
        });
}

async function exportPDF() {
    await axios
        .post(route("costos.report.GastosPorLoteDetalladoExport"), form, { responseType: 'blob' })
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'ReporteGastosDetallado.pdf');
            document.body.appendChild(link);
            link.click();
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
</script>

<template>

    <Head title="Consolidado Gastos Detallado" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Consolidado Gastos Por Lote Detallado
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Panel header="Buscador" toggleable>
                        <p class="m-0">
                        <form @submit.prevent="submitCrear">
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label for="lote" class="block text-sm font-medium text-gray-700">Finca</label>
                                    <Select v-model="form.finca" :options="OptionsFinca" filter showClear
                                        optionLabel="label" placeholder="Seleccionar" v-on:change="getOptionsLote"
                                        class="w-full md:w-56">
                                        <template #value="slotProps">
                                            <div v-if="slotProps.value" class="flex items-center">
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
                                    <label for="lote" class="block text-sm font-medium text-gray-700">Lote</label>
                                    <Select v-model="form.lote" :options="OptionsLote" filter
                                        :loading="isLoadingOptionsLote" optionLabel="label" placeholder="Seleccionar"
                                        v-on:change="getOptionsRegLote" showClear class="w-full md:w-56">
                                        <template #value="slotProps">
                                            <div v-if="slotProps.value" class="flex items-center">
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
                                    <label for="lote" class="block text-sm font-medium text-gray-700">Seleccionar Estado
                                        Lotes</label>
                                    <SelectButton v-model="form.status" :options="optionsFiltroStatus"
                                        optionLabel="name" v-on:change="getOptionsRegLote" aria-labelledby="multiple" />
                                </div>
                                <div>
                                    <label for="lote" class="block text-sm font-medium text-gray-700">Codigo
                                        Lote</label>

                                    <AutoComplete v-model="form.codigolote" :suggestions="FilteredRegLote" dataKey="id"
                                        @complete="search" :loading="isLoadingOptionsRegLote" optionLabel="Codigo"
                                        optionV="Codigo" dropdown />
                                </div>
                                <div>
                                    <Button type="submit" label="Buscar" severity="info" />
                                </div>
                            </div>
                        </form>
                        </p>
                    </Panel>


                    <Panel header="Reporte" toggleable>
                        <template #icons>
                            <Button icon="pi pi-print" severity="secondary" rounded text @click="exportPDF" />

                        </template>
                        <div class=" gap-4">
                            <div v-if="datatable">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th
                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                Finca
                                            </th>
                                            <th
                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                Lote
                                            </th>
                                            <th
                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                Codigo
                                            </th>
                                            <th
                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                Hect
                                            </th>
                                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                colspan="2">
                                                Detalle
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                {{ datatable.finca }}
                                            </td>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                {{ datatable.lote }}
                                            </td>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                {{ datatable.codigo }}
                                            </td>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                {{ datatable.hect }}
                                            </td>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300" colspan="2">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>Detalle</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(
                                                            gasto, index
                                                        ) in datatable.data" :key="index">
                                                            <td>
                                                                {{
                                                                    gasto.nombre_gasto
                                                                }}
                                                            </td>
                                                            <td>
                                                                <table
                                                                    class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                                                    <thead class="bg-gray-100">
                                                                        <tr>
                                                                            <th
                                                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                                                Gasto
                                                                            </th>
                                                                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300"
                                                                                colspan="2">
                                                                                Detalle
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                                        <tr v-for="(
                                                                            tipogasto,
                                                                                index
                                                                        ) in gasto.data" :key="index
                                                                            ">
                                                                            <td
                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                                                {{
                                                                                    tipogasto.nombre_tipogasto
                                                                                }}
                                                                            </td>
                                                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300"
                                                                                colspan="2">
                                                                                <table
                                                                                    class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                                                                    <thead class="bg-gray-100">
                                                                                        <tr>
                                                                                            <th
                                                                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                                                                Descripcion
                                                                                            </th>
                                                                                            <th
                                                                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                                                                Gasto
                                                                                                x
                                                                                                Hect
                                                                                            </th>
                                                                                            <th
                                                                                                class="px-2 py-1 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-300">
                                                                                                Total
                                                                                                Gasto
                                                                                            </th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody
                                                                                        class="bg-white divide-y divide-gray-200">
                                                                                        <tr v-for="(
                                                                                            subtipogasto,
                                                                                                index
                                                                                        ) in tipogasto.data" :key="index
                                                                                            ">
                                                                                            <td
                                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                                                                {{
                                                                                                    subtipogasto.nombre_subtipogasto
                                                                                                }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                                                                {{
                                                                                                    subtipogasto.gastoxhect
                                                                                                }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300">
                                                                                                {{
                                                                                                    subtipogasto.gasto_total
                                                                                                }}
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr class="bg-gray-200">
                                                                                            <td
                                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                                                                Subtotal
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                                                                {{
                                                                                                    tipogasto.gastoxhect
                                                                                                }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                                                                {{
                                                                                                    tipogasto.gasto_total
                                                                                                }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>

                                                                        <tr class="bg-gray-200">
                                                                            <td
                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                                                Subtotal
                                                                                {{
                                                                                    gasto.nombre_gasto
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                                                {{
                                                                                    gasto.gastoxhect
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                                                {{
                                                                                    gasto.gasto_total
                                                                                }}
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
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold"
                                                colspan="4">
                                                Total
                                            </td>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                {{ datatable.gastoxhect }}
                                            </td>
                                            <td class="px-2 py-1 whitespace-nowrap border border-gray-300 font-bold">
                                                {{ datatable.gasto_total }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </Panel>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
