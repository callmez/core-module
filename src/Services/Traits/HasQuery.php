<?php

namespace Modules\Core\src\Services\Traits;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HasQuery
{
    /**
     * @return Builder
     */
    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param $query
     * @param array $options
     */
    protected function withQueryOptions(Builder $query, array $options)
    {
        if ($where = $options['with'] ?? false) {
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
     * @param array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function all($where = null, array $options = [])
    {
        return $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]))->all();
    }

    /**
     * @param array|null $where
     * @param array $options
     *
     * @return int
     */
    public function count($where = null, array $options = [])
    {
        return $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]))->count();
    }

    /**
     * @param array|null $where
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model|Builder|object|null
     */
    public function first($where = null, array $options = [])
    {
        $model = $this->withQueryOptions($this->query(), array_merge($options, ['where' => $where]))->first();

        if ( ! $model && ($options['exception'] ?? true)) {
            throw new ModelNotFoundException(trans('指定数据未找到'));
        }

        return $model;
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
        return $this->first(['id' => $id], $options);
    }
}
