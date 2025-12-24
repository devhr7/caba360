<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from '@inertiajs/vue3'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

// Menu 2 
const items = ref([
    {  label: "Inicio", url: route("dashboard") },
    {  label: "Lote", url: route("Lote.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    get_distrito: { type: Object },
    get_finca : { type: Object },
    datos:{type:Object},
    errors: { type: Object },
});

// Formulario
const form = useForm({
    distrito: props.datos.distrito,
    finca: props.datos.finca,
    lote: props.datos.lote,
    hect: props.datos.hect,
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
            form.put(route("Lote.update", props.datos.slug), form);
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
            form.delete(route("Lote.delete", props.datos.slug), form);
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

const DataDistrito = ref(props.get_distrito); // Datos del Distrito
const FilteredDistrito = ref();  // Constante para Filto
// Auto completar Distrito
const searchDistrito = (event) => {
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

// Auto completar Finca
const DataFinca = ref(props.get_finca); // Datos del Distrito
const FilteredFinca = ref();  // Constante para Filto
const searchFinca = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            FilteredFinca.value = [...DataFinca.value];
        } else {
            FilteredFinca.value = DataFinca.value.filter((reg) => {
                return reg.name
                    .toLowerCase()
                    .startsWith(event.query.toLowerCase());
            });
        }
    }, 250);
};
</script>

<template>
    <Head title="Lote" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar - Lote
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
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="distrito">Distrito</label>
                                        <AutoComplete
                                        v-model="form.distrito"
                                        :suggestions="FilteredDistrito"
                                        dataKey="id"
                                        @complete="searchDistrito"
                                        optionLabel="name"
                                        dropdown
                                    />
                                        <small v-if="errors.distrito" id="distrito-help" class="text-red-700"
                                            >{{ errors.distrito }}</small
                                        >
                
                                    </div>
                                </div>
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="finca">Finca</label>
                                        <AutoComplete
                                        v-model="form.finca"
                                        :suggestions="FilteredFinca"
                                        dataKey="id"
                                        @complete="searchFinca"
                                        optionLabel="name"
                                        dropdown
                                    />
                                        <small v-if="errors.finca" id="finca-help" class="text-red-700"
                                            >{{ errors.finca }}</small
                                        >
                
                                    </div>
                                </div>
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="lote">Lote</label>
                                        <InputText
                                            id="lote"
                                            name="lote"
                                            v-model="form.lote"
                                            aria-describedby="lote-help"
                                        />
                                        <small v-if="errors.lote" id="lote-help" class="text-red-700"
                                            >{{ errors.lote }}</small
                                        >
                                       
                                    </div>
                                </div>
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="hect">Hect</label>
                                        <InputText
                                            id="hect"
                                            name="hect"
                                            v-model="form.hect"
                                            aria-describedby="hect-help"
                                        />
                                        <small v-if="errors.hect" id="hect-help" class="text-red-700"
                                            >{{ errors.hect }}</small
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
