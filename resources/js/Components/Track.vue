<script setup>
import {computed} from "vue";
import {formattedDuration} from "@/helpers";
import {usePlaylistStore} from "@/stores/playlist";

const props = defineProps(['track', 'action']);
const emit = defineEmits(['play']);

const playlist = usePlaylistStore();

const prettySeconds = computed(() => formattedDuration(props.track.seconds));
const isNowPlaying = computed(() => playlist.nowPlaying && playlist.nowPlaying.id === props.track.id);

function play() {
    emit('play');
    playlist.play(props.track);
}
</script>

<template>
    <div @click="play()" :class="{ 'bg-gray-100': isNowPlaying }"
         class="flex justify-between items-center py-2 px-2 h-10 border-b border-b-gray-300 hover:bg-gray-100">
        <h4 class="text-gray-700 flex-1 whitespace-nowrap overflow-hidden overflow-ellipsis" :class="{ 'font-semibold': isNowPlaying }">
            <img v-if="isNowPlaying" src="/img/playing.svg" width="30" height="30" class="mx-2 inline">
            {{ track.title }}
        </h4>
        <span class="text-gray-600 basis-1/5 lg:basis-2/12 text-sm">{{ track.user.name }}</span>
        <span class="text-gray-600 basis-1/5 lg:basis-1/12 text-sm">{{ prettySeconds }}</span>
        <button v-if="props.action" class="bg-gray-800 bg-gray-200 w-8 h-8" @click.stop="props.action.onClick">
            <i :class="props.action.icon"></i>
        </button>
    </div>
</template>
