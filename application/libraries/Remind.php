<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    /**
     * @author cxf
     */

    class Remind
    {

        public static function set($url, $data, $class='success')
        {
            $CI = & get_instance();
            $CI->load->library('session');
			
		    if($class=='success'){
                $CI->session->set_flashdata('remind', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>'.$data.'</div>');
			}elseif($class=='error'){
				$CI->session->set_flashdata('remind', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>'.$data.'</div>');
			}
			if ($url)
                redirect($url);
        }

        public static function get()
        {
            $CI = & get_instance();
            $CI->load->library('session');
            return $CI->session->flashdata('remind');
        }

    }

?>