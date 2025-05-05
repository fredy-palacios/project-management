<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'info' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::create([
            'info' => $request->info,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
        ]);


        return response()->json($task);
    }

    public function getAll(): JsonResponse
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function getTasksByUser($id): JsonResponse
    {

        $tasks = Task::where('user_id', $id)->get();
        return response()->json($tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'project_id' => $task->project_id,
                'info' => $task->info,
                'date_from' => $task->date_from,
                'date_to' => $task->date_to,
            ];
        }));
    }
}

