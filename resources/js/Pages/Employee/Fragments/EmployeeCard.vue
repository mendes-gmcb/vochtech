<script
  setup
  lang="ts"
>
  import { Link } from '@inertiajs/vue3';
  import DangerButton from '@/Components/DangerButton.vue';

  interface Unit {
    trade_name: string;
  }

  interface Employee {
    id: number;
    name: string;
    email: string;
    cpf: string;
    unit: Unit;
    created_at: string;
    updated_at: string;
  }

  interface Props {
    employee: Employee;
  }

  const props = defineProps<Props>();
  const emit = defineEmits<{
    (e: 'delete', id: number): void;
  }>();

  function maskCPF(value: string): string {
    if (!value) return "";

    const cleanedValue = value.replace(/\D/g, ""); // Remove non-numeric characters
    return cleanedValue
      // .replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$3.$3.$3-$2");
      .replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4");
  }

  const formatDate = (date: string): string => {
    return new Date(date).toLocaleString('pt-BR');
  };
</script>
<!-- UnitCard.vue -->
<template>
  <div class="bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
    <div class="flex flex-col justify-center align-middle">
      <!-- Informações principais -->
      <!-- <div class="space-y-3"> -->
      <!-- <div> -->
      <h3 class="text-lg font-semibold text-gray-500 text-center">{{ employee.name }}</h3>
      <p class="text-sm text-gray-300 text-center">{{ employee.email }}</p>
      <!-- </div> -->

      <!-- <div class="space-y-1"> -->
      <p class="text-sm">
        <span class="font-medium text-gray-500 text-center">CPF:</span>
        <span v-maska data-maska="##.###.###/####-##" class="text-gray-300 text-center"> {{ maskCPF(employee.cpf)
          }}</span>
      </p>
      <p class="text-sm">
        <span class="font-medium text-gray-500">Unidade:</span>
        <span class="text-gray-300 text-center">{{ employee.unit.trade_name }}</span>
      </p>
      <!-- </div> -->
      <!-- </div> -->

      <!-- Datas e Ações -->
      <!-- <div class="space-y-3"> -->
      <!-- <div class="space-y-1"> -->
      <p class="text-sm">
        <span class="font-medium text-gray-500">Criado em:</span>
        <span class="text-gray-300"> {{ formatDate(employee.created_at) }}</span>
      </p>
      <p class="text-sm">
        <span class="font-medium text-gray-500">Atualizado em:</span>
        <span class="text-gray-300"> {{ formatDate(employee.updated_at) }}</span>
      </p>
      <!-- </div> -->

      <div class="flex space-x-3 pt-2">
        <Link :href="route('employees.edit', employee.id)" :active="route().current('employees.index')"
          class="text-base/10 text-gray-300  align-baseline">
        Editar
        </Link>
        <form @submit.prevent="$emit('delete', employee.id)">
          <DangerButton type="submit">
            Remover
          </DangerButton>
        </form>
      </div>
      <!-- </div> -->
    </div>
  </div>
</template>
