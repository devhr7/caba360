<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    datatable: { type: Object },
    CumplidoAplicacion: { type: Object },
    options: { type: Object },
});

const reg_datatable = ref({});
const visible = ref(false);
/**
 * Adiciona un nuevo producto a la tabla de productos
 * @param {Object} - El objeto con los datos del formulario
 * @returns {void}
 */

const formLabor = useForm({
    labor: null,
    DetalleCump: null,
    CumplidoAplicacion: props.CumplidoAplicacion,
});

const OptionsLabor = ref(props.options.get_labor);

const AbrirModal = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    formLabor.DetalleCump = reg_datatable.value.id;
    visible.value = true;
    // Alerta
};

async function submitUpdate() {
    //OptionsLote.value=null;
    await axios
        .post(
            route(
                "CumplidoAplicacionProducto.storeServicios",
                formLabor.DetalleCump
            ),
            formLabor.data()
        )
        .then(function (response) {
            location.reload();
            visible.value = false;
        })
        .catch(() => {
            Swal.fire({
                title: "Error",
                text: "No se pudo actualizar el servicio.",
                icon: "error",
            });
        });
    //.catch((e) => console.log(e));
}
</script>

<template>
    <div>
        <Card class="bg-teal-50">
            <template #title>Servicios</template>

            <template #content>

                <DataTable v-model:filters="filters" :value="datatable" :size="{ label: 'Small', value: 'small' }"
                    :globalFilterFields="[
                        'labor',
                    ]" tableStyle="min-width: 50rem" class="bg-teal-50">
                    <Column field="Labor" header="labor"></Column>

                    <Column field="cantidad_Total" header="cantidad_Total"></Column>
                    <Column field="PrecioTotalFormat" header="Total"></Column>
                    <Column :exportable="false" style="min-width: 12rem">
                        <!-- Boton Editar -->
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" outlined rounded class="mr-2"
                                @click="AbrirModal(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>

        <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
            <template #header>
                <div class="inline-flex items-center justify-center gap-2">
                    <span class="font-bold whitespace-nowrap">Actualizar Aplicacion/Labor</span>
                </div>
            </template>

            <div class="flex items-center gap-4 mb-4">
                <form @submit.prevent="submitUpdate">
                    <label for="TipoAplicacion" class="font-semibold w-24">
                        Tipo Aplicacion</label>
                    <br />

                    <Select v-model="formLabor.labor" :options="OptionsLabor" filter optionLabel="label"
                        placeholder="Seleccionar" class="w-full md:w-56">
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex items-center">
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
                </form>
            </div>
            <template #footer>
                <Button label="Cancelar" text severity="secondary" @click="visible = false" autofocus />
                <Button label="Actualizar" severity="success" @click="submitUpdate()" autofocus />
            </template>
        </Dialog>
    </div>
</template>
