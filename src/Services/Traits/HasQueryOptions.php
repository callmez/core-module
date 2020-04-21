<?php

namespace Modules\Core\src\Services\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasQueryOptions
{
    /**
     * @param $query
     * @param array $options
     */
    protected function withQueryOptions(Builder $query, array $options)
    {
        if ($with = $options['with'] ?? false) {
            $query->with($with);
        }

        if ($orderBy = $options['orderBy'] ?? false) {
            call_user_func_array([$query, 'orderBy'], !is_array($orderBy) ? [$orderBy] : $orderBy);
        }

        if ($callback = $options['queryCallback'] ?? false) {
            $callback($query);
        }

        return $query;
    }
}
