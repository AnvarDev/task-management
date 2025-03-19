<?php

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getProjects(int $limit = 20): LengthAwarePaginator
    {
        return $this->getQueryBuilder()->orderBy('name')->paginate($limit);
    }
}
