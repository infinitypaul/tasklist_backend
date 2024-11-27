<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('update', $task);

        $task->name = $request->name;
        $task->save();

        return response()->json([
            'message' => 'Task created!',
            'task' => $task
        ]);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return response()->json([
            'message' => 'Task deleted!'
        ]);
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        $user = auth()->user();
        return response()->json([
            'task' => $task,
            'shared' => $user->shared()->where('task_id', $task->id)->exists()
        ]);
    }

    public function mark_task(Task $task)
    {
        Gate::authorize('update', $task);
        $task->status = !$task->status;
        $task->save();

        return response()->json([
            'message' => 'Task marked '.($task->status ? ' completed' : ' incomplete'),
            'task' => $task
        ]);
    }


}
