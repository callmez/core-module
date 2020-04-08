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

        if ($callback = $options['query_callback'] ?? false) {
            $callback($query);
        }

        return $query;
    }
}
