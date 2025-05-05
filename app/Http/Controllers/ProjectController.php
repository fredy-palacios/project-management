<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $users = User::all();

        $projects = Project::all();
        return view('projects.index')->with(['projects' => $projects, 'users' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($project);
    }

    public function getAll(): JsonResponse
    {
        $projects = Project::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($projects);
    }
}
