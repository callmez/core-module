<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 11:58
 */

namespace Modules\Core\Services\Frontend;


use Closure;
use Modules\Core\Models\Frontend\Label;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Services\Traits\HasQuery;

class LabelService
{
    use HasQuery {
        one as queryOne;
    }
    /**
     * @var Label
     */
    protected $model;

    public function __construct(Label $model)
    {
        $this->model = $model;
    }
    
    /**
     * @param $label
     * @param array $options
     * @return Label
     */
    public function getLabelInfoByLabel($label, array $options = [])
    {

        $label = $this->one(['label' => $label], array_merge([
            'orderBy' => 'created_at',
        ], $options));

        return $label;
    }


}
