<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import Empty from "@/Components/Empty.vue";
import Playlist from "@/Components/Playlist.vue";
import Track from "@/Components/Track.vue";

const props = defineProps(['tracks', 'playlists']);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="pt-12 pb-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">Your songs</h3>
                        <Track v-for="track in tracks" :key="track.id" :track="track"/>
                        <Empty :collection="tracks"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex space-x-4 items-center mb-4">
                            <h3 class="font-semibold text-lg text-gray-800">Your playlists</h3>
                            <a :href="route('playlists.create')" class="bg-gray-800 text-gray-200 w-8 h-8 rounded-full inline-grid place-items-center">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <Playlist v-for="playlist in playlists" :key="playlist.id" :playlist="playlist"/>
                        <Empty :collection="playlists"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
