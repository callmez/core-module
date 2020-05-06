<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:16
 */

namespace Modules\Core\Models\Frontend\Traits\Attribute;
/**
 * Trait NoticeAttribute
 * @package Modules\Core\Models\Frontend\Traits\Attribute
 */
Trait NoticeAttribute
{
    public function getContentTwAttribute()
    {
        return trim($this->attributes['content_tw']);
    }

    public function getContentEnAttribute()
    {
        return trim($this->attributes['content_en']);
    }

    public function getContentKoAttribute()
    {
        return trim($this->attributes['content_ko']);
    }

    public function getContentJpAttribute()
    {
        return trim($this->attributes['content_jp']);
    }
}