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
        $response = Task::where('project_id', $projectId)->with('user')->with('project')->get();
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|nullable|string',
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);

            $task = Project::find($validatedData['project_id'])->tasks()->create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'user_id' => $validatedData['user_id'],
                'status' => $validatedData['status'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Task created successfully.',
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'user_id' => $task->user,
                    'project_id' => $task->project,
                    'status' => Task::getStatusText($task->status),
                ],
            ], 201);
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
            return response()->json([
                'status' => true,
                'message' => 'Task loaded successfully.',
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'user_id' => $task->user,
                    'project_id' => $task->project,
                    'status' => $task->status,
                ],
            ],200);
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
    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|exists:tasks,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);

            $task = Task::find($data['id']);
            $task->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'user_id' => $data['user_id'],
                'status' => $data['status'],
            ]);
            return response()->json($task);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
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
            return response()->noContent();
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
