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

        $totalIbuHamil  = $this->m_data->getWhere("MONTH(created_at)", date('m'));
        $totalIbuHamil  = $this->m_data->getWhere("YEAR(created_at)", date('Y'));
        $totalIbuHamil  = $this->m_data->getData("bulanan_anak")->num_rows();

        // d($totalIbuHamil);


        
        $data["aktif"]  = "dashboard";
        return $this->loadView('dashboard', $data);
    }
}
