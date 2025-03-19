<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidTaskIdException;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\UpdatePriorityTaskRequest;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected TaskRepository $repository) {}

    /**
     * Display a listing of the resource.
     * 
     * @param  \App\Http\Requests\IndexTaskRequest  $request
     * @return \App\Http\Resources\TaskCollection
     */
    public function index(IndexTaskRequest $request)
    {
        return new TaskCollection($this->repository->getTasksByPriority($request->getPriority(), [
            'title',
            'status',
        ], $request->getProjectId(), $request->getLimit()));
    }

    /**
     * Display the specified resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $task = $this->repository->find($id);

        if (!$task) {
            throw new InvalidTaskIdException;
        }

        return new TaskResource($task);
    }

    /**
     * Update priority for the specified resource.
     * 
     * @param  \App\Http\Requests\UpdatePriorityTaskRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function priority(UpdatePriorityTaskRequest $request, int $id)
    {
        $task = $this->repository->find($id);

        if (!$task) {
            throw new InvalidTaskIdException;
        }

        if ($task->priority !== $request->getPriority()) {
            $this->repository->update([
                'priority' => $request->getPriority()
            ], $id);

            $task->refresh();
        }

        return new TaskResource($task);
    }
}
