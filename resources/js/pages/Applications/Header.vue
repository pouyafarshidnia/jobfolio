<script setup lang="ts">
/* eslint-disable vue/no-mutating-props */
import { Form } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Modal } from '@/components/ui/modal';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const props = defineProps({
    show: Boolean,
    data: {
        type: Object,
        required: true,
    },
    countries: Object,
});

const currencies = [
    { value: '$', label: '$' },
    { value: '€', label: '€' },
];

const salaryTypes = [
    { value: '2', label: 'Yearly' },
    { value: '1', label: 'Monthly' },
    { value: '0', label: 'Hourly' },
];

function erase() {
    props.data.countryId = '';
    props.data.company = '';
    props.data.position = '';
    props.data.type = '';
    props.data.salary = '';
    props.data.currency = '';
    props.data.salaryType = '';
    props.data.submittedAt = '';
    props.data.link = '';
}
</script>

<template>
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            List of Applications
        </h1>

        <button
            type="button"
            class="flex cursor-pointer items-center gap-3 rounded-lg bg-violet-500 px-4 py-2"
            @click="$emit('resetModal')"
        >
            <Plus class="text-white" />
            <span class="font-bold text-white">Add New</span>
        </button>
    </div>

    <!-- Modal -->
    <Modal
        :show="props.show"
        label="jobfolio"
        title="Add New Application"
        :erasable="props.data.erasable"
        @close="$emit('closeModal')"
        @erase="erase"
    >
        <template #default>
            <Form
                v-bind="props.data.bind"
                reset-on-success
                :options="{ preserveScroll: true }"
                :show-progress="false"
                class="flex flex-col justify-center gap-4"
                @success="$emit('closeModal')"
                #default="{ errors, processing }"
            >
                <!-- Country -->
                <div>
                    <Select v-model="props.data.countryId" name="country_id">
                        <SelectTrigger
                            class="w-full rounded-lg border px-4 py-2"
                            :class="
                                errors['country_id']
                                    ? 'border-rose-300 ring-rose-300'
                                    : 'border-gray-300 ring-gray-300'
                            "
                        >
                            <SelectValue placeholder="Choose country" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="country in countries"
                                :key="country.id"
                                :value="country.id"
                            >
                                <span class="flex items-center gap-2">
                                    <img
                                        :src="country.flag.thumbnail"
                                        class="h-5 w-5 rounded"
                                    />
                                    {{ country.name }}
                                </span>
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div
                        v-if="errors['country_id']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['country_id'] }}
                    </div>
                </div>

                <!-- Company -->
                <div>
                    <input
                        v-model="props.data.company"
                        type="text"
                        name="company"
                        placeholder="Company Name"
                        class="w-full rounded-lg border px-4 py-2 outline-0"
                        :class="
                            errors['company']
                                ? 'border-rose-300 ring-rose-300'
                                : 'border-gray-300 ring-gray-300'
                        "
                    />
                    <div
                        v-if="errors['company']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['company'] }}
                    </div>
                </div>

                <!-- Position -->
                <div>
                    <input
                        v-model="props.data.position"
                        type="text"
                        name="position"
                        placeholder="Role or Position"
                        class="w-full rounded-lg border px-4 py-2 outline-0"
                        :class="
                            errors['position']
                                ? 'border-rose-300 ring-rose-300'
                                : 'border-gray-300 ring-gray-300'
                        "
                    />
                    <div
                        v-if="errors['position']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['position'] }}
                    </div>
                </div>

                <!-- Type -->
                <div>
                    <Select v-model="props.data.type" name="type">
                        <SelectTrigger
                            class="w-full rounded-lg border px-4 py-2"
                            :class="
                                errors['type']
                                    ? 'border-rose-300 ring-rose-300'
                                    : 'border-gray-300 ring-gray-300'
                            "
                        >
                            <SelectValue placeholder="Select Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="0">
                                <span class="flex items-center gap-2">
                                    <span
                                        class="inline-block h-2 w-2 rounded-full bg-violet-400"
                                    />
                                    Remote
                                </span>
                            </SelectItem>

                            <SelectItem value="1">
                                <span class="flex items-center gap-2">
                                    <span
                                        class="inline-block h-2 w-2 rounded-full bg-violet-400"
                                    />
                                    Hybrid
                                </span>
                            </SelectItem>

                            <SelectItem value="2">
                                <span class="flex items-center gap-2">
                                    <span
                                        class="inline-block h-2 w-2 rounded-full bg-violet-400"
                                    />
                                    On Site
                                </span>
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div
                        v-if="errors['type']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['type'] }}
                    </div>
                </div>

                <!-- Salary -->
                <div>
                    <div class="flex items-center gap-2">
                        <!-- Currency -->
                        <Select
                            v-model="props.data.currency"
                            name="currency"
                            class="w-24"
                        >
                            <SelectTrigger
                                class="rounded-lg border px-4 py-2"
                                :class="
                                    errors['currency']
                                        ? 'border-rose-300 ring-rose-300'
                                        : 'border-gray-300 ring-gray-300'
                                "
                            >
                                <SelectValue placeholder="Currency" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="currency in currencies"
                                    :key="currency.value"
                                    :value="currency.value"
                                >
                                    {{ currency.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <!-- Ammount -->
                        <div class="flex-1">
                            <input
                                v-model="props.data.salary"
                                type="text"
                                name="salary"
                                placeholder="Salary"
                                class="w-full rounded-lg border px-4 py-2 outline-0"
                                :class="
                                    errors['salary']
                                        ? 'border-rose-300 ring-rose-300'
                                        : 'border-gray-300 ring-gray-300'
                                "
                            />
                        </div>
                    </div>
                    <div
                        v-if="errors['salary'] || errors['currency']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['salary'] || errors['currency'] }}
                    </div>
                </div>

                <!-- Salary Type -->
                <div>
                    <Select v-model="props.data.salaryType" name="salary_type">
                        <SelectTrigger
                            class="w-full rounded-lg border px-4 py-2"
                            :class="
                                errors['salary_type']
                                    ? 'border-rose-300 ring-rose-300'
                                    : 'border-gray-300 ring-gray-300'
                            "
                        >
                            <SelectValue placeholder="Salary Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="salaryType in salaryTypes"
                                :key="salaryType.value"
                                :value="salaryType.value"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="inline-block h-2 w-2 rounded-full"
                                        :class="
                                            salaryType.value === '2'
                                                ? 'bg-emerald-400'
                                                : salaryType.value === '1'
                                                  ? 'bg-blue-400'
                                                  : 'bg-amber-400'
                                        "
                                    />
                                    {{ salaryType.label }}
                                </span>
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div
                        v-if="errors['salary_type']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['salary_type'] }}
                    </div>
                </div>

                <!-- Submitted At -->
                <div>
                    <input
                        v-model="props.data.submittedAt"
                        type="date"
                        name="submitted_at"
                        class="w-full rounded-lg border px-4 py-2 outline-0"
                        :class="
                            errors['submitted_at']
                                ? 'border-rose-300 ring-rose-300'
                                : 'border-gray-300 ring-gray-300'
                        "
                    />
                    <div
                        v-if="errors['submitted_at']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['submitted_at'] }}
                    </div>
                </div>

                <!-- Link -->
                <div>
                    <input
                        v-model="props.data.link"
                        type="text"
                        name="link"
                        placeholder="The website you applied there"
                        class="w-full rounded-lg border px-4 py-2 outline-0"
                        :class="
                            errors['link']
                                ? 'border-rose-300 ring-rose-300'
                                : 'border-gray-300 ring-gray-300'
                        "
                    />
                    <div
                        v-if="errors['link']"
                        class="mt-1 text-sm text-rose-500"
                    >
                        {{ errors['link'] }}
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        :disabled="processing"
                        class="flex-1 cursor-pointer rounded-lg bg-green-500 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span class="font-bold">
                            {{
                                processing ? 'Processing...' : props.data.button
                            }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="flex-1 cursor-pointer rounded-lg bg-rose-500 px-4 py-2 text-white"
                        @click="$emit('closeModal')"
                    >
                        <span class="font-bold"> Cancel </span>
                    </button>
                </div>
            </Form>
        </template>
    </Modal>
</template>
