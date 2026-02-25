<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    datatable: { type: Object },
    options: { type: Object },
    data: { type: Object },
});

const reg_datatable = ref({});
const OptionsFinca = ref(props.options.get_finca);
const form = useForm({
    ppt_id: props.data.id,
    finca: null,
    gasto: null,
    costoxhect: null,
});

//Adicicionar Producto
function SubmitAdd() {
    form.post(route("costos.ppt.costo.store"));
}
// _Eliminar Producto
const submitDelete = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila

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
            form.delete(
                route("costos.ppt.costo.destroy", reg_datatable.value.id)
            );

            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
};
</script>

<template>
    <Card>
        <template #content>
            <Panel header="Adicionar Productos" toggleable>
                <form @submit.prevent="SubmitAdd">
                   
                    
                    <!-- Fila 1 Adicionar Producto-->
                    <div class="grid grid-cols-5 gap-4 mt-2">
                        <!-- Tipo Producto-->
                        <div class="flex flex-col gap-2">
                            <label for="finca">Finca</label>
                            <Select
                                v-model="form.finca"
                                :options="props.options.get_finca"
                                filter
                                showClear
                                optionLabel="label"
                                placeholder="Seleccionar"
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
                        <!--- Columna 2-->
                        <div class="flex flex-col gap-2">
                            <label for="gasto">Gasto</label>
                            <Select
                                v-model="form.gasto"
                                :options="props.options.get_gasto"
                                filter
                                optionLabel="label"
                                placeholder="Seleccionar"
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
                        <!-- Columna 3-->
                        <div class="flex flex-col gap-2">
                            <label for="costoxhect">Costo Por Hect</label>
                            <InputText
                                id="costoxhect"
                                name="costoxhect"
                                v-model="form.costoxhect"
                                aria-describedby="costoxhect-help"
                            />
                        </div>
                        <!-- Columna 5-->
                        <div class="flex flex-col gap-2">
                            <Button
                                type="submit"
                                label="Adicionar"
                                class="w-full"
                                :disabled="form.processing"
                                severity="info"
                            />
                        </div>
                    </div>
                </form>
            </Panel>
        </template>
    </Card>
    <Card>
        <template #title>
            <div>Costo X Hect</div>
        </template>
        <template #content>
            <div>
           
                <DataTable
                    v-model:filters="filters"
                    :value="props.datatable.ppt_detalle_costos"
                    :size="{ label: 'Small', value: 'small' }"
                    :globalFilterFields="['finca', 'gasto']"
                    tableStyle="min-width: 50rem"
                >
                    <Column field="finca" header="Finca"></Column>
                    <Column field="gasto" header="Gasto"></Column>
                    <Column field="costoxhect" header="CostoxHect"></Column>

                    <Column :exportable="false" style="min-width: 12rem">
                        <!-- Boton Editar -->
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                class="mr-2"
                                @click="submitDelete(slotProps.data)"
                            />
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column
                                footer="Totals:"
                                :colspan="2"
                                footerStyle="text-align:right"
                            />
                            <Column :footer="Total" />
                        </Row>
                    </ColumnGroup>
                </DataTable>
            </div>
        </template>
    </Card>
</template>
