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
    {  label: "Finca", url: route("Finca.Explorar") },
]);

/**
 * Definiendo Props
 */
const props = defineProps({
    get_distrito: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    distrito: null,
    finca: null,
});

//Crear
function submitCrear() {
    form.post(route("Finca.store"), form);
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
    <Head title="Finca" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Finca
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
                                        <label for="distrito">Distrito</label>
                                        <AutoComplete
                                        v-model="form.distrito"
                                        :suggestions="FilteredDistrito"
                                        dataKey="id"
                                        @complete="search"
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
                                        <InputText
                                            id="finca"
                                            name="finca"
                                            v-model="form.finca"
                                            aria-describedby="finca-help"
                                        />
                                        <small v-if="errors.finca" id="finca-help" class="text-red-700"
                                            >{{ errors.finca }}</small
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
