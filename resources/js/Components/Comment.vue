<script setup>
const props = defineProps({
    comment: {
        id: {
            type: Number,
        },
        created_at: {
            type: String,
        },
        user: {
            name: {
                type: String,
            },
        },
        value: {
            type: String,
        },
    },
});
</script>

<template>
    <div class="flex flex-col">
        <div class="mb-6 w-full overflow-hidden px-6 py-4 shadow-md cursor-pointer">
            <div class="text-sm font-medium">
                <u v-if="comment.user?.name">{{ comment.user.name }}</u>
                <span class="text-red-600 ml-2">{{ comment.created_at }}</span>
            </div>
            <div class="text-sm font-medium">
                {{ comment.value }}
            </div>
        </div>
    </div>
</template>
