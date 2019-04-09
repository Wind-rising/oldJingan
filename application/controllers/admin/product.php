<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends My_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("Loan_model");
        $this->load->model("Series_model");
        $this->load->model("Types_model");
        $this->load->model("Spec_model");
        $this->load->model("Region_model");
    }

    public function index()
    {
        $seriesList = $this->Series_model->getList(true);
        $seriesId = isset($_GET['id']) ? $_GET['id']: $seriesList[0]['series_id'];
        $typesList = $this->Types_model->getListById($seriesId);
        $newTypesList = array();
        foreach ($typesList as $tk => &$tv) {
            $tv['lowest'] = $this->findLowestLoan($tv['types_id']);
            if ('-' != $tv['lowest']['monthly'] || '-' != $tv['lowest']['down_payments']) {
                $newTypesList[] = $tv;
            }
        }

        $viewData = array();
        $viewData['series_id'] = $seriesId;
        $viewData['series_list'] = $seriesList;
        $viewData['types_list'] = $newTypesList;

        $this->load->view('product/product_index.php', $viewData);
    }

    public function lists()
    {
        $typesId = isset($_GET['id']) ? $_GET['id']: 0;
        if (empty($typesId)) {
            header('Location: index');
        }
        $typesInfo = $this->Types_model->getById($typesId);
        if (empty($typesInfo)) {
            header('Location: index');
        }
        $specList = $this->Spec_model->getListById($typesId);
        sort($specList);
        $loanTypesList = $this->Loan_model->getRelationList(array($typesId));
        $loanIdList = arrayValueList($loanTypesList, 'loan_id');
        $loanList = $this->Loan_model->getListByIdList($loanIdList);
        foreach ($loanList as $lk => &$lv) {
            $lv['down_payments'] = explode('-', $lv['down_payments']);
            sort($lv['down_payments']);
            $lv['lowest_payments'] = $lv['down_payments'][0];
            $lv['real_monthly'] = $this->getRealMonthly($specList[0]['guidance_price'], $lv['down_payments'][0], $lv['monthly']);
        }

        $viewData = array();
        $viewData['info'] = $typesInfo;
        $viewData['spec_list'] = $specList;
        $viewData['loan_list'] = $loanList;

        $this->load->view('product/product_list.php', $viewData);
    }

    protected function findLowestLoan($typesId)
    {
        $lowest = array();
        $lowest['down_payments'] = '-';
        $lowest['monthly'] = '-';

        $specList = $this->Spec_model->getListById($typesId);
        $loanTypesList = $this->Loan_model->getRelationList(array($typesId));
        $loanIdList = arrayValueList($loanTypesList, 'loan_id');
        $loanList = $this->Loan_model->getListByIdList($loanIdList);
        foreach ($specList as $sk => $sv) {
            foreach ($loanList as $lk => $lv) {
                $paymentsList = explode('-', $lv['down_payments']);
                foreach ($paymentsList as $pk => $pv) {
                    if ('-' == $lowest['down_payments']) {
                        $lowest['down_payments'] = $pv;
                    }
                    if ($pv < $lowest['down_payments']) {
                        $lowest['down_payments'] = $pv;
                    }
                    $realMonthly = $this->getRealMonthly($sv['guidance_price'], $pv, $lv['monthly']);
                    if ('-' == $lowest['monthly']) {
                        $lowest['monthly'] = $realMonthly;
                    }
                    if ($realMonthly < $lowest['monthly']) {
                        $lowest['monthly'] = $realMonthly;
                    }
                }
            }
        }

        return $lowest;
    }

    protected function getRealMonthly($guidancePrice, $downPayments, $monthly)
    {
        $loanMount = $guidancePrice*(1 - $downPayments/100);
        $real = $loanMount/10000*$monthly;

        return $this->getTwoDecimalPlaces($real);
    }

    protected function getTwoDecimalPlaces($num)
    {
        if (empty($num)) {
            return 0;
        }
        $pos = strpos($num, '.');
        $len = $pos+3;

        return substr($num, 0, $len);
    }

    public function apply()
    {
        $typesId = isset($_GET['types_id']) ? $_GET['types_id'] : 0;
        $typesList = $this->Types_model->getList(true);
        $pidList = $this->Region_model->getAgentProvence();
        $pidList = arrayValueList($pidList, 'pid');
        $provinceList = $this->Region_model->getListByIdList($pidList);

        $viewData = array();
        $viewData['types_id'] = $typesId;
        $viewData['types_list'] = $typesList;
        $viewData['province_list'] = $provinceList;

        $this->load->view('product/apply.php', $viewData);
    }

    public function ajaxGetRegion()
    {
        $result = array();
        $result['result'] = false;

        $parentId = $_POST['parent_id'];
        if (!empty($parentId)) {
            $regionIdList = $this->Region_model->getAgentCity($parentId);
            $regionIdList = arrayValueList($regionIdList, 'cid');
            $regionList = $this->Region_model->getListByIdList($regionIdList);

            $result['list'] = $regionList;
            $result['result'] = true;
        }

        exit(json_encode($result));
    }

    public function ajaxGetAgent()
    {
        $result = array();
        $result['result'] = false;

        $cityId = $_POST['cid'];
        if (!empty($cityId)) {
            $agentList = $this->Region_model->getAgent($cityId);

            $result['list'] = $agentList;
            $result['result'] = true;
        }

        exit(json_encode($result));
    }

    public function saveApply()
    {
        $result = array();
        $result['result'] = false;

        $info = array();
        $info['types_id'] = isset($_POST['types_id']) ? $_POST['types_id'] : 0;
        $info['types_name'] = isset($_POST['types_name']) ? $_POST['types_name'] : '';
        $info['agent_id'] = isset($_POST['agent_id']) ? $_POST['agent_id'] : 0;
        $info['user_name'] = isset($_POST['user_name']) ? $_POST['user_name'] : '';
        $info['sex'] = isset($_POST['sex']) ? $_POST['sex'] : 0;
        $info['mobile'] = isset($_POST['mobile']) ? $_POST['mobile'] : '';
        if (empty($info['types_id']) || empty($info['agent_id']) || empty($info['user_name']) || empty($info['mobile'])) {
            $result['message'] = '请完善信息!';
            exit(json_encode($result));
        }
        if (!preg_match('/1[3,4,5,6,7,8]\d{9}/', $info['mobile'])) {
            $result['message'] = '请填写正确手机号码';
            exit(json_encode($result));
        }
        $this->load->model('Agent_model');
        $agentInfo = $this->Agent_model->getById($info['agent_id']);
        if (!empty($agentInfo)) {
            $info['agent_name'] = $agentInfo['agent_name'];
            $info['province_id'] = $agentInfo['pid'];
            $info['province_name'] = $agentInfo['pro_name'];
            $info['city_id'] = $agentInfo['cid'];
            $info['city_name'] = $agentInfo['city_name'];
            $info['create_time'] = date('Y-m-d H:i:s');

            $this->load->model('Apply_model');
            $applyId = $this->Apply_model->add($info);
            if ($applyId > 0) {
                $result['result'] = true;
            }
        } else {
            $result['message'] = '经销商数据有误';
        }

        exit(json_encode($result));
    }

    public function calculator()
    {
        $typesList = $this->Types_model->getList(true);
        $loanTypesList = $this->Loan_model->getRelationList(array($typesList[0]['types_id']));
        $loanIdList = arrayValueList($loanTypesList, 'loan_id');
        $loanList = $this->Loan_model->getListByIdList($loanIdList);

        $viewData = array();
        $viewData['types_list'] = $typesList;
        $viewData['loan_list'] = $loanList;
        $this->load->view('product/calculator.php', $viewData);
    }

    public function ajaxGetSpec()
    {
        $result = array();
        $result['result'] = false;

        $typesId = isset($_POST['types_id']) ? $_POST['types_id'] : 0;
        if (!empty($typesId)) {
            $specList = $this->Spec_model->getListById($typesId);

            $result['list'] = $specList;
            $result['result'] = true;
        } else {
            $result['message'] = '请选择车型';
        }

        exit(json_encode($result));
    }

    public function ajaxGetLoan()
    {
        $result = array();
        $result['result'] = false;

        $typesId = isset($_POST['types_id']) ? $_POST['types_id'] : 0;
        if (!empty($typesId)) {
            $loanTypesList = $this->Loan_model->getRelationList(array($typesId));
            $loanIdList = arrayValueList($loanTypesList, 'loan_id');
            $loanList = $this->Loan_model->getListByIdList($loanIdList);

            $result['list'] = $loanList;
            $result['result'] = true;
        } else {
            $result['message'] = '请选择车型';
        }

        exit(json_encode($result));
    }

}