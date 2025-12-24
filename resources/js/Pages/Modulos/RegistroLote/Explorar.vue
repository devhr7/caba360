<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

// DEfiniendo Props

const props = defineProps({
    RegistroLotes: { type: Object },
    options: { type: Object },
    errors: { type: Object },
});

const OptionsDistrito = ref(["Meseta", "Norte"]); // Datos del Distrito
const OptionsFinca = ref(props.get_finca); // Datos del Finca
const OptionsLote = ref(props.get_lote); // Datos del Lote

const reg_datatable = ref({});

// Filtros de busqueda
const filters = ref({
    global: { value: null },
    RegistroLotes: { value: null },
    distrito_id: { value: null },
    finca_id: { value: null },
    NombreLote: { value: null },
    "Status.name": { value: null },
    "statusLote.name": { value: null },
    Codigo: { value: null },
    Hect: { value: null },
});

const statuses = ref(["Norte", "Meseta"]);

// Funcion del Boton Editar
const edit = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.edit_url); // redirecciona a la vista Editar
};

// Funcion del Boton RegistroLote
const RegistroLote = (prod) => {
    reg_datatable.value = { ...prod }; // Obtener datos Clic de la fila
    router.get(reg_datatable.value.RegistroLote_url); // redirecciona a la vista Editar
};

// boton de Crear
function crear() {
    router.get(route("RegLote.Crear")); // Redirecciona a la Vista Crear
}
</script>

<template>
    <Head title="RegLotes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar -
                <a
                    :href="route('RegLote.Explorar')"
                    class="text-teal-800 hover:text-teal-600"
                    >Registro Lote</a
                >
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        v-model:filters="filters"
                        :value="RegistroLotes"
                        showGridlines
                        paginator
                        :rows="10"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        dataKey="id"
                        tableStyle="max-width: 100%"
                        :loading="loading"
                        :globalFilterFields="[
                            'finca_id',
                            'distrito_id',
                            'Codigo',
                            'Status.name',
                            'statusLote.name',
                            'NombreLote',
                            'Hect',
                        ]"
                        filterDisplay="menu"
                    >
                        <template #header>
                            <div
                                class="flex flex-wrap gap-2 items-center justify-between"
                            >
                                <!-- Boton de Crear -->
                                <Button
                                    label="Crear"
                                    icon="pi pi-plus"
                                    severity="success"
                                    class="mr-2"
                                    @click="crear"
                                    v-if="
                                        $page.props.Permission.user_permision.includes(
                                            'mod.reglote.create'
                                        )
                                    "
                                />
                                <IconField>
                                    <InputIcon>
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <InputText
                                        v-model="filters['global'].value"
                                        placeholder="Buscar..."
                                    />
                                </IconField>
                            </div>
                        </template>

                        <Column
                            field="statusLote.name"
                            header="Status"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 5rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                <Tag
                                    :value="data.statusLote1"
                                    :severity="data.statusLote.severity"
                                />
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Search by name"
                                />
                            </template>
                        </Column>
                        <Column
                            header="Codigo"
                            field="Codigo"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 5rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.Codigo }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Search by name"
                                />
                            </template>
                        </Column>

                        <Column
                            field="Status.name"
                            header="Status"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 5rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                <Tag
                                    :value="data.Status.name"
                                    :severity="data.Status.severity"
                                />
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Search by name"
                                />
                            </template>
                        </Column>
                        <Column
                            header="Distrito"
                            field="distrito_id"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 5rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.distrito_id }}
                            </template>
                            <template #filter="{ filterModel }">
                                <Select
                                    v-model="filterModel.value"
                                    :options="OptionsDistrito"
                                    filter
                                    placeholder="Seleccionar"
                                    showClear
                                >
                                    <template #option="slotProps">
                                        <div class="flex items-center">
                                            <div>
                                                {{ slotProps.option }}
                                            </div>
                                        </div>
                                    </template>
                                </Select>
                            </template>
                        </Column>

                        <Column
                            header="Finca"
                            field="finca_id"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 12rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.finca_id }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Search by name"
                                />
                            </template>
                        </Column>

                        <Column
                            header="Lote"
                            field="NombreLote"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 5rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.NombreLote }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Search by name"
                                />
                            </template>
                        </Column>

                        <Column
                            header="Hect"
                            field="Hect"
                            :filterMenuStyle="{ width: '14rem' }"
                            style="min-width: 5rem"
                            :showFilterMatchModes="false"
                        >
                            <template #body="{ data }">
                                {{ data.Hect }}
                            </template>
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="filterCallback()"
                                    placeholder="Search by name"
                                />
                            </template>
                        </Column>
                        <!-- Columna de Acciones-->
                        <Column :exportable="false" style="min-width: 5rem">
                            <!-- Boton Editar -->
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-pencil"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="edit(slotProps.data)"
                                />

                                <Button
                                    icon="pi pi-receipt"
                                    outlined
                                    rounded
                                    class="mr-2"
                                    @click="RegistroLote(slotProps.data)"
                                    v-if="
                                        $page.props.Permission.user_permision.includes(
                                            'mod.reglote.show.hv'
                                        )
                                    "
                                />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
