<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    Reglote: { type: Object },
});

import axios from "axios";

const datatable = ref(null);

onMounted(() => {
    axios
        .get(route("costos.report.CostoxLote_PPT", props.Reglote))
        .then((response) => {
            datatable.value = response.data;
            console.info(datatable);
        })
        .catch((error) => {
            console.error(error);
        });
});
</script>

<template>
    <div>
        <Card class="bg-teal-50">
            <template #title>Presupuesto</template>

            <template #content>
                <Message
                    
                    :severity="
                        (() => {
                            const value =
                                datatable.datatable.find(
                                    (item) => item.concepto === 'Ejecutado'
                                )?.data.costoxhect || 0;
                            if (value >= 0 && value <= 0.8) return 'success';
                            if (value >= 0.81 && value <= 0.99) return 'warn';
                            return 'error';
                        })()
                    "
                    icon="pi pi-times-circle"
                    class="mb-2"
                    v-if="
                        datatable &&
                        datatable.datatable &&
                        datatable.datatable.length > 0
                    "
                >
             
                    <span
                        v-if="
                            (() => {
                                const value =
                                    datatable.datatable.find(
                                        (item) => item.concepto === 'Ejecutado'
                                    )?.data.costoxhect || 0;
                                return value >= 0 && value <= 0.8;
                            })()
                        "
                        >Todo está en orden con los costos, seguimos dentro del
                        presupuesto.
                    </span>
                    <span
                        v-else-if="
                            (() => {
                                const value =
                                    datatable.datatable.find(
                                        (item) => item.concepto === 'Ejecutado'
                                    )?.data.costoxhect || 0;
                                return value >= 0.81 && value <= 0.99;
                            })()
                        "
                        >El presupuesto está cerca de alcanzarse. Te recomiendo
                        ser cuidadoso con los costos para evitar
                        excederte.</span
                    >
                    <span v-else
                        >Se ha excedido el presupuesto. Es necesario comunicarlo
                        y solicitar la autorización correspondiente por parte de
                        Gerencia.</span
                    >
                </Message>
                <table class="min-w-full divide-y divide-gray-200" v-if="datatable">
                    <thead class="bg-gray-50" >
                        <tr>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Finca
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Lote
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                CodigoLote
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Hect
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Costo X Hect
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                {{ datatable.Finca }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                {{ datatable.Lote }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                {{ datatable.Codigo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                {{ datatable.Hect }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Concepto
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Materia Prima
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Servicio Agropeciarios
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Total Costo X Hect
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="item in datatable.datatable"
                                            :key="item.concepto"
                                            :class="{
                                                'bg-gray-50 font-semibold':
                                                    item.concepto ===
                                                    'Sub Total',
                                            }"
                                        >
                                            <td
                                                class="px-6 py-2 whitespace-nowrap text-right"
                                            >
                                                {{ item.concepto }}
                                            </td>
                                            <td
                                                class="px-6 py-2 whitespace-nowrap text-right items-center"
                                            >
                                                <Message
                                                    v-if="
                                                        item.concepto ===
                                                        'Ejecutado'
                                                    "
                                                    :severity="
                                                        (() => {
                                                            const value =
                                                                item.data.detallado.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        'Materia Prima'
                                                                )?.costoxhect ||
                                                                0;
                                                            if (
                                                                value >= 0 &&
                                                                value <= 0.8
                                                            )
                                                                return 'success';
                                                            if (
                                                                value >= 0.81 &&
                                                                value <= 0.98
                                                            )
                                                                return 'warn';
                                                            return 'error';
                                                        })()
                                                    "
                                                    variant="simple"
                                                    class="text-right"
                                                >
                                                    {{
                                                        item.data.detallado.find(
                                                            (gasto) =>
                                                                gasto.nombre_gasto ===
                                                                "Materia Prima"
                                                        )?.costoxhectf || ""
                                                    }}
                                                </Message>
                                                <span v-else>{{
                                                    item.data.detallado.find(
                                                        (gasto) =>
                                                            gasto.nombre_gasto ===
                                                            "Materia Prima"
                                                    )?.costoxhectf || ""
                                                }}</span>
                                            </td>
                                            <td
                                                class="px-6 py-2 whitespace-nowrap text-right items-center"
                                            >
                                                <Message
                                                    v-if="
                                                        item.concepto ===
                                                        'Ejecutado'
                                                    "
                                                    :severity="
                                                        (() => {
                                                            const value =
                                                                item.data.detallado.find(
                                                                    (gasto) =>
                                                                        gasto.nombre_gasto ===
                                                                        'Servicios Agropecuarios'
                                                                )?.costoxhect ||
                                                                0;
                                                            if (
                                                                value >= 0 &&
                                                                value <= 0.8
                                                            )
                                                                return 'success';
                                                            if (
                                                                value >= 0.81 &&
                                                                value <= 0.98
                                                            )
                                                                return 'warn';
                                                            return 'error';
                                                        })()
                                                    "
                                                    variant="simple"
                                                    class="text-right"
                                                >
                                                    {{
                                                        item.data.detallado.find(
                                                            (gasto) =>
                                                                gasto.nombre_gasto ===
                                                                "Servicios Agropecuarios"
                                                        )?.costoxhectf || ""
                                                    }}
                                                </Message>
                                                <span v-else>{{
                                                    item.data.detallado.find(
                                                        (gasto) =>
                                                            gasto.nombre_gasto ===
                                                            "Servicios Agropecuarios"
                                                    )?.costoxhectf || ""
                                                }}</span>
                                            </td>
                                            <td
                                                class="px-6 py-2 whitespace-nowrap text-right items-center"
                                            >
                                                <Message
                                                    v-if="
                                                        item.concepto ===
                                                        'Ejecutado'
                                                    "
                                                    :severity="
                                                        (() => {
                                                            const value =
                                                                item.data
                                                                    .costoxhect ||
                                                                0;
                                                            if (
                                                                value >= 0 &&
                                                                value <= 0.8
                                                            )
                                                                return 'success';
                                                            if (
                                                                value >= 0.81 &&
                                                                value <= 0.98
                                                            )
                                                                return 'warn';
                                                            return 'error';
                                                        })()
                                                    "
                                                    variant="simple"
                                                    class="text-right"
                                                >
                                                    {{ item.data.costoxhectf }}
                                                </Message>
                                                <span v-else>{{
                                                    item.data.costoxhectf
                                                }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </Card>
    </div>
</template>
