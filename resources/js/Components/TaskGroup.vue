<script setup>
import { ref, onMounted } from 'vue';
import Task from "@/Components/Task.vue";

const props = defineProps({
    index: {
        type: Number,
    },
    name: {
        type: String,
    },
    status: {
        type: Array,
    },
});

// TODO load more button for pagination
const tasks = ref([]);
onMounted(() => {
    axios.get('/task', {
        params: {
            'priority': props.index,
            'limit': 25
        }
    }).then((response) => {
        tasks.value = response.data.data;
    });
});
</script>

<template>
    <div class="flex flex-col">
        <div
            class="w-full overflow-hidden bg-white px-6 py-4 sm:max-w-md sm:rounded-lg"
            :data-index="index"
        >
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ name }}
            </h2>

            <div v-if="tasks" class="mb-4">
                <Task v-for="task in tasks" :task="task" :status="status"></Task>
            </div>
        </div>
    </div>
</template>
