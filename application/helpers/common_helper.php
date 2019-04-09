<?php

function p($var)
{
	var_dump($var);
}

function pe($var)
{
	var_dump($var);
	exit();
}

//返回静态文件的路径
function static_url()
{
	return config_item('static_url');
}

//登录url
function login_url()
{
	return config_item('login_url');
}

//默认url
function default_url()
{
	return config_item('default_url');
}

//站点url
function root_url()
{
	return config_item('root_url');
}

function SITE_ROOT(){
	return config_item('SITE_ROOT');
}

//凯翼URL
function kaiyi_url()
{
	return config_item('root_url').'index.php/kaiyi/index';
}

//项目url
function app_url()
{
	return config_item('app_url');
}

//图片url
function img_url()
{
    return config_item('img_url');
}

//积分商品细节的颜色属性
function POINT_GOODS_COLOR()
{
	return config_item('POINT_GOODS_COLOR');
}

//远程地址
function REMOTE_HOST()
{
	return config_item('REMOTE_HOST');
}

//转义单引号和双引号为
function escape_input_character($input_value)
{
	$input_value = str_replace('"', "&#34;", $input_value);
	$input_value = str_replace("'", "&#39;", $input_value);
	return $input_value;
}

//获取微信配置
function WEIXIN(){
    return config_item('WEIXIN');
}

//商品订单状态
function GOODS_ORDER()
{
	return config_item('GOODS_ORDER');
}

//FCode状态
function FCODE_STATUS()
{
	return config_item('FCODE_STATUS');
}

//支付信息状态
function PAY_TARGET_TYPE()
{
	return config_item('PAY_TARGET_TYPE');
}

//退款审核阶段
function REFUND_STATUS(){
	return config_item('REFUND_STATUS');
}

//特价车订单状态
function SALE_ORDER_STATUS(){
	return config_item('SALE_ORDER_STATUS');
}
//积分商品订单状态
function POINT_ORDER(){
	return config_item('POINT_ORDER');
}

//ftp发送文件
function ftp_upload($source_file, $destination_dir, $destination_file)
{
	$conn_id = ftp_connect(config_item('FTP_HOST'), config_item('FTP_PORT'), 20);
	if ($conn_id === false) {
		return false;
	}
	$login_result = ftp_login($conn_id, config_item('FTP_USER'), config_item('FTP_PWD'));
	ftp_pasv($conn_id, true);
	if (!$conn_id || !$login_result) {
		return false;
	}

	if (!@ftp_chdir($conn_id, $destination_dir)) {
		ftp_mkdir($conn_id, $destination_dir);
		ftp_chdir($conn_id, $destination_dir);
	}
	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
	ftp_close($conn_id);
	if ($upload) {
		return true;
	} else {
		return false;
	}
}

