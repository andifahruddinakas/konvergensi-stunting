<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->global_data = [];
    }

    function loadView($view, $local_data = array())
    {
        $data = array_merge($this->global_data, $local_data);
        return view($view, $data);
    }
}
