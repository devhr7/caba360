<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

// Menu 2
const items = ref([
    {  label: "Inicio", url: route("dashboard") },
    {  label: "TipoVariedad", url: route("TipoVariedad.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({

    datos:{type:Object},
    errors: { type: Object },
});

// Formulario
const form = useForm({
    TipoVariedad: props.datos.TipoVariedad,

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
            form.put(route("TipoVariedad.update", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Alcualizado!",
                text: "Ha sido Actualizado Correctamente.",
                icon: "success",
            });
        }
    });
}

// Eliminar
function submitDelete() {
    // Alerta
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
            form.delete(route("TipoVariedad.delete", props.datos.slug), form);
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}

/**
 * Definiendo Constantes
 * */

</script>

<template>
    <Head title="Tipo Variedad" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar - Tipo Variedad
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
                    <form @submit.prevent="submitUpdate" >
                    <Card style="overflow: hidden">
                        <template #content>
                            <div class="grid grid-cols-3 gap-4">

                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="TipoVariedad">Tipo Variedad</label>
                                        <InputText
                                            id="TipoVariedad"
                                            name="TipoVariedad"
                                            v-model="form.TipoVariedad"
                                            aria-describedby="TipoVariedad-help"
                                        />
                                        <small v-if="errors.TipoVariedad" id="TipoVariedad-help" class="text-red-700"
                                            >{{ errors.TipoVariedad }}</small
                                        >

                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- Botones Finales-->
                        <template #footer>
                            <!-- Boton Guardar-->
                            <div class="flex gap-4 mt-1">
                                <Button type="submit" label="Guardar" class="w-full" :disabled="form.processing" />
                            <!-- Boton Eliminar-->
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
