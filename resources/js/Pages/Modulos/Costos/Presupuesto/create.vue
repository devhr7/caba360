<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";

const props = defineProps({
    options: { type: Object },
    errors: { type: Object },
});

const form = useForm({
    status: null,
    fecha_ini: null,
    fecha_fin: null,
});

const OptionsStatus = ref(props.options.status);

const submit = () => {
    form.post(route("costos.ppt.store"), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Registro guardado correctamente",
                life: 3000,
            });
        },
    });
};
</script>

<template>
    <Head title="Presupuesto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Presupuesto | Crear
            </h2>
        </template>

        <div class="py-12">
            <div class="table-fixed mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-4 gap-6 px-5 py-5">
                            <div class="col sm:col-span-3">
                                <label
                                    for="status"
                                    class="block text-sm font-medium text-gray-700"
                                    >Status</label
                                >
                                <Select
                                    v-model="form.status"
                                    :options="OptionsStatus"
                                    filter
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
                                                {{ slotProps.value.label }}
                                            </div>
                                        </div>
                                        <span v-else>
                                            {{ slotProps.placeholder }}
                                        </span>
                                    </template>
                                    <template #option="slotProps">
                                        <div class="flex items-center">
                                            <div>
                                                {{ slotProps.option.label }}
                                            </div>
                                        </div>
                                    </template>
                                </Select>
                            </div>

                            <div class="col sm:col-span-3">
                                <label
                                    for="fecha_ini"
                                    class="block text-sm font-medium text-gray-700"
                                    >Fecha Inicio</label
                                >
                                <DatePicker
                                    v-model="form.fecha_ini"
                                    showIcon
                                    fluid
                                    iconDisplay="input"
                                    inputId="fecha_ini"
                                    dateFormat="dd/mm/yy"
                                />
                            </div>

                            <div class="col sm:col-span-3">
                                <label
                                    for="fecha_fin"
                                    class="block text-sm font-medium text-gray-700"
                                    >Fecha Fin</label
                                >
                                <DatePicker
                                    v-model="form.fecha_fin"
                                    showIcon
                                    fluid
                                    iconDisplay="input"
                                    inputId="fecha_fin"
                                    dateFormat="dd/mm/yy"
                                />
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 text-right sm:px-6">
                            <Button
                                type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Guardar
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
