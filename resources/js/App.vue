<script setup>
import Navbar from './components/navbar.vue';
</script>
<template>
    <v-app>
        <Navbar />
        <v-main>
            <RouterView />
        </v-main>
    </v-app>
</template>