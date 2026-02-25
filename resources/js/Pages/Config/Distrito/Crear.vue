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
    {  label: "Distrito", url: route("Distrito.Explorar") },
]);

defineProps({ errors: Object })

// Formulario
const form = useForm({
    distrito: null,
});

//Crear
function submit() {
    form.post(route("Distrito.store"), form);
}

</script>

<template>
    <Head title="Distrito" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear - Distrito
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
                                <div>
                                    <div class="flex flex-col gap-2">
                                        <label for="distrito">Distrito</label>
                                        <InputText
                                            id="distrito"
                                            name="distrito"
                                            v-model="form.distrito"
                                            aria-describedby="distrito-help"

                                        />
                                        <small v-if="errors.distrito" id="distrito-help" class="text-red-700"
                                            >{{ errors.distrito }}</small
                                        >

                                    </div>
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
