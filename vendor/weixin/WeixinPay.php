<?php

class WeixinPay
{
    protected $token;
    protected $appId;
    protected $securet;
    protected $mchId;
    protected $notifyUrl;
    protected $apiSecuret;

    public function __construct($configs)
    {
        $this->token = $configs['token'];
        $this->appId = $configs['appId'];
        $this->securet = $configs['securet'];
        $this->mchId = $configs['mch_id'];
        $this->notifyUrl = $configs['notify_url'];
        $this->apiSecuret = $configs['api_securet'];
    }

    /**
     * 将参数转成xml格式
     */
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
             if (is_numeric($val))
             {
                $xml.="<".$key.">".$val."</".$key.">";

             }
             else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 生成支付二维码URL（NATIVE支付 模式一）
     */
    public function getPaySignPackage($porductId)
    {
        $timestamp = time();
        //生成随机数
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();

        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $stringA = "appid=$this->appId&mch_id=$this->mchId&nonce_str=$nonceStr&product_id=$porductId&time_stamp=$timestamp";
        $stringSignTemp = "$stringA&key=$this->apiSecuret";
        $sign = strtoupper(MD5($stringSignTemp));

        $qrUrl = "weixin://wxpay/bizpayurl?$stringA&sign=$sign";

        return $qrUrl;
    }

    /**
     * 调用统一下单接口
     */
    public function unifiedOrder($outTradeNo, $body, $totalFee, $tradeType)
    {
        $params = array();
        $createIp = $_SERVER['REMOTE_ADDR'];
        $notifyUrl = $this->notifyUrl;
        //生成随机数
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();
        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $stringA  = "appid=$this->appId";
        $stringA .= "&body=$body";
        $stringA .= "&mch_id=$this->mchId";
        $stringA .= "&nonce_str=$nonceStr";
        $stringA .= "&notify_url=$notifyUrl";
        if ($tradeType == 'JSAPI') {
            $openId = getSession('weixin_openid');
            $stringA .= "&openid=$openId";
            $params['openid'] = $openId;
        }
        $stringA .= "&out_trade_no=$outTradeNo";
        $stringA .= "&spbill_create_ip=$createIp";
        $stringA .= "&total_fee=$totalFee";
        $stringA .= "&trade_type=$tradeType";
        $stringSignTemp = "$stringA&key=$this->apiSecuret";
        $sign = strtoupper(MD5($stringSignTemp));

        // 整理参数，调用接口
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $params['appid'] = $this->appId;
        $params['body'] = $body;
        $params['mch_id'] = $this->mchId;
        $params['nonce_str'] = $nonceStr;
        $params['notify_url'] = $notifyUrl;
        $params['out_trade_no'] = $outTradeNo;
        $params['spbill_create_ip'] = $createIp;
        $params['total_fee'] = $totalFee;
        $params['trade_type'] = $tradeType;
        $params['sign'] = $sign;
        $params = $this->arrayToXml($params);

        $url .= '?cache='.microtime(1);
        $res = getHtml($url, array(
            'source_charset' => 'utf-8',
            'charset' => 'utf-8',
            'method' => 'POST',
            'content' => $params
        ));
        // $res = iconv('UTF-8', 'GBK//IGNORE', $res); // 报错时转码用来查看错误信息

        // 解析返回结果
        $p = xml_parser_create();
        xml_parse_into_struct($p, $res, $vals, $index);
        xml_parser_free($p);


        $result = array();
        foreach ($vals as $value) {
            if ($value['tag'] == 'RESULT_CODE' && $value['value'] == 'SUCCESS') {
                $result['result_code'] = 'success';
            }
            if ($value['tag'] == 'PREPAY_ID') {
                $result['prepay_id'] = $value['value'];
            }
            if ($value['tag'] == 'CODE_URL') {
                $result['code_url'] = $value['value'];
            }
        }
        return $result;
    }

