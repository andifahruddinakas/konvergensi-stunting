<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    public function ___construct(){
        parent::___construct();
    }

    public function index(){
        return $this->loadView('profile.show-profile');
    }

}