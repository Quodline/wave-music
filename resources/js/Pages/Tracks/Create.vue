<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/inertia-vue3';
import {computed, ref} from "vue";
import {formattedDuration} from "@/helpers";

const audio = ref(null);

const form = useForm({
    file: null,
    title: '',
    seconds: 0,
});

const prettySeconds = computed(() => formattedDuration(form.seconds));

function onFileChange(e) {
    const files = e.target.files || e.dataTransfer.files;

    if (!files.length) {
        form.file = null;
    } else {
        form.file = files[0];
        audio.value.setAttribute('src', URL.createObjectURL(form.file));
    }
}

function evCanPlayThrough(e) {
    form.seconds = Math.round(e.currentTarget.duration);
    URL.revokeObjectURL(e.currentTarget.src);
}
</script>

<template>
    <Head title="Upload Song" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Upload a new song
            </h2>
        </template>

        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('tracks.store'), { onSuccess: () => form.reset() })">
                <div class="py-2">
                    <label class="font-semibold">Title</label>
                    <input class="input" v-model="form.title" placeholder="Song title">
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                <div class="py-2">
                    <label class="font-semibold">Audio file (mp3)</label>
                    <div class="relative">
                        <input type="file" class="input" @input="onFileChange" placeholder="Audio file Maximum 5MB" accept="audio/mpeg,video/mpeg">
                        <div v-if="form.file" class="inline-block absolute -bottom-8 -right-4 p-2 m-2 rounded-xl bg-gray-700 text-gray-200">
                            {{ prettySeconds }}
                        </div>
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                    </div>
                    <InputError :message="form.errors.file" class="mt-2" />
                </div>
                <audio ref="audio" @canplaythrough="evCanPlayThrough" style="display:none"></audio>
                <PrimaryButton class="mt-4 clear-both">Upload</PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
