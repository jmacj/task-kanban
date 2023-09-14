<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

/**
 * TaskController
 */
class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json($this->taskService->getAllTasks());
    }

    /**
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $this->taskService->saveTask($request->validated());

        return response()->json(['id' => $data->id], 200);
    }

    /**
     * @param UpdateTaskRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $data = $this->taskService->updateTask($id, $request->validated());

        return response()->json($data, $data ? 200 : 400);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $data = $this->taskService->deleteTask($id);

        return response()->json(['status' => true, 'message' => 'Deleted'], $data ? 200 : 400);
    }
}
