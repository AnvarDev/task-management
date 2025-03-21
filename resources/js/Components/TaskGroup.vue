<script setup>
import { ref, onMounted } from 'vue';
import draggable from 'vuedraggable'
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

const onEnd = (event) => {
    if (event.type && event.type == 'end' && event.item?.__draggable_context?.element?.id){
        if (event.from?.parentElement?.dataset?.index && event.to?.parentElement?.dataset?.index) {
            axios.put(
                '/task/' + event.item.__draggable_context.element.id + '/priority', 
                { priority: event.to.parentElement.dataset.index }
            );
        }
    }
}
</script>

<template>
    <div class="flex flex-col">
        <div class="w-full overflow-hidden bg-white px-6 py-4 sm:max-w-md sm:rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ name }}
            </h2>
            <div v-if="tasks" class="tasks mb-4" :id="'task_column_' + index" :data-index="index">
                <draggable :list="tasks" group="tasks" item-key="id" @end="onEnd">
                    <template #item="{ element }">
                        <Task :task="element" :status="status" />
                    </template>
                </draggable>
            </div>
        </div>
    </div>
</template>
