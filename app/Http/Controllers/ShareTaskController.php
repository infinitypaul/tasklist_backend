<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareTaskRequest;
use App\Models\Permission;
use App\Models\SharedTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ShareTaskController extends Controller
{
    public function share_task(ShareTaskRequest $request, Task $task)
    {
        $user = auth()->user();
        $invited_user = User::whereUsername($request->username)->first();


        $shared_task = SharedTask::where('task_id', $task->id)
            ->where('invitee', $invited_user->id)
            ->where('invited_by', $user->id)
            ->first();

        if ($shared_task) {
            return response()->json(['message' => 'Task already shared with this user']);
        }

        $shared_task =  new SharedTask();
        $shared_task->task()->associate($task);
        $shared_task->permission()->associate($request->permission);
        $shared_task->invitee = $invited_user->id;
        $shared_task->invitedBy()->associate($user);
        $shared_task->save();

        return response()->json(['message' => 'Task shared successfully']);
    }

    public function permission()
    {
        return response()->json(['data' => Permission::all()]);
    }

    public function shared_with_me()
    {
        $user = auth()->user();
        $sharedTasks = SharedTask::with(['task', 'permission'])
            ->where('invitee', $user->id)
            ->get();

        return response()->json(['data' => $sharedTasks]);
    }

    public function task_i_shared(Task $task)
    {
        $user = auth()->user();
        $sharedTasks = SharedTask::with(['task', 'invitee:id,username', 'permission'])
            ->where('task_id', $task->id)
            ->where('invited_by', $user->id)
            ->get();

        return response()->json(['data' => $sharedTasks]);
    }
}
