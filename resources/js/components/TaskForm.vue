<template>
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            {{ isEditing ? 'Edit Task' : 'Create New Task' }}
        </h2>

        <form @submit.prevent="submitForm" class="space-y-4">
            <!-- Title Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Task Title *
                </label>
                <input
                    v-model="form.title"
                    type="text"
                    placeholder="Enter task title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                />
                <p v-if="errors.title" class="mt-1 text-sm text-red-500">{{ errors.title[0] }}</p>
            </div>

            <!-- Description Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea
                    v-model="form.description"
                    placeholder="Enter task description"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-500">{{ errors.description[0] }}</p>
            </div>

            <!-- Status Select -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status *
                </label>
                <select
                    v-model="form.status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                    <option value="">Select status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-500">{{ errors.status[0] }}</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    :disabled="loading"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:bg-gray-400 transition duration-200 font-medium"
                >
                    {{ loading ? 'Saving...' : (isEditing ? 'Update Task' : 'Create Task') }}
                </button>
                <button
                    v-if="isEditing"
                    type="button"
                    @click="cancelEdit"
                    class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 font-medium"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useAuthStore } from '../store/authStore';

const props = defineProps({
    editingTask: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['taskSaved', 'editCancelled']);

const form = ref({
    title: '',
    description: '',
    status: 'pending',
});

const errors = ref({});
const loading = ref(false);
const authStore = useAuthStore();

const isEditing = computed(() => !!props.editingTask);

watch(
    () => props.editingTask,
    (newTask) => {
        if (newTask) {
            form.value = {
                title: newTask.title,
                description: newTask.description,
                status: newTask.status,
            };
        } else {
            resetForm();
        }
    }
);

const resetForm = () => {
    form.value = {
        title: '',
        description: '',
        status: 'pending',
    };
    errors.value = {};
};

const submitForm = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const config = {
            headers: {
                Authorization: `${authStore.token}`,
                'Content-Type': 'application/json',
            },
        };

        let response;
        if (isEditing.value) {
            response = await axios.put(
                `/tasks/${props.editingTask.id}`,
                form.value,
                config
            );
        } else {
            response = await axios.post('/tasks', form.value, config);
        }

        emit('taskSaved', response.data.data);
        resetForm();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {};
        } else {
            console.error('Error:', error);
        }
    } finally {
        loading.value = false;
    }
};

const cancelEdit = () => {
    resetForm();
    emit('editCancelled');
};
</script>
