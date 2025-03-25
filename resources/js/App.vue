<script setup>
import { ref, watch, onMounted } from 'vue';
import { userAuthStore } from "@/stores/userAuth";
import UnAuthNavbar from './components/navbar.vue';
import AuthNavbar from './components/authNavbar.vue';
import ability from "./services/ability";


const authStore = userAuthStore();

const loadAbilities  = () => {
    if (!authStore.isAuthenticated) return;
    ability.update([{ action: authStore.user.ability, subject: "all" }]);
}

onMounted(() => {
    loadAbilities();
});
</script>
<template>
    <v-app>
        <AuthNavbar v-if="authStore.isAuthenticated" />
        <UnAuthNavbar v-if="!authStore.isAuthenticated" />
        <v-main>
            <RouterView />
        </v-main>
    </v-app>
</template>