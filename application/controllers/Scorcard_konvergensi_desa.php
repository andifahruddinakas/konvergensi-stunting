<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scorcard_konvergensi_desa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
    }

    public function index(){
        redirect(base_url("scorcard-konvergensi-desa/tampil"));
    }

    public function tampil($kuartal = NULL, $tahun = NULL){
        if ($kuartal < 1 || $kuartal > 4) {
            $kuartal = NULL;
        }

        if ($kuartal == NULL) {
            $bulanSekarang = date('m');

            if ($bulanSekarang <= 3) {
                $_kuartal        = 1;
            } else if ($bulanSekarang <= 6) {
                $_kuartal        = 2;
            } else if ($bulanSekarang <= 9) {
                $_kuartal        = 3;
            } else if ($bulanSekarang <= 12) {
                $_kuartal        = 4;
            }
        }

        if ($kuartal == NULL || $tahun == NULL) {
            if ($tahun == NULL) {
                $tahun = date("Y");
            }
            $kuartal = $_kuartal;
            redirect(base_url('scorcard-konvergensi-desa/tampil/') . $kuartal . '/' . $tahun);
        }

        $data["ibu_hamil"]      = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);
        $data["bulanan_anak"]   = $this->rekap->get_data_bulanan_anak($kuartal, $tahun);
        $data['title']          = "Formulir Bantu Mengikuti Layanan PAUD Anak 2 s/d 6 Tahun";
        $data["dataTahun"]      = $data["ibu_hamil"]["dataTahun"];
        $data['kuartal']        = $kuartal;
        $data['_tahun']         = $tahun;
        return $this->loadView('scorcard-konvergensi.show-scorcard', $data);
    }
}