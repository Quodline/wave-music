<script setup>

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import Track from "@/Components/Track.vue";
import Empty from "@/Components/Empty.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {usePlaylistStore} from "@/stores/playlist";

const props = defineProps(['playlist', 'tracks']);

const store = usePlaylistStore();

dayjs.extend(relativeTime);

function evPlay() {
    store.setList(props.tracks);
}
</script>

<template>
    <Head :title="playlist.title"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ playlist.title }} <span class="text-sm text-gray-500">(updated {{ dayjs(playlist.updated_at).fromNow() }})</span>
            </h2>
        </template>

        <div class="pt-12 pb-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex space-x-4 px-2 mb-4 items-center">
                            <h3 class="font-semibold text-lg text-gray-800">{{ tracks.length }} songs</h3>
                            <a :href="route('playlists.edit', playlist)" class="bg-gray-800 text-gray-200 w-8 h-8 rounded-full inline-grid place-items-center">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                        <Track v-for="track in tracks" :key="track.id" :track="track" @play="evPlay"/>
                        <Empty :collection="tracks"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
