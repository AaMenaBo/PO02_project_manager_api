<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project): JsonResponse
    {
        return response()->json(Project::all());
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
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);

            $project = Project::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
            ]);

            return response()->json($project, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the project.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try {
            if (!$project) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            return response()->json($project->with('tasks')->with('users')->get());
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while loading the project.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loading the project.',
                'error' => $e->getMessage(),
            ], 500);
        }  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:pending,completed,in-progress',
            ]);

            $project->update($validatedData);

            return response()->json($project, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the project.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (!$project) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }
        try {
            $project->delete();
            return response()->noContent();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while deleting the project.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the project.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
