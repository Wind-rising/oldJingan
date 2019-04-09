<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require dirname(dirname(dirname(__FILE__))) . '/vendor/weixin/Weixin.php';

class Activityn extends My_Controller {
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
                                        0 => 3,
                                        1 => 2,
                                        2 => 3,
                                        3 => 2,
                                        4 => 3,
                                        5 => 3,
                                        6 => 3,
                                        7 => 3,
                                        8 => 2,
                                        9 => 3,
                                        10 => 3,
                                        11 => 3,
                                        12 => 3,
                                        13 => 1,
                                        14 => 1,
                                        15 => 0,
                                        16 => 3,
                                        17 => 0,
                                        18 => 0,
                                        19 => 1,
                                        20 => 3,
                                        21 => 1,
                                        22 => 3,
                                        23 => 0,
                                        24 => 1,
                                        25 => 1,
                                        26 => 1,
                                        27 => 3,
                                        28 => 0,
                                        29 => 0,
                                    ),
                                1 => array( // 第二关答案
                                        0 => 1,
                                        1 => 2,
                                        2 => 1,
                                        3 => 2,
                                        4 => 1,
                                        5 => 2,
                                        6 => 1,
                                        7 => 0,
                                        8 => 0,
                                        9 => 1,
                                        10 => 0,
                                        11 => 3,
                                        12 => 0,
                                        13 => 2,
                                        14 => 0,
                                        15 => 2,
                                        16 => 2,
                                        17 => 2,
                                        18 => 0,
                                        19 => 3,
                                        20 => 3,
                                        21 => 1,
                                        22 => 2,
                                        23 => 2,
                                        24 => 2,
                                        25 => 1,
                                        26 => 1,
                                        27 => 1,
                                        28 => 1,
                                        29 => 0,
                                    ),
                                2 => array( // 第三关答案
                                        0 => 1,
                                        1 => 2,
                                        2 => 0,
                                        3 => 0,
                                        4 => 1,
                                        5 => 0,
                                        6 => 1,
                                        7 => 1,
                                        8 => 2,
                                        9 => 0,
                                        10 => 3,
                                        11 => 2,
                                        12 => 3,
                                        13 => 1,
                                        14 => 1,
                                        15 => 0,
                                        16 => 0,
                                        17 => 3,
                                        18 => 1,
                                        19 => 2,
                                        20 => 1,
                                        21 => 1,
                                        22 => 1,
                                        23 => 0,
                                        24 => 1,
                                        25 => 3,
                                        26 => 0,
                                        27 => 2,
                                        28 => 0,
                                        29 => 1,
                                    ),
                                3 => array( // 第四关答案
                                        0 => 0,
                                        1 => 3,
                                        2 => 1,
                                        3 => 1,
                                        4 => 2,
                                        5 => 0,
                                        6 => 1,
                                        7 => 0,
                                        8 => 1,
                                        9 => 3,
                                        10 => 1,
                                        11 => 2,
                                        12 => 0,
                                        13 => '0-1-3',
                                        14 => 2,
                                        15 => '0-1-2-3',
                                        16 => '0-1-2',
                                        17 => 1,
                                        18 => 1,
                                        19 => 3,
                                        20 => 1,
                                        21 => 2,
                                        22 => '0-2-3',
                                        23 => '0-1-3',
                                        24 => 1,
                                        25 => 2,
                                        26 => 0,
                                        27 => 3,
                                        28 => 2,
                                        29 => 3,
                                    ),
                                4 => array( // 第五关答案
                                        0 => 1,
                                        1 => 2,
                                        2 => 2,
                                        3 => 1,
                                        4 => 0,
                                        5 => 2,
                                        6 => 0,
                                        7 => 3,
                                        8 => 1,
                                        9 => 0,
                                        10 => 0,
                                        11 => '1-2-3',
                                        12 => 0,
                                        13 => '0-1-3',
                                        14 => '1-2-3',
                                        15 => 2,
                                        16 => '1-2-3',
                                        17 => 3,
                                        18 => 0,
                                        19 => 3,
                                        20 => 3,
                                        21 => 0,
                                        22 => 1,
                                        23 => 0,
                                        24 => 0,
                                        25 => 2,
                                        26 => 1,
                                        27 => 3,
                                        28 => '0-1-3',
                                        29 => '1-2-3',
                                    ),
                            );

    protected $activityTimeList = array(
                                    0 => array(
                                            's' => '2018-11-23 10:00:00',
                                            // 'e' => '2018-12-03 23:59:59'
                                            'e' => '2018-12-03 22:00:00'
                                        ),
                                    1 => array(
                                            's' => '2018-12-04 10:00:00',
                                            'e' => '2018-12-04 23:59:59'
                                        ),
                                    2 => array(
                                            's' => '2018-12-05 10:00:00',
                                            'e' => '2018-12-05 23:59:59'
                                        ),
                                    3 => array(
                                            's' => '2018-12-06 10:00:00',
                                            'e' => '2018-12-06 23:59:59'
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
        // $this->sendRedPack(1, '', 30);die;
        $wxConfig = config_item('weixin');
        $weixin = new Weixin($wxConfig['token'], $wxConfig['app_id'], $wxConfig['securet'], $this->db);
        $a = $weixin->getOauth20Url('http://wx.wuliqinggu.com/activityn/lingqu');
        var_dump($a);die;
        $b = $weixin->getOauth20Url('http://wx.wuliqinggu.com/activityn/index');
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
            if (time() > strtotime('2018-12-06 23:59:59')) {
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
        // if (!$canGo2) {
        //     header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over2.html');
        //     exit();
        // }

        $res = array(
                    'code' => 1,
                    'data' => array()
                );

        $openid = $this->getWeixinInfo();
        if (empty($openid)) {
            header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa9da81f585c9d3b0&redirect_uri=http%3A%2F%2Fwx.wuliqinggu.com%2Factivityn%2Findex&response_type=code&scope=snsapi_base&state=1#wechat_redirect');
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
        if ($checkpoint <= $maxRed && $userInfo['is_send_redpack'] == 0 && $userInfo['is_get'] == 0) {
            $surplusRedpack = $this->getCurrentRedPack();
            if ($surplusRedpack[$checkpoint] > 0) {
                $amountList = array(
                                '0' => 30,
                                '1' => 50,
                                '2' => 100,
                                '3' => 300,
                                '4' => 500
                            );
                $redpackLogInfo = array(
                                    'user_id' => $userId,
                                    'amount' => $amountList[$checkpoint],
                                    'openid' => $userInfo['openid'],
                                    'checkpoint' => $checkpoint,
                                    'added_time' => date('Y-m-d H:i:s'),
                                );
                $logId = $this->RedpackLog_model->insert($redpackLogInfo);
                if ($logId) {
                    $this->Users_model->updateById(array('is_get' => 1), $userId);
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

        $isExist = $this->Users_model->getByOpenidToday($openid);
        if (!empty($isExist) && $isExist['is_get'] == 1 && $isExist['is_send_redpack'] == 0) {
            $redpackLog = $this->RedpackLog_model->getByOpenidToday($openid);
            if (!empty($redpackLog)) {
                $isSuccess = $this->sendRedPack($isExist['id'], $redpackLog['amount']);
                if (!empty($isSuccess)) {
                    $this->RedpackLog_model->updateById(array('redpack_url' => $isSuccess), $redpackLog['id']);
                    $this->Users_model->updateById(array('is_send_redpack' => 1, 'best_score' => 2), $isExist['id']);

                    header('Location: '.$isSuccess);
                    exit();
                }
            }
        }

        header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/error.html?');
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
        $res = array(1000, 200, 200, 300, 400);
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

    protected function sendRedPackOld($userId, $openid, $checkpoint, $amount)
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

    protected function sendRedPack($userId, $amount)
    {
        $url = 'http://www.yaoyaola.cn/index.php/exapi/hbticket';

        $postData = array();
        $postData['uid'] = '26341';
        $postData['type'] = 0;
        $postData['money'] = $amount;
        $postData['orderid'] = time() . '-' . $userId;
        $postData['reqtick'] = time();
        $postData['sign'] = md5($postData['uid'].$postData['type'].$postData['orderid'].$postData['money'].$postData['reqtick'].'PingAnJianAnWANGLUOJINDU');
        $postData['title'] = '宪法只是竞答活动红包';
        $postData['sendname'] = '平安静安';
        $postData['wishing'] = '平安静安';

        $o = "";
        foreach ( $postData as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $postData = substr($o,0,-1);
        $url = $url.'?'.$postData;
        $resJson = $this->post($url, '');
        $resArr = json_decode($resJson, true);
        if (!empty($resArr['ticket'])) {
            return 'http://www.yaoyaola.cn/index.php/exapi/gethb?uid=26341&ticket=' . $resArr['ticket'];
        } else {
            return false;
        }
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
           echo"<!DOCTYPE html>"
             . "<html lang=\"en\">"
             . "<head>"
             . "<meta charset=\"UTF-8\">"
             . "<meta name=\"viewport\" content=\"width=device-width,user-scalable=no\">"
             . "<meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\" />"
             . "<meta http-equiv=\"Pragma\" content=\"no-cache\" />"
             . "<meta http-equiv=\"Expires\" content=\"0\" />"
             . "<link rel=\"stylesheet\" href=\"../project/AntiDrug20180626/css/public.css\">"
             . "<link rel=\"stylesheet\" href=\"../project/AntiDrug20180626/css/index.css\">"
             . "<title>“宪法知识”竞答活动</title>"
             . "</head>"
             . "<body>"
               . "<audio preload=\"auto\" style=\"display:none;\" id=\"button\" src=\"../project/AntiDrug20180626/sound/button.mp3\">"
               . "</audio>"
               . "<audio preload=\"auto\" style=\"display:none;\" id=\"win\" src=\"../project/AntiDrug20180626/sound/win.mp3\">"
               . "</audio>"
               . "<audio preload=\"auto\"  style=\"display:none;\" id=\"lose\" src=\"../project/AntiDrug20180626/sound/lose.mp3\">"
               . "</audio>"
             . "<div class=\"container\">"
             . "	<div class=\"loading\"></div>"
             . "	<!-- 首页 -->"
             . "	<div class=\"page0 page\"  style=\"display:none;\">"
             . "		<div class=\"page_bg\"></div>"
             . "		<div class=\"page_top\"></div>"
             . "		<div class=\"page0_top\"></div>"
             . "		<div class=\"page0_title\"></div>"
             . "		<div class=\"page0_icon2\"></div>"
             . "		<div class=\"page0_icon1\">"
             . "			<div class=\"page0_wordFU\"></div>"
             . "		</div>"
             . "		<div class=\"page0_buttonWrap\">"
             . "			<div class=\"rule button\">"
             . "				<i class=\"button_word word_cansai\"></i>"
             . "			</div>"
             . "			<div class=\"moveGame button\">"
             . "				<i class=\"button_word word_canyu\"></i>"
             . "			</div>"
			 . "    <div class=\"page0_luokuan\">静安区委政法委</div>"
             . "		</div>"
             . "		<div class=\"page0_bottom\"></div>"
             . "	</div>"
             . "	<!-- 进入答题页 -->"
             . "	<div class=\"page1 page\" style=\"display:none;\">"
             . "		<div class=\"page_bg\"></div>"
             . "		<div class=\"page1_title\"></div>"
             . "		<div class=\"page0_icon1 page1_icon1\">"
             . "		</div>"
             . "		<!--<div class=\"page1_button moveGame2\"></div>-->"
             . "			<div class=\"page1_button button moveGame2\">"
             . "			<i class=\"button_word word_chuanguan\"></i>"
             . "		</div>"
             . "		<div class=\"page0_bottom\"></div>"
             . "	</div>"
             . "	<!-- 进入答题页 -->"
             . "	<div class=\"page2 page\" style=\"display:none;\">"
             . "		<div class=\"page_bg3\"></div>"
             . "		<ul class=\"checkPoint\">"
             . "			<li class=\"checkPointLi clearfix\">"
             . "				<div class=\"checkPointSub checkPointSub1\">"
             . "					<div class=\"wrap_icon\">"
             . "						<div class=\"this_state1 this_state\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_left\"></div>"
             . "					<div class=\"star_wrap clearfix\">"
             . "						<div class=\"star effect1\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_right\"></div>"
             . "				</div>"
             . "				<div class=\"redPackage\">"
             . "					<p class=\"p1\">剩余</p>"
             . "					<p class=\"p2\"><span class=\"redNum\">1000</span>个</p>"
             . "				</div>"
             . "			</li>"
             . "			<li class=\"checkPointLi clearfix\">"
             . "				<div class=\"checkPointSub checkPointSub2\">"
             . "					<div class=\"wrap_icon\">"
             . "						<div class=\"this_state1 this_state\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_left\"></div>"
             . "					<div class=\"star_wrap clearfix\">"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_right\"></div>"
             . "				</div>"
             . "				<div class=\"redPackage\">"
             . "					<p class=\"p1\">剩余</p>"
             . "					<p class=\"p2\"><span class=\"redNum\">1000</span>个</p>"
             . "				</div>"
             . "			</li>"
             . "			<li class=\"checkPointLi clearfix\">"
             . "				<div class=\"checkPointSub checkPointSub3\">"
             . "					<div class=\"wrap_icon\">"
             . "						<div class=\"this_state1 this_state\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_left\"></div>"
             . "					<div class=\"star_wrap clearfix\">"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_right\"></div>"
             . "				</div>"
             . "				<div class=\"redPackage\">"
             . "					<p class=\"p1\">剩余</p>"
             . "					<p class=\"p2\"><span class=\"redNum\">1000</span>个</p>"
             . "				</div>"
             . "			</li>"
             . "			<li class=\"checkPointLi clearfix\">"
             . "				<div class=\"checkPointSub checkPointSub4\">"
             . "					<div class=\"wrap_icon\">"
             . "						<div class=\"this_state1 this_state\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_left\"></div>"
             . "					<div class=\"star_wrap clearfix\">"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star\"></div>"
             . "						<div class=\"star\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_right\"></div>"
             . "				</div>"
             . "				<div class=\"redPackage\">"
             . "					<p class=\"p1\">剩余</p>"
             . "					<p class=\"p2\"><span class=\"redNum\">1000</span>个</p>"
             . "				</div>"
             . "			</li>"
             . "			<li class=\"checkPointLi clearfix\">"
             . "				<div class=\"checkPointSub checkPointSub5\">"
             . "					<div class=\"wrap_icon\">"
             . "						<div class=\"this_state1 this_state\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_left\"></div>"
             . "					<div class=\"star_wrap clearfix\">"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star on\"></div>"
             . "						<div class=\"star\"></div>"
             . "					</div>"
             . "					<div class=\"wrap_a_bian wrap_a_right\"></div>"
             . "				</div>"
             . "				<div class=\"redPackage\">"
             . "					<p class=\"p1\">剩余</p>"
             . "					<p class=\"p2\"><span class=\"redNum\">1000</span><span class=\"s1\">个</span></p>"
             . "				</div>"
             . "			</li>"
             . "		</ul>"
             . "		<div class=\"startQuestion\"></div>"
             . "		<div class=\"page0_bottom\"></div>"
             . "	</div>"
             . "	<!-- 答题页 -->"
             . "	<div class=\"page3 page\"  style=\"display:none;\">"
             . "		<div class=\"page_bg2\"></div>"
             . "		<div class=\"wrap_page3\">"
             . "			<div class=\"question rz3\"><span class=\"xulie\"></span><span class=\"state\"></span><span class=\"content\"></span></div>"
             . "			<ul class=\"answer rz3\">"
             . "				<li class=\"answerSub clearfix\">"
             . "					<div class=\"select on\"></div>"
             . "					<div class=\"content\"></div>"
             . "				</li>"
             . "				"
             . "				<li class=\"answerSub clearfix\">"
             . "					<div class=\"select on\"></div>"
             . "					<div class=\"content\"></div>"
             . "				</li>"
             . "					<li class=\"answerSub clearfix\">"
             . "					<div class=\"select on\"></div>"
             . "					<div class=\"content\"></div>"
             . "				</li>"
             . "					<li class=\"answerSub clearfix\">"
             . "					<div class=\"select on\"></div>"
             . "					<div class=\"content\"></div>"
             . "				</li>"
             . "			</ul>"
             . "			<div class=\"rz3 button countDown_wrap\">"
             . "				<div class=\"countDown\"></div>"
             . "			</div>"
             . "			<div class=\"rz3 tips_wrap\">"
             . "				<span class=\"cp_name\"></span>"
             . "				<span>"
             . "					第<span class=\"currentsQusetion\"></span>题/共10题"
             . "				</span>"
             . "				<span class=\"score\"></span>"
             . "			</div>"
             . "		</div>"
             . "		<div class=\"page_bottom\"></div>"
             . "	</div>"
             . "	<!-- 结算页面 -->"
             . "	<div class=\"page4 page\" style=\"display:none;\">"
             . "		<div class=\"page_bg3\"></div>"
             . "		<div class=\"page0_top\"></div>"
             . "		<div class=\"success\">"
             . "             <img class=\"gif\" src=\"\"/>"
             . "			<div class=\"page4_title\">"
             . "				<div class=\"title_word title_1\"></div>"
             . "			</div>"
             . "			<!--<div class=\"page4_icon\"></div>-->"
             . "			<div class=\"font_wrap\">"
             . "				<p class=\"p4\">恭喜！您的分数</p>"
             . "				<p class=\"p1\"><span class=\"page4_score\"></span>分</p>"
             . "				<p class=\"p2\">您已成功闯关<span class=\"page4_index\">一</span>星关卡</p>"
             . "			 	<p class=\"p3\">成胜追击吧! 继续努力！</p>"
             . "			</div>"
             . "		 	<div class=\"button_wrap clearfix\">"
             . "		 		<div class=\"continueGame button\">"
             . "					<i class=\"button_word word_continue\"></i>"
             . "		 		</div>"
             . "				<div class=\"endGame button\">"
             . "					<i class=\"button_word word_end\"></i>"
             . "				</div>"
             . "		 	</div>"
             . "		</div>"
             . "		<div class=\"failed\">"
             . "             <img class=\"gif\" src=\"\"/>"
             . "			<div class=\"page4_title\">"
             . "				<div class=\"title_word title_1\"></div>"
             . "			</div>"
             . "			<!--<div class=\"page4_icon\"></div>-->"
             . "			<div class=\"font_wrap\">"
             . "				<p class=\"p4\">很遗憾！您的分数</p>"
             . "				<p class=\"p1\"><span class=\"page4_score\"></span>分</p>"
             . "				<p class=\"p2\">您此次挑战失败！</p>"
             . "			 	<p class=\"p3\">请再接再励，再挑战一次吧！</p>"
             . "			 </div>"
             . "		 	<div class=\"button_wrap clearfix\">"
             . "		 		<div class=\"continueGame button\">"
             . "					<i class=\"button_word word_continue\"></i>"
             . "		 		</div>"
             . "				<div class=\"endGame button\">"
             . "					<i class=\"button_word word_end\"></i>"
             . "				</div>"
             . "		 	</div>"
             . "		</div>"
             . "		<div class=\"page0_bottom\"></div>"
             . "	</div>"
             . "	<!-- 转圈圈 -->"
             . "	<div class=\"popup popup_error error_tips\">"
             . "		<div class=\"popup_main\">"
             . "				<div class=\"popup_icon1\"></div>"
             . "				<!--<p class=\"p1 error_aa\">很遗憾回答错误!</p>-->"
             . "				<div class=\"popup_line1\"></div>"
             . "				<p class=\"p2\"><span class=\"s1\">正确答案：</span><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>"
             . "				<p class=\"p3\"><span class=\"s1\">正确答案：</span></p>"
             . "				<div class=\"popup_scroll\">"
             . "					<p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>"
             . "					<p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>"
             . "					<p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>"
             . "					<p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>"
             . "				</div>"
             . "			<div class=\"down\"></div>"
             . "			<div class=\"button next\">"
             . "				<i class=\"button_word word_next\"></i>"
             . "			</div>"
             . "		</div>"
             . "	</div>"
             . "	<div class=\"popup popup_success\" >"
             . "		<div class=\"popup_main\">"
             . "		</div>"
             . "	</div>"
             . "	<div class=\"popup popup_rule\">"
             . "		<div class=\"popup_main\">"
             . "			<div class=\"close\"></div>"
             . "			<p class=\"rule_title\"></p>"
             . "			<div class=\"rule_line\"></div>"
             . "			<div class=\"popup_rule_scroll\">"
             . "				<p class=\"p1\"><span class=\"s2\">1. </span>竞赛共分为5个等级：<span class=\"s1\">初出茅庐、略有小成、出类拔萃、登峰造极、举世无双</span>。每级各有<span class=\"s1\">10</span>题，答对<span class=\"s1\">9</span>题闯关成功。</p>"
             . "				<p class=\"p1\"><span class=\"s2\">2. </span>每级奖励均有<span class=\"s1\">数量限制</span>，过关可领取相应级别奖励，奖完即止。如相应级别奖励已领完，可以在剩余低级别奖励中<span class=\"s1\">任选</span>领取。</p>"
             . "				<p class=\"p1\"><span class=\"s2\">3. </span>闯关成功，可选择领取本级别奖励或继续挑战下一级别，挑战下一级别失败，则退回上一级别。</p>"
             . "			</div>"
             . "		</div>"
             . "	</div>"
             . "	<div class=\"popup popup_tips popup_tip\">"
             . "		<div class=\"popup_main\">"
             . "			<div class=\"close\"></div>"
             . "			<p class=\"p1\">请点击《平安静安》公众号下方菜单栏中《领取红包》按钮，领取您的大红包吧</p>"
             . "		</div>"
             . "	</div>"
             . "	<div class=\"popup popup_tips2 popup_tip\" >"
             . "		<div class=\"popup_main\">"
             . "			<div class=\"close\"></div>"
             . "			<p class=\"p1\">红包以发放完毕</p>"
             . "		</div>"
             . "	</div>"
             . "</div>"
             . "</body>"
             . "<script src=\"../project/AntiDrug20180626/js/load.js?\"></script>"
             . "<script src=\"http://apps.bdimg.com/libs/jquery/1.8.1/jquery.min.js\"></script>"
             . "<script src=\"../project/AntiDrug20180626/js/adapt.js\"></script>"
             . "<script src=\"../project/AntiDrug20180626/js/main.js\"></script>"
             . "<script type=\"text/javascript\">"
             . "   define = null;"
             . "   require = null;"
             . "</script>"
             . "<script type=\"http://res.wx.qq.com/open/js/jweixin-1.2.0.js\"></script>"
             . "<script type=\"text/javascript\">"
             . "	// wx.checkJsApi({"
             . "	//     jsApiList: ['onMenuShareTimeline'], // 需要检测的JS接口列表，所有JS接口列表见附录2,"
             . "	//     success: function(res) {"
             . "	//     // 以键值对的形式返回，可用的api值true，不可用为false"
             . "	//     // 如：{\"checkResult\":{\"chooseImage\":true},\"errMsg\":\"checkJsApi:ok\"}"
             . "	//     	console.log(res);"
             . "	//     }"
             . "	// });"
             . "</script>"
             . "</html>"
            ."";
        }
}
