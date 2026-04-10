<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tasks</h2>
            <div class="flex gap-3">
                <!-- Search Input -->
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Search tasks..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />

                <!-- Filter Select -->
                <select
                    v-model="filterStatus"
                    @change="handleFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="space-y-4">
            <div v-for="n in 5" :key="n" class="animate-pulse flex gap-4 p-4 border border-gray-200 rounded-lg">
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-300 rounded w-1/2"></div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredTasks.length === 0" class="text-center py-12">
            <p class="text-gray-500 text-lg">No tasks found. Create your first task!</p>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in filteredTasks" :key="task.id" class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">#{{ task.id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ task.title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span v-if="task.description" class="line-clamp-2">{{ task.description }}</span>
                            <span v-else class="text-gray-400 italic">No description</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                :class="getStatusClass(task.status)"
                                class="px-3 py-1 rounded-full text-xs font-semibold"
                            >
                                {{ formatStatus(task.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ task.created_at }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- View Button -->
                                <button
                                    @click="viewTask(task)"
                                    class="px-3 py-1  text-black text-xs rounded transition"
                                    title="View"
                                >
                                    <EyeIcon class="w-4 h-4" />
                                </button>

                                <!-- Edit Button -->
                                <button
                                    @click="editTask(task)"
                                    class="px-3 py-1 text-black text-xs rounded transition"
                                    title="Edit"
                                >
                                    <PencilSquareIcon class="w-4 h-4" />
                                </button>

                                <!-- Delete Button -->
                                <button
                                    @click="deleteTask(task.id)"
                                    class="px-3 py-1 text-black text-xs rounded transition"
                                    title="Delete"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- View Modal -->
        <div v-if="showViewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Task Details</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Title</p>
                        <p class="text-lg font-semibold text-gray-800">{{ currentTask?.title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Description</p>
                        <p class="text-gray-800">{{ currentTask?.description || 'No description' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span
                            :class="getStatusClass(currentTask?.status)"
                            class="px-3 py-1 rounded-full text-xs font-semibold inline-block"
                        >
                            {{ formatStatus(currentTask?.status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="text-gray-800">{{ currentTask?.created_at }}</p>
                    </div>
                </div>
                <button
                    @click="showViewModal = false"
                    class="mt-6 w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useAuthStore } from '../store/authStore';
import { PencilSquareIcon , TrashIcon , EyeIcon} from '@heroicons/vue/16/solid';
import Swal from 'sweetalert2';

const emit = defineEmits(['editTask']);

const tasks = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const filterStatus = ref('');
const showViewModal = ref(false);
const currentTask = ref(null);
const authStore = useAuthStore();

const filteredTasks = computed(() => {
    let filtered = tasks.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (task) =>
                task.title.toLowerCase().includes(query) ||
                (task.description && task.description.toLowerCase().includes(query))
        );
    }

    if (filterStatus.value) {
        filtered = filtered.filter((task) => task.status === filterStatus.value);
    }

    return filtered;
});

onMounted(() => {
    fetchTasks();
});

const fetchTasks = async () => {
    loading.value = true;
    try {
        const config = {
            headers: {
                Authorization: `${authStore.token}`,
            },
        };
        const response = await axios.get('/tasks', config);
        tasks.value = response.data.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        loading.value = false;
    }
};

const viewTask = (task) => {
    currentTask.value = task;
    showViewModal.value = true;
};

const editTask = (task) => {
    emit('editTask', task);
    window.scrollTo(0, 0);
};

const deleteTask = async (taskId) => {
    const result = await Swal.fire({
        title: "Are you sure?",
        text: "Are you sure you want to delete this task?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0a22c9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it.",
        cancelButtonText: "Nope, I changed my mind.",
    });

    if (!result.isConfirmed) return;

    try {
        const response = await axios.delete(`/tasks/${taskId}`, {
            headers: {
                Authorization: authStore.token,
            },
        });
        tasks.value = tasks.value.filter(task => task.id !== taskId);

        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "success",
            title: "Task deleted successfully and sent to trash!",
            showConfirmButton: false,
            timer: 3500,
        });

    } catch (error) {
        console.error("Error deleting task:", error);
        Swal.fire("Error", "Failed to delete task", "error");
    } finally {
        fetchTasks();
    }
};

const formatStatus = (status) => {
    const statusMap = {
        pending: 'Pending',
        in_progress: 'In Progress',
        completed: 'Completed',
    };
    return statusMap[status] || status;
};

const getStatusClass = (status) => {
    const statusClassMap = {
        pending: 'bg-yellow-100 text-yellow-800',
        in_progress: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
    };
    return statusClassMap[status] || 'bg-gray-100 text-gray-800';
};

// Watch for task saved event to refresh
defineExpose({
    refreshTasks: fetchTasks,
});
</script>
