import { defineStore } from "pinia";

export const userAuthStore = defineStore("auth", {
    state: () => ({
        token: localStorage.getItem("user-token") || "",
        status: "",
        user: null,
    }),
    actions: {
        authSuccess(resp) {
            this.user   = resp.user;
            this.token  = resp.token;
            this.status = 'success';
            localStorage.setItem("user-token", "Bearer " + this.token);
        },
        userUpdate(resp) {
            this.user = resp;
        },
        authLogout() {
            this.token  = "";
            this.status = 'error';
            localStorage.removeItem("user-token");
        },
    },
    getters: {
        isAuthenticated: (state) => !!state.token,
        authStatus: (state) => state.status,
    },
    persist: true,
});
