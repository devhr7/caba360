<script setup>
import { ref, onMounted, computed  } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Cumplido Maquinaria", url: route("CumpMaquinaria.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    get_finca: { type: Object },
    get_lote: { type: Object },
    get_empleado: {type:Object},
    get_labor: { type: Object },
    get_maquina:{type:Object},
    errors: { type: Object },
});

// Formulario
const form = useForm({
    CodigoCumplido:null,
    FechaCumplido:null,
    finca: null,
    lote: null,
    RegLote_id:null,
    empleado:null,
    Maquina_id:null,
    horometro_inicio:null,
    horometro_fin: null,
    GalACPM:null,
    labor:null,
    Cant:0,
});

// Constantes
const OptionsFinca = ref(props.get_finca); // Datos del Finca
const OptionsLote = ref(props.get_lote); // Datos del Lote
const OptionsRegLote = ref([]);
const OptionsEmpleado = ref(props.get_empleado);
const DataLote = ref(props.get_lote); // Datos del Finca
const OptionsMaquinaria = ref(props.get_maquina);
const RegistroLotes = ref([]); // Datos del Finca
const OptionsLabor = ref(props.get_labor);
const costoHect =  ref();


// Filtros de busqueda
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    RegistroLotes: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    distrito_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    finca_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

//Crear
function submitCrear() {
    form.post(route("CumpMaquinaria.store"), form);
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
// Carga Los Datos de los Codigos Lote
async function get_data_RegLote() {

    await axios
        .post(route("RegLote.getOptionsRegLoteActivo"), form.lote)
        .then(function (response) {
            OptionsRegLote.value = response.data;
            console.log("cargarnos Codigos Lote");
            console.log(response.data)
        })
        .catch((e) => console.log(e));
}

const valorTotal = computed(() => {
    if(form.labor === null){
        costoHect.value = 0;
    }else{
        costoHect.value = form.labor.CostoHect ?? 0;
    }
    const cantidad = form.Cant ?? 0;

    return new Intl.NumberFormat('es-ES', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(costoHect.value * cantidad);

                });


</script>

<template>
    <Head title="Cumplidos Maquinaria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Cumplido Maquinaria
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
                                            <label for="FechaCumplido">Fecha</label>
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
                                                >{{ errors.FechaCumplido }}</small
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
                                            <label for="RegLote_id"
                                                >Codigo Lote</label
                                            >

                                            <Select
                                                v-model="form.RegLote_id"
                                                :options="OptionsRegLote"
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
                                                v-if="errors.RegLote_id"
                                                id="RegLote_id-help"
                                                class="text-red-700"
                                                >{{ errors.RegLote_id }}</small
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Fila 2-->
                                <div class="grid grid-cols-5 gap-4 mt-2">
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="empleado">Operario</label>

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
                                                >{{ errors.horometro_inicio }}</small
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
                                                >{{ errors.horometro_fin }}</small
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
                                            <label for="hect"
                                                >Labor</label
                                            >
                                            <Select
                                                v-model="form.labor"
                                                :options="OptionsLabor"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                class="w-full "
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
                                                v-if="errors.hect"
                                                id="hect-help"
                                                class="text-red-700"
                                                >{{ errors.hect }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <label for="hect"
                                                >Total</label
                                            >
                                            <h1> ${{ valorTotal }}</h1>

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
