<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<template>
    <Head title="Welcome" />

    <div id="banner" class="flex flex-col px-4 lg:px-36 h-screen bg-gray-700">
        <header class="flex justify-between items-center py-12">
            <Link href="/">
                <ApplicationLogo class="block w-auto" color="text-gray-200" />
            </Link>
            <nav class="space-x-8">
                <Link :href="route('login')" class="text-md text-gray-400">Log in</Link>
                <Link v-if="canRegister" :href="route('register')" class="text-md text-gray-400">Register</Link>
            </nav>
        </header>

        <div id="heroCaption" class="flex-1 flex flex-col justify-center">
            <h2 class="text-4xl py-4 text-gray-100 drop-shadow-2xl">Welcome to Wave music!</h2>
            <h3 class="text-2xl text-gray-200 drop-shadow-2xl">The best streaming platform in the world.</h3>
        </div>
    </div>


<!--    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</Link>
            </template>
        </div>
    </div>-->
</template>
