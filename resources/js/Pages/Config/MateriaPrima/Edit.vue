<script setup>
/**
 * Componente para editar un Grupo Maquina
 *
 * Se utiliza el hook useForm para crear un formulario con los datos del Grupo Maquina
 * y se utiliza el hook router para redirigir a la ruta de explorer de Grupo Maquina
 * cuando se hace click en el boton de regresar
 *
 * Se utiliza el hook onMounted para inicializar el formulario con los datos del Grupo Maquina
 * y se utiliza el hook onUpdated para actualizar el formulario cuando los datos del Grupo Maquina
 * cambian
 *
 * Se utiliza el hook defineProps para definir las propiedades que se pasan al componente
 * y se utiliza el hook defineEmits para definir los eventos que se emiten desde el componente
 *
 * @param {Object} props - Las propiedades del componente
 * @param {Object} props.datos - Los datos del Grupo Maquina
 * @param {Object} props.errors - Los errores del formulario
 * @param {Object} props.get_distrito - Los datos del Distrito
 *
 * @emits {update:modelValue} - Se emite cuando se actualiza el formulario
 * @emits {delete:modelValue} - Se emite cuando se elimina el Grupo Maquina
 */
import { ref, onMounted, watch } from "vue";
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
    datos:{type:Object},
    options: { type: Object },
    errors: { type: Object },

});


const optionsGrupoMP = ref(props.options.OptionGrupoMP);
// Formulario
const form = useForm({
    GrupoMP: props.datos.GrupoMP,
    MateriaPrima: props.datos.MateriaPrima,
    PrecioUnitario: props.datos.PrecioUnitario,
});

// Actualizar
function submitUpdate() {
    // Alerta
    Swal.fire({
        title: "Esta Seguro de Actualizar?",
        text: "Esta Accion es Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Acualizar!",
    }).then((result) => {
        // Confirmacion
        if (result.isConfirmed) {
            // Exitoso
            form.put(route("MateriaPrima.update", props.datos.slug), form, {
                onSuccess: () => {
                    // Mensaje Final
                    Swal.fire({
                        title: "Alcualizado!",
                        text: "Ha sido Actualizado Correctamente.",
                        icon: "success",
                    });
                },
                onError: (error) => {
                    // Mostrar Errores
                    console.log(error);
                }
            });
        }
    });
}

// Eliminar
function submitDelete() {
    // Alerta
    Swal.fire({
        title: "Esta  seguro de eliminar?",
        text: "Esta Accion es irreversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Exitoso
            form.delete(route("MateriaPrima.delete", props.datos.slug), form)
                .then(() => {
                    // Mensaje Final
                    Swal.fire({
                        title: "Eliminado!",
                        text: "Ha sido eliminado correctamente.",
                        icon: "success",
                    });
                })
                .catch((error) => {
                    // Mostrar Errores
                    console.log(error);
                });
        }
    });
}

/**
 * Definiendo Constantes
 * */


const DataDistrito = ref(props.get_distrito); // Datos del Distrito
const FilteredDistrito = ref();  // Constante para Filto
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
    <Head title="Grupo Maquina " />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar - Grupo Maquina
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
                    <!-- Formulario para Editar Grupo Maquina -->
                    <form @submit.prevent="submitUpdate">
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
                                    <Button
                                        type="button"
                                        label="Eliminar"
                                        severity="danger"
                                        class="w-full"
                                        @click="submitDelete"
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

