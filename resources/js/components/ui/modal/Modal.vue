<script setup lang="ts">

import { X, Eraser } from "@lucide/vue";

defineProps({
    show: Boolean,
    label: String,
    title: String,
    erasable: {
        type: Boolean,
        default: false,
    },
});

</script>

<template>
    <Transition enter-from-class="opacity-0 scale-125" enter-to-class="opacity-100 scale-100"
        enter-active-class="transition duration-300" leave-active-class="transition duration-200"
        leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-125">

        <div v-if="show" class="fixed inset-0 z-20 bg-[#000000b2] grid place-items-center">
            <div class="bg-white w-10/12 max-w-125 rounded-lg">

                <!-- Header -->
                <div class="flex items-center justify-between p-3 border-b border-gray-200">
                    <h1 class="font-bold text-gray-800">{{ title }}</h1>
                    <div class="flex items-center gap-2">
                        <Eraser v-if="erasable" @click="$emit('erase')"
                            class="text-violet-800 w-4 h-4 cursor-pointer" />
                        <X @click="$emit('close')" class="text-gray-800 w-4 h-4 cursor-pointer" />
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <slot>default body</slot>
                </div>

                <!-- Footer -->
                <footer class="mt-2 bg-gray-50 py-2 px-4 text-sm rounded-b-lg">
                    <span class="text-sm text-gray-500">{{ label }}</span>
                </footer>

            </div>
        </div>

    </Transition>
</template>
