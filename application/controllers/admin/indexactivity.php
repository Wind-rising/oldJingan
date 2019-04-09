<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexActivity extends BaseController{
    function __construct(){
        parent::__construct();
    }

    public function uploadactivityImg(){
        $maxSize = 1024*500;
        $ext_arr = array('jpg', 'jpeg', 'png', 'bmp');
        $fileElementName = 'upload_goods_img';
        if($_FILES[$fileElementName]['size'] > $maxSize){
            $status = false;
            $info = '文件过大，请小于'.($maxSize/1024)."Kb";
        }else if(!empty($_FILES[$fileElementName]['error'])){
            switch($_FILES[$fileElementName]['error']){
                case '1':
                    $error = '上传失败';
                    break;
                case '2':
                    $error = '上传失败';
                    break;
                case '3':
                    $error = '上传失败';
                    break;
                case '4':
                    $error = '上传内容不能为空';
                    break;
                case '6':
                    $error = '系统缺少临时文件夹';
                    break;
                case '7':
                    $error = '写文件失败';
                    break;
                case '8':
                    $error = '上传文件类型不匹配';
                    break;
                case '999':
                default:
                    $error = '未知错误';
            }
            $status = false;
            $info = $error;
        }elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
            $status = false;
            $info   ='没有上传文件...';
        }else{
            //获得文件扩展名
            $file_name = htmlspecialchars($_FILES[$fileElementName]['name']);
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            if(in_array($file_ext, $ext_arr) === false) {
                $status = false;
                $info   ='文件类型不能上传';
            }else{
                $local_dirname = config_item('SITE_ROOT')."static/uploads/types/";
                $remote_dirname = config_item('FTP_UPLOADS_DIR')."types/";
                $upfilename = "activity_".time().rand(0, 1000).".".$file_ext;

                move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $local_dirname.$upfilename);
                @chmod($local_dirname.$upfilename, 0777);

                $status = true;
                $info = 'types/'.$upfilename;
            }
        }
        if($status == true){
            $this->json_return(true, "", $info);
        }else{
            $this->json_return(false, "", $info);
        }
    }
}
