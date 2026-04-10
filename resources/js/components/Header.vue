<template>
    <nav class="bg-gray-100 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#" class="text-xl font-bold text-blue-600">FHM Book Store</a>
                </div>

                <!-- Toggle button (mobile) -->
                <div class="md:hidden">
                    <button @click="toggleMenu" class="text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Desktop menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <RouterLink to="/" class="text-gray-700 hover:text-blue-600">Home</RouterLink>
                    <RouterLink v-for="category in categories" :key="category.id" to="/"
                        class="text-gray-700 hover:text-blue-600 capitalize">{{ category.title }}</RouterLink>
                    <div v-if="authStore.token" class="hidden md:flex items-center space-x-4 relative">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">{{ authStore.user.name }}</p>
                            <p class="text-xs text-gray-500">{{ authStore.user.email }}</p>
                        </div>
                        <div @click="toggleProfileMenu" class="cursor-pointer p-0 m-0">
                            <img class="w-10 h-10 rounded-full border-2 border-blue-500" src="https://i.pravatar.cc/100"
                                alt="User" />
                        </div>
                        <!-- Dropdown -->
                        <div v-if="profileOpen" class="absolute top-12 right-0 bg-white shadow-md rounded-md w-40 z-50">
                            <button @click="logout"
                                class="w-full text-left px-4 py-2 text-red-700 hover:text-red-500 text-sm">Logout</button>
                        </div>
                    </div>
                    <RouterLink v-else to="/login"
                        class="inline-block px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition duration-200 cursor-pointer">
                        Login
                    </RouterLink>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="isOpen" class="md:hidden pt-2 pb-4 space-y-2">
                <RouterLink to="/" class="block text-gray-700 hover:text-blue-600">Home</RouterLink>
                <RouterLink v-for="category in categories" :key="category.id" href="/"
                    class="text-gray-700 hover:text-blue-600 capitalize">{{ category.title }}</RouterLink>

                <div v-if="authStore.token" class="md:hidden border-t border-gray-200 pt-4 mt-4">
                    <div class="flex items-center space-x-4">
                        <img class="w-10 h-10 rounded-full border-2 border-blue-500" src="https://i.pravatar.cc/100"
                            alt="User" />
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ authStore.user.name }}</p>
                            <p class="text-xs text-gray-500">{{ authStore.user.email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        <button @click="logout" class="text-sm text-red-700 hover:text-red-500">Logout</button>
                    </div>
                </div>

                <RouterLink v-else to="/login"
                    class="block px-4 py-2 mt-3 text-center text-sm font-medium text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition duration-200 cursor-pointer">
                    Login
                </RouterLink>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useAuthStore } from '@/store/authStore';
import Swal from 'sweetalert2';

const isOpen = ref(false);
const categories = ref([]);
const profileOpen = ref(false);
const authStore = useAuthStore();

const toggleProfileMenu = () => {
    profileOpen.value = !profileOpen.value;
};

onMounted(async () => {
    try {
        const response = await axios.get('/categories');
        categories.value = response.data?.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
});

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const logout = () => {
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure you want to log out?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0a22c9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, I want to log out.",
        cancelButtonText: "Nope, stay logged in.",
    }).then((result) => {
        if (result.isConfirmed) {
            authStore.clearAuthData();

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "You’ve exited safely. Have a great day!"
            });
        }
        router.push("/");
    });
};
</script>
