<script setup lang="ts">
/*
 * Imports
 */
import { Head, Link as ActionLink } from '@inertiajs/vue3';
import { parseDate } from '@internationalized/date';
import type { CalendarDate } from '@internationalized/date';
import {
    Pencil,
    Link,
    Mail,
    ExternalLink,
    RefreshCw,
    SquareCheck,
    Ban,
} from '@lucide/vue';
import { reactive, ref } from 'vue';

import {
    store,
    update,
    process,
    approve,
    reject,
} from '@/actions/App/Http/Controllers/ApplicationsController';
import { Pagination } from '@/components/ui/pagination';

import {
    Table,
    TableBody,
    TableData,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';

import { applications } from '@/routes';
import Filter from './Filter.vue';
import Header from './Header.vue';

/*
 * Props
 */
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'My Applications',
                href: applications(),
            },
        ],
    },
});

defineProps({
    list: Object,
    countries: Object,
});

/*
 * Consts
 */
const showModal = ref(false);

const linkIcons: Record<string, typeof Link> = {
    Link,
    Mail,
    ExternalLink,
};

const modalData = reactive<{
    bind: ReturnType<typeof store.form>;
    countryId: string;
    company: string;
    position: string;
    salary: string;
    salaryType: string;
    type: string;
    currency: string;
    submittedAt: CalendarDate | undefined;
    link: string;
    erasable: boolean;
    button: string;
}>({
    bind: store.form(),
    countryId: '',
    company: '',
    position: '',
    salary: '',
    salaryType: '',
    type: '',
    currency: '',
    submittedAt: undefined,
    link: '',
    erasable: true,
    button: 'Create Application',
});

/*
 * Functions
 */
function updateModalData(application: any) {
    modalData.bind = update.form(application);
    modalData.countryId = application.country.id;
    modalData.company = application.company;
    modalData.position = application.position;
    modalData.type = String(application.type.value);
    modalData.salary = application.salary.value;
    modalData.currency = application.currency ?? '';
    modalData.salaryType = String(application.salaryType);
    modalData.submittedAt = parseDate(application.submittedAt.value);
    modalData.link = application.link.value;
    modalData.erasable = false;
    modalData.button = 'Update Application';

    showModal.value = true;
}

function resetModal() {
    modalData.bind = store.form();
    modalData.erasable = true;
    modalData.button = 'Create Application';

    showModal.value = true;
}
</script>

<template>
    <Head title="My Applications" />

    <div class="p-5">
        <!-- Header -->
        <Header
            @resetModal="resetModal"
            :show="showModal"
            :data="modalData"
            :countries="countries"
            @closeModal="showModal = false"
        />

        <!-- Filters -->
        <Filter :countries="countries" />

        <!--Table -->
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead># </TableHead>
                    <TableHead TableHead> Country </TableHead>
                    <TableHead TableHead> Company </TableHead>
                    <TableHead TableHead> Position </TableHead>
                    <TableHead TableHead> Salary </TableHead>
                    <TableHead TableHead> Submitted At </TableHead>
                    <TableHead TableHead> Type </TableHead>
                    <TableHead TableHead> Status </TableHead>
                    <TableHead TableHead> Link </TableHead>
                    <TableHead TableHead class="text-right">Actions</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <TableRow
                    v-for="(application, index) in list?.data"
                    :key="application.id"
                >
                    <TableData
                        class="font-medium whitespace-nowrap text-pink-800 dark:text-gray-100"
                    >
                        {{ index + 1 }}
                    </TableData>

                    <TableData
                        class="flex items-center gap-3 whitespace-nowrap text-gray-700 dark:text-gray-300"
                    >
                        <img
                            :src="application.country.flag.thumbnail"
                            alt=""
                            class="h-5 w-5 rounded"
                        />
                        {{ application.country.flag.label }}
                    </TableData>

                    <TableData
                        class="font-bold whitespace-nowrap text-gray-700 dark:text-gray-300"
                    >
                        {{ application.company }}
                    </TableData>

                    <TableData
                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                    >
                        {{ application.position }}
                    </TableData>

                    <TableData
                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                    >
                        {{ application.salary.display }}
                    </TableData>

                    <TableData
                        class="whitespace-nowrap text-gray-500 dark:text-gray-400"
                    >
                        {{ application.submittedAt.formatted }}
                    </TableData>

                    <TableData class="whitespace-nowrap">
                        <span class="badge badge-primary">
                            {{ application.type.label }}</span
                        >
                    </TableData>

                    <TableData class="whitespace-nowrap">
                        <span
                            :class="'badge badge-' + application.status.color"
                        >
                            {{ application.status.label }}</span
                        >
                    </TableData>

                    <TableData
                        class="whitespace-nowrap text-gray-500 dark:text-gray-400"
                    >
                        <a
                            v-if="application.link.url"
                            :href="application.link.url"
                        >
                            <component
                                :is="linkIcons[application.link.icon]"
                                class="h-4 w-4 text-gray-600"
                            />
                        </a>
                        <span v-else>
                            <component
                                :is="linkIcons[application.link.icon]"
                                class="h-4 w-4 text-gray-400"
                            />
                        </span>
                    </TableData>

                    <TableData class="text-right whitespace-nowrap">
                        <!-- Process -->
                        <TooltipProvider
                            :delay-duration="0"
                            v-if="application.actions.processable"
                        >
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <ActionLink
                                        :href="process(application)"
                                        class="inline-flex cursor-pointer items-center justify-center rounded-md p-1.5 text-amber-400 transition-colors hover:bg-gray-100 hover:text-amber-500 dark:hover:bg-gray-800"
                                    >
                                        <RefreshCw class="h-4 w-4" />
                                    </ActionLink>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Process</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>

                        <!-- Approve -->
                        <TooltipProvider
                            :delay-duration="0"
                            v-if="application.actions.approvable"
                        >
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <ActionLink
                                        :href="approve(application)"
                                        class="inline-flex cursor-pointer items-center justify-center rounded-md p-1.5 text-green-400 transition-colors hover:bg-gray-100 hover:text-green-500 dark:hover:bg-gray-800"
                                    >
                                        <SquareCheck class="h-4 w-4" />
                                    </ActionLink>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Approve</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>

                        <!-- Reject -->
                        <TooltipProvider
                            :delay-duration="0"
                            v-if="application.actions.rejectabale"
                        >
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <ActionLink
                                        :href="reject(application)"
                                        class="inline-flex cursor-pointer items-center justify-center rounded-md p-1.5 text-rose-400 transition-colors hover:bg-gray-100 hover:text-rose-500 dark:hover:bg-gray-800"
                                    >
                                        <Ban class="h-4 w-4" />
                                    </ActionLink>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Reject</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>

                        <!-- Edit -->
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <button
                                        type="button"
                                        @click="updateModalData(application)"
                                        class="inline-flex cursor-pointer items-center justify-center rounded-md p-1.5 text-sky-400 transition-colors hover:bg-gray-100 hover:text-sky-500 dark:hover:bg-gray-800"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Edit</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </TableData>
                </TableRow>
            </TableBody>
        </Table>

        <!-- PAgination -->
        <Pagination
            :list="list?.meta"
            :nextUrl="list?.links?.next"
            :prevUrl="list?.links?.prev"
        ></Pagination>
    </div>
</template>
