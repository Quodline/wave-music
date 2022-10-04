import {defineStore} from "pinia";
import {computed, ref} from "vue";

export const usePlaylistStore = defineStore('playlist', () => {
    const nowPlaying = ref(null);
    const list = ref([]);
    const position = ref(0);

    const isBackable = computed(() => position.value > 0);
    const isNextable = computed(() => position.value < list.value.length - 1);

    function setList(trackList) {
        list.value = trackList;
        if (nowPlaying.value) {
            const index = list.value.findIndex(t => t.id === nowPlaying.value.id);
            if (index >= 0 ) {
                position.value = index;
            }
        } else {
            nowPlaying.value = trackList[0] || null;
        }
    }

    function play(track) {
        const index = list.value.findIndex(t => t.id === track.id);
        if (index >= 0 ) {
            position.value = index;
        } else {
            list.value = [];
        }
        nowPlaying.value = track;
    }

    function back() {
        if (isBackable.value) {
            nowPlaying.value = list.value[--position.value];
        }
    }

    function next() {
        if (isNextable.value) {
            nowPlaying.value = list.value[++position.value];
        }
    }

    return { back, isBackable, isNextable, next, nowPlaying, position, play, setList };
})
