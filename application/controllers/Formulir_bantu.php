<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Formulir_bantu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
    }

    public function index()
    {
        redirect(base_url());
    }

    public function capaian_penerimaan_layanan($kuartal = NULL, $tahun = NULL)
    { 
        $data = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);

        debug($data);
    }
}
