<script setup>
import { ref } from "vue";

import Logo from '@/images/LogoCabaD.png';
const imageUrl = ref(Logo);

</script>
<template>
    <img :src='imageUrl'  width="100px" />
</template>
