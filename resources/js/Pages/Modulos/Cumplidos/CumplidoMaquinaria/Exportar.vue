<script setup>
// Importar librerias de Vue
import { ref, onMounted, computed, watch } from "vue";
// Importar componentes de la aplicacion
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// Importar hooks de useForm
import { useForm } from "@inertiajs/vue3";
// Importar funcion de Toast
import { useToast } from "primevue/usetoast";

import { FilterMatchMode } from "@primevue/core/api";

const toast = useToast();

const props = defineProps({
    get_finca: { type: Object },
});

const form = useForm({
    fecha_inicio: null,
    fecha_fin: null,
    finca :null,
    RegLote_id :null,
    lote :null,

});
const CumplidoMaquinaria = ref(null);
const OptionsFinca = ref(props.get_finca);

// Datos del Lote
const OptionsLote = ref(props.get_lote);

// Datos del registro lote
const RegLote = ref(false);

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

async function submitExportExcel() {
    const fecha_inicio = new Date(form.fecha_inicio).toISOString().slice(0, 10);
    const fecha_fin = new Date(form.fecha_fin).toISOString().slice(0, 10);
    var url = route("CumpMaquinaria.ExportarXLSX", { fecha_inicio, fecha_fin });

    window.open(url, "_blank");
}

async function submitExportExcelPost() {
    const fecha_inicio = form.fecha_inicio === null ? null : new Date(form.fecha_inicio).toISOString().slice(0, 10);
    const fecha_fin = form.fecha_fin === null ? null : new Date(form.fecha_fin).toISOString().slice(0, 10);
    const response = await axios.post(route("CumpMaquinaria.exportXLSXpost"), {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        finca:  form.finca,
        lote:  form.lote,
        reglote:  null,
    }, { responseType: 'blob' });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `cumplidos_maquinaria_.xlsx`);
    document.body.appendChild(link);
    link.click();
}


/**
 * Consulta general de cumplidos de maquinaria segun las fechas ingresadas en el formulario
 * y devuelve la respuesta en consola.
 */
async function ConsultaGeneral() {
    console.log(selectedBuscador.value);
    const fecha_inicio = form.fecha_inicio === null ? null : new Date(form.fecha_inicio).toISOString().slice(0, 10);
    const fecha_fin = form.fecha_fin === null ? null : new Date(form.fecha_fin).toISOString().slice(0, 10);
    await axios
        .post(route("CumpMaquinaria.consulta"), {
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin,
            finca:  form.finca,
            lote:  form.lote,
            reglote:  null,

        })
        .then(function (response) {
            console.log(response.data);
            CumplidoMaquinaria.value = response.data;
        })
        .catch((e) => console.log(e));
}

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    codigo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    fecha: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

const calculateTotal = (name) => {
    let total = 0;
    if (CumplidoMaquinaria.value) {
        for (let CumplidoMaquinarias of CumplidoMaquinaria.value) {
            if (CumplidoMaquinarias.representative.name === name) {
                total = CumplidoMaquinarias.valor_total + total;
            }
        }
    }

    return total;
};

const selectedBuscador = ref();
const CamposBuscador = ref([
    { name: "RangoFechas", code: "date" },
    { name: "Cosecha", code: "Cosecha" },
    { name: "Finca", code: "Finca" },
    { name: "Lote", code: "Lote" },
]);

/**
 * Muestra un mensaje de advertencia cuando se deselecciona un producto en el buscador.
 * @param {object} event - Evento que se lanza cuando se deselecciona un producto.
 */
const RangoFechasSelected = ref(false);
const CosechaSelected = ref(false);
const FincaSelected = ref(false);
const LoteSelected = ref(false);

const onFilterSearchselect = () => {
    RangoFechasSelected.value = selectedBuscador.value.some((item) => item.name === "RangoFechas" && item.code === "date");
    CosechaSelected.value = selectedBuscador.value.some((item) => item.name === "Cosecha" && item.code === "Cosecha");
    FincaSelected.value = selectedBuscador.value.some((item) => item.name === "Finca" && item.code === "Finca");
    LoteSelected.value = selectedBuscador.value.some((item) => item.name === "Lote" && item.code === "Lote");

    if (!RangoFechasSelected.value) {
        form.fecha_inicio = null;
        form.fecha_fin = null;
    }
    if(!FincaSelected.value){
        form.finca = null;
    }
    if(!LoteSelected.value){
        form.lote = null;
    }
    if(!CosechaSelected.value){
        form.RegLote_id = null;
    }

}


