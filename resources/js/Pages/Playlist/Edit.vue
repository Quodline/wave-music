<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import Track from "@/Components/Track.vue";

const props = defineProps(['tracks', 'content', 'playlist']);

const form = useForm({
    title: props.playlist.title,
    tracks: props.content,
});

function addTrack(track) {
    form.tracks.push(track);
}

function removeTrack(track) {
    const index = form.tracks.findIndex(t => t.id === track.id);
    form.tracks.splice(index, 1);
}

</script>

<template>
    <Head title="Edit playlist"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editing "{{ playlist.title }}"
            </h2>
        </template>

        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form class="mb-4" @submit.prevent="form.put(route('playlists.update', playlist), { onSuccess: () => form.reset() })">
                <div class="py-2">
                    <label class="font-semibold">Title</label>
                    <input v-model="form.title" class="input" placeholder="Playlist title">
                    <InputError :message="form.errors.title" class="mt-2"/>
                </div>

                <h3 class="font-xl font-semibold">Content</h3>
                <div class="p-2 my-2 bg-white rounded-lg">
                    <Track v-for="track in form.tracks" :key="track.id" :track="track"
                           :action="{icon: 'fa fa-minus', onClick: () => removeTrack(track)}"/>
                </div>
                <PrimaryButton class="mt-2 clear-both">Update</PrimaryButton>
            </form>
            <div class="p-2 my-2 bg-white rounded-lg">
                <h3 class="px-2 font-xl font-semibold">All tracks</h3>
                <Track v-for="track in tracks" :key="track.id" :track="track"
                       :action="{icon: 'fa fa-plus', onClick: () => addTrack(track)}"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
