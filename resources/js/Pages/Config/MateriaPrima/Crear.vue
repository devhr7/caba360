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
    { label: "Materia Prima", url: route("MateriaPrima.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    options: { type: Object },
    errors: { type: Object },
});

const optionsGrupoMP = ref(props.options.OptionGrupoMP);
// Formulario
const form = useForm({
    GrupoMP: null,
    MateriaPrima: null,
    PrecioUnitario: null,
});

//Crear
function submitCrear() {
    form.post(route("MateriaPrima.store"), form);
}

/**
 * Definiendo Constantes
 * */
</script>

<template>
    <Head title="Materia Prima" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Materia Prima
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
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label for="GrupoMaquina"
                                            >Grupo Materia Prima</label
                                        >
                                        <Select
                                            v-model="form.GrupoMP"
                                            :options="optionsGrupoMP"
                                            filter
                                            optionLabel="label"
                                            placeholder="Select a Country"
                                            class="w-full md:w-56"
                                        >
                                            <template #value="slotProps">
                                                <div v-if="slotProps.value" class="flex items-center">
                                                    <div>
                                                        {{
                                                            slotProps.value.label
                                                        }}
                                                    </div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                            <template #option="slotProps">
                                                <div class="flex items-center">

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
                                            v-if="errors.GrupoMP"
                                            id="GrupoMP-help"
                                            class="text-red-700"
                                            >{{ errors.GrupoMP }}</small
                                        >
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <label for="MateriaPrima"
                                            >Materia Prima</label
                                        >
                                        <InputText
                                            id="MateriaPrima"
                                            name="MateriaPrima"
                                            v-model="form.MateriaPrima"
                                            aria-describedby="MateriaPrima-help"
                                        />
                                        <small
                                            v-if="errors.MateriaPrima"
                                            id="MateriaPrima-help"
                                            class="text-red-700"
                                            >{{ errors.MateriaPrima }}</small
                                        >
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <label for="PrecioUnitario"
                                            >Precio Unitario</label
                                        >
                                        <InputText
                                            id="PrecioUnitario"
                                            name="PrecioUnitario"
                                            v-model="form.PrecioUnitario"
                                            aria-describedby="PrecioUnitario-help"
                                        />
                                        <small
                                            v-if="errors.PrecioUnitario"
                                            id="PrecioUnitario-help"
                                            class="text-red-700"
                                            >{{ errors.PrecioUnitario }}</small
                                        >
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
