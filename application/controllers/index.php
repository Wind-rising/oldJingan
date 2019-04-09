<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require dirname(dirname(dirname(__FILE__))) . '/vendor/weixin/Weixin.php';

class Index extends My_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
    }

    protected $allAightAnswerList = array(
                                0 => array( // 第一关答案
                                        0 => 0,
                                        1 => 1,
                                        2 => 0,
                                        3 => 1,
                                        4 => 1,
                                        5 => 2,
                                        6 => 2,
                                        7 => 2,
                                        8 => 1,
                                        9 => 2,
                                        10 => 1,
                                        11 => 0,
                                        12 => 0,
                                        13 => 0,
                                        14 => 1,
                                        15 => 0,
                                        16 => 1,
                                        17 => 0,
                                        18 => 0,
                                        19 => 0,
                                        20 => 1,
                                        21 => 0,
                                        22 => 0,
                                        23 => 1,
                                        24 => 1,
                                        25 => 0,
                                        26 => 3,
                                        27 => 1,
                                        28 => 0,
                                        29 => 3
                                    ),

                            );

    protected function checkAnswer($checkPointId, $answerList)
    {
        $rightAnswerList = $allAightAnswerList[$checkPoint];
        $point = 0;
        foreach ($answerList as $ak => $av) {
            if ($av['selectID'] == $rightAnswerList[$av['questionID']]) {
                $point = $point + 10;
            }
        }

        return $point;
    }

    public function test()
    {
        $t = array(
                'checkpointID' => 0,
                'answer' => array(
                                0 => array(
                                        'questionID' => 0,
                                        'selectID' => 1
                                    )
                            )
            );
    }

    public function index()
    {
        $openid = $this->getWeixinInfo();
        echo $openid;
    }

    protected function getWeixinInfo()
    {
        $wxConfig = config_item('weixin');

        if (!isWeixinRequest()) {
            return;
        }
        $weixin = new Weixin($wxConfig['token'], $wxConfig['app_id'], $wxConfig['securet'], $this->db);
        $code = requestGetString('code');
        $openid = $weixin->getOpenidFromOauth20Code($code);
        return $openid;
        if (!empty($openid)) {
            $openidList = array(
                'user_list' => array(
                    0 => array(
                        'openid' => $openid
                    )
                )
            );
            $userJson = $weixin->getUserInfoList($openidList);
            $userInfo = json_decode($userJson, true);
            if (!empty($userInfo['user_info_list'][0])) {
                $weixinInfo = $userInfo['user_info_list'][0];
                $_SESSION['nick_name'] = $weixinInfo['nickname'];
                $_SESSION['headimg'] = $weixinInfo['headimgurl'];
            }

            return $userInfo;
        }
    }

    protected function post($url, $data)
    {
       //初使化init方法
       $ch = curl_init();
       //指定URL
       curl_setopt($ch, CURLOPT_URL, $url);
       //设定请求后返回结果
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       //声明使用POST方式来进行发送
       curl_setopt($ch, CURLOPT_POST, 1);
       //发送什么数据呢
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

       //忽略证书
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

       //忽略header头信息
       curl_setopt($ch, CURLOPT_HEADER, 0);
       //设置超时时间
       curl_setopt($ch, CURLOPT_TIMEOUT, 10);
       //发送请求
       $output = curl_exec($ch);

       //关闭curl
       curl_close($ch);

       //返回数据
       return $output;
    }

    public function sendRedPack(){
        $url = 'https://www.jufenyun.com/openapi/gateway';
        $post_data['appkey']       = '2d953ea3-d3ad-4066-a3c4-8db3b2fd3e0d';
        $post_data['openid']      = 'obKkbt2mtAAmOMDAfwuj1YuhRXPA';
        $post_data['method'] = 'jfy.redpacks.send';
        $post_data['money']    = '30';
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $res = $this->post($url, $post_data);       
        print_r($res);
    }
}