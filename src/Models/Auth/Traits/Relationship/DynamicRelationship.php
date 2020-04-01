<?php

namespace Modules\Core\Models\Auth\Traits\Relationship;

use LogicException;
use Illuminate\Database\Eloquent\Relations\Relation;

trait DynamicRelationship
{
    protected static $registeredRelations = [];

    public static function registerRelations($key, $relation = null)
    {
        $relations = is_array($key) ? $key : [$key => $relation];

        static::$registeredRelations = array_merge(static::$registeredRelations, $relations);
    }

    public function hasRegisteredRelation($key)
    {
        return array_key_exists($key, static::$registeredRelations);
    }

    /**
     * Get a relationship.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key) || $this->hasRegisteredRelation($key)) {
            return $this->getRelationshipFromMethod($key);
        }
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        } elseif ($this->hasRegisteredRelation($method)) {
            $method = static::$registeredRelations[$method];
            return $method($this);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }
}
