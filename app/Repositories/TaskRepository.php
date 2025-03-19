<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * TaskRepository constructor.
     *
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $priority
     * @param array $fields
     * @param int|null $project_id
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getTasksByPriority(int $priority, array $fields, int|null $project_id, int $limit = 20): LengthAwarePaginator
    {
        $query = $this->getQueryBuilder()->select(array_merge([
            'id',
            'date',
            'priority',
        ], $fields));

        $query->wherePriority($priority);

        if (!is_null($project_id)) {
            $query->whereProjectId($project_id);
        }

        return $query->orderBy('date')->paginate($limit);
    }
}
