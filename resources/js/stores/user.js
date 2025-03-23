import { defineStore } from "pinia";

export const userStore = defineStore("user", {

    state: () => {
        return {
            users: [],
            userPagination: {
            search: ' ',
            current_page: 1,
            per_page: 0,
            total: 0,
            visible: 10
        },
            userLoader: false
        };
    },
    actions: {
        setUsers(user) {
            this.users                       = user.data;
            this.userPagination.current_page = user.current_page;
            this.userPagination.total        = user.total;
            this.userPagination.per_page     = user.per_page;
        },
        changeLoaderStatus(){
        this.userLoader = !this.userLoader;
        },
    },
    getters: {},
    persist: true,
});