<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import TaskGroup from "@/Components/TaskGroup.vue";

const props = defineProps({
    priority: {
        type: Array,
    },
    status: {
        type: Array,
    },
});

Echo.channel('tasks').listen('TaskHasBeenUpdated', (event) => {
    if (event.id !== undefined && event.new_priority !== undefined && event.old_priority !== undefined) {
        const task_block = document.getElementById('task_block_' + event.id);
        if (task_block.closest('.tasks')) {
            if (task_block.closest('.tasks').dataset?.index !== undefined && task_block.closest('.tasks').dataset?.index != event.new_priority) {
                const new_column = document.getElementById('task_column_' + event.new_priority);
                if (new_column && new_column.firstChild) {
                    new_column.firstChild.appendChild(task_block);
                }
            }
        }
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="grid gap-6 lg:grid-cols-4 lg:gap-8 p-6">
                        <TaskGroup
                            v-for="(name, index) in priority"
                            :name="name"
                            :index="index"
                            :status="status"
                        ></TaskGroup>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