function isMobileRequest()
{
	static $isMobile = null;
	if ($isMobile !== null) {
		return $isMobile;
	}
	$devices = array();
	$devices["android"] = "android";
	$devices["blackberry"] = "blackberry";
	$devices["iphone"] = "(iphone|ipod)";
	$devices["opera"] = "(opera mini|opera mobi|presto)";
	$devices["palm"] = "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)";
	$devices["windows"] = "windows ce; (iemobile|ppc|smartphone)";
	$devices["generic"] = "(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)";

	$isMobile = false;
	if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
		$isMobile = true;
	} elseif (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml') > 0 || strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') > 0)) {
		$isMobile = true;
	} elseif (isset($_SERVER['HTTP_USER_AGENT'])) {
		foreach ($devices as $device => $regexp) {
			if (preg_match("/".$regexp."/i", $_SERVER['HTTP_USER_AGENT'])) {
				$isMobile = true;
			}
		}
	}
	return $isMobile;
}

	function getIP()
	{
	$ip = false;
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) {
			array_unshift($ips, $ip);
			$ip = FALSE;
		}
		for ($i = 0; $i < count($ips); $i++) {
			if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
				$ip = $ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}

	/*
     * CURL POST
     * @param string $url 请求接口地址
     * @param string $data 请求数据
     * @param int $timeout 超时限制
     * @return string $response 接口返回
     */

    function get_api($url, $request = NULL, $type = 'POST', $timeout = 60) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => $timeout,
        ));
        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        if (curl_errno($ch)) {
            $response = curl_errno($ch);
        } else {
            $response = curl_exec($ch);
        }
        curl_close($ch);
        return $response;
    }

	  function csvencode($array)
    {
        $csv = '';
        foreach ($array as $line) {
            if (!empty($csv)) {
                $csv .= "\n";
            }
            $csv_l = '';
            foreach ($line as $i => $item) {
                if ($i > 0) {
                    $csv_l .= ',';
                }
                $csv_l .= escape($item);
            }
            $csv .= $csv_l;
        }
        return $csv;
    }

    function csvdecode($csv)
    {
        $csv = trim($csv);
        $array = array();
        $line = 0;
        $offset = 0;
        $item = '';
        $fp = FALSE;
        while (isset($csv{$offset})) {
            if (!isset($array[$line])) {
                $array[$line] = array();
            }
            switch ($csv{$offset}) {
                case '"':
                    $c = 0;
                    while (isset($csv{$offset}) AND $csv{$offset} === '"') {
                        $c++;
                        $offset++;
                    }
                    if ($c % 2 === 1) {
                        $fp = !$fp;
                        $c--;
                    }
                    if ($c > 0) {
                        $item .= str_repeat('"', $c);
                    }
                    break;
                case ',':
                    if ($fp === FALSE) {
                        $array[$line][] = escape($item, 'D');
                        $item = '';
                    } else {
                        $item .= $csv{$offset};
                    }
                    $offset++;
                    break;
                case "\n":
                    if ($fp === FALSE) {
                        $array[$line][] = escape($item, 'D');
                        $item = '';
                        $line++;
                    } else {
                        $item .= $csv{$offset};
                    }
                    $offset++;
                    break;
                default:
                    $item .= $csv{$offset};
                    $offset++;
            }
            if (!isset($csv{$offset})) {
                $array[$line][] = escape($item, 'D');
            }
        }
        return $array;
    }

    function escape($str, $coding = 'E')
    {
        if ($coding === 'E') {
            if (strpos($str, '"') !== FALSE) {
                $str = str_replace('"', '""', $str);
            }
            if (strpos($str, ',') !== FALSE OR strpos($str, "\n") !== FALSE) {
                $str = '"' . $str . '"';
            }
        } else {
            if ($str != '""') {
                $str = str_replace('""', '"', $str);
            } else {
                $str = '';
            }
        }
        return $str;
    }


function getHtml($url, $config = null)
{
    ini_set('default_socket_timeout', 3);
    $header = "User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36\r\n";
    $method = 'GET';
    $content = '';
    if (strtoupper(get($config, 'method', 'GET')) == 'POST') {
        $method = 'POST';
        $content = get($config, 'content', '');
    }
    $opts = array(
        'http' => array(
            'header' => $header . "Content-type: application/x-www-form-urlencoded ",
            'method' => $method,
            'content' => $content
        )
    );
    $context = stream_context_create($opts);
    if (!empty($url)) {
        $html = file_get_contents($url, false, $context);
        if ($html) {
            $charset = get($config, 'charset', 'utf-8');
            $sourceCharset = get($config, 'source_charset', 'gbk');
            if ($charset != $sourceCharset) {
                $html = iconv($sourceCharset, $charset . '//IGNORE', $html);
            }
            return $html;
        }
    }
    return false;
}

function get(&$array, $key, $default = null)
{
    if (isset($array[$key])) {
        return $array[$key];
    }
    return $default;
}

function arrayValueIndex(&$list, $name)
{
    $ret = array();
    foreach ($list as &$item) {
        $ret[$item[$name]] = &$item;
    }
    unset($item);
    return $ret;
}

function arrayValueList()
{
    $ret = array();
    $argList = func_get_args();
    $name = end($argList);
    if (!is_string($name)) {
        return $ret;
    }
    foreach ($argList as $arg) {
        if (!empty($arg) && is_array($arg)) {
            foreach ($arg as $item) {
                if (isset($item[$name])) {
                    $ret[] = $item[$name];
                }
            }
        }
    }
    return array_unique($ret);
}

function isWeixinRequest()
{
    static $isWeixin = null;
    if ($isWeixin !== null) {
        return $isWeixin;
    }

    $isWeixin = false;
    if (preg_match("/micromessenger/i", $_SERVER['HTTP_USER_AGENT'])) {
        $isWeixin = true;
    }
    return $isWeixin;
}

function requestGetString($name, $default = '')
{
    $ret = $default;
    if (isset($_REQUEST[$name])) {
        $ret = $_REQUEST[$name];
    }
    return $ret;
}


function requestGetInt($name, $default = 0)
{
    $ret = null;
    if ($default !== null) {
        $ret = intval($default);
    }
    if (isset($_REQUEST[$name]) && $_REQUEST[$name] !== '') {
        $ret = intval(trim($_REQUEST[$name]));
    }
    return $ret;
}