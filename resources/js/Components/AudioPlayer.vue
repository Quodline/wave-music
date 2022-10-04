<script setup>

import {computed, ref, watch} from "vue";
import {formattedDuration} from "@/helpers";
import {usePlaylistStore} from "@/stores/playlist";

const props = defineProps(['playerid']);
const playbackTime = ref(0); // Syncs to audio.currentTime
const audioDuration = ref(0);
const audioLoaded = ref(false);
const isPlaying = ref(false);
const audio = ref(null);
const listenerActive = ref(false);

const playlist = usePlaylistStore();

const totalTime = computed(() => formattedDuration(audioDuration.value));
const elapsedTime = computed(() => formattedDuration(playbackTime.value));

function initSlider() {
    audioDuration.value = Math.round(audio.value.duration);
}

function evCanPlay() {
    audioLoaded.value = true;
    toggleAudio();
}

function playbackListener() {
    playbackTime.value = audio.value?.currentTime;

    audio.value.addEventListener("ended", endListener);
    audio.value.addEventListener("pause", pauseListener);
}

function pauseListener() {
    isPlaying.value = false;
    listenerActive.value = false;
    cleanupListeners();
}

function endListener() {
    isPlaying.value = false;
    listenerActive.value = false;
    cleanupListeners();
    playlist.next();
}

function cleanupListeners() {
    audio?.value.removeEventListener("timeupdate", playbackListener);
    audio?.value.removeEventListener("ended", endListener);
    audio?.value.removeEventListener("pause", pauseListener);
}

function toggleAudio() {
    if (audio.value.paused) {
        audio.value.play();
        isPlaying.value = true;
    } else {
        audio.value.pause();
        isPlaying.value = false;
    }
}

watch(isPlaying, function () {
    if (isPlaying.value) {
        initSlider();
        if (!listenerActive.value) {
            listenerActive.value = true;
            audio.value.addEventListener("timeupdate", playbackListener);
        }
    }
});

watch(playbackTime, function () {
    const diff = Math.abs(playbackTime.value - audio.value.currentTime);

    if (diff > 0.01) {
        audio.value.currentTime = playbackTime.value;
    }

    if (Math.abs(audioDuration.value - playbackTime.value) < 1) {
        playbackTime.value += 1;
    }
});

</script>

<template>
    <div v-if="playlist.nowPlaying"
         class="fixed bottom-0 w-full lg:mx-auto lg:w-2/3 bg-transparent self-center rounded-t-xl p-4">
        <div id="audio-player-root" class="relative">
            <button class="absolute -top-4 -right-4 w-8 h-8 bg-gray-800 text-white rounded-full border-2 border-white"
                    @click="playlist.nowPlaying = null">
                <i class="fa fa-times"></i>
            </button>

            <div>
                <audio :id="playerid" ref="audio" :src="playlist.nowPlaying.file" style="display:none"
                       @canplay="evCanPlay" @loadedmetadata="initSlider">
                    <source :src="playlist.nowPlaying.file" type="audio/mpeg"/>
                </audio>
            </div>

            <div
                class="bg-white rounded border border-gray-300 px-2 pt-2 mt-4 shadow-md"
                style="margin: auto;"
            >
                <h4 class="text-md text-center text-gray-800 font-bold">{{ playlist.nowPlaying.title }}</h4>

                <div id="player-row" class="inline-flex flex-wrap items-center w-full max-w-5xl px-8">
                    <div id="button-div" class="flex-initial pr-3 space-x-2">
                        <button :disabled="!playlist.isBackable" @click="playlist.back()"
                                class="h-8 w-8 inline-grid place-items-center rounded-full bg-gray-800 disabled:bg-gray-400 hover:bg-gray-500 text-white">
                            <i class="fa fa-backward"></i>
                        </button>
                        <button :disabled="!audioLoaded" class="h-8 w-8 inline-grid place-items-center rounded-full bg-gray-800 hover:bg-gray-500 text-white"
                                @click="toggleAudio()">
                            <i v-show="!isPlaying" class="fa fa-play"></i>
                            <i v-show="isPlaying" class="fa fa-pause"></i>
                        </button>
                        <button :disabled="!playlist.isNextable" @click="playlist.next()" class="h-8 w-8 inline-grid place-items-center rounded-full bg-gray-800 disabled:bg-gray-400 hover:bg-gray-500 text-white">
                            <i class="fa fa-forward"></i>
                        </button>
                    </div>

                    <div
                        id="progress-bar"
                        class="flex-grow"
                    >
                        <div class="overlay-container relative w-full h-full">
                            <input
                                id="position"
                                v-model="playbackTime"
                                :max="audioDuration"
                                class="slider w-full h-full"
                                min="0"
                                name="position"
                                type="range"
                            />

                            <!-- Show loading indicator until audio has been loaded -->

                            <div v-show="!audioLoaded"
                                 class="w-full absolute top-0 bottom-0 right-0 left-0 px-2 pointer-events-none"
                                 style="color: #94bcec">
                                Loading please wait...
                            </div>

                            <div
                                v-show="audioLoaded"
                                class="flex w-full justify-between absolute bottom-0 left-0 px-2 pointer-events-none"
                            >
                                <span class="text-sm text-gray-800 font-semibold">{{ elapsedTime }}</span>
                                <span class="text-sm text-gray-800 font-semibold">{{ totalTime }}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- outer gray border -->
        </div>
    </div>
