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

//Crear
function submitCrear() {
    form.post(route("MateriaPrima.store"), form);
}

// Formulario para subir archivo
const formFile = useForm({
    file: null,
});

// Validar archivo
function validarArchivo() {
    formFile.post(route("MateriaPrima.validarExcel"), {
        onSuccess: () => {
            alert("Archivo validado correctamente");
        },
    });
}

// Importar archivo
function importarArchivo() {
    formFile.post(route("MateriaPrima.importarExcel"), {
        onSuccess: () => {
            alert("Archivo importado correctamente");
        },
    });
}

/**
 * Definiendo Constantes
 * */


const menu = ref(null);



const items = ref([
    {
        label: 'Descargar Plantilla',
        icon: 'pi pi-search',
        command: () => {
            window.location.href = route('MateriaPrima.descargarPlantilla');
        }
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
    <Head title="Materia Prima" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Actualizar Precio Masivo -  <a :href='route("MateriaPrima.Explorar")'> Materia Prima</a>
            </h2>

             
        </template>
        <!-- Menu 2-->

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Formulario-->
                    <form @submit.prevent="submitCrear">
                        <Panel toggleable>
                            <template #header>
                                <div class="flex items-center gap-2">
                                    
                                    <span class="font-bold">Importar Informacion</span>
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
                                <form @submit.prevent="validarArchivo" class="flex flex-col items-center">
                                    <input type="file" @change="handleFileChange" class="mb-2" />
                                    <Button type="submit">Validar Archivo</Button>
                                </form>
                                <form @submit.prevent="importarArchivo" class="flex flex-col items-center">
                                    <Button type="submit">Importar Archivo</Button>
                                </form>
                                
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <table>
                                </table>
                                
                            </div>
                        </Panel>
                    </form>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
