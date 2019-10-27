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

        if ($ibuHamil) {
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
                        if ($status_kehamilan == "KEK" || $status_kehamilan == "RISTI") {
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
                        if ($status_kehamilan == "KEK" || $status_kehamilan == "RISTI") {
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
                        if ($status_kehamilan == "KEK" || $status_kehamilan == "RISTI") {
                            $kunjunganRumah = $hitungKunjunganRumah >= 1 ? "Y" : "T";
                        } else {
                            $kunjunganRumah = "T";
                        }
                    }
                }

                $aksesAirBersih = $hitungAksesAirBersih >= 1 ? "Y" : "T";
                $kepemilikanJamban = $hitungKepemilikanJamban >= 1 ? "Y" : "T";
                $jaminanKesehatan = $hitungJaminanKesehatan >= 1 ? "Y" : "T";

                $dataFilter[$key]["user"] = array(
                    "ket_usia_kehamilan"   => $isSudahMelahirkan ? "Ibu Bersalin" : $dataUsiaKehamilan,
                    "no_kia"               => $key,
                    "nama_ibu"             => $dataGrup[$key][0]["nama_ibu"],
                    "status_kehamilan"     => $status_kehamilan,
                    "usia_kehamilan"       => $usia_kehamilan,
                    "tanggal_melahirkan"   => $tanggal_melahirkan
                );

                $dataFilter[$key]["indikator"] = array(
                    "periksa_kehamilan"    => $periksaKehamilan,
                    "pil_fe"               => $pilFe,
                    "pemeriksaan_nifas"    => $periksaNifas,
                    "konseling_gizi"       => $konseling,
                    "kunjungan_rumah"      => $kunjunganRumah,
                    "akses_air_bersih"     => $aksesAirBersih,
                    "kepemilikan_jamban"   => $kepemilikanJamban,
                    "jaminan_kesehatan"    => $jaminanKesehatan
                );

                foreach ($dataFilter as $key => $item) {
                    $jumlahY            = 0;
                    $jumlahT            = 0;
                    $jumlahTS           = 0;
                    $jumlahLayanan      = sizeof($item["indikator"]);
                    foreach ($item["indikator"] as $indikator) {
                        if ($indikator == "Y") {
                            $jumlahY++;
                        }

                        if ($indikator == "T") {
                            $jumlahT++;
                        }

                        if ($indikator == "TS") {
                            $jumlahTS++;
                        }
                    }

                    $jumlahSeharusnya = (int) $jumlahLayanan - (int) $jumlahTS;
                    $dataFilter[$key]["konvergensi_indikator"] = array(
                        "jumlah_diterima_lengkap"   => $jumlahY,
                        "jumlah_seharusnya"         => $jumlahSeharusnya,
                        "persen"                    => $jumlahSeharusnya == 0 ? "0.00" : number_format($jumlahY / $jumlahSeharusnya * 100, 2)
                    );
                }
            }

            $capaianKonvergensi = array(
                "periksa_kehamilan"     => array("Y" => 0, "T" => 0, "TS" => 0),
                "pil_fe"                => array("Y" => 0, "T" => 0, "TS" => 0),
                "pemeriksaan_nifas"     => array("Y" => 0, "T" => 0, "TS" => 0),
                "konseling_gizi"        => array("Y" => 0, "T" => 0, "TS" => 0),
                "kunjungan_rumah"       => array("Y" => 0, "T" => 0, "TS" => 0),
                "akses_air_bersih"      => array("Y" => 0, "T" => 0, "TS" => 0),
                "kepemilikan_jamban"    => array("Y" => 0, "T" => 0, "TS" => 0),
                "jaminan_kesehatan"     => array("Y" => 0, "T" => 0, "TS" => 0)
            );            

            foreach ($dataFilter as $item) {
                $capaianKonvergensi['periksa_kehamilan'][$item['indikator']['periksa_kehamilan']]++;
                $capaianKonvergensi['pil_fe'][$item['indikator']['pil_fe']]++;
                $capaianKonvergensi['pemeriksaan_nifas'][$item['indikator']['pemeriksaan_nifas']]++;
                $capaianKonvergensi['konseling_gizi'][$item['indikator']['konseling_gizi']]++;
                $capaianKonvergensi['kunjungan_rumah'][$item['indikator']['kunjungan_rumah']]++;
                $capaianKonvergensi['akses_air_bersih'][$item['indikator']['akses_air_bersih']]++;
                $capaianKonvergensi['kepemilikan_jamban'][$item['indikator']['kepemilikan_jamban']]++;
                $capaianKonvergensi['jaminan_kesehatan'][$item['indikator']['jaminan_kesehatan']]++;
            }

            foreach ($capaianKonvergensi as $key => $item) {
                $capaianKonvergensijumlahSeharusnya             = sizeof($dataFilter) - (int) $item["TS"];                
                $capaianKonvergensi[$key]["jumlah_seharusnya"]  = $capaianKonvergensijumlahSeharusnya;
                $capaianKonvergensi[$key]["persen"]             = $capaianKonvergensijumlahSeharusnya == 0 ? "0.00" : number_format($item["Y"] / $capaianKonvergensijumlahSeharusnya * 100, 2);
            }           
            
            $totalIndikator = sizeof($capaianKonvergensi) * sizeof($dataFilter);
            $tingkatKonvergensiDesa = array(
                "jumlah_diterima"   => 0, 
                "jumlah_seharusnya" => 0, 
                "persen"            => 0
            );

            $TotalTS = 0;
            foreach($capaianKonvergensi as $item){
                $tingkatKonvergensiDesa["jumlah_diterima"] += $item["Y"];
                $TotalTS += $item["TS"];
            }

            $tingkatKonvergensiDesa["jumlah_seharusnya"] = $totalIndikator - $TotalTS;
            $tingkatKonvergensiDesa["persen"] = $tingkatKonvergensiDesa["jumlah_seharusnya"] == 0 ? "0.00" : number_format($tingkatKonvergensiDesa["jumlah_diterima"] / $tingkatKonvergensiDesa["jumlah_seharusnya"] * 100, 2) ;            
        } else {
            $dataGrup               = NULL;
            $dataFilter             = NULL;
            $capaianKonvergensi     = NULL;
            $tingkatKonvergensiDesa = NULL;
        }

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("ibu_hamil")->result();

        // die(json_encode($dataFilter));

        $data["dataFilter"]             = $dataFilter;
        $data["capaianKonvergensi"]     = $capaianKonvergensi;
        $data["tingkatKonvergensiDesa"] = $tingkatKonvergensiDesa;

        $data["dataGrup"]               = $dataGrup;
        $data["_tahun"]                 = $tahun;
        $data['ibuHamil']               = $ibuHamil;
        $data['dataTahun']              = $dataTahun;
        $data['kuartal']                = $kuartal;
        $data['title']                  = "Rekapitulasi Hasil Pemantauan 3 Bulananan Bagi Ibu Hamil";

        return $this->loadView('rekapitulasi.ibu-hamil', $data);
    }
}
