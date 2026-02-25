<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { useToast } from "primevue/usetoast";

const form = ref({

    fecha: new Date(new Date().getFullYear(), new Date().getMonth() , 1).toISOString().split('T')[0],
    file: null,
});

const toast = useToast();
const validatedData = ref([]);
const isImportButtonDisabled = ref(true);

const handleFileUpload = (event) => {
    form.value.file = event.target.files[0];
    isImportButtonDisabled.value = true;
};

const validateFile = async () => {
    if (!form.value.file || !form.value.fecha) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Por favor, selecciona un archivo y una fecha.",
        });
        return;
    }

    const formData = new FormData();
    formData.append("file", form.value.file);
    // Formatear la fecha a dd/mm/yy
    const formattedDate = new Date(form.value.fecha).toLocaleDateString(
        "es-ES",
        {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        }
    );
    formData.append("fecha", formattedDate);

    try {
        const response = await axios.post(
            route("costos.ingresos.importar.validar"),
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        if (response.data.success) {
            validatedData.value = response.data.data;
            toast.add({
                severity: "success",
                summary: "Validación exitosa",
                detail: "El archivo es válido.",
            });
           
            isImportButtonDisabled.value = false;
        } else {
            validatedData.value = response.data.data;
            toast.add({
                severity: "error",
                summary: "Validación fallida",
                detail: "El archivo no es válido.",
            });
            isImportButtonDisabled.value = true;
        }
    } catch (error) {
        console.error("Error al validar el archivo:", error);
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Hubo un error al validar el archivo.",
        });
        isImportButtonDisabled.value = true;
    }
};

const submitForm = async () => {
    if (!form.value.file || !form.value.fecha) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Por favor, selecciona un archivo y una fecha.",
        });
        return;
    }

    const formData = new FormData();
    formData.append("file", form.value.file);

    // Formatear la fecha a dd/mm/yy
    const formattedDate = new Date(form.value.fecha).toLocaleDateString(
        "es-ES",
        {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        }
    );
    formData.append("fecha", formattedDate);

    try {
        const response = await axios.post(
            route("costos.ingresos.importar.subir"),
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        if (response.data.success) {
            toast.add({
                severity: "success",
                summary: "Subida exitosa",
                detail: "El archivo se ha subido correctamente.",
            });
            isImportButtonDisabled.value = true;
            form.value.file = null;
        } else {
            toast.add({
                severity: "error",
                summary: "Subida fallida",
                detail: "Hubo un problema al subir el archivo.",
            });
        }
    } catch (error) {
        console.error("Error al subir el archivo:", error);
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Hubo un error al subir el archivo.",
        });
    }
};
</script>

<template>
    <Head title="Ingresos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ingresos | Importar Movimientos
            </h2>
        </template>

        <div class="py-12">
            <div class="table-fixed mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submitForm">
                        <div class="grid grid-cols-3 gap-4 mt-2">
                            <div class="flex flex-col gap-2">
                                <label for="FechaRecord">Fecha</label>
                                <DatePicker
                                    v-model="form.fecha"
                                    showIcon
                                    fluid
                                    iconDisplay="input"
                                    dateFormat="dd/mm/yy"
                                />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Toast />
                                <label for="archivo">Archivo</label>
                                <input
                                    type="file"
                                    @change="handleFileUpload"
                                    accept=".xlsx, .xls, .csv"
                                    required
                                    class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400
    file:bg-gray-50 file:border-0
    file:me-4
    file:py-3 file:px-4
    dark:file:bg-neutral-700 dark:file:text-neutral-400"
                                />
                            </div>
                            <div class="col-span-3 flex gap-2">
                                <Button  type="button" label="Validar"  @click="validateFile" severity="warn" raised />
                                <Button type="submit" label="Importar" severity="info" raised :disabled="isImportButtonDisabled" />
                               
                            </div>
                        </div>
                    </form>
                    <div v-if="validatedData.length > 0" class="mt-4">
                        <h3 class="text-lg font-semibold">Datos Validados</h3>
                        
                        <table
                            class="table-auto w-full mt-2 border-collapse border border-slate-400"
                        >
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700">
                                    <th class="border border-slate-300 px-4 py-2">Lote</th>
                                    <th class="border border-slate-300 px-4 py-2">Documento</th>
                                    <th class="border border-slate-300 px-4 py-2">Kilogramos</th>
                                    <th class="border border-slate-300 px-4 py-2">Valor Venta</th>
                                    <th class="border border-slate-300 px-4 py-2">Observaciones</th>
                                    <th class="border border-slate-300 px-4 py-2">Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(row, index) in validatedData"
                                    :key="index"
                                    :class="{
                                        'bg-white dark:bg-gray-800': index % 2 === 0,
                                        'bg-gray-100 dark:bg-gray-900': index % 2 !== 0
                                    }"
                                >
                                    <td class="border border-slate-300 px-4 py-2">{{ row.Lote }}</td>
                                    <td class="border border-slate-300 px-4 py-2">{{ row.Documento }}</td>
                                    <td class="border border-slate-300 px-4 py-2">{{ row.Kilogramos }}</td>
                                    <td class="border border-slate-300 px-4 py-2">{{ row.ValorVenta }}</td>
                                    <td class="border border-slate-300 px-4 py-2">{{ row.Observaciones }}</td>
                                    <td class="border border-slate-300 px-4 py-2">{{ row.Error }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
