<script setup lang="ts">
defineProps<{
  class?: string;
}>();
</script>

<template>
  <h3 
    class="text-lg font-semibold leading-none tracking-tight text-gray-900 dark:text-gray-100"
    :class="class"
  >
    <slot />
  </h3>
</template>