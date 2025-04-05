<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'message' => 'not authenticated',
                ], 422);
            }
            $projects = $user->projects()->with('tasks')->with('users')->get();
            return response()->json($projects, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while loading projects.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loading projects.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $project = Project::create([
                'name' => $data['name'],
            ]);
            $project->users()->attach(auth()->user()->id);
            $project->save();

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
            $project->load('tasks', 'users');
            return response()->json([
                'status' => true,
                'message' => 'Project loaded successfully',
                'data' => $project
            ], 200);
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
    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer|exists:projects,id',
                'name' => 'required|string|max:255',
            ]);
            $project = Project::findOrFail($data['id']);
            $project->name = $data['name'];
            $project->save();
            return response()->json($project, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while updating the project.',
                'error' => $e->getMessage(),
            ], 500);
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
    /**
     * Add a user to the project.
     */
    public function addUser(User $user, Project $project)
    {
        try {
            if($project->users()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User already exists in the project',
                ], 422);
            }
            $project->users()->attach($user->id);
            $project->save();

            return response()->json([
                'status' => true,
                'message' => 'User added to project successfully',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while adding the user to the project.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the user to the project.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Remove a user from the project.
     */
    public function removeUser(User $user, Project $project)
    {
        try {
            if(!$project->users()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not exist in the project',
                ], 422);
            }
            $project->users()->detach($user->id);
            $project->save();
            return response()->json([
                'status' => true,
                'message' => 'User removed from project successfully',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Database error occurred while removing the user from the project.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while removing the user from the project.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
