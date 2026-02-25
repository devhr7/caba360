<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Labor", url: route("Labor.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    options: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    labor: null,
    costoHect: null,
    TipoCumplido: null,
    TipoLabor: null,
    Hect: true,
    CumplidoAplicacion: false,
    CumplidoMaquinaria: false,
    CumplidoOrdenServicio: false,
    CumplidoLaboresCampo: false,
});

//Crear
function submitCrear() {
    form.post(route("Labor.store"), form);
}

/**
 * Definiendo Constantes
 */
const getOptionTipoLabor = ref(props.options.OptionTipoLabor); // Datos del Distrito
const getOptionTipoCumplido = ref(props.options.OptionTipoCumplido); // Datos del Distrito
const FilteredDistrito = ref(); // Constante para Filto
// Buscado de Auto completar
const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            FilteredDistrito.value = [...DataDistrito.value];
        } else {
            FilteredDistrito.value = DataDistrito.value.filter((reg) => {
                return reg.name
                    .toLowerCase()
                    .startsWith(event.query.toLowerCase());
            });
        }
    }, 250);
};
</script>

<template>

    <Head title="Labor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Labor
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
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label for="labor">Labor</label>
                                        <InputText id="labor" v-model="form.labor" aria-describedby="labor-help" />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="costoHect">Costo/Hect</label>
                                        <InputText id="costoHect" v-model="form.costoHect"
                                            aria-describedby="costoHect-help" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label for="tipolabor">TipoLabor</label>
                                        <Select v-model="form.TipoLabor" :options="getOptionTipoLabor" filter
                                            optionLabel="label" placeholder="Seleccionar" showClear class="w-full"
                                            fluid>
                                            <template #value="slotProps">
                                                <div v-if="slotProps.value" class="flex items-center">
                                                    <div>
                                                        <!-- Mostrar el nombre de la finca-->
                                                        {{
                                                            slotProps.value
                                                                .label
                                                        }}
                                                    </div>
                                                </div>
                                                <span v-else>
                                                    <!-- Mostrar el placeholder-->
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex items-center">
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
                                    <div class="flex flex-col gap-2">
                                        <label for="tipocumplido">Tipo Cumplido</label>
                                        <Select v-model="form.TipoCumplido" :options="getOptionTipoCumplido" filter
                                            optionLabel="label" placeholder="Seleccionar" showClear class="w-full"
                                            fluid>
                                            <template #value="slotProps">
                                                <div v-if="slotProps.value" class="flex items-center">
                                                    <div>
                                                        <!-- Mostrar el nombre de la finca-->
                                                        {{
                                                            slotProps.value
                                                                .label
                                                        }}
                                                    </div>
                                                </div>
                                                <span v-else>
                                                    <!-- Mostrar el placeholder-->
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex items-center">
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
                                </div>
                                <Panel header="Configuración de labor" class="mt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex flex-col gap-2">
                                            <label for="Hect">Unidad de medida</label>
                                            <ToggleButton id="Hect" v-model="form.Hect" onLabel="Hectárea"
                                                offLabel="Bultos" class="w-full" />
                                            <small class="text-gray-500">
                                                Hectárea = 1, Bultos = 0
                                            </small>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <ToggleSwitch v-model="form.CumplidoAplicacion
                                                    " />

                                                <label for="CumpAplicacion">
                                                    Cumplido Aplicacion
                                                </label>
                                            </div>
                                            <div>
                                                <ToggleSwitch v-model="form.CumplidoMaquinaria
                                                    " />
                                                <label for="CumplidoMaquinaria">
                                                    Cumplido Maquinaria
                                                </label>
                                            </div>
                                            <div>
                                                <ToggleSwitch v-model="form.CumplidoOrdenServicio
                                                    " />
                                                <label for="CumplidoOrdenServicio">
                                                    Cumplido Orden Servicio
                                                </label>
                                            </div>
                                            <div>
                                                <ToggleSwitch v-model="form.CumplidoLaboresCampo
                                                    " />
                                                <label for="CumplidoLaboresCampo">
                                                    Cumplido Labores Campo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </Panel>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div class="flex gap-4 mt-1">
                                    <Button type="submit" label="Guardar" severity="success" class="w-full"
                                        :disabled="form.processing" />
                                </div>
                            </template>
                        </Card>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
