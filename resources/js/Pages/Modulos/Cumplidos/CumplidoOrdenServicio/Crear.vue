<script setup>
import { ref, onMounted, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const toast = useToast();

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    {
        label: "Cumplido Orden Servicio",
        url: route("CumplidoOrdenServicio.Explorar"),
    },
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
    codigo: null,
    fecha: null,
    contratista: null,
    autorizado: null,
});

// Constantes
const OptionsContratista = ref(props.options.optionsContratista); // Datos del Finca
const OptionsAutorizado = ref(props.options.optiosEmpleado); // Datos del Lote



//Crear
function submitCrear() {
    form.post(route("CumplidoOrdenServicio.store"), form);
}

</script>

<template>
    <Head title="Cumplidos Orden Servicio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear -
                <a
                    :href="route('CumplidoOrdenServicio.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Orden de Servicio</a
                >
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <form @submit.prevent="submitCrear">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols-4 gap-4 mt-2">
                                    <!-- Codigo-->
                                    <div class="flex flex-col gap-2">
                                        <label for="codigo"
                                            >Codigo Cumplido</label
                                        >


   <InputText
                                            id="codigo"
                                            name="codigo"
                                            v-model="form.codigo"
                                            aria-describedby="codigo-help"
                                        />

                                        
                                        <small
                                            v-if="errors.codigo"
                                            id="codigo-help"
                                            class="text-red-700"
                                            >{{ errors.codigo }}</small
                                        >
                                    </div>
                                    <!-- Fecha-->
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="FechaCumplido"
                                                >Fecha</label
                                            >
                                            <DatePicker
                                                v-model="form.fecha"
                                                showIcon
                                                fluid
                                                iconDisplay="input"
                                                inputId="fecha"
                                                dateFormat="dd/mm/yy"
                                            />
                                            <small
                                                v-if="errors.fecha"
                                                id="fecha-help"
                                                class="text-red-700"
                                                >{{ errors.fecha }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="contratista"
                                                >Contratista</label
                                            >

                                            <Select
                                                v-model="form.contratista"
                                                :options="OptionsContratista"
                                                filter
                                                fluid
                                                showClear
                                                optionLabel="label"
                                                placeholder="Seleccionar"
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
                                                v-if="errors.contratista"
                                                id="contratista-help"
                                                class="text-red-700"
                                                >{{ errors.contratista }}</small
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="finca"
                                                >Autorizado</label
                                            >

                                            <Select
                                                v-model="form.autorizado"
                                                :options="OptionsAutorizado"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                fluid
                                                showClear
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
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                                <div class="flex gap-4 mt-1">
                                    <Button
                                        type="submit"
                                        label="Crear y Seguir"
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
