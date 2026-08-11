<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, reactive, watch } from 'vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineProps({
    countries: Object,
});

const filters = reactive({
    search: '',
    date: '',
    status: '',
    per_page: '',
    countryId: '',
});

const statuses = [
    { value: 'pending', label: 'Pending', color: 'bg-amber-400' },
    { value: 'processing', label: 'Processing', color: 'bg-blue-400' },
    { value: 'approved', label: 'Approved', color: 'bg-emerald-400' },
    { value: 'rejected', label: 'Rejected', color: 'bg-rose-400' },
];

const perPageOptions = [
    { value: '10', label: '10' },
    { value: '25', label: '25' },
    { value: '50', label: '50' },
    { value: '100', label: '100' },
];

const hasFilter = computed(() => {
    return Object.values(filters).some((value) => {
        return value !== '' && value !== null && value !== undefined;
    });
});

const reload = function () {
    router.get('applications', filters, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
};

function resetFilters() {
    Object.keys(filters).forEach((key) => {
        if (key === 'per_page') {
            filters[key as keyof typeof filters] = '10';
        } else {
            filters[key as keyof typeof filters] = '';
        }
    });

    router.get('applications');
}

watch(filters, reload, { deep: true });
</script>

<template>
    <div class="my-4 flex items-center gap-4">
        <!-- Search -->
        <input
            v-model="filters.search"
            type="text"
            placeholder="Search ..."
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2"
        />

        <!-- Status -->
        <Select v-model="filters.status">
            <SelectTrigger
                class="w-44 rounded-lg border border-gray-300 px-4 py-2"
            >
                <SelectValue placeholder="Select Status" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem
                    v-for="status in statuses"
                    :key="status.value"
                    :value="status.value"
                >
                    <span class="flex items-center gap-2">
                        <span
                            class="inline-block h-2.5 w-2.5 rounded-full"
                            :class="status.color"
                        />
                        {{ status.label }}
                    </span>
                </SelectItem>
            </SelectContent>
        </Select>

        <!-- Countries -->
        <Select v-model="filters.countryId">
            <SelectTrigger
                class="w-52 rounded-lg border border-gray-300 px-4 py-2"
            >
                <SelectValue placeholder="Select Country" />
            </SelectTrigger>
            <SelectContent searchable>
                <SelectItem
                    v-for="country in countries"
                    :key="country.id"
                    :value="country.id"
                >
                    <span class="flex items-center gap-2">
                        <img
                            :src="country.flag.thumbnail"
                            alt=""
                            class="h-4 w-4 rounded"
                        />
                        {{ country.name }}
                    </span>
                </SelectItem>
            </SelectContent>
        </Select>

        <!-- Date -->
        <input
            v-model="filters.date"
            type="date"
            datepicker
            class="rounded-lg border border-gray-300 px-4 py-2"
        />

        <!-- Per Page -->
        <Select v-model="filters.per_page">
            <SelectTrigger
                class="w-32 rounded-lg border border-gray-300 px-4 py-2"
            >
                <SelectValue placeholder="Page Size" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem
                    v-for="option in perPageOptions"
                    :key="option.value"
                    :value="option.value"
                >
                    {{ option.label }}
                </SelectItem>
            </SelectContent>
        </Select>

        <button
            v-show="hasFilter"
            type="button"
            class="cursor-pointer rounded-lg bg-rose-500 px-4 py-2 font-bold text-white"
            @click="resetFilters"
        >
            Reset
        </button>
    </div>
</template>
