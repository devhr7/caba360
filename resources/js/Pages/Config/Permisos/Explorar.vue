<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head,router } from "@inertiajs/vue3";

import { FilterMatchMode } from "@primevue/core/api";
const props = defineProps(["dataPermisos"]);
const datatd = ref({});
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    distrito: { value: null, matchMode: FilterMatchMode.STARTS_WITH }
});
const edit = (prod) => {
    datatd.value = {...prod};
    router.get(route('Permisos.edit',datatd.value.id));
};

function crear() {
    router.get(route('Permisos.create'));
}

</script>

<template>
    <Head title="Permisos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Explorar - Permisos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <DataTable
                     v-model:filters="filters"
                        :value="dataPermisos"
                        showGridlines
                        paginator
                        :rows="5"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem"
                        filterDisplay="row"
                        :loading="loading"
                        :globalFilterFields="['name']"
                    >
                        <template #header>
                            <div
                                class="flex flex-wrap gap-2 items-center justify-between"
                            >
                            <Button label="Crear" icon="pi pi-plus" severity="success" class="mr-2" @click="crear" />
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


                        <Column field="name" header="Permisos"></Column>
                        <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="edit(slotProps.data)" />

                    </template>
                </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
