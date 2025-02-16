<script setup>
import { usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import PrimaryButton from './PrimaryButton.vue';

const exportReport = () => {
  // const swal = usePage().props?.$swal || window.Swal;

  Swal.fire({
    title: 'Processando...',
    text: 'Iniciando a exportação do relatório',
    allowOutsideClick: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading()
    }
  })

  router.post('employees-export', {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (response) => {
      Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: response.message || 'Relatório está sendo gerado. Você receberá uma notificação quando estiver pronto.',
        confirmButtonText: 'Ok'
      })
    },
    onError: (errors) => {
      Swal.fire({
        icon: 'error',
        title: 'Erro',
        text: 'Ocorreu um erro ao gerar o relatório. Tente novamente.',
        confirmButtonText: 'Ok'
      })
    }
  })
}
</script>

<template>
  <PrimaryButton @click="exportReport">Exportar relatório</PrimaryButton>
</template>