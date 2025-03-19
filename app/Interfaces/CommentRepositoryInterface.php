<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    /**
     * Getting comments list per page by a task
     *
     * @param int $task_id
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getCommentsByTask(int $task_id, int $limit): LengthAwarePaginator;
}
