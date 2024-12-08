<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SelectRole from '@/Components/SelectRole.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inicio
            </h2>
        </template>

        <div >
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <SelectRole />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
