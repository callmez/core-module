<?php

namespace Modules\Core\Models;


class Media extends \Plank\Mediable\Media
{
    protected $appends = [
        'path',
        'url',
        'basename',
        'original_basename',
    ];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getPathAttribute()
    {
        return $this->getDiskPath();
    }

    public function getOriginalBasenameAttribute(): string
    {
        return $this->original_filename . '.' . $this->extension;
    }
}
