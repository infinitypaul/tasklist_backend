<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tasks = $user->tasks()->orderBy('created_at')->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }

    public function store(StoreTaskRequest $request)
    {
        $user = auth()->user();

        $task = $user->tasks()->create($request->validated());

        return response()->json([
            'message' => 'Task created!',
            'task' => $task
        ]);
    }


    public function update(Task $task, UpdateTaskRequest $request)
    {
        $task->name = $request->name;
        $task->save();

        return response()->json([
            'message' => 'Task created!',
            'task' => $task
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted!'
        ]);
    }

    public function show(Task $task)
    {
        return response()->json([
            'task' => $task
        ]);
    }


}
