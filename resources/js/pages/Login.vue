<template>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 md:p-10">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-blue-700">Welcome Back 👋</h2>
                <p class="mt-1 text-sm text-gray-500">Login to your account</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email address</label>
                    <input v-model="email" type="email" placeholder="you@example.com"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 transition" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Password</label>
                    <input v-model="password" type="password" placeholder="••••••••"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 transition" />
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-medium py-2.5 rounded-xl transition">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Don't have an account?
                <RouterLink to="/register" class="text-blue-600 hover:underline font-medium">Sign up</RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/authStore'
import Swal from 'sweetalert2'
import axios from 'axios'

const email = ref('user@fhmtasksystem.dev')
const password = ref('secret')
const router = useRouter()
const authStore = useAuthStore()

const handleLogin = async () => {

    if (!email.value || !password.value) {
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
            icon: "error",
            title: "Please fill in all fields."
        });
        return
    }

    const response = await axios.post('login', {
        email: email.value,
        password: password.value
    })

    if (response.data.data.token != null) {
        authStore.login(response.data.data.user, response.data.data.token)
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
                title: "Welcome back, " + response.data.data.user.name + "!"
            });
        router.push('/')
    }
}


onMounted(() => {
    if (authStore.token) {
        router.push('/')
    }
})
</script>
