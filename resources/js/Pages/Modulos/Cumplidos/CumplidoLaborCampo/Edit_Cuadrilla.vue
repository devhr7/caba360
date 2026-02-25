<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    CumplidoLaborCampo: { type: Object },
    datatable: { type: Object },
    options: { type: Object },
});

/** Formulario */

const form = useForm({
    CumplidoLaborCampo: props.CumplidoLaborCampo, // id del Cumplido de la Labor de Campo
    reg: null, // id
    empleado: null,
});
const reg_datatable = ref(props.datatable); // Registro de la Tabla
const optionsEmpleado = ref(props.options.get_empleados);
// Constante Ver Modal
const visible = ref(false);

const empleado = ref(null);

const ModalAddRegistro = (prod) => {
    // Crear Registro
    if (prod) {
        console.info("Editar Registro");

        reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila

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
                form.delete(
                    route(
                        "CumpLaborCampoCuadrilla.destroy",
                        reg_datatable.value.id
                    )
                ).then();

                // Mensaje Final
                Swal.fire({
                    title: "Eliminado!",
                    text: "Ha Sido Eliminado Correctamente.",
                    icon: "success",
                });
            }
        });

        //empleado.value = reg_datatable.value.id;
    } else {
        console.info("Nuevo Registro");
        form.CumplidoLaborCampo_cuadrilla = null;
        form.empleado = null;
        visible.value = true;
    }
};

const formatCurrency = (value) => {
    return value.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });

};


const lastTotal = computed(() => {
    let total = 0;
    for (let data of props.datatable) {
        total += data.total_sinf;
    }

    return formatCurrency(total);
});

const CantTotal = computed(() => {
    let cant_total = 0;
    for (let data of props.datatable) {
        cant_total += data.cantidad_sinf;
    }

    return formatCurrency(cant_total);
});

async function submit() {
    if (form.CumplidoLaborCampo_cuadrilla == null) {
        // Crear Registro
        await axios
            .post(route("CumpLaborCampoCuadrilla.store"), form)
            .then(function (response) {
                location.reload();
                visible.value = false;
            })
            .catch((e) => console.log(e));
    } else {
        // Editar Registro
        await axios
            .put(
                route(
                    "CumplidoLaborCampo.updateCuadrilla",
                    form.CumplidoLaborCampo_cuadrilla
                ),
                form
            )
            .then(function (response) {
                location.reload();
                visible.value = false;
            })
            .catch((e) => console.log(e));
    }
}
</script>

<template>
    <div>
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Formulario-->
                <Card>
                    <template #title>Cuadrilla</template>
                    <template #content>

                        <DataTable :value="props.datatable" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex justify-between">
                                    <Button type="button" label="Añadir" outlined @click="ModalAddRegistro(false)" />
                                </div>
                            </template>
                            <Column field="identificacion" header="Identificacion"></Column>
                            <Column field="empleado" header="Nombre"></Column>
                            <Column field="cantidad" header="Cantidad"></Column>
                            <Column field="valor_unit" header="PrecioUnit"></Column>
                            <Column field="total" header="total"></Column>

                            <Column :exportable="false" style="max-width: 3rem" header="Detalle">
                                <!-- Boton Editar -->
                                <template #body="slotProps">
                                    <Button icon="pi pi-trash" outlined rounded severity="danger" class="mr-2" @click="
                                        ModalAddRegistro(slotProps.data)
                                        " />
                                </template>
                            </Column>
                            <template #footer>
                                En total hay
                                {{
                                    props.datatable ? props.datatable.length : 0
                                }}
                                Empleados.
                            </template>
                            <ColumnGroup type="footer">
                                <Row>
                                    <Column footer="Totals:" :colspan="2" footerStyle="text-align:right" />
                                    <Column :footer="CantTotal" />
                                    <Column :footer="0" />
                                    <Column :footer="lastTotal" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>
                    </template>
                </Card>

                <Dialog v-model:visible="visible" modal header="Añadir Registro" :style="{ width: '25rem' }">
                    <template #header>
                        <div class="inline-flex items-center justify-center gap-2">
                            <span class="font-bold whitespace-nowrap">{{
                                form.CumplidoLaborCampo_cuadrilla
                                    ? "Editar Registro"
                                    : "Añadir Registro"
                            }}
                            </span>
                        </div>
                    </template>

                    <div class="flex items-center gap-4 mb-4">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols gap-4 mt-2">
                                <div>
                                    <label for="Empleado" class="font-semibold w-24">
                                        Empleado</label>
                                    <Select v-model="form.empleado" :options="optionsEmpleado" filter
                                        optionLabel="label" placeholder="Seleccionar" class="w-full">
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
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-2">
                                <div>
                                    <Button label="Cancelar" text severity="secondary" @click="visible = false"
                                        autofocus />
                                </div>
                                <div>
                                    <Button type="submit" label="Añadir Registro" severity="success" autofocus />
                                </div>
                            </div>
                        </form>
                    </div>
                </Dialog>
            </div>
        </div>
    </div>
</template>
