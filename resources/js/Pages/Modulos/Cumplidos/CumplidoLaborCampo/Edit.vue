<script setup>
import { ref, onMounted, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

import Edit_Lotes from "./Edit_Lotes.vue";
import Edit_Cuadrilla from "./Edit_Cuadrilla.vue";

const isLoadingOptionsLote = ref(false);

/**
 * Definiendo Props
 */
const props = defineProps({
    data: { type: Object },
    options: { type: Object },
    datatable: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    codigo: props.data.codigo,
    fecha: props.data.fecha,
    finca: props.data.finca,
    labor: props.data.labor,
    UnidadMedida: props.data.unidadmedida,
    CantidadTotal: props.data.cantidadtotal,
});

// Constantes
const OptionsFinca = ref(props.options.get_finca); // Datos del Finca
const OptionsLabor = ref(props.options.get_labor); // Datos del Lote
const OptionsUnidadMedida = ref(props.options.get_unidadmedida); // Datos del Lote

//Crear
function submitEdit() {
    form.put(route("CumpLaborCampo.update", props.data.id), form);
}

function eliminar() {
    Swal.fire({
        title: "Esta Seguro de Eliminar?",
        text: "Esta Accion es Irreversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        // Confirmacion
        if (result.isConfirmed) {
            // Exitoso
            form.delete(route("CumpLaborCampo.delete", props.data.id));

            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}
</script>

<template>
    <Head title="Cumplidos Labor Campo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar -
                <a
                    :href="route('CumpLaborCampo.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Cumplido Labor Campo</a
                >
            </h2>
        </template>
        <!-- Menu 2-->

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <form @submit.prevent="submitEdit">
                        <Card style="overflow: hidden">
                            <template #content>
                                <!-- Fila 1-->
                                <div class="grid grid-cols-3 gap-4 mt-2">
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
                                            <label for="fecha">Fecha</label>
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
                                            <label for="finca">Finca</label>

                                            <Select
                                                v-model="form.finca"
                                                :options="OptionsFinca"
                                                filter
                                                showClear
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
                                                v-if="errors.finca"
                                                id="finca-help"
                                                class="text-red-700"
                                                >{{ errors.finca }}</small
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Fila 2 -->
                                <div class="grid grid-cols-4 gap-4 mt-2">
                                    <div class="flex flex-col gap-2">
                                        <label for="Labor">Labor</label>

                                        <Select
                                            v-model="form.labor"
                                            :options="OptionsLabor"
                                            filter
                                            :loading="isLoadingOptionsLote"
                                            optionLabel="label"
                                            placeholder="Seleccionar"
                                            showClear
                                            class="w-full"
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
                                            v-if="errors.labor"
                                            id="labor-help"
                                            class="text-red-700"
                                            >{{ errors.labor }}</small
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="UnidadMedida"
                                            >Unidad Medida</label
                                        >

                                        <Select
                                            v-model="form.UnidadMedida"
                                            :options="OptionsUnidadMedida"
                                            filter
                                            :loading="isLoadingOptionsLote"
                                            optionLabel="label"
                                            placeholder="Seleccionar"
                                            showClear
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
                                            v-if="errors.UnidadMedida"
                                            id="UnidadMedida-help"
                                            class="text-red-700"
                                            >{{ errors.UnidadMedida }}</small
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="CantidadTotal"
                                            >Cantidad Total</label
                                        >
                                        <InputText
                                            id="CantidadTotal"
                                            name="CantidadTotal"
                                            v-model="form.CantidadTotal"
                                            aria-describedby="CantidadTotal-help"
                                        />
                                        <small
                                            v-if="errors.CantidadTotal"
                                            id="CantidadTotal-help"
                                            class="text-red-700"
                                            >{{ errors.CantidadTotal }}</small
                                        >
                                    </div>
                                    <div class="flex gap-4 mt-1">
                                        <Button
                                            type="submit"
                                            label="Actualizar"
                                            class="w-full"
                                            :disabled="form.processing"
                                        />
                                        <Button
                                            type="button"
                                            label="Eliminar"
                                            @click="eliminar"
                                            severity="danger"
                                            class="w-full"
                                            :disabled="form.processing"
                                        />
                                    </div>
                                </div>
                            </template>
                            <!-- Botones Finales-->
                            <template #footer>
                                <!-- Boton Guardar-->
                            </template>
                        </Card>
                    </form>
                </div>
            </div>
            <br />
            <Divider></Divider>
            <Edit_Lotes
                :datatable="props.datatable.datatable_lotes"
                :CumplidoLaborCampo="props.data.id"
                :options="props.options"
            >
            </Edit_Lotes>

            <Divider></Divider>
            <Edit_Cuadrilla
                :datatable="props.datatable.datatable_cuadrilla"
                :CumplidoLaborCampo="props.data.id"
                :options="props.options"
            >
            </Edit_Cuadrilla>
        </div>
    </AuthenticatedLayout>
</template>
