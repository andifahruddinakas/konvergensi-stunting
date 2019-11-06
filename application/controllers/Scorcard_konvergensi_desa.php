<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scorcard_konvergensi_desa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect(base_url("scorcard-konvergensi-desa/tampil"));
    }

    public function tampil($kuartal = NULL, $tahun = NULL)
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
        } else {
            if ($kuartal == 1) {
                $batasBulanBawah = 1;
                $batasBulanAtas  = 3;
            } else if ($kuartal == 2) {
                $batasBulanBawah = 4;
                $batasBulanAtas  = 6;
            } else if ($kuartal == 3) {
                $batasBulanBawah = 7;
                $batasBulanAtas  = 9;
            } else if ($kuartal == 4) {
                $batasBulanBawah = 10;
                $batasBulanAtas  = 12;
            } else {
                die("Terjadi Kesalahan di kuartal!");
            }
        }

        if ($kuartal == NULL || $tahun == NULL) {
            if ($tahun == NULL) {
                $tahun = date("Y");
            }
            $kuartal = $_kuartal;
            redirect(base_url('scorcard-konvergensi-desa/tampil/') . $kuartal . '/' . $tahun);
        }

        $JTRT_IbuHamil  = $this->m_data->select("ibu_hamil.no_kia as no_kia");
        $JTRT_IbuHamil  = $this->m_data->distinct();
        $JTRT_IbuHamil  = $this->m_data->getJoin("kia", "ibu_hamil.no_kia = kia.no_kia", "INNER");
        $JTRT_IbuHamil  = $this->m_data->getWhere("MONTH(ibu_hamil.created_at) >=", $batasBulanBawah);
        $JTRT_IbuHamil  = $this->m_data->getWhere("MONTH(ibu_hamil.created_at) <=", $batasBulanAtas);
        $JTRT_IbuHamil  = $this->m_data->getWhere("YEAR(ibu_hamil.created_at)", $tahun);
        $JTRT_IbuHamil  = $this->m_data->getData("ibu_hamil")->result();

        $JTRT_BulananAnak  = $this->m_data->select("bulanan_anak.no_kia as no_kia");
        $JTRT_BulananAnak  = $this->m_data->distinct();
        $JTRT_BulananAnak  = $this->m_data->getJoin("kia", "bulanan_anak.no_kia = kia.no_kia", "INNER");
        $JTRT_BulananAnak  = $this->m_data->getWhere("MONTH(bulanan_anak.created_at) >=", $batasBulanBawah);
        $JTRT_BulananAnak  = $this->m_data->getWhere("MONTH(bulanan_anak.created_at) <=", $batasBulanAtas);
        $JTRT_BulananAnak  = $this->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
        $JTRT_BulananAnak  = $this->m_data->getData("bulanan_anak")->result();

        foreach ($JTRT_IbuHamil as $item_ibuHamil) {
            $dataNoKia[]    = $item_ibuHamil;
            foreach ($JTRT_BulananAnak as $item_bulananAnak) {
                if (!in_array($item_bulananAnak, $dataNoKia)) {
                    $dataNoKia[]    = $item_bulananAnak;
                }
            }
        }

        $ibu_hamil              = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);
        $bulanan_anak           = $this->rekap->get_data_bulanan_anak($kuartal, $tahun);
        
        //HITUNG HASIL PENGUKURAN TIKAR PERTUMBUHAN
        $tikar  = array("TD" => 0, "M" => 0, "K" => 0, "H" => 0);
        foreach ($bulanan_anak["dataGrup"] as $detail) {
            $totalItem = count($detail);
            $i = 0;
            foreach ($detail as $item) {
                if (++$i === $totalItem) {                    
                    $tikar[$item["status_tikar"]]++;
                }
            }
        }        

        //HITUNG KEK ATAU RISTI
        $jumlahKekRisti = 0;
        foreach ($ibu_hamil["dataFilter"] as $item) {
            if ($item["user"]["status_kehamilan"] != "NORMAL") {
                $jumlahKekRisti++;
            }
        }

        $jumlahGiziBukanNormal  = 0;
        foreach ($bulanan_anak["dataFilter"] as $item) {
            if ($item["umur_dan_gizi"]["status_gizi"] != "N") {
                $jumlahGiziBukanNormal++;
            }
        }


        $data["JTRT"]                   = sizeof($dataNoKia);
        $data["jumlahKekRisti"]         = $jumlahKekRisti;
        $data["jumlahGiziBukanNormal"]  = $jumlahGiziBukanNormal;
        $data["tikar"]                  = $tikar;
        $data["ibu_hamil"]              = $ibu_hamil;
        $data["bulanan_anak"]           = $bulanan_anak;
        $data['title']                  = "Scorcard Konvergensi Desa";
        $data["dataTahun"]              = $data["ibu_hamil"]["dataTahun"];
        $data['kuartal']                = $kuartal;
        $data['_tahun']                 = $tahun;

        return $this->loadView('scorcard-konvergensi.show-scorcard', $data);
    }
}
