<?php

namespace Modules\Core\Services\Frontend;

use Modules\Core\Models\Frontend\Label;
use Modules\Core\Services\Traits\HasListConfig;

class LabelService
{
    use HasListConfig;

    /**
     * @var string
     */
    protected $key = 'core::label';
    
    /**
     * @param $label
     * @param array $options
     * @return Label
     */
    public function getLabelInfoByLabel($label, array $options = [])
    {
        $label = $this->getByKey($label, $options);

        return $label['value'] ?? null;
    }
}
