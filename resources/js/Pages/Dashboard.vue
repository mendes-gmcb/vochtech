<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Card } from '@/Components/Card';
import { computed } from 'vue';

import ExportExcel from '@/Components/ExportExcel.vue';

// Definição das props
const props = defineProps<{
    data: {
        total_employees: number;
        employees_by_group: Array<{ name: string; total: number }>;
        new_employees_last_30_days: number;
        employees_by_brand: Array<{ name: string; total: number }>;
    }
}>();

// Computar percentual de novos funcionários
const newEmployeesPercentage = computed(() => {
    return ((props.data.new_employees_last_30_days / props.data.total_employees) * 100).toFixed(1);
});

// Ordenar dados por total para os gráficos
const sortedGroupData = computed(() => {
    return [...props.data.employees_by_group].sort((a, b) => b.total - a.total);
});

const sortedBrandData = computed(() => {
    return [...props.data.employees_by_brand].sort((a, b) => b.total - a.total);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>

            <ExportExcel />
        </template>

        <div class="py-12 dark:text-gray-300">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Cards de Métricas -->
                <div class="grid gap-4 mb-8 md:grid-cols-3">
                    <!-- Total de Colaboradores -->
                    <Card class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Total de Colaboradores
                                </p>
                                <h3 class="text-2xl font-bold mt-2">
                                    {{ data.total_employees }}
                                </h3>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-900">
                                <svg
                                    class="w-6 h-6 text-blue-600 dark:text-blue-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </Card>

                    <!-- Novos Colaboradores -->
                    <Card class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Novos Colaboradores (30 dias)
                                </p>
                                <h3 class="text-2xl font-bold mt-2">
                                    {{ data.new_employees_last_30_days }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ newEmployeesPercentage }}% do total
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full dark:bg-green-900">
                                <svg
                                    class="w-6 h-6 text-green-600 dark:text-green-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                            </div>
                        </div>
                    </Card>

                    <!-- Média por Unidade -->
                    <Card class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Grupos Econômicos
                                </p>
                                <h3 class="text-2xl font-bold mt-2">
                                    {{ data.employees_by_group.length }}
                                </h3>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full dark:bg-purple-900">
                                <svg
                                    class="w-6 h-6 text-purple-600 dark:text-purple-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Tabelas Detalhadas -->
                <div class="grid gap-6">
                    <!-- Detalhamento por Grupo Econômico -->
                    <Card class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                            Detalhamento por Grupo Econômico
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Grupo Econômico
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total de Colaboradores
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            % do Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="group in sortedGroupData" :key="group.name">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ group.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ group.total }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ((group.total / data.total_employees) * 100).toFixed(1) }}%
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>