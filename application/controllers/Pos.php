<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends MY_Controller {
    public function ___construct(){
        parent::___construct();
    }

    public function index(){
        return $this->loadView('petugas.pos.show-pos');
    }

    public function tambah(){
        return $this->loadView('petugas.pos.tambah-pos');
    }

}