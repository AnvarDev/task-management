<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    /**
     * Getting projects list per page
     *
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getProjects(int $limit): LengthAwarePaginator;
}
