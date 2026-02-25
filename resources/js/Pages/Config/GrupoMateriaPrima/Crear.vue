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
    {  label: "Grupo Materia Prima", url: route("GrupoMateriaPrima.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    errors: { type: Object },
});

// Formulario
const form = useForm({
    GrupoMateriaPrima: null,
});

//Crear
function submitCrear() {
    form.post(route("GrupoMateriaPrima.store"), form);
}

/**
 * Definiendo Constantes
 * */



</script>

<template>
    <Head title="Grupo Materia Prima" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Grupo Materia Prima
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
                    <form @submit.prevent="submitCrear" >
                    <Card style="overflow: hidden">
                        <template #content>
                            <div class="grid grid-cols-3 gap-4">

                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="GrupoMateriaPrima">Grupo Materia Prima</label>
                                        <InputText
                                            id="GrupoMateriaPrima"
                                            name="GrupoMateriaPrima"
                                            v-model="form.GrupoMateriaPrima"
                                            aria-describedby="GrupoMateriaPrima-help"
                                        />
                                        <small v-if="errors.GrupoMateriaPrima" id="GrupoMateriaPrima-help" class="text-red-700"
                                            >{{ errors.GrupoMateriaPrima }}</small
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
                            </div>
                        </template>
                    </Card>
                </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
