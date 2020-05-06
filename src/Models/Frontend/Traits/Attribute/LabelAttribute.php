<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 13:59
 */

namespace Modules\Core\Models\Frontend\Traits\Attribute;

/**
 * Trait LabelAttribute.
 */
trait LabelAttribute
{
    public function getInfoCnAttribute()
    {
        return is_null($this->attributes['info_cn']) ? "" : $this->attributes['info_cn'];
    }

    public function getInfoEnAttribute()
    {
        return trim($this->attributes['info_en']);
    }

    public function getInfoKoAttribute()
    {
        return trim($this->attributes['info_ko']);
    }

    public function getInfoJpAttribute()
    {
        return trim($this->attributes['info_jp']);
    }

    public function getInfoTwAttribute()
    {
        return trim($this->attributes['info_tw']);
    }

}