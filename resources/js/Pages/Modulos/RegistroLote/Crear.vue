<script setup>
import { ref, onMounted } from "vue";
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
    errors: { type: Object },
});

// Formulario
const form = useForm({
    distrito: null,
    finca: null,
    lote: null,
    hect: null,
    FechaInicio: null,
    FechaSiembra: null,
    FechaRecoleccion: null,
    FechaGerminacion: null,
    Codigo: null,
    NombreLote: null,
    TipoCultivo: null,
    TipoVariedad: null,
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
    global: { value: null },
    RegistroLotes: { value: null },
    distrito_id: { value: null },
    finca_id: { value: null },
    NombreLote: { value: null },
    statusLote1: { value: null },
    Codigo: { value: null },
    Hect: { value: null },
});

const reg_datatable = ref({});
const validacionActivos = ref(true);

//Crear
function submitCrear() {
    form.post(route("RegLote.store"), form);
}

const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.edit_url); // redirecciona a la vista Editar
};
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
            console.info("inicio");
            console.info(validacionActivos.value);
            console.info("El valor consulta");

            console.info(response.data.Validacion);
            validacionActivos.value = response.data.Validacion;
            RegistroLotes.value = response.data.RegistroLote;
            console.info("El valor final");

            console.info(validacionActivos.value);
        })
        .catch((e) => console.log(e));
}
</script>

