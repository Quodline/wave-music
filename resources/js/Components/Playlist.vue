<script setup>
import dayjs from 'dayjs';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import relativeTime from 'dayjs/plugin/relativeTime';
import duration from 'dayjs/plugin/duration';

const props = defineProps(['playlist']);

dayjs.extend(relativeTime);
dayjs.extend(duration);

function openPlaylist() {
    window.location.href = route('playlists.show', props.playlist);
}
</script>

<template>
    <div @click="openPlaylist" class="flex justify-between items-center py-2 px-2 h-10 border-b border-b-gray-300 hover:bg-gray-100 rounded">
        <h4 class="text-gray-700 flex-1 whitespace-nowrap overflow-hidden overflow-ellipsis">
            {{ playlist.title }}
        </h4>
        <span class="inline-flex flex-col lg:flex-row lg:space-x-1 text-gray-600 basis-2/5 lg:basis-3/12 text-sm">
            <span>{{ playlist.tracks.length }} songs</span>
            <span>({{ dayjs.duration(playlist.tracks.reduce((a,b)=>a+b.seconds, 0), 'seconds').humanize() }} long)</span>
        </span>
        <span class="hidden lg:inline text-gray-500 basis-2/12 text-sm">
            {{ dayjs(playlist.updated_at).fromNow() }}
        </span>
        <Dropdown v-if="playlist.user.id === $page.props.auth.user.id">
            <template #trigger>
                <button class="mx-4">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
            </template>
            <template #content>
                <DropdownLink as="button" :href="route('playlists.destroy', playlist.id)" method="delete">
                    <span class="text-red-500">Delete</span>
                </DropdownLink>
            </template>
        </Dropdown>
    </div>
</template>
