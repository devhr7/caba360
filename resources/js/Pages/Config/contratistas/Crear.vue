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
    {  label: "Contratista", url: route("contratista.Explorar") },
]);

defineProps({ errors: Object })

// Formulario
const form = useForm({
    identificacion: null,
    nombre: null,


});

//Crear
function submit() {
    form.post(route("contratista.store"), form);
}

</script>

<template>
    <Head title="Contratista" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Contratista
            </h2>
        </template>
        <Breadcrumb :model="items" >
            <template #item="{ item, props }" class="bg-transparent">
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
                    <form @submit.prevent="submit" >



                    <Card style="overflow: hidden">
                        <template #content>
                            <div class="grid grid-cols-3 gap-4">

                                     <div class="flex flex-col gap-2">
                                        <label for="identificacion">Identificacion</label>
                                        <InputText
                                            id="identificacion"
                                            name="identificacion"
                                            v-model="form.identificacion"
                                            aria-describedby="identificacion-help"

                                        />
                                        <small v-if="errors.identificacion" id="identificacion-help" class="text-red-700"
                                            >{{ errors.identificacion }}</small
                                        >

                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="nombre">Contratista</label>
                                        <InputText
                                            id="nombre"
                                            name="nombre"
                                            v-model="form.nombre"
                                            aria-describedby="nombre-help"

                                        />
                                        <small v-if="errors.nombre" id="nombre-help" class="text-red-700"
                                            >{{ errors.nombre }}</small
                                        >

                                    </div>

                            </div>
                        </template>
                        <template #footer>
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