    /**
     * 普通红包接口
     */
    public function sendredpackold($actName, $remark, $reOpenid, $sendName, $totalAmount, $totalNum, $wishing)
    {
        $params = array();
        $createIp = $_SERVER['REMOTE_ADDR'];
        $notifyUrl = $this->notifyUrl;
        //生成随机数
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();
        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $mchBillno = $this->mchId . date('YmdHis') . rand(1000, 9999);
        $stringA  = "act_name=$actName";
        $stringA .= "&client_ip=$createIp";
        $stringA .= "&mch_billno=$mchBillno";
        $stringA .= "&mch_id=$this->mchId";
        $stringA .= "&nonce_str=$nonceStr";
        $stringA .= "&re_openid=$reOpenid";
        $stringA .= "&remark=$remark";
        $stringA .= "&send_name=$sendName";
        $stringA .= "&total_amount=$totalAmount";
        $stringA .= "&total_num=$totalNum";
        $stringA .= "&wishing=$wishing";
        $stringA .= "&wxappid=$this->appId";
        $stringSignTemp = "$stringA&key=$this->apiSecuret";
        $sign = strtoupper(MD5($stringSignTemp));

        // 整理参数，调用接口
        $params['act_name'] = $actName;
        $params['client_ip'] = $createIp;
        $params['mch_billno'] = $mchBillno;
        $params['mch_id'] = $this->mchId;
        $params['nonce_str'] = $nonceStr;
        $params['remark'] = $remark;
        $params['re_openid'] = $reOpenid;
        $params['send_name'] = $sendName;
        $params['total_amount'] = $totalAmount;
        $params['total_num'] = $totalNum;
        $params['wishing'] = $wishing;
        $params['wxappid'] = $this->appId;
        $params['sign'] = $sign;
        $postXml = $this->arrayToXml($params);

        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $responseXml = $this->curl_post_ssl($url, $postXml);
        //用作结果调试输出
        var_dump($responseXml);
        $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $responseObj->return_code;
    }

    /**
     * actName: 活动名称
     * remark: 备注
     * reOpenid: 用户openid
     * sendName: 商户名称
     * wishing: 红包祝福语
     */
    public function sendredpack($actName, $remark, $reOpenid, $sendName, $totalAmount, $totalNum, $wishing)
    {
        $params = array();
        $createIp = $_SERVER['REMOTE_ADDR'];
        $notifyUrl = $this->notifyUrl;
        //生成随机数
        include 'D:/wamp/www/htqcadmin/vendor/weixin/Weixin.php';
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();

        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $mchBillno = $this->mchId . date('YmdHis') . rand(1000, 9999);

        // 整理参数，调用接口
        $params['act_name'] = $actName;
        $params['client_ip'] = $createIp;
        $params['mch_billno'] = $mchBillno;
        $params['mch_id'] = $this->mchId;
        $params['nonce_str'] = $nonceStr;
        $params['remark'] = $remark;
        $params['re_openid'] = $reOpenid;
        $params['send_name'] = $sendName;
        $params['wishing'] = $wishing;
        $params['wxappid'] = $this->appId;
        $params['total_amount'] = $totalAmount;
        $params['total_num'] = $totalNum;
        $sign = $this->getSign($params);
        $params['sign'] = $sign;
        $postXml = $this->arrayToXml($params);

        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $responseXml = $this->curl_post_ssl($url, $postXml);
       // return $responseXml;
        //用作结果调试输出
         echo($responseXml);
        $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);