<template>
    <Head title="Registros Lotes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear -
                <a
                    :href="route('RegLote.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Registro Lote</a
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
                                                v-on:change="getOptionsFinca"
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
                                                v-if="errors.distrito"
                                                id="distrito-help"
                                                class="text-red-700"
                                                >{{ errors.distrito }}</small
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
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getdataLote"
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
                                                >{{ errors.FechaInicio }}</small
                                            >
                                        </div>
                                    </div>

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
                                                >{{ errors.NombreLote }}</small
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
                                                :options="OptionsTipoCultivo"
                                                filter
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
                                                v-if="errors.TipoCultivo"
                                                id="TipoCultivo-help"
                                                class="text-red-700"
                                                >{{ errors.TipoCultivo }}</small
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
                                                :options="OptionsTipoVariedad"
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
                                                v-if="errors.TipoVariedad"
                                                id="TipoVariedad-help"
                                                class="text-red-700"
                                                >{{
                                                    errors.TipoVariedad
                                                }}</small
                                            >
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div
                                    class="flex gap-4 mt-1 items-center justify-center"
                                >
                                    <Button
                                        type="submit"
                                        label="Guardar"
                                        class="w-full"
                                        :disabled="
                                            form.processing ||
                                            !validacionActivos
                                        "
                                        v-if="
                                            $page.props.Permission.user_permision.includes(
                                                'mod.reglote.create'
                                            )
                                        "
                                    />
                                    <Message
                                        v-if="validacionActivos == false"
                                        severity="error"
                                        fluid
                                        >No se Puede Guardar Existe un Lote
                                        Activo</Message
                                    >
                                </div>
                            </template>
                        </Card>
                    </form>

                    <Card style="overflow: hidden">
                        <template #content>
                            <div class="grid grid-cols gap-4 mt-2">
                                <table></table>
                            </div>

                            <div class="grid grid-cols gap-4 mt-2">
                                <DataTable
                                    v-model:filters="filters"
                                    :value="RegistroLotes"
                                    showGridlines
                                    paginator
                                    :rows="10"
                                    :rowsPerPageOptions="[5, 10, 20, 50]"
                                    dataKey="id"
                                    tableStyle="max-width: 100%"
                                    :loading="loading"
                                    :globalFilterFields="[
                                        'finca_id',
                                        'distrito_id',
                                        'Codigo',
                                        'statusLote1',

                                        'NombreLote',
                                        'Hect',
                                    ]"
                                    filterDisplay="menu"
                                >
                                    <Column
                                        field="statusLote1"
                                        header="Status"
                                        :filterMenuStyle="{ width: '14rem' }"
                                        style="min-width: 5rem"
                                        :showFilterMatchModes="false"
                                    >
                                        <template #body="{ data }">
                                            {{ data.statusLote1 }}
                                        </template>
                                        <template
                                            #filter="{
                                                filterModel,
                                                filterCallback,
                                            }"
                                        >
                                            <InputText
                                                v-model="filterModel.value"
                                                type="text"
                                                @input="filterCallback()"
                                                placeholder="Search by name"
                                            />
                                        </template>
                                    </Column>
                                    <Column
                                        header="Codigo"
                                        field="Codigo"
                                        :filterMenuStyle="{ width: '14rem' }"
                                        style="min-width: 5rem"
                                        :showFilterMatchModes="false"
                                    >
                                        <template #body="{ data }">
                                            {{ data.Codigo }}
                                        </template>
                                        <template
                                            #filter="{
                                                filterModel,
                                                filterCallback,
                                            }"
                                        >
                                            <InputText
                                                v-model="filterModel.value"
                                                type="text"
                                                @input="filterCallback()"
                                                placeholder="Search by name"
                                            />
                                        </template>
                                    </Column>

                                    <Column
                                        header="Hect"
                                        field="Hect"
                                        :filterMenuStyle="{ width: '14rem' }"
                                        style="min-width: 5rem"
                                        :showFilterMatchModes="false"
                                    >
                                        <template #body="{ data }">
                                            {{ data.Hect }}
                                        </template>
                                        <template
                                            #filter="{
                                                filterModel,
                                                filterCallback,
                                            }"
                                        >
                                            <InputText
                                                v-model="filterModel.value"
                                                type="text"
                                                @input="filterCallback()"
                                                placeholder="Search by name"
                                            />
                                        </template>
                                    </Column>
                                    <Column
                                        header="Inicio"
                                        field="FechaInicio"
                                        :filterMenuStyle="{ width: '14rem' }"
                                        style="min-width: 5rem"
                                        :showFilterMatchModes="false"
                                    >
                                        <template #body="{ data }">
                                            {{ data.FechaInicio }}
                                        </template>
                                        <template
                                            #filter="{
                                                filterModel,
                                                filterCallback,
                                            }"
                                        >
                                            <InputText
                                                v-model="filterModel.value"
                                                type="text"
                                                @input="filterCallback()"
                                                placeholder="Search by name"
                                            />
                                        </template>
                                    </Column>
                                    <Column
                                        header="Siembra"
                                        field="FechaSiembra"
                                        :filterMenuStyle="{ width: '14rem' }"
                                        style="min-width: 5rem"
                                        :showFilterMatchModes="false"
                                    >
                                        <template #body="{ data }">
                                            {{ data.FechaSiembra }}
                                        </template>
                                        <template
                                            #filter="{
                                                filterModel,
                                                filterCallback,
                                            }"
                                        >
                                            <InputText
                                                v-model="filterModel.value"
                                                type="text"
                                                @input="filterCallback()"
                                                placeholder="Search by name"
                                            />
                                        </template>
                                    </Column>
                                    <Column
                                        header="Recoleccion"
                                        field="FechaRecoleccion"
                                        :filterMenuStyle="{ width: '14rem' }"
                                        style="min-width: 5rem"
                                        :showFilterMatchModes="false"
                                    >
                                        <template #body="{ data }">
                                            {{ data.FechaRecoleccion }}
                                        </template>
                                        <template
                                            #filter="{
                                                filterModel,
                                                filterCallback,
                                            }"
                                        >
                                            <InputText
                                                v-model="filterModel.value"
                                                type="text"
                                                @input="filterCallback()"
                                                placeholder="Search by name"
                                            />
                                        </template>
                                    </Column>
                                    <!-- Columna de Acciones-->
                                    <Column
                                        :exportable="false"
                                        style="min-width: 5rem"
                                    >
                                        <!-- Boton Editar -->
                                        <template #body="slotProps">
                                            <Button
                                                icon="pi pi-pencil"
                                                outlined
                                                rounded
                                                class="mr-2"
                                                @click="edit(slotProps.data)"
                                            />
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
