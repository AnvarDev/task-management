<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    /**
     * CommentRepository constructor.
     *
     * @param Task $model
     */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $task_id
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getCommentsByTask(int $task_id, int $limit = 20): LengthAwarePaginator
    {
        return $this->getQueryBuilder()->whereTaskId($task_id)->orderByDesc('id')->paginate($limit);
    }
}
