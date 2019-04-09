<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//ftp服务器配置
$config['FTP_HOST'] = "182.92.232.61";
$config['FTP_PORT'] = "21";
$config['FTP_USER'] = "chelabaadmin";
$config['FTP_PWD'] = "chelabaadmin";
//FTP用户默认目录为/home/www/zhuoli/projects/chelaba/uploads
$config['FTP_UPLOADS_DIR'] = "/";

$config['POINT_LOG']['exchange'] = '积分兑换';
$config['POINT_LOG']['order_offset'] = '抵扣订单金额';
$config['POINT_LOG']['sign'] = '签到';
$config['POINT_LOG']['share_lottery'] = '分享有礼';
$config['POINT_LOG']['sale_bidding'] = '成功报价';
$config['POINT_LOG']['sale_checking'] = '成功抢单';
$config['POINT_LOG']['sale_success'] = '成功卖车';
$config['POINT_LOG']['buy_success'] = '成功买车';
$config['POINT_LOG']['affiliate'] = '分销卖车';
$config['POINT_LOG']['system_change'] = '系统修改';
$config['POINT_LOG']['order_cancel'] = '积分订单取消';
$config['POINT_LOG']['share_register'] = '分享注册';



$config['POINT_ORDER']['POINT_ORDER_APPLY'] = 0;//订单未支付
$config['POINT_ORDER']['POINT_ORDER_PAYED'] = 1;//订单已经支付
$config['POINT_ORDER']['POINT_ORDER_SENDED'] = 2;//订单已经发货
$config['POINT_ORDER']['POINT_ORDER_FAILED'] = 9;//订单作废

//积分商品颜色信息,该配置不可修改
$config['POINT_GOODS_COLOR'][] = array("name" => "白色", "value" => "#FFFFFF");
$config['POINT_GOODS_COLOR'][] = array("name" => "黑色", "value" => "#000000");
$config['POINT_GOODS_COLOR'][] = array("name" => "灰色", "value" => "#999999");
$config['POINT_GOODS_COLOR'][] = array("name" => "红色", "value" => "#FF0000");
$config['POINT_GOODS_COLOR'][] = array("name" => "黄色", "value" => "#FFFF00");
$config['POINT_GOODS_COLOR'][] = array("name" => "绿色", "value" => "#00FF00");
$config['POINT_GOODS_COLOR'][] = array("name" => "蓝色", "value" => "#0000FF");
$config['POINT_GOODS_COLOR'][] = array("name" => "粉色", "value" => "#FF00FF");
$config['POINT_GOODS_COLOR'][] = array("name" => "天蓝", "value" => "#00FFFF");

//远程地址
$config['REMOTE_HOST'] = "www.ichelaba.com";
//$config['REMOTE_HOST'] = "localhost/git/zhuoli/projects/chelaba/public";

//商品订单状态
$config['GOODS_ORDER']['GOODS_ORDER_APPLY'] = '0'; //订单未支付
$config['GOODS_ORDER']['GOODS_ORDER_PAYED'] = '1'; //订单已经支付
$config['GOODS_ORDER']['GOODS_ORDER_SENDED'] = '2'; //订单已经发货
$config['GOODS_ORDER']['GOODS_ORDER_FAILED'] = '9'; //订单作废

//FCode状态
$config['FCODE_STATUS']['NOT_EXCHANGE'] = '0'; //未兑换
$config['FCODE_STATUS']['IS_EXCHANGE'] = '1'; //兑换

//支付对象类型
$config['PAY_TARGET_TYPE']['PAY_TARGET_DEPOSIT'] = 'deposit'; //支付对象类型，充值支付
$config['PAY_TARGET_TYPE']['PAY_TARGET_BIDDING_REWARD'] = 'bidding_reward'; //支付对象类型，比价赏金
$config['PAY_TARGET_TYPE']['PAY_TARGET_SALE_ORDER'] = 'sale_order'; //支付对象类型，特价订单
$config['PAY_TARGET_TYPE']['PAY_TARGET_BARGAIN'] = 'bargain'; //支付对象类型，人工砍价
$config['PAY_TARGET_TYPE']['PAY_TARGET_POINT'] = 'point'; //支付对象类型，积分支付
$config['PAY_TARGET_TYPE']['PAY_TARGET_GOODS'] = 'goods'; //商品订单支付类型

//退款审核阶段
$config['REFUND_STATUS'] = array(
	'REFUND_STATUS_CUSTOM_REFUNDING' => 0, //客服审核阶段
	'REFUND_STATUS_OPER_REFUNDING' => 1, //运营审核阶段
	'REFUND_STATUS_FINANCE_REFUNDING' => 2, //财务审核阶段
	'REFUND_STATUS_REFUND_DONE' => 3, //退款成功
	'REFUND_STATUS_REFUND_DISABLE' => 4 //退款失败，用户取消取消退款
);

//特价车订单状态
$config['SALE_ORDER_STATUS'] = array(
	'SALE_ORDER_STATUS_DISABLED' => 0, //销售订单状态，已取消
	'SALE_ORDER_STATUS_PAYING' => 1, //销售订单状态，等待付款
	'SALE_ORDER_STATUS_CHECKING' => 2, //销售订单状态，已付款，等待核销
	'SALE_ORDER_STATUS_CUSTOM_REFUNDING' => 10, //销售订单状态，正在退款，客服审核阶段
	'SALE_ORDER_STATUS_OPER_REFUNDING' => 11, //销售订单状态，正在退款，运营审核阶段
	'SALE_ORDER_STATUS_FINANCE_REFUNDING' => 12, //销售订单状态，正在退款，财务审核阶段
	'SALE_ORDER_STATUS_DONE' => 9 //销售订单状态，已完成
);

$config['WEIXIN'] = array(
	'public' => array(
		'token' => 'chelaba_public',
		'app_id' => 'wx6a0dd3f7b87725d6',
		'securet' => 'f08b9b2f2189aa61a1dab04492d3453e'
	),
	'saler' => array(
		'token' => 'chelaba_saler',
		'app_id' => 'wx8cd82b008d8da8a1',
		'securet' => 'ec4a66daab84b1e8a030f80dce32b99b'
	)
);

//订单实际打款状态
$config['DAKUAN_STATUS'] = array(
	'SALE_ORDER_NORMAL' => 0,//无需打款的状态
	'SALE_ORDER_DAKUAN' => 1, //打款
	'SALE_ORDER_SHOUKUAN' => 2, //收款
	'SALE_ORDER_FACHE' => 3, //发车
	'SALE_ORDER_SHOUCHE' => 4, //收车
	'SALE_ORDER_JIAOCHE' => 5, //交车
	'SALE_ORDER_SUBMIT' => 10 //确认
);

//按照颜色增减库存的车子
$config["COLOR_INC_DEC_SERIES_ID_LIST"] = array(
		305,//长安 欧力威X6
		1516,//艾瑞泽M7
		945,//瑞虎5
		1521,//欧力威
);

