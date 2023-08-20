<?php

namespace App\Repositories\Interface;

interface BaseInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll(array $columns = ['*'], array $relations = []);

    /**
     * Find with conditions
     *
     * @param array $conditions
     * @param array $relations
     * @param array $columns
     * @return mixed
     */
    public function find($conditions = [], array $relations = [], array $columns = ['*']);

    public function findById(int $id, $conditions = [], array $relations = []);

    public function findOne($conditions = [], array $relations = []);

    public function paginate(int $perPage = 15, $columns = ['*']);

    public function orderBy($order, $sort = 'asc');

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete(array $conditions);

    public function formatParams($params);

    public function insert(array $attributes);

    public function updateByModel($model, array $attributes);

    public function getMaxRecord(array $conditions = []);

    public function getCountRecord(array $conditions = []);

}
