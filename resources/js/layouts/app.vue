<template>
    <div>
        <h1 class="text-red-500 text-3xl p-12">{{ messae }}</h1>
    </div>
</template>
<script setup lang="ts">
import { onMounted, ref } from "vue";

const messae = ref("say hello to world !!%%$#@");

onMounted(() => {
    console.log("mounted");
});
</script>