        return $responseObj->return_code;
    }

    function getSign($params)
    {
        $buff = "";
        ksort($params);
        foreach ($params as $key => $value) {
            $buff .= $key . "=" . $value . "&";
        }
        $res = '';
        if (strlen($buff) > 0) {
            $res = substr($buff, 0, strlen($buff)-1) . "&key=$this->apiSecuret";
            $res = strtoupper(MD5($res));
        }
        return $res;
    }

    function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
    {
        //echo getcwd().'/apiclient_cert.pem';exit;
        $myurl = "D:/wamp/www/htqcadmin/application/controllers";
        if (!file_exists($myurl.'/apiclient_cert.pem') || !file_exists($myurl.'/apiclient_key.pem') || !file_exists($myurl.'/rootca.pem')) {
            exit('file is not exists!');
        }
    
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);

        //以下两种方式需选择一种
        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,$myurl.'/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,$myurl.'/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO,$myurl.'/rootca.pem');

        //第二种方式，两个文件合成一个.pem文件
        // curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);

        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 生成JSAPI所需签名
     */
    public function createSign($prepayId)
    {
        $timestamp = time();
        //生成随机数
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();
        $package = 'prepay_id=' . $prepayId;
        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $stringA = "appId=$this->appId&nonceStr=$nonceStr&package=$package&signType=MD5&timeStamp=$timestamp";
        $stringSignTemp = "$stringA&key=$this->apiSecuret";
        $sign = strtoupper(MD5($stringSignTemp));

        return $data = array('appid' => $this->appId, 'sign' => $sign, 'nonceStr' => $nonceStr);
    }

    /**
     * 支付结果通知
     */
    public function notifyResult($notify)
    {
        if (empty($notify)) return FALSE;

        $result = array();
        // 解析返回结果
        $p = xml_parser_create();
        xml_parse_into_struct($p, $notify, $vals, $index);
        xml_parser_free($p);
        foreach ($vals as $value) {
            if ($value['tag'] == 'RESULT_CODE' && $value['value'] == 'SUCCESS') {
                $result['result_code'] = 'success';
            }
            if ($value['tag'] == 'OPENID') {
                $result['openid'] = $value['value'];
            }
            if ($value['tag'] == 'TRANSACTION_ID') {
                $result['transaction_id'] = $value['value'];
            }
            if ($value['tag'] == 'OUT_TRADE_NO') {
                $result['out_trade_no'] = $value['value'];
            }
        }

        return $result;
    }

    /**
     * 调用查询订单接口
     */
    public function orderQuery($transactionId = '', $outTradeNo = '')
    {
        $flag = 'false'; // 用来检查是否具备调用接口的条件（$transactionId和$outTradeNo不同时为''）
        $params = array(); // 传参数组
        //生成随机数
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();

        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $stringA = "appid=$this->appId&mch_id=$this->mchId&nonce_str=$nonceStr";
        if ($outTradeNo != '') {
            $stringA .= "&out_trade_no=$outTradeNo";
            $params['out_trade_no'] = $outTradeNo;
            $flag = 'true';
        }
        if ($transactionId != '') {
            $stringA .= "&transaction_id=$transactionId";
            $params['transaction_id'] = $transactionId;
            $flag = 'true';
        }
        if ($flag == 'false') return FALSE;
        $stringSignTemp = "$stringA&key=$this->apiSecuret";
        $sign = strtoupper(MD5($stringSignTemp));

        // 整理参数，调用接口
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        $params['appid'] = $this->appId;
        $params['mch_id'] = $this->mchId;
        $params['nonce_str'] = $nonceStr;
        $params['sign'] = $sign;
        $params = $this->arrayToXml($params);
        $res = getHtml($url, array(
            'method' => 'POST',
            'content' => $params
        ));

        return $res;
    }

    /**
     * 调用申请退款接口
     */
    public function refund($transactionId = '', $outTradeNo = '', $refundFee, $totalFee)
    {
        $flag = 'false'; // 用来检查是否具备调用接口的条件（$transactionId和$outTradeNo不同时为''）
        $params = array(); // 传参数组
        //生成随机数
        $weixin = new Weixin($this->token, $this->appId, $this->securet);
        $nonceStr = $weixin->createNonceStr();
        // 生成签名
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $stringA  = "appid=$this->appId";
        $stringA .= "&mch_id=$this->mchId";
        $stringA .= "&nonce_str=$nonceStr";
        $stringA .= "&op_user_id=$this->mchId";  // 操作员ID，默认用商户号
        if ($outTradeNo != '') {
            $stringA .= "&out_trade_no=$outTradeNo";
            $params['out_trade_no'] = $outTradeNo;
            $flag = 'true';
        }
        $stringA .= "&refund_fee=$createIp";
        $stringA .= "&total_fee=$totalFee";
        if ($transactionId != '') {
            $stringA .= "&transaction_id=$transactionId";
            $params['transaction_id'] = $transactionId;
            $flag = 'true';
        }
        if ($flag == 'false') return FALSE;
        $stringSignTemp = "$stringA&key=$this->apiSecuret";
        $sign = strtoupper(MD5($stringSignTemp));

        // 整理参数，调用接口
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        $params['appid'] = $this->appId;
        $params['mch_id'] = $this->mchId;
        $params['nonce_str'] = $nonceStr;
        $params['op_user_id'] = $this->mchId;
        $params['refund_fee'] = $tradeType;
        $params['total_fee'] = $totalFee;
        $params['sign'] = $sign;
        $params = $this->arrayToXml($params);

        $res = getHtml($url, array(
            'method' => 'POST',
            'content' => $params
        ));
    }
}
