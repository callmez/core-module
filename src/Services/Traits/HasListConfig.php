<?php

namespace Modules\Core\Services\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Exceptions\DataExistsException;
use Modules\Core\Exceptions\DataNotFoundException;

/**
 * 轻量级列表数据 保存到config中
 *
 * 列表数据不多的情况下可以保存到config中
 *
 * 注意: 大数据量的话还是建议单独做表操作.
 *
 * @package Modules\Core\Services\Traits
 */
trait HasListConfig
{
    private $config;

    /**
     * @param array $options
     *
     * @return array|Collection
     */
    protected function config(array $options = [])
    {
        if ($this->config == null || ($options['force'] ?? false)) {
            $this->config = collect(config($this->key, []));
        }

        if ($options['collection'] ?? true) {
            return $this->config;
        }

        return $this->config->all(); // TODO 是否需要toArray?
    }

    /**
     * @param $data
     */
    protected function saveConfig($data)
    {
        store_config($this->key, $data);
    }

    /**
     * @param Collection $data
     * @param array $options
     *
     * @return Collection
     */
    protected function withCollectionOptions(Collection $data, array $options)
    {
        if ($where = $options['where'] ?? false) {
            $data->where($where);
        }

        if ($sortBy = $options['sortBy'] ?? false) {
            call_user_func_array([$data, 'sortBy'], ! is_array($sortBy) ? [$sortBy] : $sortBy);
        }

        if ($callback = $options['collectionCallback'] ?? false) {
            $callback($data);
        }

        return $data;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return array
     */
    public function all($where = null, array $options = [])
    {
        $config = $this->config();

        return $this->withCollectionOptions($config, array_merge($options, [
            'where' => $where,
        ]))->all();
    }

    /**
     * @param null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        $config = $this->config();

        $data = $this->withCollectionOptions($config, array_merge($options, [
            'where' => $where,
        ]))->first();

        if (!$data) {
            // @param \Closure|bool $exception 自定义异常设置
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception() : new DataExistsException(trans('指定数据未找到'));
            }
        }

        return $data;
    }

    public function count($where = null, array $options = [])
    {
        $config = $this->config();

        return $this->withCollectionOptions($config, array_merge($options, [
            'where' => $where,
        ]))->count();
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return int
     */
    public function has($where = null, array $options = [])
    {
        $config = $this->config();

        if (Arr::isAssoc($where) || is_callable($where)) { // where查询或者回调方法查询
            $this->withCollectionOptions($config, array_merge($options, [
                'where' => $where,
            ]))->count();
        }

        return $config->has($where); // key 或者 [key, key1] 查询
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($where = null, array $options = [])
    {
        $limit = $options['limit'] ?? request('limit', 15);
        $pageName = $options['pageName'] ?? 'page';
        $page = $options['page'] ?? null;

        $maxLimit = $options['maxLimit'] ?? config('core::system.paginate.maxLimit', 100);
        if ($limit > $maxLimit) {
            $limit = $maxLimit;
        }

        $collection = $this->withCollectionOptions($where, $options);

        return app()->makeWith(LengthAwarePaginator::class, [
            'items' => $collection->forPage($page, $limit),
            'count' => $collection->count(),
            'perPage' => $limit,
            'currentPage' => $page,
            'options' => [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        ]);
    }

    /**
     * @param string $key
     * @param array $options
     *
     * @return array
     */
    public function getByKey($key, array $options = [])
    {
        return $this->one(['key' => $key], $options);
    }

    /**
     * @param int $id
     * @param array $options
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteByKey($key, array $options = [])
    {
        $config = $this->config()->filter(function($item) use ($key) {
            return $item['key'] !== $key;
        });

        $this->saveConfig($config);

        return true;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool
     * @throws DataExistsException
     */
    public function create(array $data, array $options = [])
    {
        ['key' => $key, 'value' => $value, 'description' => $description] = $data;

        if ($this->has($key)) {
            throw new DataExistsException(trans('数据已存在'));
        }

        $config = $this->config();

        $config->push([
            'key' => $key,
            'value' => $value,
            'description' => $description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->saveConfig($config);

        return true;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool
     * @throws DataNotFoundException
     */
    public function update(array $data, array $options = [])
    {
        if (!$this->has($data['key'])) {
            throw new DataNotFoundException(trans('指定数据未找到'));
        }

        $config = $this->config()->map(function($oldData) use ($data) {
            if ($oldData['key'] == $data['key']) {
                $oldData = array_merge($oldData, $data, [
                    'key' => $oldData['key'],
                    'created_at' => $oldData['created_at'] ?? Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            return $oldData;
        });

        $this->saveConfig($config);

        return true;
    }
}
