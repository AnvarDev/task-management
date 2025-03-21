<script setup>
import { ref } from 'vue';
import Comments from '@/Components/Comments.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownItem from '@/Components/DropdownItem.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    task: {
        id: {
            type: Number,
        },
        date: {
            type: String,
        },
        title: {
            type: String,
        },
        status: {
            type: Number,
        },
        status_name: {
            type: String,
        },
    },
    status: {
        type: Array,
    },
});

const fullDetails = ref(null);
const showModal = ref(false);

const modalOnClick = () => {
    if (fullDetails.value) {
        showModal.value = true;
    } else {
        axios.get('/task/' + props.task.id).then((response) => {
            fullDetails.value = response.data.data;
            showModal.value = true;
        });
    }
};

const closeModal = () => {
    showModal.value = false;
};

const onStatusChange = (index) => {
    axios.put('/task/' + props.task.id + '/status', { status: index }).then((response) => {
        if (response.data?.data?.status_name) {
            props.task.status_name = response.data.data.status_name;
        }
    });
};
</script>

<template>
    <div class="flex flex-col">
        <div class="mt-6 w-full overflow-hidden px-6 py-4 shadow-md cursor-pointer">
            <div @click="modalOnClick">
                <h3 class="mb-4 font-medium">{{ task.title }}</h3>
                <div v-if="task.date" class="text-sm font-medium text-red-600">
                    {{ task.date }}
                </div>
            </div>
            <Modal :show="showModal" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ fullDetails?.title }}
                    </h2>
                    <div class="flex items-center justify-end">
                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                        >
                                            {{ task.status_name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <template #content>
                                    <DropdownItem
                                        v-for="(name, index) in status"
                                        :index="index"
                                        @click="() => onStatusChange(index)"
                                    >
                                        {{ name }}
                                    </DropdownItem>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">{{ fullDetails?.description }}</p>
                    <div class="mt-6">
                        <Comments :task_id="task.id"></Comments>
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</template>
