<script setup lang="ts">
import { ChevronRight } from "@lucide/vue";
defineProps({
  url: String || null,
});
</script>

<template>
  <div>
    <button
      type="button"
      :disabled="!url"
      class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-md border text-sm transition-colors disabled:cursor-not-allowed disabled:opacity-40"
      :class="
        url
          ? 'border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800'
          : 'border-gray-200 text-gray-300 dark:border-gray-700 dark:text-gray-600'
      "
      @click="$emit('goPage', url)"
    >
      <span class="sr-only">Next</span>
      <ChevronRight class="h-4 w-4" />
    </button>
  </div>
</template>
