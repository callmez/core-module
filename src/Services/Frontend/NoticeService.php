<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 14:34
 */

namespace Modules\Core\src\Services\Frontend;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\src\Services\Traits\HasQuery;
use Modules\Core\src\Models\Frontend\Notice;

class NoticeService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
        create as queryCreate;
    }

    /**
     * @var Notice
     */
    protected $model;

    public function __construct(Notice $model)
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
                return new ModelNotFoundException(trans('公告未找到'));
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

}