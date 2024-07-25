<?php

namespace App\Http\Controllers\Task;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\ExportTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Exports\TaskExport;
use App\Services\TaskService;
use Maatwebsite\Excel\Facades\Excel;


class TaskController extends Controller
{
    private $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        try {
            $tasks = $this->getTask()->where('user_id', $user->id);
            if(isset($request->id)){
                $tasks = $tasks->where('id', $request->id);
            }
            if ($tasks->count() === 0) {
                return $this->errorResponse('No tasks found', 404);
            }
            $response = $this->paginate($request, $tasks);
            return $this->successResponse('Tasks retrieved successfully', $response);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), 400);
        }
    }
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            DB::beginTransaction();
            $newTask = $this->taskService->createTask($validated);
           
            DB::commit();
            return $this->successResponse('Task created successfully', $newTask, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function update(UpdateTaskRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $task = $this->getTask()->where('id', $request->id)->where('user_id', $user->id)->first();
            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }
            $task = $this->taskService->updateTask($task, $request->validated());
            DB::commit();
            return $this->successResponse('Task updated successfully', $task);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $task = $this->getTask()->where('id', $request->id)->first();
            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }
            $this->taskService->deleteTask($task);
            return $this->successResponse('Task deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    public function search(Request $request): JsonResponse
    {
        $user = Auth::user();
        $keyword = $request->query('keyword');
        if (!$keyword) {
            return $this->errorResponse('Keyword is required', 400);
        }
        try {
            $tasks = $this->getTask()
                ->where('user_id', $user->id)
                ->where(function($query) use ($keyword) {
                    $query->where('task_description', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('task_title', 'LIKE', '%' . $keyword . '%');
                });
            if ($tasks->count() === 0) {
                return $this->errorResponse('No tasks found', 404);
            }
            $response = $this->paginate($request, $tasks);
            return $this->successResponse('Tasks retrieved successfully', $response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } 
    }
    public function export(ExportTaskRequest $request)
    {
        try {
            $user = Auth::user();
            $tasks = Task::where('user_id', $user->id)->get();
            if ($tasks->count() === 0) {
                return $this->errorResponse('No tasks found to export.', 404);
            }
            return Excel::download(new TaskExport($user->id), $request->filename.$request->type, \Maatwebsite\Excel\Excel::CSV);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    protected function successResponse(string $message, $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'errors' => [],
            'message' => $message,
            'data' => $data
        ], $status);
    }
    protected function errorResponse(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => [$message],
            'message' => 'An error ocurred',
        ], $status);
    }
    protected function paginate($request, $query)
    {
        $page = $request->query('page') ?? 1;
        $perPage = $request->query('per_page') ?? 5;
        return $query->paginate($perPage, ['*'], 'page', $page);
    }
    protected function getTask()
    {
        return Task::where('deleted_at', null)
                ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                ->orderBy('due_date', 'asc');
    }
}
