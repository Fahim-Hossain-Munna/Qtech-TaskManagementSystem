import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        token: null,
    }),
    actions: {
        async login(user, token) {
            this.user = user;
            this.token = `Bearer ${token}`;
        },
        clearAuthData() {
            this.token = null;
            this.user = null;
        },
    },
    persist: true,
});
