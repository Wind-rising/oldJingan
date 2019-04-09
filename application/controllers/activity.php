<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require dirname(dirname(dirname(__FILE__))) . '/vendor/weixin/Weixin.php';

class Activity extends My_Controller {
    private $ipList = array(
                        '113.86.166.89',
                        '113.86.166.18',
                        '171.212.21.106',
                        '119.39.23.134',
                        '121.231.226.170'
                    );
    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->model('Answer_model');
        $this->load->model('RedpackLog_model');
        $this->load->model('TokenCache_model');
        $this->load->library('session');
    }

    //获取ip地址
    public function getIp() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }

    protected $allAightAnswerList = array(
                                0 => array( // 第一关答案
                                        0 => 0,
                                        1 => 1,
                                        2 => 0,
                                        3 => 1,
                                        4 => 0,
                                        5 => 2,
                                        6 => 2,
                                        7 => 1,
                                        8 => 0,
                                        9 => 2,
                                        10 => 1,
                                        11 => 0,
                                        12 => 0,
                                        13 => 0,
                                        14 => 0,
                                        15 => 0,
                                        16 => 0,
                                        17 => 0,
                                        18 => 0,
                                        19 => 1,
                                        20 => 0,
                                        21 => 0,
                                        22 => 1,
                                        23 => 1,
                                        24 => 0,
                                        25 => 3,
                                        26 => 1,
                                        27 => 0,
                                        28 => 2,
                                    ),
                                1 => array( // 第二关答案
                                        0 => 1,
                                        1 => 1,
                                        2 => 0,
                                        3 => 1,
                                        4 => 0,
                                        5 => 0,
                                        6 => 1,
                                        7 => 1,
                                        8 => 1,
                                        9 => 1,
                                        10 => 1,
                                        11 => 0,
                                        12 => 0,
                                        13 => 1,
                                        14 => 0,
                                        15 => 0,
                                        16 => 0,
                                        17 => 2,
                                        18 => 2,
                                        19 => 1,
                                        20 => 0,
                                        21 => 2,
                                        22 => 2,
                                        23 => 0,
                                        24 => 2,
                                        25 => 0,
                                        26 => 2,
                                        27 => 0,
                                        28 => 0,
                                        29 => 0
                                    ),
                                2 => array( // 第三关答案
                                        0 => 2,
                                        1 => 1,
                                        2 => 2,
                                        3 => 0,
                                        4 => 1,
                                        5 => 3,
                                        6 => 2,
                                        7 => 3,
                                        8 => 1,
                                        9 => 1,
                                        10 => 1,
                                        11 => 1,
                                        12 => 0,
                                        13 => 1,
                                        14 => 2,
                                        15 => 0,
                                        16 => 3,
                                        17 => 1,
                                        18 => 2,
                                        19 => 1,
                                        20 => 0,
                                        21 => 2,
                                        22 => 1,
                                        23 => 2,
                                        24 => 2,
                                        25 => 2,
                                        26 => 1,
                                        27 => 1,
                                        28 => 3,
                                        29 => 1
                                    ),
                                3 => array( // 第四关答案
                                        0 => 1,
                                        1 => 1,
                                        2 => 2,
                                        3 => 1,
                                        4 => 2,
                                        5 => 0,
                                        6 => 1,
                                        7 => 1,
                                        8 => 1,
                                        9 => 0,
                                        10 => 2,
                                        11 => 1,
                                        12 => 0,
                                        13 => 1,
                                        14 => 1,
                                        15 => 3,
                                        16 => 0,
                                        17 => 2,
                                        18 => 0,
                                        19 => 3,
                                        20 => 1,
                                        21 => 2,
                                        22 => 1,
                                        23 => 3,
                                        24 => 3,
                                        25 => 0,
                                        26 => 1,
                                        27 => 2,
                                        28 => 2,
                                        29 => 0
                                    ),
                                4 => array( // 第五关答案
                                        0 => 1,
                                        1 => 1,
                                        2 => 2,
                                        3 => 2,
                                        4 => 2,
                                        5 => 3,
                                        6 => 3,
                                        7 => 2,
                                        8 => 2,
                                        9 => 1,
                                        10 => 1,
                                        11 => 2,
                                        12 => '0-2',
                                        13 => '0-1-2',
                                        14 => 1,
                                        15 => 0,
                                        16 => 0,
                                        17 => '0-1-3',
                                        18 => '1-2-3',
                                        19 => 2,
                                        20 => '1-2-3',
                                        21 => 0,
                                        22 => 0,
                                        23 => 2,
                                        24 => 0,
                                        25 => 1,
                                        26 => 2,
                                        27 => 1,
                                        28 => 0,
                                        29 => '0-1-3'
                                    ),
                            );

    protected $activityTimeList = array(
                                    0 => array(
                                            's' => '2018-06-26 10:00:00',
                                            'e' => '2018-06-26 23:59:59'
                                        ),
                                    1 => array(
                                            's' => '2018-06-27 10:00:00',
                                            'e' => '2018-06-27 23:59:59'
                                        ),
                                    2 => array(
                                            's' => '2018-06-28 10:00:00',
                                            'e' => '2018-06-28 23:59:59'
                                        ),
                                    3 => array(
                                            's' => '2018-06-25 10:00:00',
                                            'e' => '2018-06-25 17:34:00'
                                    )
                                );

    protected function checkAnswer($checkPointId, $answerList)
    {
        $rightAnswerList = $this->allAightAnswerList[$checkPointId];
        $point = 0;
        foreach ($answerList as $ak => $av) {
            if (count($av['selectID']) == 1 && $av['selectID'][0] == $rightAnswerList[$av['questionID']]) {
                $point = $point + 10;
            } else if (count($av['selectID']) > 1 && empty(array_diff(explode('-', $rightAnswerList[$av['questionID']]), $av['selectID']))) {
                $point = $point + 10;
            }
        }

        return $point;
        // $    = $this->allAightAnswerList[$checkPointId];
        // $point = 0;
        // foreach ($answerList as $ak => $av) {
        //     if (count($av['selectID']) == 1 && $av['selectID'][0] == $rightAnswerList[$av['questionID']]) {
        //         $point = $point + 10;
        //     // } else if (count($av['selectID']) > 1 &&  empty(array_diff($rightAnswerList[$av['questionID']], $av['selectID']))) {
        //         } else if (count($av['selectID']) > 1 && implode('-', $av['selectID']) == $rightAnswerList[$av['questionID']]) {
        //         $point = $point + 10;
        //     }
        // }

        // return $point;
    }

    public function test()
    {
        $wxConfig = config_item('weixin');
        $weixin = new Weixin($wxConfig['token'], $wxConfig['app_id'], $wxConfig['securet'], $this->db);
        $a = $weixin->getOauth20Url('http://wx.wuliqinggu.com/activity/lingqu');
        var_dump($a);
        $b = $weixin->getOauth20Url('http://wx.wuliqinggu.com/activity/index');
        var_dump($b);
        die;

        $a = '{"checkpointID":0,"answer":[{"questionID":26,"selectID":[3]},{"questionID":12,"selectID":[0]},{"questionID":8,"selectID":[0]},{"questionID":4,"selectID":[0]},{"questionID":10,"selectID":[1]},{"questionID":16,"selectID":[0]},{"questionID":3,"selectID":[1]},{"questionID":23,"selectID":[1]},{"questionID":18,"selectID":[0]},{"questionID":1,"selectID":[1]}]}';
        $t = json_decode($a, true);
        $res = $this->checkAnswer($t['checkpointID'], $t['answer']);
        var_dump($res);
    }

    public function getSign()
    {
        $wxConfig = config_item('weixin');
        $weixin = new Weixin($wxConfig['token'], $wxConfig['app_id'], $wxConfig['securet'], $this->db);
        $sign = $weixin->getSignPackage();
        exit(json_encode($sign));
    }

    public function index()
    {
        $ip = $this->getIp();
        if (in_array($ip, $this->ipList)) {
            exit('非法进入');
        }
        
        // 检验时间
        $canGo1 = false;
        foreach ($this->activityTimeList as $av) {
            if (time() > strtotime($av['s']) && time() < strtotime($av['e'])) {
                $canGo1 = true;
                break;
            }
        }
        if (!$canGo1) {
            if (time() > strtotime('2018-06-28 23:59:59')) {
                header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over2.html');
                exit();
            }
            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over.html');
            exit();
        }
        // 检验红包
        $canGo2 = false;
        $redpacks = $this->getCurrentRedPack();
        foreach ($redpacks as $rv) {
            if ($rv > 0) {
                $canGo2 = true;
                break;
            }
        }
        if (!$canGo2) {
            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over2.html');
            exit();
        }

        $res = array(
                    'code' => 1,
                    'data' => array()
                );

        $openid = $this->getWeixinInfo();
        if (empty($openid)) {
            header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa9da81f585c9d3b0&redirect_uri=http%3A%2F%2Fwx.wuliqinggu.com%2Factivity%2Findex&response_type=code&scope=snsapi_base&state=1#wechat_redirect');
            die();
            // $res['data']['reason'] = '查找openid';
            // exit(json_encode($res));
        }
        $isExist = $this->Users_model->getByOpenidToday($openid);

        $isFirst = 1;
        $userId = 0;
        $res['code'] = 0;
        if (empty($isExist)) {
            $sourceType = requestGetString('source_type');
            if ($sourceType == 'gzh') {
                $isFirst = 0;
            }
            $info = array(
                        'openid' => $openid,
                        'is_first' => $isFirst,
                        'added_time' => date('Y-m-d H:i:s')
                    );
            $userId = $this->Users_model->insert($info);

            $res['data']['isFirst'] = $isFirst;
            $res['data']['isGetRed'] = 0;
            $res['data']['max'] = 0;
            $res['data']['maxRed'] = -1;
            $res['data']['score'] = array(0, 0, 0, 0, 0);
            $res['data']['currentRed'] = $this->getCurrentRedPack();
            // $this->load->view('qrcode', array('return_json', json_encode($res)));

            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/code.html');
        } else {
            $this->Users_model->updateById(array('is_first' => 0), $isExist['id']);
            $userId = $isExist['id'];

            $res['data']['isFirst'] = 0;
            $res['data']['isGetRed'] = $isExist['is_send_redpack'];
            $res['data']['max'] = $this->getMaxCheckpoint($isExist);
            $res['data']['maxRed'] = $this->getMaxRed($isExist);
            $res['data']['score'] = array($isExist['checkpoint0'], $isExist['checkpoint1'], $isExist['checkpoint2'], $isExist['checkpoint3'], $isExist['checkpoint4']);
            $res['data']['currentRed'] = $this->getCurrentRedPack();

            $this->session->set_userdata('user_id', $userId);
//            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/index.php?'.time());
            $this->html();
//            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/error2.html');
        }





    }

    // 首次进入获取信息
    public function getInfo()
    {
        $res = array(
                    'code' => 0,
                    'data' => array()
                );

        // 检验时间
        $canGo = false;
        foreach ($this->activityTimeList as $av) {
            if (time() > strtotime($av['s']) && time() < strtotime($av['e'])) {
                $canGo = true;
                break;
            }
        }
        if (!$canGo) {
            $res['code'] = 3;
            $res['data']['reason'] = '不是活动时间';
            exit(json_encode($res));
        }

        $userId = $this->session->userdata('user_id');
        $isExist = $this->Users_model->getById($userId);

        if (empty($isExist)) {
            $res['code'] = 1;
            $res['data']['reason'] = '未找到对应消息';
            exit(json_encode($res));
        } else {
            $this->Users_model->updateById(array('is_first' => 0), $isExist['id']);
            $userId = $isExist['id'];

            $res['data']['isFirst'] = 0;
            $res['data']['isFirstCP'] = $isExist['isFirstCP'];
            $res['data']['isGetRed'] = $isExist['is_send_redpack'];
            $res['data']['max'] = $this->getMaxCheckpoint($isExist);
            $res['data']['maxRed'] = $this->getMaxRed($isExist);
            $res['data']['score'] = array($isExist['checkpoint0'], $isExist['checkpoint1'], $isExist['checkpoint2'], $isExist['checkpoint3'], $isExist['checkpoint4']);
            $res['data']['currentRed'] = $this->getCurrentRedPack();
        }

        echo json_encode($res);
    }

    // 根据选择结果算出分数
    public function getScore()
    {
        $res = array('code' => 1);

        // 检验时间
        $canGo = false;
        foreach ($this->activityTimeList as $av) {
            if (time() > strtotime($av['s']) && time() < strtotime($av['e'])) {
                $canGo = true;
                break;
            }
        }
        if (!$canGo) {
            $res['code'] = 3;
            $res['data']['reason'] = '不是活动时间';
            exit(json_encode($res));
        }

        $userId = $this->session->userdata('user_id');
        $userId = isset($userId) ? $userId : 0;
        $userInfo = $this->Users_model->getById($userId);
        if (empty($userInfo)) {
            $res['data']['reason'] = '未查到信息，请重新进入！';
            exit(json_encode($res));
        }

        $res['code'] = 0;
        $answerJson = requestGetString('answer_list');
        $answerList = json_decode($answerJson, 'true');

        $score = $this->checkAnswer($answerList['checkpointID'], $answerList['answer']);
        if ($score >= 90) {
            // 更新主表
            $info = array();
            $info['isFirstCP'] = 1;
            $info['checkpoint'.$answerList['checkpointID']] = $score;
            $this->Users_model->updateById($info, $userId);
        } else {
            $downGradeStart = $answerList['checkpointID'];
            $downGradeEnd = ($downGradeStart - 2) > 0 ? $downGradeStart - 2 : 0;
            $updateInfo = array();
            for ($i=$downGradeStart; $i >= $downGradeEnd; $i--) {
                $updateInfo['checkpoint'.$i] = 0;
            }
            if (!empty($updateInfo)) {
                $info['is_fail'] = 1;
                $this->Users_model->updateById($updateInfo, $userInfo['id']);
            }
        }
        $answer = array();
        foreach ($answerList['answer'] as $ak => $av) {
            $answer[] = array(
                            $av['questionID'].'' => $av['selectID']
                        );
        }

        // 保存答案
        $answerInfo = array();
        $answerInfo['user_id'] = $userId;
        $answerInfo['checkpoint'] = $answerList['checkpointID'];
        $answerInfo['answer'] = json_encode($answer);
        $answerInfo['score'] = $score;
        $answerInfo['added_time'] = date('Y-m-d H:i:s');
        $this->Answer_model->insert($answerInfo);

        $userInfo = $this->Users_model->getById($userId);
        $userInfo['checkpoint'.$answerList['checkpointID']] = $score;
        $res = array(
                    'code' => 0,
                    'data' => array()
                );
        $res['data']['isGetRed'] = $userInfo['is_send_redpack'];
        if ($score < 90 && $answerList['checkpointID'] - 2 <= 0) {
            $res['data']['max'] = 0;
        } else {
            $res['data']['max'] = $this->getMaxCheckpoint($userInfo);
        }
        $res['data']['isFirstCP'] = $userInfo['isFirstCP'];
        $res['data']['maxRed'] = $this->getMaxRed($userInfo);
        $res['data']['score'] = array($userInfo['checkpoint0'], $userInfo['checkpoint1'], $userInfo['checkpoint2'], $userInfo['checkpoint3'], $userInfo['checkpoint4']);
        $res['data']['currentRed'] = $this->getCurrentRedPack();

        exit(json_encode($res));
    }

    // 获得红包
    public function getRedPack()
    {
        $ip = $this->getIp();
        if (in_array($ip, $this->ipList)) {
            exit('非法进入');
        }

        $res = array(
                    'code' => 1,
                    'data' => array()
                );

        // 检验时间
        $canGo = false;
        foreach ($this->activityTimeList as $av) {
            if (time() > strtotime($av['s']) && time() < strtotime($av['e'])) {
                $canGo = true;
                break;
            }
        }
        if (!$canGo) {
            $res['code'] = 3;
            $res['data']['reason'] = '不是活动时间';
            exit(json_encode($res));
        }

        $userId = $this->session->userdata('user_id');
        $userId = isset($userId) ? $userId : 0;
        $userInfo = $this->Users_model->getById($userId);
        if (empty($userInfo)) {
            $res['data']['reason'] = '未查到信息，请重新进入！';
            return json_encode($res);
        }

        $checkpoint = requestGetString('checkpoint');
        $maxRed = $this->getMaxRed($userInfo);
        if ($checkpoint <= $maxRed && $userInfo['is_send_redpack'] == 0) {
            $surplusRedpack = $this->getCurrentRedPack();
            if ($surplusRedpack[$checkpoint] > 0) {
                $amountList = array(
                                '0' => 30,
                                '1' => 50,
                                '2' => 100,
                                '3' => 300,
                                '4' => 600
                            );
                $redRes = $this->sendRedPack($userId, $userInfo['openid'], $checkpoint, $amountList[$checkpoint]);
                if ($redRes['result']) {
                    $this->Users_model->updateById(array('is_send_redpack' => 1), $userId);
                    $surplusRedpack[$checkpoint]--;
                    
                    $res['code'] = 0;
                    $res['data']['currentRed'] = $surplusRedpack;
                } else {
                    $res['data']['reason'] = '红包溜走了';
                    $res['data']['jufenyun'] = $redRes;
                }
            } else {
                $res['data']['reason'] = '该等级红包已发完';
            }
        }  else {
            $res['data']['reason'] = '不符合条件';
        }

        exit(json_encode($res));
    }

    // 领取红包
    public function lingqu()
    {
        $ip = $this->getIp();
        if (in_array($ip, $this->ipList)) {
            exit('非法进入');
        }

        $res = array('code' => 1);
        // 检验时间
        $canGo = false;
        foreach ($this->activityTimeList as $av) {
            if (time() > strtotime($av['s']) && time() < strtotime($av['e'])) {
                $canGo = true;
                break;
            }
        }
        if (!$canGo) {
            $res['code'] = 3;
            $res['data']['reason'] = '不是活动时间';
            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/error.html?');
            exit(json_encode($res));
        }
        
        $openid = $this->getWeixinInfo();
        if (empty($openid)) {
            $res['data']['reason'] = '查找openid';
            exit(json_encode($res));
        }

        $isExist = $this->RedpackLog_model->getByOpenidToday($openid);
        if (!empty($isExist['redpack_url'])) {
            $userInfo = $this->Users_model->getByOpenidToday($openid);
            // if (!empty($userInfo) && $userInfo['best_score'] < 2) {
            //     $this->Users_model->updateById(array('best_score' => 2), $userInfo['id']);
            //     header('Location: '.$isExist['redpack_url']);
            // } else {
            //     $res['data']['reason'] = '您已领取过';
            // }
            $this->Users_model->updateById(array('best_score' => 2), $userInfo['id']);
            header('Location: '.$isExist['redpack_url']);
        } else {
            // $res['data']['reason'] = '未找到对应红包或已隔天失效';
            header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/error.html?');
        }

        // exit(json_encode($res));
    }

    protected function getWeixinInfo()
    {
        $openid = $this->session->userdata('openid');
        if (!empty($openid)) {
            return $openid;
        }
        
        $wxConfig = config_item('weixin');
        $weixin = new Weixin($wxConfig['token'], $wxConfig['app_id'], $wxConfig['securet'], $this->db);
        $weixin->getAccessToken();
        $code = requestGetString('code');
        $openid = $weixin->getOpenidFromOauth20Code($code);
        if (!empty($openid)) {
            $this->session->set_userdata('openid', $openid);
        }
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

    protected function getCurrentRedPack()
    {
        $list = $this->RedpackLog_model->getRedPackListToday();
        $res = array(1000, 999, 500, 300, 200);
        foreach ($list as $lk => $lv) {
            $res[$lv['checkpoint']] = $res[$lv['checkpoint']] - 1;
            if ($res[$lv['checkpoint']] <= 0) {
                $res[$lv['checkpoint']] = 0;
            }
        }

        return $res;
    }

    // 最大可闯关关卡
    protected function getMaxCheckpoint($info)
    {
        $max = 0;
        for ($i=0; $i < 5; $i++) { 
            if ($info['checkpoint'.$i] >= 90) {
                $max++;
            } else {
                break;
            }
        }

        return $max;
    }

    // 最大可领红包关卡
    protected function getMaxRedOld($info)
    {
        $maxCheckpoint = $this->getMaxCheckpoint($info);
        if ($maxCheckpoint == 0) {
            if ($info['is_fail'] == 0) {
                return 0;
            } else {
                return -1;
            }
        } else if ($info['is_fail'] == 0) {
            return $maxCheckpoint;
        } else {
            return ($maxCheckpoint - 2) > 0 ? ($maxCheckpoint - 2) : 0;
        }
    }

    protected function getMaxRed($info, $fail = false)
    {
        $maxCheckpoint = $this->getMaxCheckpoint($info);
        if ($maxCheckpoint == 0) {
            if ($info['isFirstCP'] > 0) {
                return 0;
            } else {
                return -1;
            }
        } else {
            if ($fail) {
                return $maxCheckpoint;
            } else {
                return $maxCheckpoint - 1;
            }
        }
    }

    protected function sendRedPack($userId, $openid, $checkpoint, $amount)
    {
        $url = 'https://www.jufenyun.com/openapi/gateway';
        $post_data['appkey'] = '2d953ea3-d3ad-4066-a3c4-8db3b2fd3e0d';
        $post_data['openid'] = $openid;
        $post_data['method'] = 'jfy.redpacks.send';
        $post_data['money'] = $amount;
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $resJson = $this->post($url, $post_data);
        $res = json_decode($resJson, true);
        $returnArr = array('result' => false);

        if (!empty($res['redpack_url'])) {
            $info = array();
            $info['user_id'] = $userId;
            $info['amount'] = $amount;
            $info['openid'] = $openid;
            $info['checkpoint'] = $checkpoint;
            $info['redpack_url'] = $res['redpack_url'];
            $info['added_time'] = date('Y-m-d H:i:s');

            $this->RedpackLog_model->insert($info);

            $returnArr['result'] = true;
        } else {
            $returnArr['reason'] = $res;
        }

        return $returnArr;
    }

    public function getCode($len = 6)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789abcdefghjkmnpqrstuwy';
        $code = '';
        for ($i=0; $i < 6; $i++) { 
            $key = rand(0, 54);
            $code .= substr($str, $key, 1);
        }

        $isExist = $this->RedpackLog_model->getByCode($code);
        if (!empty($isExist)) {
            $this->getCode();
        } else {
            return $code;
        }
    }


    public function html(){
            echo "<!DOCTYPE html>\n";
            echo "<html lang=\"en\">\n";
            echo "<head>\n";
            echo "<meta charset=\"UTF-8\">\n";
            echo "<meta name=\"viewport\" content=\"width=device-width,user-scalable=no\">\n";
            echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\" />\n";
            echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
            echo "<meta http-equiv=\"Expires\" content=\"0\" />\n";
            echo "<link rel=\"stylesheet\" href=\"../project/AntiDrug20180626/css/public.css\">\n";
            echo "<link rel=\"stylesheet\" href=\"../project/AntiDrug20180626/css/index.css\">\n";
            echo "<title>“禁毒王者”竞答活动</title>\n";
            echo "</head>\n";
            echo "<body>\n";
            echo "<div class=\"container\">\n";
            echo "  <div class=\"loading\"></div>\n";
            echo "  <!-- 首页 -->\n";
            echo "  <div class=\"page0 page\" style=\"display:none;\">\n";
            echo "    <div class=\"page_bg\"></div>\n";
            echo "    <div class=\"page_top\"></div>\n";
            echo "    <div class=\"page0_top\"></div>\n";
            echo "    <div class=\"page0_title\"></div>\n";
            echo "    <div class=\"page0_icon2\"></div>\n";
            echo "    <div class=\"page0_icon1\"></div>\n";
            echo "    <div class=\"page0_buttonWrap\">\n";
            echo "      <div class=\"rule button\">\n";
            echo "        <i class=\"button_word word_cansai\"></i>\n";
            echo "      </div>\n";
            echo "      <div class=\"moveGame button\">\n";
            echo "        <i class=\"button_word word_canyu\"></i>\n";
            echo "      </div>\n";
            echo "    </div>\n";
            echo "    <div class=\"page0_bottom\"></div>\n";
            echo "  </div>\n";
            echo "  <!-- 进入答题页 -->\n";
            echo "  <div class=\"page1 page\" style=\"display:none;\">\n";
            echo "    <div class=\"page_bg\"></div>\n";
            echo "    <div class=\"page0_title\"></div>\n";
            echo "    <div class=\"page1_button moveGame2\"></div>\n";
            echo "    <div class=\"page0_bottom\"></div>\n";
            echo "  </div>\n";
            echo "  <!-- 进入答题页 -->\n";
            echo "  <div class=\"page2 page\" style=\"display:none;\">\n";
            echo "    <div class=\"page_bg\"></div>\n";
            echo "    <ul class=\"checkPoint\">\n";
            echo "      <li class=\"checkPointLi clearfix\">\n";
            echo "        <div class=\"checkPointSub checkPointSub1\">\n";
            echo "          <div class=\"wrap_icon\">\n";
            echo "            <div class=\"this_state1 this_state\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
            echo "          <div class=\"star_wrap clearfix\">\n";
            echo "            <div class=\"star effect1\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
            echo "        </div>\n";
            echo "        <div class=\"redPackage\">\n";
            echo "          <p class=\"p1\">剩余</p>\n";
            echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
            echo "        </div>\n";
            echo "      </li>\n";
            echo "      <li class=\"checkPointLi clearfix\">\n";
            echo "        <div class=\"checkPointSub checkPointSub2\">\n";
            echo "          <div class=\"wrap_icon\">\n";
            echo "            <div class=\"this_state1 this_state\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
            echo "          <div class=\"star_wrap clearfix\">\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
            echo "        </div>\n";
            echo "        <div class=\"redPackage\">\n";
            echo "          <p class=\"p1\">剩余</p>\n";
            echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
            echo "        </div>\n";
            echo "      </li>\n";
            echo "      <li class=\"checkPointLi clearfix\">\n";
            echo "        <div class=\"checkPointSub checkPointSub3\">\n";
            echo "          <div class=\"wrap_icon\">\n";
            echo "            <div class=\"this_state1 this_state\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
            echo "          <div class=\"star_wrap clearfix\">\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
            echo "        </div>\n";
            echo "        <div class=\"redPackage\">\n";
            echo "          <p class=\"p1\">剩余</p>\n";
            echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
            echo "        </div>\n";
            echo "      </li>\n";
            echo "      <li class=\"checkPointLi clearfix\">\n";
            echo "        <div class=\"checkPointSub checkPointSub4\">\n";
            echo "          <div class=\"wrap_icon\">\n";
            echo "            <div class=\"this_state1 this_state\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
            echo "          <div class=\"star_wrap clearfix\">\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
            echo "        </div>\n";
            echo "        <div class=\"redPackage\">\n";
            echo "          <p class=\"p1\">剩余</p>\n";
            echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
            echo "        </div>\n";
            echo "      </li>\n";
            echo "      <li class=\"checkPointLi clearfix\">\n";
            echo "        <div class=\"checkPointSub checkPointSub5\">\n";
            echo "          <div class=\"wrap_icon\">\n";
            echo "            <div class=\"this_state1 this_state\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
            echo "          <div class=\"star_wrap clearfix\">\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star on\"></div>\n";
            echo "            <div class=\"star\"></div>\n";
            echo "          </div>\n";
            echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
            echo "        </div>\n";
            echo "        <div class=\"redPackage\">\n";
            echo "          <p class=\"p1\">剩余</p>\n";
            echo "          <p class=\"p2\"><span class=\"redNum\">1000</span><span class=\"s1\">个</span></p>\n";
            echo "        </div>\n";
            echo "      </li>\n";
            echo "    </ul>\n";
            echo "    <div class=\"startQuestion\"></div>\n";
            echo "    <div class=\"page0_bottom\"></div>\n";
            echo "  </div>\n";
            echo "  <!-- 答题页 -->\n";
            echo "  <div class=\"page3 page\" style=\"display:none;\">\n";
            echo "    <div class=\"page_bg\"></div>\n";
            echo "    <div class=\"wrap_page3\">\n";
            echo "      <div class=\"question rz3\"><span class=\"xulie\"></span><span class=\"state\"></span><span class=\"content\"></span></div>\n";
            echo "      <ul class=\"answer rz3\">\n";
            echo "        <li class=\"answerSub clearfix\">\n";
            echo "          <div class=\"select on\"></div>\n";
            echo "          <div class=\"content\"></div>\n";
            echo "        </li>\n";
            echo "        \n";
            echo "        <li class=\"answerSub clearfix\">\n";
            echo "          <div class=\"select on\"></div>\n";
            echo "          <div class=\"content\"></div>\n";
            echo "        </li>\n";
            echo "          <li class=\"answerSub clearfix\">\n";
            echo "          <div class=\"select on\"></div>\n";
            echo "          <div class=\"content\"></div>\n";
            echo "        </li>\n";
            echo "          <li class=\"answerSub clearfix\">\n";
            echo "          <div class=\"select on\"></div>\n";
            echo "          <div class=\"content\"></div>\n";
            echo "        </li>\n";
            echo "      </ul>\n";
            echo "      <div class=\"rz3 button countDown_wrap\">\n";
            echo "        <div class=\"countDown\"></div>\n";
            echo "      </div>\n";
            echo "      <div class=\"rz3 tips_wrap\">\n";
            echo "        <span class=\"cp_name\"></span>\n";
            echo "        <span>\n";
            echo "          第<span class=\"currentsQusetion\"></span>题/共10题\n";
            echo "        </span>\n";
            echo "        <span class=\"score\"></span>\n";
            echo "      </div>\n";
            echo "    </div>\n";
            echo "    <div class=\"page0_bottom\"></div>\n";
            echo "  </div>\n";
            echo "  <!-- 结算页面 -->\n";
            echo "  <div class=\"page4 page\" style=\"display:none;\">\n";
            echo "    <div class=\"page_bg\"></div>\n";
            echo "    <div class=\"page0_top\"></div>\n";
            echo "    <div class=\"success\">\n";
            echo "      <div class=\"page4_title\">\n";
            echo "        <div class=\"title_word\"></div>\n";
            echo "      </div>\n";
            echo "      <div class=\"page4_icon\"></div>\n";
            echo "      <div class=\"font_wrap\">\n";
            echo "        <p class=\"p4\">恭喜！</p>\n";
            echo "        <p class=\"p1\">您的分数为<span class=\"page4_score\"></span></p>\n";
            echo "        <p class=\"p2\">您已成功闯关<span class=\"page4_index\">一</span>星关卡</p>\n";
            echo "        <p class=\"p3\">乘胜追击，继续闯关吧</p>\n";
            echo "      </div>\n";
            echo "      <div class=\"button_wrap clearfix\">\n";
            echo "        <div class=\"continueGame button\">\n";
            echo "          <i class=\"button_word word_continue\"></i>\n";
            echo "        </div>\n";
            echo "        <div class=\"endGame button\">\n";
            echo "          <i class=\"button_word word_getAward\"></i>\n";
            echo "        </div>\n";
            echo "      </div>\n";
            echo "    </div>\n";
            echo "    <div class=\"failed\">\n";
            echo "      <div class=\"page4_title\">\n";
            echo "        <div class=\"title_word\"></div>\n";
            echo "      </div>\n";
            echo "      <div class=\"page4_icon\"></div>\n";
            echo "      <div class=\"font_wrap\">\n";
            echo "        <p class=\"p4\">很遗憾！</p>\n";
            echo "        <p class=\"p1\">您的分数为<span class=\"page4_score\"></span></p>\n";
            echo "        <p class=\"p2\">您此关闯关失败</p>\n";
            echo "        <p class=\"p3\">请再接再厉，重新挑战一次吧</p>\n";
            echo "       </div>\n";
            echo "      <div class=\"button_wrap clearfix\">\n";
            echo "        <div class=\"continueGame button\">\n";
            echo "          <i class=\"button_word word_again\"></i>\n";
            echo "        </div>\n";
            echo "        <div class=\"endGame button\">\n";
            echo "          <i class=\"button_word word_end\"></i>\n";
            echo "        </div>\n";
            echo "      </div>\n";
            echo "    </div>\n";
            echo "    <div class=\"page0_bottom\"></div>\n";
            echo "  </div>\n";
            echo "  <!-- 转圈圈 -->\n";
            echo "  <div class=\"popup popup_error error_tips\">\n";
            echo "    <div class=\"popup_main\">\n";
            echo "        <div class=\"popup_icon1\"></div>\n";
            echo "        <p class=\"p1 error_aa\">很遗憾回答错误!</p>\n";
            echo "        <div class=\"popup_line1\"></div>\n";
            echo "        <p class=\"p2\"><span class=\"s1\">正确答案：</span><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
            echo "        <p class=\"p3\"><span class=\"s1\">正确答案：</span></p>\n";
            echo "        <div class=\"popup_scroll\">\n";
            echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
            echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
            echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
            echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
            echo "        </div>\n";
            echo "      <div class=\"down\"></div>\n";
            echo "      <div class=\"button next\">\n";
            echo "        <i class=\"button_word word_next\"></i>\n";
            echo "      </div>\n";
            echo "    </div>\n";
            echo "  </div>\n";
            echo "  <div class=\"popup popup_success\">\n";
            echo "    <div class=\"popup_main\">\n";
            echo "    </div>\n";
            echo "  </div>\n";
            echo "  <div class=\"popup popup_rule\">\n";
            echo "    <div class=\"popup_main\">\n";
            echo "      <div class=\"close\"></div>\n";
            echo "      <p class=\"rule_title\">竞答规则</p>\n";
            echo "      <div class=\"rule_line\"></div>\n";
            echo "      <div class=\"popup_rule_scroll\">\n";
            echo "        <p class=\"p1\"><span class=\"s2\">1. </span>竞答设<span class=\"s1\">青铜奖、白银奖、黄金奖、铂金奖和钻石奖</span>五级奖励，每级各有<span class=\"s1\">10</span>题，答对<span class=\"s1\">9</span>题闯关成功。</p>\n";
            echo "        <p class=\"p1\"><span class=\"s2\">2. </span>每级奖励均有数量限制，奖完即止,一个微信号每天只能领取一个答题奖励。</p>\n";
            echo "        <p class=\"p1\"><span class=\"s2\">3. </span>闯关成功，可选择领取本级别奖励或者继续挑战下一级别，挑战下一级别失败，退回上一级别。\n";
            echo "例：闯过黄金奖，选择冲击铂金奖，失败后要退回白银奖选择“重新挑战“或“终止挑战”。</p>\n";
            echo "      </div>\n";
            echo "    </div>\n";
            echo "  </div>\n";
            echo "  <div class=\"popup popup_tips popup_tip\">\n";
            echo "    <div class=\"popup_main\">\n";
            echo "      <div class=\"close\"></div>\n";
            echo "      <p class=\"p1\">请点击《平安静安》公众号下方菜单栏中《领取红包》按钮，领取您的大红包吧</p>\n";
            echo "    </div>\n";
            echo "  </div>\n";
            echo "  <div class=\"popup popup_tips2 popup_tip\">\n";
            echo "    <div class=\"popup_main\">\n";
            echo "      <div class=\"close\"></div>\n";
            echo "      <p class=\"p1\">红包以发放完毕</p>\n";
            echo "    </div>\n";
            echo "  </div>\n";
            echo "</div>\n";
            echo "</body>\n";
            echo "<script src=\"../project/AntiDrug20180626/js/load.js?".time()."\"></script>\n";
            echo "<script src=\"http://apps.bdimg.com/libs/jquery/1.8.1/jquery.min.js\"></script>\n";
            echo "<script src=\"../project/AntiDrug20180626/js/adapt.js?".time()."\"></script>\n";
            echo "<script src=\"../project/AntiDrug20180626/js/main.js?".time()."\"></script>\n";
            echo "</html>\n";
        }
}