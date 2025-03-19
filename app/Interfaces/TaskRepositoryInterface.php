<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    /**
     * Getting tasks list per page by a priority
     *
     * @param int $priority
     * @param array $fields
     * @param int|null $project_id
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getTasksByPriority(int $priority, array $fields, int|null $project_id, int $limit): LengthAwarePaginator;
}
