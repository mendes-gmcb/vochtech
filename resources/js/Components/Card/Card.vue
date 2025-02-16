<script setup lang="ts">
defineProps<{
  class?: string;
}>();
</script>

<template>
  <div 
    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700"
    :class="class"
  >
    <slot />
  </div>
</template>
