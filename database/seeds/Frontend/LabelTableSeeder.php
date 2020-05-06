<?php
/**
 * Created by PhpStorm.
 * User: aoxiang
 * Date: 2020-05-06
 * Time: 11:10
 */

namespace Modules\Core\database\seeds\Frontend;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\src\Models\Frontend\Label;

class LabelTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {

        // Add the master administrator, user id of 1
        $labelList = [
            [
                'label'  => 'system.recharge.explain',
                'info'   => '温馨提示：
1、最小充值额度为{recharge_min}{symbole}
2、向上述地址转入非{symbole}资产，否则将不可找回',
                'remark' => '充币说明',
            ],
            [
                'label'  => 'system.withdraw.explain',
                'info'   => '温馨提示：
1、最小提币数量为:{withdraw_min}{symbol}',
                'remark' => '提币说明',
            ],
            [
                'label'  => 'system.auth.explain',
                'info'   => '1.所有图片禁止含有其他水印，否则无法通过；
2.拍照请勿拍的太远，否则看不清字面将无法通过审核；单张照片请勿超过1MB大小，建议使用截图，点击提交认证后请耐心等待上传，请勿重复点击。',
                'remark' => '提交实名认证说明',
            ],
            [
                'label'  => 'user.Invitation.title',
                'info'   => '注册就送50 Token',
                'remark' => '邀请页面标题内容',
            ],
            [
                'label'  => 'system.internal_trade.user',
                'info'   => '注意：
1、内部转账是实时到账
2、转账成功后将不可退回',
                'remark' => '内部转账页面一',
            ],
            [
                'label'  => 'system.internal_trade.aaaa',
                'info'   => '注意：
1、内部转账是实时到账
2、转账成功后将不可退回',
                'remark' => '内部转账页面二',
            ],
            [
                'label'  => 'otc.rule',
                'info'   => '交易挖矿规则内容',
                'remark' => '',
            ],
            [
                'label'  => 'system.share.poster',
                'info'   => '[{"img":"https://zkipfs.oss-ap-southeast-1.aliyuncs.com/22306bbcd9de365d318030338e6bee34.png","sort":0},{"img":"https://zkipfs.oss-ap-southeast-1.aliyuncs.com/0d53fa2b63a7422c13b96da572387441.png","sort":0}]',
                'remark' => '分享海报路径，多张json格式',
            ],
            [
                'label'  => 'system.start.poster',
                'info'   => '[{"img":"https://zkipfs.oss-ap-southeast-1.aliyuncs.com/86bd3fdd35dcd68c9ecc3db34dc8bd76.png","sort":1},{"img":"https://zkipfs.oss-ap-southeast-1.aliyuncs.com/9af794a3c4dbbaac1f321375204f3ae2.png","sort":2},{"img":"https://zkipfs.oss-ap-southeast-1.aliyuncs.com/a9d3afa9d814c7944bec96a1da55f5ae.png","sort":3}] ',
                'remark' => '启动画面路径',
            ],
            [
                'label'  => 'shop.top.slide',
                'info'   => '[{ img: "https://oss.zkipfs.com/huimin/bn1.png", sort: 1 },{ img: "https://oss.zkipfs.com/huimin/bn2.png", sort: 2 }]',
                'remark' => '商城头部幻灯片',
            ],
            [
                'label'  => 'system.share.remark',
                'info'   => '',
                'remark' => '',
            ],
            [
                'label'  => 'flash.exchange.explain',
                'info'   => '1、汇率变动,以实际到账数量为准
2、非矿机拥有用户交易手续费50%
3、普通矿工交易手续费30%
4、一星矿商交易手续费28%
5、二星矿商交易手续费25%
6、三星矿商交易手续费22%
7、四星矿商交易手续费20%
8、五星矿商交易手续费15%',
                'remark' => '兑换说明',
            ],

        ];
        foreach ($labelList as $label) {
            Label::create($label);
        }

    }
}