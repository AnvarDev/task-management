<script setup>
import { ref, onMounted } from 'vue';
import {  useForm } from '@inertiajs/vue3';
import Comment from "@/Components/Comment.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    task_id: {
        type: Number,
    },
});

// TODO load more button for pagination
const comments = ref([]);
onMounted(() => {
    axios.get('/comment', {
        params: {
            'task_id': props.task_id,
            'limit': 25
        }
    }).then((response) => {
        comments.value = response.data.data;
    });
});

const form = useForm({
    text: '',
});

const validation = ref();
const onClick = () => {
    if (form.processing) {
        return;
    }

    form.processing = true;
    axios.post('/comment', {
        task_id: props.task_id,
        text: form.comment
    }).then((response) => {
        if (response.data?.data) {
            comments.value.unshift(response.data.data);
        }

        validation.value = '';
        form.comment = '';
    })
    .catch((error) => {
        if (error.response.status === 422) {
            validation.value = error.response.data.message;
        }
    })
    .finally(() => {
        form.processing = false;
    });
};
</script>

<template>
        <div class="w-full overflow-hidden">
            <h2 class="text-lg font-medium text-gray-900">Comments </h2>
            <div v-if="validation" class="mb-4 text-sm font-medium text-red-600">
                {{ validation }}
            </div>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-7 gap-4">
                    <div class="flex items-center col-start-1 col-end-6">
                        <TextInput
                            id="comment"
                            type="text"
                            class="block w-full"
                            placeholder="Add a comment..."
                            v-model="form.comment"
                            required
                            autofocus
                            @keyup.enter="onClick"
                        />
                    </div>
                    <div class="flex items-center justify-end">
                        <SecondaryButton
                            @click="onClick"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Submit
                        </SecondaryButton>
                    </div>
                </div>
            </form>
            <div v-if="comments" class="mt-6">
                <Comment v-for="comment in comments" :comment="comment"></Comment>
            </div>
        </div>
</template>
