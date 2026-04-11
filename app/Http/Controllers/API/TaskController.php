<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Get all tasks for authenticated user with search and filter
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Task::where('user_id', $user->id);

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Sort by latest
            $tasks = $query->orderBy('created_at', 'desc')->get();

            return $this->json('Tasks fetched successfully', TaskResource::collection($tasks) ,200);
        } catch (\Exception $e) {
            return $this->json('Error fetching tasks', $e->getMessage(),500);
        }
    }

    /**
     * Create a new task
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            $task = Task::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ]);

             return $this->json('Task created successfully', [] ,200);
        } catch (ValidationException $e) {
            return $this->json('Validation error', $e->errors(),422);
        } catch (\Exception $e) {
            return $this->json('Error creating task', $e->getMessage(),500);
        }
    }

    /**
     * Get a single task
     */
    public function show($id)
    {
        try {
            $task = Task::where('user_id', Auth::id())->findOrFail($id);

            return $this->json('Task show successfully', $task,200);
        } catch (\Exception $e) {
           return $this->json('Error creating task', $e->getMessage(),500);
        }
    }

    /**
     * Update a task
     */
    public function update(TaskUpdateRequest $request, $id)
    {
        try {
            $task = Task::where('user_id', Auth::id())->findOrFail($id);

            $task->update($request->only(['title', 'description', 'status']));

             return $this->json('Task updated successfully', TaskResource::make($task),200);
        } catch (\Exception $e) {
            return $this->json('Error creating task', $e->getMessage(),500);
        }
    }

    /**
     * Delete a task
     */
    public function destroy($id)
    {
        try {
            $task = Task::where('user_id', Auth::id())->findOrFail($id);
            $task->delete();

            return $this->json('Task deleted successfully', [],200);
        } catch (\Exception $e) {
           return $this->json('Error creating task', $e->getMessage(),500);
        }
    }
}