</template>

<style>

/* Play/Pause Button */
.play-button {
    height: 45px;
}

input[type=range] {
    height: 26px;
    -webkit-appearance: none;
    margin: 10px 0;
    width: 100%;
}

input[type=range]:focus {
    outline: none;
}

input[type=range]::-webkit-slider-runnable-track {
    width: 100%;
    height: 14px;
    cursor: pointer;
    animate: 0.2s;
    box-shadow: 1px 1px 1px #50555C;
    background: #50555C;
    border-radius: 14px;
    border: 0px solid #000000;
}

input[type=range]::-webkit-slider-thumb {
    box-shadow: 0px 0px 0px #000000;
    border: 0px solid #000000;
    height: 20px;
    width: 40px;
    border-radius: 12px;
    background: #1a202c;
    cursor: pointer;
    -webkit-appearance: none;
    margin-top: -3px;
}

input[type=range]:focus::-webkit-slider-runnable-track {
    background: #50555C;
}

input[type=range]::-moz-range-track {
    width: 100%;
    height: 14px;
    cursor: pointer;
    animate: 0.2s;
    box-shadow: 1px 1px 1px #50555C;
    background: #50555C;
    border-radius: 14px;
    border: 0px solid #000000;
}

input[type=range]::-moz-range-thumb {
    box-shadow: 0px 0px 0px #000000;
    border: 0px solid #000000;
    height: 20px;
    width: 40px;
    border-radius: 12px;
    background: #1a202c;
    cursor: pointer;
}

input[type=range]::-ms-track {
    width: 100%;
    height: 14px;
    cursor: pointer;
    animate: 0.2s;
    background: transparent;
    border-color: transparent;
    color: transparent;
}

input[type=range]::-ms-fill-lower {
    background: #50555C;
    border: 0px solid #000000;
    border-radius: 28px;
    box-shadow: 1px 1px 1px #50555C;
}

input[type=range]::-ms-fill-upper {
    background: #50555C;
    border: 0px solid #000000;
    border-radius: 28px;
    box-shadow: 1px 1px 1px #50555C;
}

input[type=range]::-ms-thumb {
    margin-top: 1px;
    box-shadow: 0px 0px 0px #000000;
    border: 0px solid #000000;
    height: 20px;
    width: 40px;
    border-radius: 12px;
    background: #1a202c;
    cursor: pointer;
}

input[type=range]:focus::-ms-fill-lower {
    background: #50555C;
}

input[type=range]:focus::-ms-fill-upper {
    background: #50555C;
}
</style>
