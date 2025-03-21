<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const validation = ref();
const submit = () => {
    validation.value = '';
    form.processing = true;
    const { email, password } = form;
    axios.post('/auth/login', { email, password })
        .then((response) => {
            validation.value = '';
            localStorage.setItem('token', response.data.token);
            window.location.href = '/';
        })
        .catch((error) => {
            if (error.response.status === 422) {
                validation.value = error.response.data.message;
            }
        })
        .finally(() => {
            form.processing = false;
            form.password = '';
        });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="validation" class="mb-4 text-sm font-medium text-red-600">
            {{ validation }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
