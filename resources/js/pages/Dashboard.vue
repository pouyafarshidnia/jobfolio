<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Clock, XCircle, Loader2, CalendarPlus } from '@lucide/vue';
import { computed } from 'vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const props = defineProps({
    stats: Object,
    month: Array,
    year: String,
});

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 5 }, (_, i) => currentYear - i);

const monthNames = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec',
];

const chartSeries = computed(() => [
    {
        name: 'Applications',
        data: monthNames.map((_, i) => {
            const found = props.month?.find((m) => m.month === i + 1);

            return found ? found.total : 0;
        }),
    },
]);

const chartOptions = {
    chart: {
        type: 'area',
        toolbar: { show: false },
        background: 'transparent',
    },
    colors: ['#8b5cf6'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100],
        },
    },
    dataLabels: { enabled: false },
    stroke: {
        curve: 'smooth',
        width: 2,
    },
    xaxis: {
        categories: monthNames,
        labels: {
            style: {
                colors: '#9ca3af',
                fontSize: '12px',
            },
        },
    },
    yaxis: {
        labels: {
            style: {
                colors: '#9ca3af',
                fontSize: '12px',
            },
        },
    },
    grid: {
        borderColor: '#f3f4f6',
        strokeDashArray: 4,
    },
    tooltip: {
        theme: 'light',
    },
};

function onYearChange(value: string) {
    router.visit(dashboard.url({ query: { year: value } }), {
        preserveState: true,
        preserveScroll: true,
    });
}

const cards = [
    {
        key: 'pending',
        title: 'Pending',
        description: 'Applications awaiting review',
        icon: Clock,
        color: 'bg-amber-500',
        lightColor: 'bg-amber-50',
        iconColor: 'text-amber-500',
        borderColor: 'border-amber-200',
    },
    {
        key: 'processing',
        title: 'Processing',
        description: 'Applications under evaluation',
        icon: Loader2,
        color: 'bg-blue-500',
        lightColor: 'bg-blue-50',
        iconColor: 'text-blue-500',
        borderColor: 'border-blue-200',
    },
    {
        key: 'rejected',
        title: 'Rejected',
        description: 'Applications not accepted',
        icon: XCircle,
        color: 'bg-rose-500',
        lightColor: 'bg-rose-50',
        iconColor: 'text-rose-500',
        borderColor: 'border-rose-200',
    },
    {
        key: 'today',
        title: 'Submitted Today',
        description: 'New applications today',
        icon: CalendarPlus,
        color: 'bg-emerald-500',
        lightColor: 'bg-emerald-50',
        iconColor: 'text-emerald-500',
        borderColor: 'border-emerald-200',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <div class="p-5">
        <h1 class="mb-6 text-lg font-bold text-gray-800 dark:text-gray-100">
            Application Overview
        </h1>

        <!-- Stat Cards -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="card in cards"
                :key="card.key"
                class="group relative overflow-hidden rounded-xl border bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:bg-gray-800"
                :class="card.borderColor"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            {{ card.title }}
                        </p>
                        <p
                            class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ stats[card.key] }}
                        </p>
                        <p
                            class="mt-1 text-xs text-gray-400 dark:text-gray-500"
                        >
                            {{ card.description }}
                        </p>
                    </div>
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg"
                        :class="card.lightColor"
                    >
                        <component
                            :is="card.icon"
                            class="h-6 w-6"
                            :class="card.iconColor"
                        />
                    </div>
                </div>

                <div
                    class="absolute inset-x-0 bottom-0 h-1 transition-all group-hover:h-1.5"
                    :class="card.color"
                />
            </div>
        </div>

        <!-- Chart -->
        <div
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800"
        >
            <div class="mb-4 flex items-center justify-between">
                <h2
                    class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                >
                    Applications by Month
                </h2>
                <Select :model-value="year" @update:model-value="onYearChange">
                    <SelectTrigger
                        class="w-28 rounded-lg border border-gray-300 px-3 py-1.5 text-sm"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="y in years"
                            :key="y"
                            :value="String(y)"
                        >
                            {{ y }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <apexchart
                height="350"
                :options="chartOptions"
                :series="chartSeries"
            />
        </div>
    </div>
</template>
