<script setup lang="ts">
defineProps({
  links: Object,
});
</script>

<template>
  <button
    v-for="link in links"
    :key="link.label"
    type="button"
    :disabled="link.active || !link.url"
    class="inline-flex h-8 min-w-8 cursor-pointer items-center justify-center rounded-md border px-2 text-sm font-medium transition-colors"
    :class="
      link.active
        ? 'border-violet-500 bg-violet-500 text-white'
        : link.url
        ? 'border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800'
        : 'border-gray-200 text-gray-300 dark:border-gray-700 dark:text-gray-600'
    "
    @click="$emit('goPage', link.url)"
    v-html="link.label"
  />
</template>
