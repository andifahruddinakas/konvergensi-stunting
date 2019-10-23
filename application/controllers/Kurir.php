<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kurir extends MY_Controller
{
    public function ___construct()
    {
        parent::___construct();
    }

    public function index()
    {
        return $this->loadView('petugas.kurir.show-kurir');
    }

    public function tambah()
    {
        return $this->loadView('petugas.kurir.tambah-kurir');
    }
}
