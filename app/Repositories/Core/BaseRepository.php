<?php

namespace App\Repositories\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get paginated records.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Find record by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find record by ID or throw exception.
     *
     * @param int $id
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Get model instance.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set model instance.
     *
     * @param Model $model
     * @return self
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get records with relations.
     *
     * @param array $relations
     * @return Collection
     */
    public function with(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    /**
     * Get paginated records with relations.
     *
     * @param array $relations
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function withPaginate(array $relations, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage);
    }

    /**
     * Find record by ID with relations.
     *
     * @param int $id
     * @param array $relations
     * @return Model|null
     */
    public function findWith(int $id, array $relations): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    /**
     * Find record by ID with relations or throw exception.
     *
     * @param int $id
     * @param array $relations
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findWithOrFail(int $id, array $relations): Model
    {
        return $this->model->with($relations)->findOrFail($id);
    }
} 