<?php

namespace Modules\Core\src\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Exceptions\ModelSaveException;

/**
 * Service需要设置model参数
 *
 * 可以在__construct中初始化model, 示例:
 *
 * protected $model;
 *
 * public function __construct(User $model)
 * {
 *     $this->model = $model;
 * }
 *
 * @package Modules\Core\src\Services\Traits
 */
trait HasQuery
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param $query
     * @param array $options
     */
    protected function withQueryOptions(Builder $query, array $options)
    {
        if ($where = $options['where'] ?? false) {
            $query->where($where);
        }

        if ($with = $options['with'] ?? false) {
            $query->with($with);
        }

        if ($orderBy = $options['orderBy'] ?? false) {
            call_user_func_array([$query, 'orderBy'], ! is_array($orderBy) ? [$orderBy] : $orderBy);
        }

        if ($callback = $options['queryCallback'] ?? false) {
            $callback($query);
        }

        return $query;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function one($where = null, array $options = [])
    {
        $model = $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]))->first();

        if (!$model) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception() : new ModelNotFoundException(trans('指定数据未找到'));
            }
        }

        return $model;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function all($where = null, array $options = [])
    {
        return $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]))->get();
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return int
     */
    public function count($where = null, array $options = [])
    {
        return $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]))->count();
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($where = null, array $options = [])
    {
        $limit = $options['limit'] ?? request('limit', 15);
        $pageName = $options['pageName'] ?? 'page';
        $columns = $options['columns'] ?? ['*'];
        $page = $options['page'] ?? null;

        $maxLimit = $options['maxLimit'] ?? config('core::system.paginate.maxLimit', 100);
        if ($limit > $maxLimit) {
            $limit = $maxLimit;
        }

        $query = $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]));

        return $query->paginate($limit, $columns, $pageName, $page);
    }

    /**
     * @param int $id
     * @param array $options
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id, array $options = [])
    {
        return $this->getById($id, $options)->delete();
    }

    /**
     * @param int $id
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $options = [])
    {
        return $this->one(['id' => $id], $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool|Model
     */
    public function create(array $data, array $options = [])
    {
        $model = $this->query()->newModelInstance($data);

        if (!$model->save()) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception($model) : ModelSaveException::withModel($model);
            }

            return false;
        }

        return $model;
    }
}
