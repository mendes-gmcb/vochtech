<script setup lang="ts">
defineProps<{
  class?: string;
}>();
</script>

<template>
  <p 
    class="text-sm text-gray-600 dark:text-gray-400"
    :class="class"
  >
    <slot />
  </p>
</template>
