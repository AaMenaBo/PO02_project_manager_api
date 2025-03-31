<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($projectId)
    {
        return response()->json(Task::where('project_id', $projectId)->with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);

            $task = Project::find($validatedData['project_id'])->tasks()->create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'user_id' => $validatedData['user_id'],
            ]);

            return response()->json($task, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            if(!$task) {
                return response()->json([
                    'message' => 'Task not found.',
                ], 404);
            }
            return response()->json($task);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task, Request $request)
    {
        try {
            if(!$task) {
                return response()->json([
                    'message' => 'Task not found.',
                ], 404);
            }
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);
            $task->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'user_id' => $data['user_id'],
                'status' => $data['status'],
            ]);
            return response()->json($task);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        try {
            if(!$task) {
                return response()->json([
                    'message' => 'Task not found.',
                ], 404);
            }
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);
            $task->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'user_id' => $data['user_id'],
                'status' => $data['status'],
            ]);
            return response()->json($task);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            if(!$task) {
                return response()->json([
                    'message' => 'Task not found.',
                ], 404);
            }
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loading the task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
