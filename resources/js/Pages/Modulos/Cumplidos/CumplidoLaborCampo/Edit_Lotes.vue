<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const toast = useToast();

const props = defineProps({
    CumplidoLaborCampo: { type: Object },
    datatable: { type: Object },
    options: { type: Object },
});
const RegLote = ref(false);
/** Formulario */

const form = useForm({
    CumplidoLaborCampo: props.CumplidoLaborCampo, // id del Cumplido de la Labor de Campo
    reg: null, // id
    finca: null,
    lote: null,
    RegLote_id: null,
});
const reg_datatable = ref(props.datatable); // Registro de la Tabla

const OptionsLote = ref(props.options.get_lote);
// Constante Ver Modal
const visible = ref(false);

// Carga los Datos de los REgLotes en los campos nombre lote y hect
async function getdataRegLote() {
    await axios
        .post(route("RegLote.getRegLoteActivo"), form.lote)
        .then(function (response) {
            console.error(response.data);
            if (response.data) {
                RegLote.value = response.data;

                form.RegLote_id = RegLote.value.id;
            } else {
                RegLote.value = false;
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "Verificar en Módulo Registro Lote",
                    life: 8000,
                });
            }
        })
        .catch((e) => console.log(e));
}

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
                    route("CumpLaborCampo_lote.destroy", reg_datatable.value.id)
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

async function submit() {
    if (form.CumplidoLaborCampo) {
        // Crear Registro
        await axios
            .post(route("CumpLaborCampo_lote.store"), form)
            .then(function (response) {
                location.reload();
                visible.value = false;
            })
            .catch((e) => console.log(e));
    }
}

const formatCurrency = (value) => {
    return value.toLocaleString("es-CO", {
        style: "currency",
        currency: "COP",
    });
};

const lastTotal = computed(() => {
    let total = 0;
    for (let data of props.datatable) {
        total += data.Hect;
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
</script>

<template>
    <div>
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Formulario-->
                <Card>
                    <template #title>Lotes</template>
                    <template #content>
                        <DataTable
                            :value="props.datatable"
                            tableStyle="min-width: 50rem"
                        >
                            <template #header>
                                <div class="flex justify-between">
                                    <Button
                                        type="button"
                                        label="Añadir"
                                        outlined
                                        @click="ModalAddRegistro(false)"
                                    />
                                </div>
                            </template>
                            <Column field="lote" header="Lote"></Column>
                            <Column
                                field="codigo_lote"
                                header="Codigo"
                            ></Column>
                            <Column field="Hect" header="Hect"></Column>
                            <Column field="cantidad" header="Total"></Column>
                            <Column
                                :exportable="false"
                                style="max-width: 3rem"
                                header="Detalle"
                            >
                                <!-- Boton Editar -->
                                <template #body="slotProps">
                                    <Button
                                        icon="pi pi-trash"
                                        outlined
                                        rounded
                                        severity="danger"
                                        class="mr-2"
                                        @click="
                                            ModalAddRegistro(slotProps.data)
                                        "
                                    />
                                </template>
                            </Column>
                            <template #footer>
                                En total hay
                                {{
                                    props.datatable ? props.datatable.length : 0
                                }}
                                Lotes.
                            </template>
                            <ColumnGroup type="footer">
                                <Row>
                                    <Column
                                        footer="Totals:"
                                        :colspan="2"
                                        footerStyle="text-align:right"
                                    />
                                    <Column :footer="lastTotal" />
                                
                                    <Column :footer="CantTotal" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>

                        <Dialog
                            v-model:visible="visible"
                            modal
                            header="Añadir Registro"
                            :style="{ width: '50rem' }"
                        >
                            <template #header>
                                <div
                                    class="inline-flex items-center justify-center gap-2"
                                >
                                    <span class="font-bold whitespace-nowrap"
                                        >{{
                                            form.CumplidoLaborCampo_cuadrilla
                                                ? "Editar Registro"
                                                : "Añadir Registro"
                                        }}
                                    </span>
                                </div>
                            </template>

                            <div class="flex items-center gap-4 mb-4">
                                <form @submit.prevent="submit">
                                    <div class="grid grid-cols-4 gap-4 mt-2">
                                        <div>
                                            <label for="lote">Lote</label>

                                            <Select
                                                v-model="form.lote"
                                                :options="OptionsLote"
                                                filter
                                                optionLabel="label"
                                                placeholder="Seleccionar"
                                                v-on:change="getdataRegLote"
                                                showClear
                                                class="w-full"
                                            >
                                                <template #value="slotProps">
                                                    <div
                                                        v-if="slotProps.value"
                                                        class="flex items-center"
                                                    >
                                                        <div>
                                                            {{
                                                                slotProps.value
                                                                    .label
                                                            }}
                                                        </div>
                                                    </div>
                                                    <span v-else>
                                                        {{
                                                            slotProps.placeholder
                                                        }}
                                                    </span>
                                                </template>
                                                <template #option="slotProps">
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <div>
                                                            {{
                                                                slotProps.option
                                                                    .label
                                                            }}
                                                        </div>
                                                    </div>
                                                </template>
                                            </Select>
                                        </div>
                                        <div>
                                            <label for="RegLote_id"
                                                >Codigo Lote</label
                                            >
                                            <Toast />

                                            <p class="text-base" v-if="RegLote">
                                                {{ RegLote.Codigo }} |
                                                {{ RegLote.Hect }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mt-2">
                                        <div>
                                            <Button
                                                label="Cancelar"
                                                text
                                                severity="secondary"
                                                @click="visible = false"
                                                autofocus
                                            />
                                        </div>
                                        <div>
                                            <Button
                                                type="submit"
                                                label="Añadir Registro"
                                                severity="success"
                                                autofocus
                                            />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </Dialog>
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>
