<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(protected Model $model) {}

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->getQueryBuilder()->create($attributes);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return bool
     */
    public function update(array $attributes, $id): bool
    {
        return $this->getQueryBuilder()->find($id)->fill($attributes)->save();
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->getQueryBuilder()->find($id)->delete();
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->getQueryBuilder()->find($id);
    }

    /**
     * @return Builder
     */
    public function getQueryBuilder(): Builder
    {
        return $this->model::query();
    }
}
