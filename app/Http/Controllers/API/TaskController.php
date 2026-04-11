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

    public function index(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $query = Task::where('user_id', $user->id);

            $perPage = $request->input('items_per_page', 15);
            $pageNumber = $request->input('page_number', 1);
            $skip = ($pageNumber - 1) * $perPage;

            // Search
            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->status) {
                $query->where('status', $request->status);
            }

            $total = $query->count();

            $tasks = $query->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($perPage)
            ->get();

            return $this->json('Tasks fetched successfully',[
                'tasks' => TaskResource::collection($tasks),
                'total' => $total,
            ]
            , 200);

        } catch (\Exception $e) {
            return $this->json('Error fetching tasks', $e->getMessage(), 500);
        }
    }

    public function store(TaskStoreRequest $request)
    {
        try {
            $user = Auth::guard('api')->user();

            $task = Task::create([
                'user_id' => $user->id,
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

    public function show($id)
    {
        try {
            $user = Auth::guard('api')->user();
            $task = Task::where('user_id', $user->id)->findOrFail($id);

            return $this->json('Task show successfully', $task,200);
        } catch (\Exception $e) {
           return $this->json('Error creating task', $e->getMessage(),500);
        }
    }

    public function update(TaskUpdateRequest $request, $id)
    {
        try {
            $user = Auth::guard('api')->user();
            $task = Task::where('user_id', $user->id)->findOrFail($id);

            $task->update($request->only(['title', 'description', 'status']));

             return $this->json('Task updated successfully', TaskResource::make($task),200);
        } catch (\Exception $e) {
            return $this->json('Error creating task', $e->getMessage(),500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::guard('api')->user();
            $task = Task::where('user_id', $user->id)->findOrFail($id);
            $task->delete();

            return $this->json('Task deleted successfully', [],200);
        } catch (\Exception $e) {
           return $this->json('Error creating task', $e->getMessage(),500);
        }
    }
}
