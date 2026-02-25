<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import GestionRol from "@/Pages/Config/Rol/GestionRol.vue";


const products = ref(null);

// Definiendo props
const props = defineProps({
    datos: { type: Object },
    errors: { type: Object },
    dt_permisos: { type: Object },
    permisos_rol: { type: Object },
});

const ListPermisos = ref([props.dt_permisos, props.permisos_rol]);

// Menu 2
const items = ref([
    { label: "Inicio", url: route("dashboard") },
    { label: "Permiso", url: route("Permisos.Explorar") },
]);

// Formulario
// Formulario
const form = useForm({
    name: props.datos.name,
});

// Actualizar
function submit() {
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
            form.put(route("Rol.update", props.datos.id), form);
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
            form.delete(route("Rol.delete", props.datos.id));
            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
}

async function ActualizarPermisos() {
    await axios({
        method: "post",
        url: route("Rol.Gestion", props.datos.id),
        data: {
            permisos: ListPermisos.value,
        },
    });
}
</script>

<template>
    <Head title="Rol" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit - Rol
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

                    <form @submit.prevent="submit">
                        <Card style="overflow: hidden">
                            <template #content>
                                <div class="grid grid-cols-4 gap-4">
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <label for="name">Nombre Rol</label>
                                            <InputText
                                                id="name"
                                                name="name"
                                                v-model="form.name"
                                                aria-describedby="name-help"
                                            />
                                            <small
                                                v-if="errors.name"
                                                id="name-help"
                                                class="text-red-700"
                                                >{{ errors.name }}</small
                                            >
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template #footer>
                                <div class="flex gap-4 mt-1">
                                    <Button
                                        type="submit"
                                        label="Guardar"
                                        class="w-full"
                                        :disabled="form.processing"
                                    />
                                    <Button
                                        type="button"
                                        label="Eliminar Rol"
                                        class="w-full"
                                        @click="submitDelete()"
                                    />
                                </div>
                            </template>
                        </Card>
                    </form>
                </div>

                <Card>
                    <template #title>Permisos</template>
                    <template #content>
                        <PickList
                            v-model="ListPermisos"
                            v-change="ActualizarPermisos()"
                            dataKey="id"
                            scrollHeight="20rem"
                        >
                            <template #option="{ option, selected }">
                                <div
                                    class="flex flex-wrap p-1 items-center gap-4 w-full"
                                >
                                    <div class="flex-1 flex flex-col">
                                        <span class="text-sm font-bold">{{
                                            option.name
                                        }}</span>
                                        <span
                                            :class="[
                                                'text-sm',
                                                {
                                                    'text-surface-500 dark:text-surface-400':
                                                        !selected,
                                                    'text-inherit': selected,
                                                },
                                            ]"
                                            >{{ option.description }}</span
                                        >
                                    </div>
                                </div>
                            </template>
                        </PickList>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
