<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { PaginatePrev } from "@/components/ui/pagination";
import { PaginateNext } from "@/components/ui/pagination";
import { PageNumbers } from "@/components/ui/pagination";
import { computed } from "vue";

const props = defineProps({
  list: Object,
  nextUrl: String,
  prevUrl: String,
});

const numbers = computed(() => {
  const list = props.list?.links ?? [];

  return list.filter(
    (link: { label: string }) =>
      !link.label.includes("Previous") && !link.label.includes("Next")
  );
});

function goToPage(url: string | null) {
  if (!url) {
    return;
  }

  router.visit(url, { preserveState: true });
}
</script>

<template>
  <div v-if="list?.last_page && list.last_page > 1" class="mt-4 flex items-center justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      Showing {{ list?.from }} to {{ list?.to }} of {{ list?.total }} entries
    </p>

    <div class="flex items-center gap-1">
      <PaginatePrev @go-page="goToPage" :url="prevUrl" />
      <PageNumbers @go-page="goToPage" :links="numbers" />
      <PaginateNext @go-page="goToPage" :url="nextUrl" />
    </div>
  </div>
</template>
