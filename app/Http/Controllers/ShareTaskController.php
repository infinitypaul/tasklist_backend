<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareTaskRequest;
use App\Http\Resources\SharedTaskResource;
use App\Models\Permission;
use App\Models\SharedTask;
use App\Models\Task;
use App\Models\User;
use App\Repositories\SharedTaskRepository;
use Illuminate\Http\Request;

class ShareTaskController extends Controller
{
    protected SharedTaskRepository $sharedTaskRepository;

    public function __construct(SharedTaskRepository $sharedTaskRepository)
    {
        $this->sharedTaskRepository = $sharedTaskRepository;
    }
    public function share_task(ShareTaskRequest $request, Task $task)
    {
        $user = auth()->user();
        $invitedUser = $request->username;

        if ($this->sharedTaskRepository->isTaskAlreadyShared($task->id, $user->id, $invitedUser->id)) {
            return response()->json(['message' => 'Task already shared with this user']);
        }

        $sharedTask = $this->sharedTaskRepository->createSharedTask([
            'task_id' => $task->id,
            'invited_by' => $user->id,
            'invitee' => $invitedUser->id,
            'permission_id' => $request->permission,
        ]);

        return response()->json(['message' => 'Task shared successfully']);
    }

    public function permission()
    {
        return response()->json(['data' => Permission::all()]);
    }

    public function shared_with_me()
    {
        $sharedTasks = $this->sharedTaskRepository->getTasksSharedWith(auth()->user());

        return response()->json([
            'data' => SharedTaskResource::collection($sharedTasks),
        ]);
    }

    public function task_i_shared(Task $task)
    {
        $sharedTasks = $this->sharedTaskRepository->getTasksSharedByUser(auth()->user(), $task->id);

        return response()->json([
            'data' => SharedTaskResource::collection($sharedTasks),
        ]);
    }
}
