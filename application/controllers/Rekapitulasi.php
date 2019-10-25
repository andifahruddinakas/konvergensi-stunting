<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rekapitulasi extends MY_Controller
{
    public function ___construct()
    {
        parent::___construct();
    }

    public function index()
    {
        redirect(base_url());
    }

    public function ibu_hamil($bulan = NULL, $tahun = NULL){

        $ibuHamil = $this->m_data->getJoin("kia", "ibu_hamil.no_kia = kia.no_kia", "INNER");
        $ibuHamil = $this->m_data->getWhere("MONTH(ibu_hamil.created_at)", $bulan);
        $ibuHamil = $this->m_data->getWhere("YEAR(ibu_hamil.created_at)", $tahun);
        $ibuHamil = $this->m_data->order_by("ibu_hamil.created_at", "ASC");
        $ibuHamil = $this->m_data->getData("ibu_hamil")->result();

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("ibu_hamil")->result();

        $data["_bulan"]     = $bulan;
        $data["_tahun"]     = $tahun;
        $data['ibuHamil']   = $ibuHamil;
        $data['dataTahun']  = $dataTahun;
        $data['bulan']      = bulan($bulan);        
        $data['title']      = "Rekapitulasi Hasil Pemantauan 3 Bulanan Ibu Hamil";
        return $this->loadView('rekapitulasi.ibu-hamil', $data);
    }
}