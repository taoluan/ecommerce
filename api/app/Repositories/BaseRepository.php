<?php

namespace App\Repositories;

use App\Repositories\Interface\BaseInterface;

abstract class BaseRepository implements BaseInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $_model;

    protected $_table;

    /**
     * The current sort field and direction
     * @var array
     */
    protected $currentSort = array('created_at', 'desc');

    /**
     * The current number of results to return per page
     * @var integer
     */
    protected $perPage = 25;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->initModel();
    }

    /**
     * Set model
     * @return string
     */
    abstract public function setModel();

    /**
     * Init model
     */
    public function initModel()
    {
        $this->_model = app()->make(
            $this->setModel()
        );
        $this->_table = $this->_model->getTable();
    }

    /**
     * Paginate all
     * @param  integer $perPage
     * @param  array   $columns
     * @return \Illuminate\Pagination\Paginator
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->_model->paginate($perPage, $columns);
    }

    public function orderBy($order, $sort = 'asc')
    {
        return $this->_model->orderBy($order, $sort);
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(array $relations = [], array $columns = ['*'])
    {
        return $this->_model->with($relations)->get($columns);
    }

    /**
     * Find with conditions
     *
     * @param array $conditions
     * @param array $relations
     * @param array $columns
     * @return mixed
     */
    public function find($conditions = [], array $relations = [], array $columns = ['*'])
    {
        return $this->_model->select($columns)->with($relations)->where($conditions)->get();
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function findById(int $id, $conditions = [], array $relations = [])
    {
        return $this->_model->with($relations)->where($conditions)->findOrFail($id);
    }

    /**
     * findOne
     *
     * @param array $conditions
     * @param array $relations
     *
     * @return object
     */
    public function findOne($conditions = [], array $relations = [])
    {
        return $this->_model->with($relations)->where($conditions)->first();
    }

    /**
     * Create
     * @param array $attributes
     * @return object
     */
    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->findById($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete(array $conditions = [])
    {
        $result = $this->_model->where($conditions);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    public function formatParams($params)
    {
        $formatted = [];
        $arrColumn = $this->_model->getFillable();
        foreach ($arrColumn as $column) {
            if (array_key_exists($column, $params)) {
                $formatted[$column] = $params[$column];
            }
        }
        return $formatted;
    }

    public function insert($inputs)
    {
        return $this->create($this->formatParams($inputs));
    }

    public function updateByModel($model, $inputs)
    {
        $modelClone = clone $model;
        $modelClone->forceFill($this->formatParams($inputs))->save();

        return $modelClone;
    }

    public function getMaxRecord(array $conditions = [])
    {
        return $this->_model->where($conditions)->max('id');
    }

    public function getCountRecord(array $conditions = [])
    {
        return $this->_model->where($conditions)->count('id');
    }

}
