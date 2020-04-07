<?php

namespace Modules\Core\Http\Controllers;

use Exception;
use Modules\Core\Captcha\Captcha;

class CaptchaController extends Controller
{
    /**
     * get CAPTCHA
     *
     * @param Captcha $captcha
     * @param string $config
     * @return array|mixed
     * @throws Exception
     */
    public function captcha(Captcha $captcha, string $config = 'default')
    {
        if (ob_get_contents()) {
            ob_clean();
        }

        return $captcha->create($config);
    }

    /**
     * get CAPTCHA api
     *
     * @param Captcha $captcha
     * @param string $config
     * @return array|mixed
     * @throws Exception
     */
    public function captchaApi(Captcha $captcha, string $config = 'default')
    {
        return $captcha->create($config, true);
    }
}
