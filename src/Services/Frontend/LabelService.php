<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 11:58
 */

namespace Modules\Core\src\Services\Frontend;


use Closure;
use Modules\Core\src\Models\Frontend\Label;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\src\Services\Traits\HasQuery;

class LabelService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
        create as queryCreate;
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
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        return $this->queryOne($where, array_merge([
            'exception' => function () {
                return new ModelNotFoundException(trans('Label未找到'));
            },
        ], $options));
    }


    /**
     * @param array $data
     * @param array $options
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data, array $options = [])
    {
        return $this->queryCreate($data, $options);
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
