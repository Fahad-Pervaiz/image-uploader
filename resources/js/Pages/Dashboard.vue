<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ImageGallery from '../Components/ImageGallery.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <!-- <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2> -->
            <!-- <h2
            class="text-3xl font-bold text-center
                    bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600
                    text-transparent bg-clip-text
                    drop-shadow-lg
                    border-b border-gray-700
                    pb-3 mb-8 tracking-wide"
            >
            Welcome to Your Image Gallery
            </h2> -->

        </template>
              <!-- Animated Video + Heading -->
            <div class="flex flex-col items-center justify-center mb-8 space-y-4">
                <!-- WebM Video Animation -->
                <video
                class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full shadow-lg dark:shadow-gray-700"
                autoplay
                loop
                muted
                playsinline
                >
                <source src="/videos/Animation - 1750446412953.webm" type="video/webm" />
                Your browser does not support the video tag.
                </video>

                <!-- Gradient Heading -->
                <h2
                class="text-3xl font-bold text-center
                        bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600
                        text-transparent bg-clip-text
                        drop-shadow-lg
                        border-b border-gray-700
                        pb-3 tracking-wide"
                >
                Welcome to Your Image Gallery
                </h2>
            </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                         <ImageGallery />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
