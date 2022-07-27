<template>
    <div>
        <h1 class="text-gray-600 text-center text-3xl p-12 uppercase font-bold">{{ messae }}</h1>
    </div>
</template>
<script setup lang="ts">
import { onMounted, ref } from "vue";

const messae = ref("ready for provide api");

onMounted(() => {
    console.log("mounted");
});
</script>
