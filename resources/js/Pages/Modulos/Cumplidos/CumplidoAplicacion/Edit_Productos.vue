<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";

//Adicicionar Producto
function SubmitAddProduct() {
    formAddProduct.post(
        route("CumplidoAplicacionProducto.store", props.datos.slug)
    );
}
// _Eliminar Producto
const submitDeleteProducto = (prod) => {
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
            formAddProduct
                .delete(
                    route(
                        "CumplidoAplicacionProducto.delete",
                        reg_datatable.value.id
                    )
                )
                .then();

            // Mensaje Final
            Swal.fire({
                title: "Eliminado!",
                text: "Ha Sido Eliminado Correctamente.",
                icon: "success",
            });
        }
    });
};

async function getdataProducto() {
    await axios
        .post(route("MateriaPrima.getOptionsMateriaPrimaScope"), {
            GrupoMPrima_id: formAddProduct.TipoProducto,
        })
        .then(function (response) {
            props.options.get_MPrima = response.data;
        });
}
</script>

<template>
    <Card>
        <template #content>
            <Panel header="Adicionar Productos" toggleable>
                <form @submit.prevent="SubmitAddProduct">
                    <!-- Fila 1 Adicionar Producto-->
                    <div class="grid grid-cols-5 gap-4 mt-2">
                        <!-- Tipo Producto-->
                        <div class="flex flex-col gap-2">
                            {{ props.datos.slug }}
                            <label for="TipoProducto">Tipo Producto</label>
                            <Select
                                v-model="formAddProduct.TipoProducto"
                                :options="props.options.get_GrupoMPrima"
                                filter
                                showClear
                                optionLabel="label"
                                placeholder="Seleccionar"
                                v-on:change="getdataProducto"
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

                            <small
                                v-if="errors.TipoProducto"
                                id="finca-help"
                                class="text-red-700"
                                >{{ errors.TipoProducto }}</small
                            >
                        </div>
                        <!--- Columna 2-->
                        <div class="flex flex-col gap-2">
                            <label for="Producto">Producto</label>
                            <Select
                                v-model="formAddProduct.Producto"
                                v-on:change="getdataGrupoProducto"
                                :options="props.options.get_MPrima"
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

                            <small
                                v-if="errors.Producto"
                                id="finca-help"
                                class="text-red-700"
                                >{{ errors.Producto }}</small
                            >
                        </div>
                        <!-- Columna 3-->
                        <div class="flex flex-col gap-2">
                            <label for="CantxHect">Cantidad Por Hect</label>
                            <InputText
                                id="CantxHect"
                                name="CantxHect"
                                v-model="formAddProduct.CantxHect"
                                aria-describedby="CantxHect-help"
                            />
                            <small
                                v-if="errors.CantxHect"
                                id="CantxHect-help"
                                class="text-red-700"
                                >{{ errors.CantxHect }}</small
                            >
                        </div>

                        <!-- Columna 4-->
                        <div class="flex flex-col gap-2">
                            <label for="CantTotal">Cantidad Total</label>
                            <InputText
                                id="CantTotal"
                                name="CantTotal"
                                v-model="formAddProduct.Total"
                                aria-describedby="CantTotal-help"
                            />
                        </div>
                        <!-- Columna 5-->

                        <!-- Columna 5-->
                        <div class="flex flex-col gap-2">
                            <Button
                                type="submit"
                                label="Adicionar Producto"
                                class="w-full"
                                :disabled="formAddProduct.processing"
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
            <div>Productos</div>
        </template>
        <template #content>
            <div>
                {{ props.datos.DataTableProductoCumplidoAplicacion }}
                <DataTable
                    v-model:filters="filters"
                    :value="DataTableProductoCumplidoAplicacion"
                    :size="{ label: 'Small', value: 'small' }"
                    :globalFilterFields="[
                        'GrupoMateriaPrima',
                        'NombreProducto',
                    ]"
                    tableStyle="min-width: 50rem"
                >
                    <Column field="GrupoMateriaPrima" header="Grupo"></Column>
                    <Column field="NombreProducto" header="Producto"></Column>
                    <Column field="PrecioUnit" header="Precio Unit"></Column>
                    <Column field="Dosis_por_Ha" header="Dosis xHa"></Column>
                    <Column
                        field="cantidad_Total"
                        header="cantidad_Total"
                    ></Column>
                    <Column field="PrecioTotalFormat" header="Total"></Column>
                    <Column :exportable="false" style="min-width: 12rem">
                        <!-- Boton Editar -->
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                class="mr-2"
                                @click="submitDeleteProducto(slotProps.data)"
                            />
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column
                                footer="Totals:"
                                :colspan="5"
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
