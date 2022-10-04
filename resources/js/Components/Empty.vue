<script setup>
    const props = defineProps(['collection']);
</script>

<template>
    <div v-if="!collection.length" class="grid place-items-center h-48">
        <img src="/img/empty.svg" width="100" height="100">
    </div>
</template>
