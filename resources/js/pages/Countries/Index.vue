<script setup lang="ts">
/*
 * Imports
 */
import { Head, Form, usePage } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { ref } from 'vue';
import CountriesController from '@/actions/App/Http/Controllers/CountriesController';
import { Modal } from '@/components/ui/modal';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { countries } from '@/routes';

/*
 * Props
 */
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Countires',
                href: countries(),
            },
        ],
    },
});

defineProps({
    list: Object,
    flags: Object,
});

/*
 * Constants
 */
const icon = Plus;
const page = usePage();
const showModal = ref(false);
const country = ref(null);
</script>

<template>

    <Head title="Countries" />

    <div v-if="page.flash.toast" class="toast">
        {{ page.flash.toast.message }}
    </div>

    <div class="p-5">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                List of added countries
            </h1>

            <button type="button" class="flex cursor-pointer items-center gap-3 rounded-lg bg-violet-500 px-4 py-2"
                @click="showModal = true">
                <component :is="icon" class="text-white" />
                <span class="font-bold text-white">Add New</span>
            </button>
        </div>

        <!-- Modal -->
        <Modal :show="showModal" label="jobfolio" title="Add New Country" @close="showModal = false">
            <template #default>
                <Form v-bind="CountriesController.store.form()" method="post" reset-on-success
                    :options="{ preserveScroll: true }" :show-progress="false"
                    class="flex flex-col justify-center gap-4" @success="showModal = false"
                    #default="{ errors, processing }">

                    <!-- Select Country -->
                    <div>
                        <Select name="country">
                            <SelectTrigger class="w-full rounded-lg border px-4 py-2" :class="errors['country']
                                ? 'border-rose-300 ring-rose-300'
                                : 'border-gray-300 ring-gray-300'
                                ">
                                <SelectValue placeholder="Select Country" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="flag in flags" :key="flag.value" :value="flag.value">
                                    <span class="flex items-center gap-2">
                                        <img :src="flag.thumbnail" class="h-5 w-5 rounded" />
                                        {{ flag.label }}
                                    </span>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <div v-if="errors['country']" class="mt-1 text-sm text-rose-500">
                            {{ errors['country'] }}
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="submit" :disabled="processing"
                            class="flex-1 cursor-pointer rounded-lg bg-green-500 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50">
                            <span class="font-bold">
                                {{ processing ? 'Adding...' : 'Add Country' }}
                            </span>
                        </button>

                        <button type="button" class="flex-1 cursor-pointer rounded-lg bg-rose-500 px-4 py-2 text-white"
                            @click="showModal = false">
                            <span class="font-bold"> Cancel </span>
                        </button>
                    </div>
                </Form>
            </template>
        </Modal>

        <!-- List -->
        <div class="mt-10 grid grid-cols-3 gap-4 md:grid-cols-5 lg:grid-cols-10">
            <div v-for="country in list" :key="country.id">
                <div
                    class="grid place-items-center gap-3 rounded-xl border border-gray-300 p-4 transition-all duration-300 hover:bg-gray-50 dark:border-gray-700">
                    <img :src="country.flag.image" :alt="country.name" class="h-8 w-8" />
                    <span class="text-gray-800 dark:text-gray-100">
                        {{ country.name }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
