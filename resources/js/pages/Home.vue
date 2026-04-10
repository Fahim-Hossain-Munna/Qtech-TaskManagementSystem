<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Task Management System</h1>
                <p class="text-gray-600 mt-2">Organize and track your daily tasks efficiently</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form -->
                <div class="lg:col-span-1">
                    <TaskForm
                        :editingTask="editingTask"
                        @taskSaved="handleTaskSaved"
                        @editCancelled="editingTask = null"
                    />
                </div>
                <!-- Table -->
                <div class="lg:col-span-2">
                    <TaskTable
                        ref="taskTable"
                        @editTask="handleEditTask"
                    />
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import TaskForm from '../components/TaskForm.vue';
import TaskTable from '../components/TaskTable.vue';

const editingTask = ref(null);
const taskTable = ref(null);

const handleTaskSaved = (task) => {
    editingTask.value = null;
    // Refresh the task table
    if (taskTable.value) {
        taskTable.value.refreshTasks();
    }
};

const handleEditTask = (task) => {
    editingTask.value = task;
};
</script>
