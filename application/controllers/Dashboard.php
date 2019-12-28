<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();    
    }

    public function index()
    {       
        $totalPosyandu  = $this->m_data->getData("posyandu")->num_rows();

        $totalKpm       = $this->m_data->getWhere("level", "admin");
        $totalKpm       = $this->m_data->getData("user")->num_rows();

        if ($this->session->userdata("login")->level !== "super_admin") {
            $ibuHamilBulanIni = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        }
        $ibuHamilBulanIni   = $this->m_data->getWhere("MONTH(created_at)", date('m'));
        $ibuHamilBulanIni   = $this->m_data->getWhere("YEAR(created_at)", date('Y'));
        $ibuHamilBulanIni   = $this->m_data->getData("ibu_hamil")->num_rows();

        if ($this->session->userdata("login")->level !== "super_admin") {
            $ibuHamilSemua = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        }
        $ibuHamilSemua      = $this->m_data->getData("ibu_hamil")->num_rows();

        if ($this->session->userdata("login")->level !== "super_admin") {
            $anakBulanIni = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        }
        $anakBulanIni   = $this->m_data->getWhere("MONTH(created_at)", date('m'));
        $anakBulanIni   = $this->m_data->getWhere("YEAR(created_at)", date('Y'));
        $anakBulanIni   = $this->m_data->getData("bulanan_anak")->num_rows();

        if ($this->session->userdata("login")->level !== "super_admin") {
            $anakSemua = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        }
        $anakSemua      = $this->m_data->getData("bulanan_anak")->num_rows();
        

        $data["totalPosyandu"]      = $totalPosyandu;
        $data["ibuHamilBulanIni"]   = $ibuHamilBulanIni;
        $data["ibuHamilSemua"]      = $ibuHamilSemua;
        $data["anakBulanIni"]       = $anakBulanIni;
        $data["anakSemua"]          = $anakSemua;
        $data["totalKpm"]           = $totalKpm;        
        $data["aktif"]              = "dashboard";
        return $this->loadView('dashboard', $data);
    }
}
