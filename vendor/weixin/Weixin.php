<?php

// require_once dirname(dirname(dirname(__FILE__))) . '/system/database/DB.php';
class Weixin
{
    protected $token;
    protected $appId;
    protected $securet;
    protected $db;

    public function __construct($token, $appId, $securet, $db)
    {
        $this->token = $token;
        $this->appId = $appId;
        $this->securet = $securet;
        $this->db = $db;
    }

    public function getAccessToken($flag = false)
    {
		$accessToken = '';
        if ($flag) {
    		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
    		$url .= '&appid=' . $this->appId . '&secret=' . $this->securet;
    		$html = getHtml($url);
    		if (!empty($html)) {
    			$result = json_decode($html, true);
    			$accessToken = get($result, 'access_token', '');
    		}
        } else {
            $cache = $this->db->select()
                              ->where('key_name', 'access_token')
                              ->get('token_cache')
                              ->row_array();
            if (!empty($cache)) {
                $now = time();
                $cacheTime = strtotime($cache['added_time']);
                if (($now - $cacheTime) < 1000) {
                    $result = json_decode($cache['key_value'], true);
                    $accessToken = get($result, 'access_token', '');
                } else {
                    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
                    $url .= '&appid=' . $this->appId . '&secret=' . $this->securet;
                    $html = getHtml($url);

                    if (!empty($html)) {
                        // $create_time = date('Y-m-d H:i:s');
                        // $upsql = "UPDATE cma_weixin_cache set key_value = '$html', create_time = '$create_time' WHERE key_name = 'access_token'";
                        // $this->model->query($upsql);
                        $data = array(
                                    'key_value' => $html,
                                    'added_time' => date('Y-m-d H:i:s')
                                );
                        $this->db->where('key_name', 'access_token')
                                 ->update('token_cache', $data);

                        $result = json_decode($html, true);
                        $accessToken = get($result, 'access_token', '');
                    }
                }
            }
        }
        return $accessToken;
    }

    public function createTicket($param) {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$accessToken";
        if (empty($param)) {
            return false;
        }
        $param = urldecode(json_encode($this->url_encode($param)));
        $res = getHtml($url, array(
            'source_charset' => 'utf-8',
            'charset' => 'utf-8',
            'method' => 'POST',
            'content' => $param
        ));

        return $res;
    }

