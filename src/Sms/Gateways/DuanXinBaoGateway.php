<?php

namespace Modules\Sms\Gateways;

use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Traits\HasHttpRequest;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class DuanXinBaoGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'http://api.smsbao.com/sms';

    /**
     * @var array
     */
    protected $statuses = [
        "0" => "短信发送成功",
        "-1" => "参数不全",
        "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "密码错误",
        "40" => "账号不存在",
        "41" => "余额不足",
        "42" => "帐户已过期",
        "43" => "IP地址限制",
        "50" => "内容含有敏感词"
    ];

    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $endpoint = $config->get('endpoint', static::ENDPOINT_URL);
        $user = $config->get('user');
        $pass = md5($config->get('pass'));
        $phone = $to;
        $content = urlencode($message->getContent($this));
        $url = $endpoint . "?u=" . $user . "&p=" . $pass . "&m=" . $phone . "&c=" . $content;

        $result = file_get_contents($url);

        if ($result != 0) {
            throw new GatewayErrorException($this->statuses[$result] ?? 'error', $result, $result);
        }


        return $result;
    }
}
