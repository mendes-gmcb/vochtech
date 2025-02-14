<script
  setup
  lang="ts"
>
  import { Link } from '@inertiajs/vue3';
  import DangerButton from '@/Components/DangerButton.vue';

  interface Brand {
    name: string;
  }

  interface Unit {
    id: number;
    trade_name: string;
    legal_name: string;
    cnpj: string;
    brand: Brand;
    created_at: string;
    updated_at: string;
  }

  interface Props {
    unit: Unit;
  }

  const props = defineProps<Props>();
  const emit = defineEmits<{
    (e: 'delete', id: number): void;
  }>();

  function maskCNPJ(value: string): string {
  if (!value) return "";
  
  const cleanedValue = value.replace(/\D/g, ""); // Remove non-numeric characters
  return cleanedValue
    .replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
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
          <h3 class="text-lg font-semibold text-gray-500 text-center">{{ unit.trade_name }}</h3>
          <p class="text-sm text-gray-300 text-center">{{ unit.legal_name }}</p>
        <!-- </div> -->

        <!-- <div class="space-y-1"> -->
          <p class="text-sm">
            <span class="font-medium text-gray-500 text-center">CNPJ:</span>
            <span v-maska data-maska="##.###.###/####-##" class="text-gray-300 text-center"> {{ maskCNPJ(unit.cnpj) }}</span>
          </p>
          <p class="text-sm">
            <span class="font-medium text-gray-500">Marca:</span>
            <span class="text-gray-300 text-center">{{ unit.brand.name }}</span>
          </p>
        <!-- </div> -->
      <!-- </div> -->

      <!-- Datas e Ações -->
      <!-- <div class="space-y-3"> -->
        <!-- <div class="space-y-1"> -->
          <p class="text-sm">
            <span class="font-medium text-gray-500">Criado em:</span>
            <span class="text-gray-300"> {{ formatDate(unit.created_at) }}</span>
          </p>
          <p class="text-sm">
            <span class="font-medium text-gray-500">Atualizado em:</span>
            <span class="text-gray-300"> {{ formatDate(unit.updated_at) }}</span>
          </p>
        <!-- </div> -->

        <div class="flex space-x-3 pt-2">
          <Link :href="route('unit.edit', unit.id)" :active="route().current('unit.list')"
            class="text-base/10 text-gray-300  align-baseline">
          Editar
          </Link>
          <form @submit.prevent="$emit('delete', unit.id)">
            <DangerButton type="submit">
              Remover
            </DangerButton>
          </form>
        </div>
      <!-- </div> -->
    </div>
  </div>
</template>
