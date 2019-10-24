<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    public function ___construct(){
        parent::___construct();
        // $this->lang->load()
    }

    public function index(){
        return $this->loadView('dashboard');
    }
}
