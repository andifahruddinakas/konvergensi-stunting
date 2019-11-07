<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_data');
        $CI = &get_instance();
        $this->global_data = [
            "app_name"          => "E-PPKPS",
            "app_complete_name" => "Pengelolaan dan Pelaporan Konvergensi Pencegahan Stunting Elektronik ",
            "CI"                => $CI,
            "aktif"             => NULL
        ];

        if ($this->router->fetch_class() != "auth") {
            if (!$this->session->has_userdata('login')) {
                redirect(base_url("login"));
            }
        }
    }

    function loadView($view, $local_data = array(), $ses = NULL)
    {
        $data = array_merge($this->global_data, $local_data);
        return view($view, $data);
    }
}
