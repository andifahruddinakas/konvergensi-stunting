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

    public function ibu_hamil($kuartal = NULL, $tahun = NULL)
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
            redirect(base_url('rekapitulasi/ibu-hamil/') . $kuartal . '/' . $tahun);
        }

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

        $ibuHamil = $this->m_data->select(array(
            "ibu_hamil.*",
            "kia.nama_ibu",
            "kia.nama_anak",
            "kia.jenis_kelamin_anak",
            "kia.tanggal_lahir_anak"
        ));
        $ibuHamil = $this->m_data->getJoin("kia", "ibu_hamil.no_kia = kia.no_kia", "INNER");
        $ibuHamil = $this->m_data->order_by("ibu_hamil.created_at", "ASC");
        $ibuHamil = $this->m_data->getWhere("MONTH(ibu_hamil.created_at) >=", $batasBulanBawah);
        $ibuHamil = $this->m_data->getWhere("MONTH(ibu_hamil.created_at) <=", $batasBulanAtas);
        $ibuHamil = $this->m_data->getData("ibu_hamil")->result();

        foreach ($ibuHamil as $item) {
            $item = (array) $item;
            $dataGrup[$item['no_kia']][] = $item;
        }

        foreach ($dataGrup as $key => $value) {
            $isSudahMelahirkan = FALSE;
            $dataUsiaKehamilan = -1;

            $hitungPeriksaKehamilan     = 0;
            $hitungPilFe                = 0;
            $hitungPeriksaNifas         = 0;
            $hitungKonseling            = 0;
            $hitungKunjunganRumah       = 0;
            $hitungAksesAirBersih       = 0;
            $hitungKepemilikanJamban    = 0;
            $hitungJaminanKesehatan     = 0;
            foreach ($value as $item) {
                // FIND USIA KEHAMILAN : CARI YANG TERBESAR USIA KEHAMILANYA            
                if ($item["tanggal_melahirkan"]) {
                    $isSudahMelahirkan = TRUE;
                    $tanggal_melahirkan = $item["tanggal_melahirkan"];
                }

                if ($dataUsiaKehamilan < (int) $item["usia_kehamilan"]) {
                    $dataUsiaKehamilan = (int) $item["usia_kehamilan"];
                    if ($dataUsiaKehamilan <= 3) {
                        $dataUsiaKehamilan = "0 - 3 Bulan (Trisemester 1)";
                    } else if ($dataUsiaKehamilan <= 6) {
                        $dataUsiaKehamilan = "4 - 6 Bulan (Trisemester 2)";
                    } else if ($dataUsiaKehamilan <= 9) {
                        $dataUsiaKehamilan = "7 - 9 Bulan (Trisemester 3)";
                    } else {
                        $dataUsiaKehamilan = "Ibu Bersalin";
                    }
                }

                //HITUNG PERIKSA KEHAMILAN
                if ($item["pemeriksaan_kehamilan"] == "v") {
                    $hitungPeriksaKehamilan++;
                }

                //HITUNG PIL FE
                if ($item["konsumsi_pil_fe"] == "v") {
                    $hitungPilFe++;
                }

                //HITUNG PERIKSA NIFAS
                if ($item["pemeriksaan_nifas"] == "v") {
                    $hitungPeriksaNifas++;
                }

                //HITUNG KONSELING
                if ($item["konseling_gizi"] == "v") {
                    $hitungKonseling++;
                }

                //HITUNG KUNJUNGAN RUMAH
                if ($item["kunjungan_rumah"] == "v") {
                    $hitungKunjunganRumah++;
                }

                //HITUNG AKSES AIR BERSIH
                if ($item["akses_air_bersih"] == "v") {
                    $hitungAksesAirBersih++;
                }

                //HITUNG KEPEMILIKAN JAMBAN
                if ($item["kepemilikan_jamban"] == "v") {
                    $hitungKepemilikanJamban++;
                }

                //HITUNG JAMINAN KESEHATAN
                if ($item["jaminan_kesehatan"] == "v") {
                    $hitungJaminanKesehatan++;
                }


                // FIND STATUS KEHAMILAN : DATA TERAKHIR STATUS KEHAMILAN
                $status_kehamilan   = $item["status_kehamilan"];
                $usia_kehamilan     = $item["usia_kehamilan"];
                $tanggal_melahirkan = $isSudahMelahirkan ? $tanggal_melahirkan : "-";
            }

            if ($isSudahMelahirkan) {
                //Ibu Bersalin
                $periksaKehamilan   = "TS";
                $pilFe              = "TS";
                $periksaNifas       = $hitungPeriksaNifas >= 3 ? "Y" : "T";
                $konseling          = "TS";
                $kunjunganRumah     = "TS";
            } else {
                if ($dataUsiaKehamilan <= 3) {
                    // 0 - 3 Bulan (Trisemester 1)
                    $periksaKehamilan   = $hitungPeriksaKehamilan >= 1 ? "Y" : "T";
                    $pilFe              = $hitungPilFe >= 1 ? "Y" : "T";
                    $periksaNifas       = "TS";
                    $konseling          = $hitungKonseling >= 1 ? "Y" : "T";                    
                    if($status_kehamilan == "KEK" || $status_kehamilan == "RISTI"){
                        $kunjunganRumah = $hitungKunjunganRumah >= 1 ? "Y" : "T";
                    } else {
                        $kunjunganRumah = "T";
                    }
                } else if ($dataUsiaKehamilan <= 6) {
                    // 4 - 6 Bulan (Trisemester 2)
                    $periksaKehamilan   = $hitungPeriksaKehamilan >= 1 ? "Y" : "T";
                    $pilFe              = $hitungPilFe >= 1 ? "Y" : "T";
                    $periksaNifas       = "TS";
                    $konseling          = $hitungKonseling >= 1 ? "Y" : "T";                    
                    if($status_kehamilan == "KEK" || $status_kehamilan == "RISTI"){
                        $kunjunganRumah = $hitungKunjunganRumah >= 1 ? "Y" : "T";
                    } else {
                        $kunjunganRumah = "T";
                    }
                } else {
                    // 7 - 9 Bulan (Trisemester 3) atau lebih
                    $periksaKehamilan   = $hitungPeriksaKehamilan >= 2 ? "Y" : "T";
                    $pilFe              = $hitungPilFe >= 1 ? "Y" : "T";
                    $periksaNifas       = "TS";
                    $konseling          = $hitungKonseling >= 2 ? "Y" : "T";                    
                    if($status_kehamilan == "KEK" || $status_kehamilan == "RISTI"){
                        $kunjunganRumah = $hitungKunjunganRumah >= 1 ? "Y" : "T";
                    } else {
                        $kunjunganRumah = "T";
                    }
                }
            }

            $aksesAirBersih = $hitungAksesAirBersih >= 1 ? "Y" : "T";
            $kepemilikanJamban = $hitungKepemilikanJamban >= 1 ? "Y" : "T";
            $jaminanKesehatan = $hitungJaminanKesehatan >= 1 ? "Y" : "T";

            $dataGrup[$key]["nik"]                  = $key;
            $dataGrup[$key]["ket_usia_kehamilan"]   = $isSudahMelahirkan ? "Ibu Bersalin" : $dataUsiaKehamilan;
            $dataGrup[$key]["nama_ibu"]             = $dataGrup[$key][0]["nama_ibu"];
            $dataGrup[$key]["status_kehamilan"]     = $status_kehamilan;
            $dataGrup[$key]["usia_kehamilan"]       = $usia_kehamilan;
            $dataGrup[$key]["tanggal_melahirkan"]   = $tanggal_melahirkan;
            $dataGrup[$key]["periksaKehamilan"]     = $periksaKehamilan;
            // $dataGrup[$key]["pilFe"]                = $pilFe;
            $dataGrup[$key]["pilFe"]                = "BINGUNG";
            $dataGrup[$key]["periksaNifas"]         = $periksaNifas;
            $dataGrup[$key]["konseling"]            = $konseling;
            $dataGrup[$key]["kunjunganRumah"]       = $kunjunganRumah;
            $dataGrup[$key]["aksesAirBersih"]       = $aksesAirBersih;
            $dataGrup[$key]["kepemilikanJamban"]    = $kepemilikanJamban;
            $dataGrup[$key]["jaminanKesehatan"]     = $jaminanKesehatan;
        }



        die(json_encode($dataGrup));
    }
}