const items = [
    {
        label: 'Descargar Excel',
        command: () => {
            submitExportExcelPost();
        }
    },
]

</script>

<template>
    <Head title="Cumplidos Maquinaria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Exportar - Cumplido Maquinaria
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
                    <form @submit.prevent="submitExportExcel">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols gap-4 mt-2">
                                    <div >
                                        <label for="Select"
                                            >Filtros busqueda</label
                                        >
                                        <MultiSelect
                                            v-model="selectedBuscador"
                                            display="chip"
                                            :options="CamposBuscador"
                                            optionLabel="name"
                                            filter
                                            placeholder="Select Campos Busqueda"
                                            :maxSelectedLabels="4"
                                            @change="onFilterSearchselect"
                                            class="w-full"
                                        />
                                    </div>
                                    <!-- Fecha-->
                                </div>
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <div v-show="FincaSelected | CosechaSelected | LoteSelected">
                                        <label for="Finca">Finca</label>
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
                                    </div>
                                    <div  v-show="LoteSelected | CosechaSelected">
                                        <label for="Lote">lote</label>
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
                                    </div>
                                    <div v-show="CosechaSelected" >
                                        <label for="Codigo">Codigo Lote</label>
                                    </div>
                                    <div v-show="RangoFechasSelected">
                                        <label for="fecha_inicio"
                                            >Fecha Inicio</label
                                        >
                                        <DatePicker
                                            v-model="form.fecha_inicio"
                                            showIcon
                                            fluid
                                            iconDisplay="input"
                                            dateFormat="dd/mm/yy"
                                        />
                                    </div>

                                    <div v-show="RangoFechasSelected">
                                        <label for="fecha_fin">FechaFin</label>
                                        <DatePicker
                                            v-model="form.fecha_fin"
                                            showIcon
                                            fluid
                                            iconDisplay="input"
                                            dateFormat="dd/mm/yy"
                                        />
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <Fluid>
                                <div class="">
                                    <!-- :disabled="isInvalid"  -->
                                    <SplitButton fluid  label="Buscar" :model="items" @click="ConsultaGeneral" raised></SplitButton>

                                </div>
                                </Fluid>
                            </template>
                        </Card>
                        <Card style="overflow: hidden">
                            <template #content>
                                <DataTable
                                    v-model:filters="filters"
                                    :value="CumplidoMaquinaria"
                                    rowGroupMode="subheader"
                                    groupRowsBy="representative.name"
                                    sortMode="single"
                                    sortField="representative.name"
                                    :sortOrder="1"
                                    scrollable
                                    scrollHeight="400px"
                                    tableStyle="min-width: 50rem"
                                >
                                    <Column
                                        field="codigo"
                                        sortable
                                        header="Codigo"
                                    ></Column>
                                    <Column
                                        field="fecha"
                                        sortable
                                        header="Fecha"
                                    ></Column>
                                    <Column
                                        field="finca"
                                        sortable
                                        header="Lote"
                                    ></Column>
                                    <Column
                                        field="lote"
                                        sortable
                                        header="Lote"
                                    ></Column>
                                    <Column
                                        field="labor"
                                        sortable
                                        header="labor"
                                    ></Column>
                                    <Column
                                        field="cantidad"
                                        sortable
                                        header="cantidad"
                                    ></Column>
                                    <Column
                                        field="valor_total"
                                        header="Total"
                                    ></Column>
                                    <template #groupheader="slotProps">
                                        <div class="flex items-center gap-2">
                                            <span>{{
                                                slotProps.data.representative
                                                    .name
                                            }}</span>
                                        </div>
                                    </template>
                                    <template #groupfooter="slotProps">
                                        <div
                                            class="flex justify-end font-bold w-full"
                                        >
                                            Total:
                                            {{
                                                calculateTotal(
                                                    slotProps.data
                                                        .representative.name
                                                )
                                            }}
                                        </div>
                                    </template>
                                </DataTable>
                            </template>
                        </Card>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