    public function createMenu($menu)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken";
        if (empty($menu)) {
            return false;
        }
        $menu = urldecode(json_encode($this->url_encode($menu)));
        $res = getHtml($url, array(
            'method' => 'POST',
            'content' => $menu
        ));
        print_r($res);
    }

    /**
     * 通过openID获得用户基本信息
     */
    public function getUserInfoList($openidList)
    {
        $accessToken = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=$accessToken";
        if (empty($openidList)) {
            return false;
        }
        $openidList = urldecode(json_encode($this->url_encode($openidList)));
        $res = getHtml($url, array(
            'method' => 'POST',
            'content' => $openidList
        ));
        print_r($res);
    }

    /**
     * 通过openID获得用户基本信息
     */
    public function getUserInfo($openId)
    {
        if (empty($openId)) {
            return false;
        }
        $accessToken = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openId&lang=zh_CN";

        $res = getHtml($url, array(
            'source_charset' => 'utf-8',
            'charset' => 'utf-8'
        ));
        $result = json_decode($res, true);
        if (!empty($result['errcode']) && $result['errcode'] == 40001) {
            $accessToken = $this->getAccessToken(false);
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openId&lang=zh_CN";
            $res = getHtml($url, array(
                'source_charset' => 'utf-8',
                'charset' => 'utf-8'
            ));
        }

        return $res;
    }

    public function getOauth20Url($redirect)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appId;
        $url .= '&redirect_uri=' . urlencode($redirect);
        $url .= '&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
        return $url;
    }

    /**
     * 获得AccessToken
     */
    public function getScopeAccessToken($code)
    {
        if (empty($code)) {
            return false;
        }
        $appId = $this->appId;
        $secret = $this->securet;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appId&secret=$secret&code=$code&grant_type=authorization_code";
        // $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId;
        // $url .= '&secret=' . $this->securet;
        // $url .= '&code=' . $code;
        // $url .= '&grant_type=authorization_code';

        $res = getHtml($url, array(
            'source_charset' => 'utf-8',
            'charset' => 'utf-8'
        ));

        return $res;
    }

    /**
     * 通过openId以snsapi_userinfo的方式获得用户信息
     */
    public function getUserInfoWithScope($openId, $accessToken)
    {
        if (empty($openId) || empty($accessToken)) {
            return false;
        }

        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken&openid=$openId&lang=zh_CN";

        $res = getHtml($url, array(
            'source_charset' => 'utf-8',
            'charset' => 'utf-8'
        ));

        return $res;
    }


    public function getOpenidFromOauth20Code($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId;
        $url .= '&secret=' . $this->securet;
        $url .= '&code=' . $code;
        $url .= '&grant_type=authorization_code';
        $html = getHtml($url);
        $openid = '';
        if (!empty($html)) {
            $result = json_decode($html, true);
            $openid = get($result, 'openid', '');
        }
        return $openid;
    }

    public function parseMsg()
    {
        $postStr = get($GLOBALS, "HTTP_RAW_POST_DATA");
        if (empty($postStr)) {
            return false;
        }

        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $ret = array();
        $ret['app_name'] = strval($postObj->ToUserName);
        $ret['openid'] = strval($postObj->FromUserName);
        $ret['msg_type'] = strval($postObj->MsgType);
        if ($ret['msg_type'] == 'text') {
            $ret['keyword'] = trim($postObj->Content);
        } elseif ($ret['msg_type'] == 'event') {
            $ret['event'] = strval($postObj->Event);
        }
        return $ret;
    }

    public function responseMsg($appName, $openid, $msgType, $content)
    {
        $textTpl = '<xml>';
        $textTpl .= '<ToUserName><![CDATA[%s]]></ToUserName>';
        $textTpl .= '<FromUserName><![CDATA[%s]]></FromUserName>';
        $textTpl .= '<CreateTime>%s</CreateTime>';
        $textTpl .= '<MsgType><![CDATA[%s]]></MsgType>';
        $textTpl .= '<Content><![CDATA[%s]]></Content>';
        $textTpl .= '<FuncFlag>0</FuncFlag>';
        $textTpl .= '</xml>';
        $resultStr = sprintf($textTpl, $openid, $appName, APP_SYS_TIME, $msgType, $content);
        echo $resultStr;
        exit();
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            'jsapiTicket' => $jsapiTicket,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    public function getJsApiTicketCacheKey()
    {
        return 'weixin_jsapiticket_' . $this->appId;
    }

    public function refreshJsApiTicket()
    {
        $cache = getCache();
        $cacheKey = $this->getJsApiTicketCacheKey();
        $cache->remove($cacheKey);
        return $this->getJsApiTicket();
    }

    public function getJsApiTicket($flag = false)
    {
        $jsApiTicke = '';
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=' . $accessToken;
        $html = getHtml($url);
        if (!empty($html)) {
            $result = json_decode($html, true);
            $jsApiTicke = get($result, 'ticket', '');
        }

        return $jsApiTicke;
    }

    public function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    // json_encode 中文变Unicode的问题
    public function url_encode($str)
    {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[urlencode($key)] = $this->url_encode($value);
            }
        } else {
            $str = urlencode($str);
        }

        return $str;
    }

    /**
     * 向销售顾问端发送模板消息
     * @param array $msg
     */
    public function salerTplMsg($msg)
    {
		$weixin_conf = WEIXIN();
        $saler = $weixin_conf['saler'];
        $this->tplMsg($saler, $msg);
    }

    /**
     * 向用户端发送模板消息
     * @param array $msg $msg包含的元素有openId、tplId等
     */
    public function userTplMsg($msg)
    {
		$weixin_conf = WEIXIN();
        $public = $weixin_conf['public'];
        $this->tplMsg($public, $msg);
    }

    /**
     * 推送微信
     * @param array $mp
     * @param array $msg
     */
    private function tplMsg($mp, $msg)
    {
        $this->appId = $mp['app_id'];
        $this->securet = $mp['securet'];
        $this->token = $mp['token'];
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $accessToken;
        $params = $this->tplData($msg);
        $res = getHtml($url, array('content' => json_encode($params, JSON_UNESCAPED_UNICODE), 'method' => 'post'));
		$result = json_decode($res, true);
		if($result['errcode'] == 40001){
			$accessToken = $this->getAccessToken(false);
			$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $accessToken;
			$res = getHtml($url, array('content' => json_encode($params, JSON_UNESCAPED_UNICODE), 'method' => 'post'));
		}
	}

    /**
     * 生成微信通知数据参数
     * @param array $msg
     * @return multitype:string unknown multitype:string unknown  multitype:string Ambigous <mixed, unknown>
     */
    private function tplData($msg)
    {
        $params['touser'] = $msg['toUser'];
        $params['url'] = $msg['url'];
        $params['topcolor'] = !empty($msg['topColor']) ? $msg['topColor'] : '#000000';
        $data['first'] = array('value' => $msg['first'], 'color' => '#000000');

        switch($msg['tplId'])
        {
            //定单支付成功向客户微信端推送信息
            case 1:
                $params['template_id'] = '-lgh-YPgMptRwbnSuR0Gt9OZhfbT_koLrvqHtvmKWV0';
                $data['orderMoneySum'] = array('value' => $msg['orderMoneySum'], 'color' => '#000000');
                $data['orderProductName'] = array('value' => $msg['orderProductName'], 'color' => '#000000');
                $data['Remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;
            //定单生成之后向客户端微信推送信息
            case 2:
                $params['template_id'] = 'PVEW_ApmHaNinmJ46jp6eQYCPFeWmqbBChJbMH90MxE';
                $data['orderProductPrice'] = array('value' => $msg['orderMoneySum'], 'color' => '#000000');
                $data['orderProductName'] = array('value' => $msg['orderProductName'], 'color' => '#000000');
                $data['orderAddress'] = array('value' => $msg['orderAddress'], 'color' => '#000000');
                $data['orderName'] = array('value' => $msg['orderId'], 'color' => '#000000');
                $data['Remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;


            //核销成功之后向客户端推送微信信息
            case 5:
            //销售顾问报价之后向客户端微信推送信息
            case 3:
                $params['template_id'] = '7AwMsPXnNNaqPVZt7QVfW1GRDYn9bZjeiWpopWGD5fc';
                $data['OrderSn'] = array('value' => $msg['orderId'], 'color' => '#000000');
                $data['OrderStatus'] = array('value' => $msg['orderStatus'], 'color' => '#000000');
                $data['Remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;
            //退款成功之后向客户端微信推送信息
            case 4:



            //顾客付款成功后，提示销售顾问参与竞价，向销售顾问微信端推送信息
            case 11:
                // $params['template_id'] = 'qaybPPw3qLkLa1qtjnn-5KW8kcGkoDPXp8pekAvlPpI';
                $params['template_id'] = 'ZQUXBU7ePCyW5bCbeKoe1hSOK9rVj71XhQQVCobk5OY';
                $data['keyword1'] = array('value' => $msg['keyword1'], 'color' => '#000000');
                $data['keyword2'] = array('value' => $msg['keyword2'], 'color' => '#000000');
                $data['keyword3'] = array('value' => $msg['keyword3'], 'color' => '#000000');
                $data['keyword4'] = array('value' => $msg['keyword4'], 'color' => '#000000');
                $data['keyword5'] = array('value' => $msg['keyword5'], 'color' => '#000000');
                $data['Remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;
            //顾客选择该销售顾问的报价之后向销售顾问端微信推送信息
            case 12:
                $params['template_id'] = 'T2InPl9bzGQid2LpphDuJhd525FZGI95kQSMhyoOavg';
                $data['keyword1'] = array('value' => $msg['time'], 'color' => '#000000');
                $data['keyword2'] = array('value' => $msg['productName'], 'color' => '#000000');
                $data['keyword3'] = array('value' => $msg['orderId'], 'color' => '#000000');
                $data['Remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;
            //顾客评价和打赏成功之后向销售顾问端微信推送信息
            case 14:
            //核销成功之后向销售顾问端微信推送信息
            case 13:
                $params['template_id'] = '5kMXiWuX0B4fzxGgQliq0RJatgQznv7iL6IC196yGQQ';
                $data['keyword1'] = array('value' => $msg['orderId'], 'color' => '#000000');
                $data['keyword2'] = array('value' => $msg['orderStatus'], 'color' => '#000000');
                $data['keyword3'] = array('value' => $msg['time'], 'color' => '#000000');
                $data['Remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;
			//系统发送消息给销售顾问或者用户
			case 15:
				// $params['template_id'] = 'Yypz2r-OKbR6JQxZZakpdTz2T6r8SLNdjMS4GyG5Ymk';
				// $data['keyword1'] = array('value' => $msg['system_msg'], 'color' => '#000000');
    //             $data['keyword2'] = array('value' => $msg['system_time'], 'color' => '#000000');
    //             $data['remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
    //             $params['data'] = $data;
    //             return $params;
                $params['template_id'] = '_SUHMetBv-0ogZIPg3DnrNptF_46gM9jNMinfGAfT9E';
                $data['keyword1'] = array('value' => $msg['keyword1'], 'color' => '#000000');
                $data['keyword2'] = array('value' => '成功', 'color' => '#000000');
                $data['keyword3'] = array('value' => $msg['keyword3'], 'color' => '#000000');
                $data['keyword4'] = array('value' => '辛勤工作的您', 'color' => '#000000');
                $data['remark'] = array('value' => !empty($msg['remark']) ? $msg['remark'] : '', 'color' => '#000000');
                $params['data'] = $data;
                return $params;
        }
    }
}
