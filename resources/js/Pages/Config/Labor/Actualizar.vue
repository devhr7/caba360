<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";

/**
 * Definiendo Props
 */
const props = defineProps({
    options: { type: Object },
    errors: { type: Object },
});

// Formulario
const form = useForm({
    GrupoMP: null,
    MateriaPrima: null,
    PrecioUnitario: null,
});

// Formulario para subir archivo
const formFile = useForm({
    file: null,
});

const datatable = ref([]);

async function validarArchivo() {
    const formData = new FormData();
    formData.append('file', formFile.file);

    await axios
        .post(route("Labor.validarExcel"), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(function (response) {
            datatable.value = response.data.data;
        })
        .catch(function (error) {
            console.error(error);
            alert("Error al validar el archivo");
        });
}

// Importar archivo
function importarArchivo() {
    formFile.post(route("Labor.importarExcel"), {
        onSuccess: () => {
            alert("Archivo importado correctamente");
        },
    });
}

const menu = ref(null);

const items = ref([
    {
        label: "Descargar Plantilla",
        icon: "pi pi-search",
        command: () => {
            window.location.href = route("Labor.descargarPlantilla");
        },
    },
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

const handleFileChange = (event) => {
    formFile.file = event.target.files[0];
};
</script>

<template>
    <Head title="Labor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Actualizar Masivo -
                <a :href="route('Labor.Explorar')"> Labor</a>
            </h2>
        </template>
        <!-- Menu 2-->

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->

                    <Panel toggleable>
                        <template #header>
                            <div class="flex items-center gap-2">
                                <span class="font-bold"
                                    >Importar Informacion</span
                                >
                            </div>
                        </template>

                        <template #icons>
                            <Button
                                icon="pi pi-cog"
                                severity="secondary"
                                rounded
                                text
                                @click="toggle"
                            />
                            <Menu
                                ref="menu"
                                id="config_menu"
                                :model="items"
                                popup
                            />
                        </template>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <form
                                @submit.prevent="validarArchivo"
                                class="flex flex-col items-center"
                            >
                                <input
                                    type="file"
                                    @change="handleFileChange"
                                    class="mb-2"
                                />
                                <Button type="submit">Validar Archivo</Button>
                            </form>
                            <form
                                @submit.prevent="importarArchivo"
                                class="flex flex-col items-center"
                            >
                                <Button type="submit">Importar Archivo</Button>
                            </form>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                      
                            <table v-if="datatable.length > 0" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Labor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CostoHect</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cumplido</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in datatable" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.slug }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.labor }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.CostoHect }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.TipoCumplido }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </Panel>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
