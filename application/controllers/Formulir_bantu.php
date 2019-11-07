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

    public function layanan_paud($kuartal = NULL, $tahun = NULL){
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
            redirect(base_url('formulir-bantu/layanan-paud/') . $kuartal . '/' . $tahun);
        }
      

        $data['title']      = "Formulir Bantu Mengikuti Layanan PAUD Anak 2 s/d 6 Tahun";
        $data['kuartal']    = $kuartal;
        $data['_tahun']     = $tahun;
        $data["aktif"]      = "formulir_bantu";

        return $this->loadView('formulir-bantu.layanan-paud', $data);
    }

    public function capaian_penerimaan_layanan($kuartal = NULL, $tahun = NULL)
    { 
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
            redirect(base_url('formulir-bantu/capaian-penerimaan-layanan/') . $kuartal . '/' . $tahun);
        }

        $data["ibu_hamil"]      = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);
        $data["bulanan_anak"]   = $this->rekap->get_data_bulanan_anak($kuartal, $tahun);
        $data['title']          = "Formulir Bantu Capaian Penerimaan Layanan";
        $data["dataTahun"]      = $data["ibu_hamil"]["dataTahun"];
        $data["_tahun"]         = $tahun;
        $data["kuartal"]        = $kuartal;
        $data["aktif"]          = "formulir_bantu";
        return $this->loadView('formulir-bantu.capaian-penerimaan-layanan', $data);
    }

    public function konvergensi_desa($kuartal = NULL, $tahun = NULL){
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
            redirect(base_url('formulir-bantu/konvergensi-desa/') . $kuartal . '/' . $tahun);
        }

        $data["ibu_hamil"]      = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);
        $data["bulanan_anak"]   = $this->rekap->get_data_bulanan_anak($kuartal, $tahun);        
        $data["dataTahun"]      = $data["ibu_hamil"]["dataTahun"];
        $data["_tahun"]         = $data["ibu_hamil"]["_tahun"];
        $data["kuartal"]        = $kuartal;
        $data['title']          = "Formulir Bantu Konvergensi Desa";
        $data["aktif"]          = "formulir_bantu";
        return $this->loadView('formulir-bantu.konvergensi-desa', $data);

    }
}
