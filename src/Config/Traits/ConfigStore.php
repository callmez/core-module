<?php

namespace Modules\Core\Config\Traits;

use InvalidArgumentException;
use Modules\Core\Models\Config;
use Illuminate\Filesystem\Filesystem;

trait ConfigStore
{
    /**
     * @return Config
     */
    public function getModel()
    {
        return Config::class;
    }

    public function loadSettingsFromCachedFile()
    {
        $path = $this->getSettingsCachedPath();

        if (file_exists($path)) {
            $this->set(require $path);
        }
    }

    public function getSettingsCachedPath()
    {
        return storage_path('framework/config.php');
    }

    public function cacheSettingsToFile()
    {
        $modelClass = $this->getModel();

        $items = [];
        foreach ($modelClass::all() as $setting) {
            if (! empty($setting->module)) {
                $items[$setting->module . '::'] = array_merge($items[$setting->module . '::'] ?? [], [
                    $setting->key => $setting->value
                ]);
            } else {
                $items[$setting->key] = $setting->value;
            }
        }

        $path = $this->getSettingsCachedPath();

        resolve(Filesystem::class)
            ->put($path, '<?php return ' . var_export($items, true) . ';' . PHP_EOL);
    }

    public function store($key, $value = null, $refreshCache = true)
    {
        if (is_array($key)) {
            $keys = $key;

            if ($value != null) {
                $refreshCache = boolval($value);
            }
        } else {
            $keys = [$key => $value];
        }

        $modelClass = $this->getModel();

        foreach ($keys as $key => $value) {
            if (strpos($key, '.') !== false) {
                throw new InvalidArgumentException('Config only support store one-level settings(key without ".").');
            }
            list($module, $key) = explode('::', $key);

            $model = $modelClass::firstOrNew([
                'key' => $key,
                'module' => $module,
            ]);
            $model->value = $value;
            $model->saveOrFail();
        }

        $this->set($keys);

        if ($refreshCache) {
            $this->cacheSettingsToFile();
        }
    }
}
