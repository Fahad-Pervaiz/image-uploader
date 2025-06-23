<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <div class="w-full sm:max-w-md mx-auto px-4">
            <h2
                class="text-3xl font-bold text-center
                    bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600
                    text-transparent bg-clip-text
                    drop-shadow-lg
                    border-b border-gray-700
                    pb-3 tracking-wide"
            >
                Sign in to Your Account
            </h2>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-4 space-y-4">
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
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                            Remember me
                        </span>
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                        >
                            Forgot your password?
                        </Link>

                        <Link
                            :href="route('register')"
                            class="text-sm text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                        >
                            Don’t have an account? Register
                        </Link>
                    </div>

                    <PrimaryButton
                        class="w-full sm:w-auto sm:ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
