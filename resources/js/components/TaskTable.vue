<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tasks</h2>
            <div class="flex gap-3">
                <!-- Search Input -->
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search tasks..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @keyup.enter="handleFilterChange"
                />

                <!-- Filter Select -->
                <select
                    v-model="filterStatus"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @change="handleFilterChange"
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

        <!-- Table -->
        <div v-else class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-2 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" :key="task.id" class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-2 py-4 text-sm text-gray-800">#{{ task.id }}</td>
                        <td class="px-2 py-4 text-sm font-medium text-gray-800">{{ formatTitle(task.title) }}</td>
                        <td class="px-2 py-4 text-sm text-gray-600">
                            <span v-if="task.description" class="line-clamp-2">{{ formatDescription(task.description) }}</span>
                            <span v-else class="text-gray-400 italic">No description</span>
                        </td>
                        <td class="px-2 py-4 text-sm">
                            <span
                                :class="getStatusClass(task.status)"
                                class="px-2 py-1 rounded-full text-[10px] font-semibold"
                            >
                                {{ formatStatus(task.status) }}
                            </span>
                        </td>
                        <td class="px-2 py-4 text-sm text-gray-600">
                            {{ task.created_at }}
                        </td>
                        <td class="px-2 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="viewTask(task)" class="px-1 py-1 text-black text-xs rounded transition" title="View">
                                    <EyeIcon class="w-4 h-4" />
                                </button>
                                <button @click="editTask(task)" class="px-1 py-1 text-black text-xs rounded transition" title="Edit">
                                    <PencilSquareIcon class="w-4 h-4" />
                                </button>
                                <button @click="deleteTask(task.id)" class="px-1 py-1 text-black text-xs rounded transition" title="Delete">
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            <VueAwesomePaginate
                v-model="currentPage"
                :total-items="pagination.total"
                :items-per-page="pagination.items_per_page"
                :max-pages-shown="5"
                @click="onClickHandler"
            />
        </div>

        <!-- View Modal (same as before) -->
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
                            class="px-2 py-1 rounded-full text-xs font-semibold inline-block"
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
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '../store/authStore';
import { PencilSquareIcon, TrashIcon, EyeIcon } from '@heroicons/vue/16/solid';
import Swal from 'sweetalert2';
import { VueAwesomePaginate } from 'vue-awesome-paginate';
import 'vue-awesome-paginate/dist/style.css';

const emit = defineEmits(['editTask']);

const tasks = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const filterStatus = ref('');
const showViewModal = ref(false);
const currentTask = ref(null);
const authStore = useAuthStore();

const currentPage = ref(1);

const pagination = ref({
    page_number: 1,
    items_per_page: 5,
    total: 0,
});

onMounted(() => {
    fetchTasks();
});

const handleFilterChange = () => {
    pagination.value.page_number = 1;
    fetchTasks();
};

const onClickHandler = (page) => {
    currentPage.value = page;
    pagination.value.page_number = page;
    fetchTasks();
};

const fetchTasks = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/tasks', {
            headers: {
                Authorization: `${authStore.token}`,
            },
            params: {
                page_number: pagination.value.page_number,
                items_per_page: pagination.value.items_per_page,
                search: searchQuery.value || null,
                status: filterStatus.value || null,
            },
        });

        tasks.value = response.data.data.tasks;
        pagination.value.total = response.data.data.total;

    } catch (error) {
        console.error('Error fetching tasks:', error);
        Swal.fire('Error', 'Failed to load tasks', 'error');
    } finally {
        loading.value = false;
    }
};

watch(currentPage, () => {
    fetchTasks();
});

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
        await axios.delete(`/tasks/${taskId}`, {
            headers: { Authorization: authStore.token },
        });

        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "success",
            title: "Task deleted successfully!",
            showConfirmButton: false,
            timer: 3000,
        });

        fetchTasks();
    } catch (error) {
        console.error("Error deleting task:", error);
        Swal.fire("Error", "Failed to delete task", "error");
    }
};

const formatStatus = (status) => ({
    pending: 'Pending',
    in_progress: 'In Progress',
    completed: 'Completed',
}[status] || status);

const formatDescription = (desc) => desc?.length > 30 ? desc.substring(0, 30) + '...' : desc;
const formatTitle = (title) => title?.length > 15 ? title.substring(0, 15) + '...' : title;

const getStatusClass = (status) => ({
    pending: 'bg-yellow-100 text-yellow-800',
    in_progress: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
}[status] || 'bg-gray-100 text-gray-800');

defineExpose({ refreshTasks: fetchTasks });
</script>

<style scoped>
:deep(.pagination-container) {
    gap: 6px;
}

:deep(.paginate-buttons) {
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 9999px;
    border: 1px solid #e5e7eb;
    background: white;
    font-weight: 500;
    transition: all 0.25s ease;
}

:deep(.paginate-buttons:hover:not(:disabled)) {
    background-color: #eff6ff;
    border-color: #60a5fa;
    color: #1e40af;
}

:deep(.paginate-buttons.active-page) {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border: none;
    box-shadow: 0 4px 6px -1px rgb(59 130 246 / 0.3);
}

:deep(.paginate-buttons:disabled) {
    opacity: 0.4;
}
</style>
