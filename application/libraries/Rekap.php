<?php

class Rekap
{
    public function get_data_ibu_hamil($kuartal = NULL, $tahun = NULL)
    {
        $CI = &get_instance();
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

        $ibuHamil = $CI->m_data->select(array(
            "ibu_hamil.*",
            "kia.nama_ibu",
            "kia.nama_anak",
            "kia.jenis_kelamin_anak",
            "kia.tanggal_lahir_anak"
        ));
        $ibuHamil = $CI->m_data->getJoin("kia", "ibu_hamil.no_kia = kia.no_kia", "INNER");
        $ibuHamil = $CI->m_data->order_by("ibu_hamil.created_at", "ASC");
        $ibuHamil = $CI->m_data->getWhere("MONTH(ibu_hamil.created_at) >=", $batasBulanBawah);
        $ibuHamil = $CI->m_data->getWhere("MONTH(ibu_hamil.created_at) <=", $batasBulanAtas);
        $ibuHamil = $CI->m_data->getWhere("YEAR(ibu_hamil.created_at)", $tahun);
        $ibuHamil = $CI->m_data->getData("ibu_hamil")->result();

        $dataTahun = $CI->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $CI->m_data->distinct();
        $dataTahun = $CI->m_data->getData("ibu_hamil")->result();

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
            foreach ($capaianKonvergensi as $item) {
                $tingkatKonvergensiDesa["jumlah_diterima"] += $item["Y"];
                $TotalTS += $item["TS"];
            }

            $tingkatKonvergensiDesa["jumlah_seharusnya"] = $totalIndikator - $TotalTS;
            $tingkatKonvergensiDesa["persen"] = $tingkatKonvergensiDesa["jumlah_seharusnya"] == 0 ? "0.00" : number_format($tingkatKonvergensiDesa["jumlah_diterima"] / $tingkatKonvergensiDesa["jumlah_seharusnya"] * 100, 2);
        } else {
            $dataGrup               = NULL;
            $dataFilter             = NULL;
            $capaianKonvergensi     = NULL;
            $tingkatKonvergensiDesa = NULL;
        }

        $data["dataFilter"]             = $dataFilter;
        $data["capaianKonvergensi"]     = $capaianKonvergensi;
        $data["tingkatKonvergensiDesa"] = $tingkatKonvergensiDesa;
        $data["dataGrup"]               = $dataGrup;

        $data["batasBulanBawah"]        = $batasBulanBawah;
        $data["batasBulanAtas"]         = $batasBulanAtas;
        $data["_tahun"]                 = $tahun;
        $data['ibuHamil']               = $ibuHamil;
        $data['dataTahun']              = $dataTahun;
        $data['kuartal']                = $kuartal;

        return $data;
    }

    public function get_data_bulanan_anak($kuartal = NULL, $tahun = NULL)
    {
        $CI = &get_instance();
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

        $bulananAnak = $CI->m_data->select(array(
            "bulanan_anak.*",
            "kia.nama_ibu",
            "kia.nama_anak",
            "kia.jenis_kelamin_anak",
            "kia.tanggal_lahir_anak"
        ));
        $bulananAnak = $CI->m_data->getJoin("kia", "bulanan_anak.no_kia = kia.no_kia", "INNER");
        $bulananAnak = $CI->m_data->order_by("bulanan_anak.created_at", "ASC");
        $bulananAnak = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at) >=", $batasBulanBawah);
        $bulananAnak = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at) <=", $batasBulanAtas);
        $bulananAnak = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
        $bulananAnak = $CI->m_data->getData("bulanan_anak")->result();

        $dataTahun = $CI->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $CI->m_data->distinct();
        $dataTahun = $CI->m_data->getData("bulanan_anak")->result();

        if ($bulananAnak) {
            foreach ($bulananAnak as $item) {
                $item = (array) $item;
                $dataGrup[$item['no_kia']][] = $item;
            }

            // d($dataGrupLengkap);
            foreach ($dataGrup as $key => $value) {
                $umurAnak               = 0;
                $hitungImunisasi        = 0;
                $hitungImunisasiCampak  = 0;
                $hitungKunjunganRumah   = 0;
                $hitungAksesAirBersih   = 0;
                $hitungJambanSehat      = 0;
                $hitungAktaLahir        = 0;
                $hitungJaminanKesehatan = 0;

                foreach ($value as $item) {

                    if ($umurAnak < (int) $item["umur_bulan"]) {
                        $umurAnak = (int) $item["umur_bulan"];
                        if ($umurAnak < 6) {
                            $kategoriUmur = 1;
                            $usiaAnak = "0 - < 6 Bulan";
                        } else if ($umurAnak <= 12) {
                            $kategoriUmur = 2;
                            $usiaAnak = "6 - 12 Bulan";
                        } else if ($umurAnak > 12 && $umurAnak < 18) {
                            $kategoriUmur = 3;
                            $usiaAnak = "> 12 - < 18 Bulan";
                        } else {
                            $kategoriUmur = 4;
                            $usiaAnak = "> 18 - 23 Bulan";
                        }
                    }

                    if ($item["pemberian_imunisasi_dasar"] == "v") {
                        $hitungImunisasi++;
                    }

                    if ($item["pemberian_imunisasi_campak"] == "v") {
                        $hitungImunisasiCampak++;
                    }

                    if ($item["kunjungan_rumah"] == "v") {
                        $hitungKunjunganRumah++;
                    }

                    if ($item["air_bersih"] == "v") {
                        $hitungAksesAirBersih++;
                    }

                    if ($item["akta_lahir"] == "v") {
                        $hitungAktaLahir++;
                    }

                    if ($item["jaminan_kesehatan"] == "v") {
                        $hitungJaminanKesehatan++;
                    }

                    if ($item["kepemilikan_jamban"] == "v") {
                        $hitungJambanSehat++;
                    }

                    $statusGizi = $item["status_gizi"];
                }

                // HITUNG PENIMBANGAN DALAM 1 TAHUN
                $hitungPenimbangan = $CI->m_data->select("pengukuran_berat_badan");
                $hitungPenimbangan = $CI->m_data->getWhere("no_kia", $key);
                $hitungPenimbangan = $CI->m_data->getWhere("pengukuran_berat_badan", "v");
                $hitungPenimbangan = $CI->m_data->getData("bulanan_anak")->num_rows();

                //HITUNG KONSELING DALAM 1 TAHUN
                $KonselingGizi = $CI->m_data->select(array("konseling_gizi_ayah", "konseling_gizi_ibu"));
                $KonselingGizi = $CI->m_data->getWhere("no_kia", $key);
                $KonselingGizi = $CI->m_data->getData("bulanan_anak")->result();

                $KGL = 0;
                $KGP = 0;
                foreach ($KonselingGizi as $item) {
                    if ($item->konseling_gizi_ayah == "v") {
                        $KGL++;
                    }
                    if ($item->konseling_gizi_ibu == "v") {
                        $KGP++;
                    }
                }
                $JUMLAH_KG      = $KGP;

                //HITUNG PENGASUHAN DALAM 1 TAHUN
                $hitungPengasuhan     = $CI->m_data->select("pengasuhan_paud");
                $hitungPengasuhan     = $CI->m_data->getWhere("no_kia", $key);
                $hitungPengasuhan     = $CI->m_data->getWhere("pengasuhan_paud", "v");
                $hitungPengasuhan     = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                $hitungPengasuhan     = $CI->m_data->getData("bulanan_anak")->num_rows();

                if ($kategoriUmur == 1) {
                    $imunisasi              = "TS";
                    $penimbanganBeratBadan  = "TS";
                    $konseling_gizi         = "TS";
                    $kunjungan_rumah        = $hitungKunjunganRumah   >= 2 ? "Y" : "T";
                    $air_bersih             = $hitungAksesAirBersih   >= 1 ? "Y" : "T";
                    $jamban_sehat           = $hitungJambanSehat      >= 1 ? "Y" : "T";
                    $jaminanKesehatan       = $hitungJaminanKesehatan >= 1 ? "Y" : "T";
                    $akta_lahir             = $hitungAktaLahir        >= 1 ? "Y" : "T";
                    $pengasuhan_paud        = "TS";
                } else if ($kategoriUmur == 2) {
                    if ($umurAnak <= 9) {
                        $imunisasi          = $hitungImunisasi        > 0  ? "Y" : "T";
                    } else {
                        $imunisasi          = $hitungImunisasi > 0 && $hitungImunisasiCampak > 0 ? "Y" : "T";
                    }
                    $penimbanganBeratBadan  = $hitungPenimbangan      >= 5 ? "Y" : "T";
                    $konseling_gizi         = $JUMLAH_KG              >= 5 ? "Y" : "T";
                    $kunjungan_rumah        = $hitungKunjunganRumah   >= 2 ? "Y" : "T";
                    $air_bersih             = $hitungAksesAirBersih   >= 1 ? "Y" : "T";
                    $jamban_sehat           = $hitungJambanSehat      >= 1 ? "Y" : "T";
                    $jaminanKesehatan       = $hitungJaminanKesehatan >= 1 ? "Y" : "T";
                    $akta_lahir             = $hitungAktaLahir        >= 1 ? "Y" : "T";
                    $pengasuhan_paud        = $hitungPengasuhan       >= 5 ? "Y" : "T";
                } else if ($kategoriUmur == 3) {
                    $imunisasi              = $hitungImunisasi > 0 && $hitungImunisasiCampak > 0 ? "Y" : "T";
                    $penimbanganBeratBadan  = $hitungPenimbangan      >= 8 ? "Y" : "T";
                    $konseling_gizi         = $JUMLAH_KG              >= 8 ? "Y" : "T";
                    $kunjungan_rumah        = $hitungKunjunganRumah   >= 2 ? "Y" : "T";
                    $air_bersih             = $hitungAksesAirBersih   >= 1 ? "Y" : "T";
                    $jamban_sehat           = $hitungJambanSehat      >= 1 ? "Y" : "T";
                    $jaminanKesehatan       = $hitungJaminanKesehatan >= 1 ? "Y" : "T";
                    $akta_lahir             = $hitungAktaLahir        >= 1 ? "Y" : "T";
                    $pengasuhan_paud        = $hitungPengasuhan       >= 5 ? "Y" : "T";
                } else if ($kategoriUmur == 4) {
                    $imunisasi              = $hitungImunisasi > 0 && $hitungImunisasiCampak > 0 ? "Y" : "T";
                    $penimbanganBeratBadan  = $hitungPenimbangan      >= 15 ? "Y" : "T";
                    $konseling_gizi         = $JUMLAH_KG              >= 15 ? "Y" : "T";
                    $kunjungan_rumah        = $hitungKunjunganRumah   >= 2  ? "Y" : "T";
                    $air_bersih             = $hitungAksesAirBersih   >= 1  ? "Y" : "T";
                    $jamban_sehat           = $hitungJambanSehat      >= 1  ? "Y" : "T";
                    $jaminanKesehatan       = $hitungJaminanKesehatan >= 1  ? "Y" : "T";
                    $akta_lahir             = $hitungAktaLahir        >= 1  ? "Y" : "T";
                    $pengasuhan_paud        = $hitungPengasuhan       >= 5  ? "Y" : "T";
                } else {
                    d("kesalahan di kategori umur!");
                }

                if ($kuartal == 1) {
                    if ($umurAnak <= 3) {
                        $tinggiBadan = "TS";
                    } else {
                        // CARI TINGGI BADAN DI DATABASE                        
                        $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                        $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                        $hitungTinggiBadan = $CI->m_data->getWhere("pengukuran_tinggi_badan", "v");
                        // $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at) >=", $batasBulanBawah);
                        // $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at) <=", $batasBulanAtas);
                        // $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                        $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at)", 2); //Februari
                        $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                        $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->num_rows();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? "Y" : "T";
                    }
                } else if ($kuartal == 2) {
                    if ($umurAnak <= 3) {
                        $tinggiBadan = "TS";
                    } else {
                        // CARI TINGGI BADAN DI DATABASE                        
                        $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                        $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                        $hitungTinggiBadan = $CI->m_data->getWhere("pengukuran_tinggi_badan", "v");
                        $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at)", 2); //Februari
                        $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                        $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->num_rows();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? "Y" : "T";
                    }
                } else if ($kuartal == 3) {
                    if ($umurAnak <= 3) {
                        $tinggiBadan = "TS";
                    } else if ($umurAnak <= 8) {
                        // CARI TINGGI BADAN DI DATABASE                        
                        $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                        $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                        $hitungTinggiBadan = $CI->m_data->getWhere("pengukuran_tinggi_badan", "v");
                        $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at)", 8); //Agustus
                        $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                        $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->num_rows();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? "Y" : "T";
                    } else {
                        $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                        $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                        $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at)", 2); //Februari
                        $hitungTinggiBadan = $CI->m_data->getOrWhere("MONTH(bulanan_anak.created_at)", 8); //Agustus
                        $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                        $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->result();

                        $TB_FEB_AGS = 0;
                        foreach ($hitungTinggiBadan as $item) {
                            if ($item->pengukuran_tinggi_badan == "v") {
                                $TB_FEB_AGS++;
                            }
                        }

                        $tinggiBadan = $TB_FEB_AGS > 1 ? "Y" : "T"; //ada di februari atau agustus
                    }
                } else if ($kuartal == 4) {
                    if ($umurAnak <= 6) {
                        $tinggiBadan = "TS";
                    } else if ($umurAnak <= 11) {
                        // CARI TINGGI BADAN DI DATABASE                        
                        $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                        $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                        $hitungTinggiBadan = $CI->m_data->getWhere("pengukuran_tinggi_badan", "v");
                        $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at)", 8); //Agustus                    
                        $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->num_rows();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? "Y" : "T";
                    } else {
                        $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                        $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                        $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at)", 2); //Februari
                        $hitungTinggiBadan = $CI->m_data->getOrWhere("MONTH(bulanan_anak.created_at)", 8); //Agustus
                        $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                        $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->result();

                        $TB_FEB_AGS = 0;
                        foreach ($hitungTinggiBadan as $item) {
                            if ($item->pengukuran_tinggi_badan == "v") {
                                $TB_FEB_AGS++;
                            }
                        }

                        $tinggiBadan = $TB_FEB_AGS > 1 ? "Y" : "T"; //ada di februari atau agustus
                    }
                } else {
                    d("kesalahan di kuartal!");
                }

                // START--------------------------------------------------------------------------------------------
                //HAPUS KODE DI BAWAH INI JIKA PENGECEKAN TINGGI BADAN HANYA DILAKUKAN DI BULAN FEBRUARI DAN AGUSTUS
                //INI CARINYA DI DALAM 1 KUARTAL MINIMAL 1X
                $hitungTinggiBadan = $CI->m_data->select("pengukuran_tinggi_badan");
                $hitungTinggiBadan = $CI->m_data->getWhere("no_kia", $key);
                $hitungTinggiBadan = $CI->m_data->getWhere("pengukuran_tinggi_badan", "v");
                $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at) >=", $batasBulanBawah);
                $hitungTinggiBadan = $CI->m_data->getWhere("MONTH(bulanan_anak.created_at) <=", $batasBulanAtas);
                $hitungTinggiBadan = $CI->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
                $hitungTinggiBadan = $CI->m_data->getData("bulanan_anak")->num_rows();
                $tinggiBadan = $hitungTinggiBadan > 0 ? "Y" : "T";
                // END ---------------------------------------------------------------------------------------------


                $dataFilter[$key]["user"]["no_kia"]                         = $key;
                $dataFilter[$key]["user"]["usia_anak"]                      = $usiaAnak;
                $dataFilter[$key]["user"]["nama_anak"]                      = $dataGrup[$key][0]["nama_anak"];
                $dataFilter[$key]["user"]["jenis_kelamin"]                  = $dataGrup[$key][0]["jenis_kelamin_anak"];
                $dataFilter[$key]["umur_dan_gizi"]["umur_bulan"]            = $umurAnak;
                $dataFilter[$key]["umur_dan_gizi"]["status_gizi"]           = $statusGizi;
                $dataFilter[$key]["indikator"]["imunisasi"]                 = $imunisasi;
                $dataFilter[$key]["indikator"]["pengukuran_berat_badan"]    = $penimbanganBeratBadan;
                $dataFilter[$key]["indikator"]["pengukuran_tinggi_badan"]   = $tinggiBadan;
                $dataFilter[$key]["indikator"]["konseling_gizi"]            = $konseling_gizi;
                $dataFilter[$key]["indikator"]["kunjungan_rumah"]           = $kunjungan_rumah;
                $dataFilter[$key]["indikator"]["air_bersih"]                = $air_bersih;
                $dataFilter[$key]["indikator"]["jamban_sehat"]              = $jamban_sehat;
                $dataFilter[$key]["indikator"]["akta_lahir"]                = $akta_lahir;
                $dataFilter[$key]["indikator"]["jaminan_kesehatan"]         = $jaminanKesehatan;
                $dataFilter[$key]["indikator"]["pengasuhan_paud"]           = $pengasuhan_paud;

                $jumlahLayanan      = sizeof($dataFilter[$key]["indikator"]);
                $jumlahY            = 0;
                $jumlahT            = 0;
                $jumlahTS           = 0;
                foreach ($dataFilter[$key]["indikator"] as $indikator) {
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
                $tingkatKonvergensiIndikator = array(
                    "jumlah_diterima_lengkap"   => $jumlahY,
                    "jumlah_seharusnya"         => $jumlahSeharusnya,
                    "persen"                    => $jumlahSeharusnya == 0 ? "0.00" : number_format($jumlahY / $jumlahSeharusnya * 100, 2)
                );
                $dataFilter[$key]["tingkat_konvergensi_indikator"]  = $tingkatKonvergensiIndikator;
            }

            // KALKULASI TINGKATAN CAPAIAN KONVERGENSI
            $capaianKonvergensi = array(
                "imunisasi"                 => array("Y" => 0, "T" => 0, "TS" => 0),
                "pengukuran_berat_badan"    => array("Y" => 0, "T" => 0, "TS" => 0),
                "pengukuran_tinggi_badan"   => array("Y" => 0, "T" => 0, "TS" => 0),
                "konseling_gizi"            => array("Y" => 0, "T" => 0, "TS" => 0),
                "kunjungan_rumah"           => array("Y" => 0, "T" => 0, "TS" => 0),
                "air_bersih"                => array("Y" => 0, "T" => 0, "TS" => 0),
                "jamban_sehat"              => array("Y" => 0, "T" => 0, "TS" => 0),
                "akta_lahir"                => array("Y" => 0, "T" => 0, "TS" => 0),
                "jaminan_kesehatan"         => array("Y" => 0, "T" => 0, "TS" => 0),
                "pengasuhan_paud"           => array("Y" => 0, "T" => 0, "TS" => 0)
            );

            foreach ($dataFilter as $item) {
                $capaianKonvergensi["imunisasi"][$item["indikator"]["imunisasi"]]++;
                $capaianKonvergensi["pengukuran_berat_badan"][$item["indikator"]["pengukuran_berat_badan"]]++;
                $capaianKonvergensi["pengukuran_tinggi_badan"][$item["indikator"]["pengukuran_tinggi_badan"]]++;
                $capaianKonvergensi["konseling_gizi"][$item["indikator"]["konseling_gizi"]]++;
                $capaianKonvergensi["kunjungan_rumah"][$item["indikator"]["kunjungan_rumah"]]++;
                $capaianKonvergensi["air_bersih"][$item["indikator"]["air_bersih"]]++;
                $capaianKonvergensi["jamban_sehat"][$item["indikator"]["jamban_sehat"]]++;
                $capaianKonvergensi["akta_lahir"][$item["indikator"]["akta_lahir"]]++;
                $capaianKonvergensi["jaminan_kesehatan"][$item["indikator"]["jaminan_kesehatan"]]++;
                $capaianKonvergensi["pengasuhan_paud"][$item["indikator"]["pengasuhan_paud"]]++;
            }

            foreach ($capaianKonvergensi as $key => $item) {
                $capaianKonvergensijumlahSeharusnya             = sizeof($dataFilter) - (int) $item["TS"];
                $capaianKonvergensi[$key]["jumlah_diterima"]    = $item["Y"];
                $capaianKonvergensi[$key]["jumlah_seharusnya"]  = $capaianKonvergensijumlahSeharusnya;
                $capaianKonvergensi[$key]["persen"]             = $capaianKonvergensijumlahSeharusnya == 0 ? "0.00" : number_format($item["Y"] / $capaianKonvergensijumlahSeharusnya * 100, 2);;
            }

            $totalIndikator = sizeof($capaianKonvergensi) * sizeof($dataFilter);
            $tingkatKonvergensiDesa = array(
                "jumlah_diterima"   => 0,
                "jumlah_seharusnya" => 0,
                "persen"            => 0
            );

            $TotalTS = 0;
            foreach ($capaianKonvergensi as $item) {
                $tingkatKonvergensiDesa["jumlah_diterima"] += $item["Y"];
                $TotalTS += $item["TS"];
            }

            $tingkatKonvergensiDesa["jumlah_seharusnya"] = $totalIndikator - $TotalTS;
            $tingkatKonvergensiDesa["persen"] = $tingkatKonvergensiDesa["jumlah_seharusnya"] == 0 ? "0.00" : number_format($tingkatKonvergensiDesa["jumlah_diterima"] / $tingkatKonvergensiDesa["jumlah_seharusnya"] * 100, 2);
        } else {
            $dataGrup               = NULL;
            $dataFilter             = NULL;
            $capaianKonvergensi     = NULL;
            $tingkatKonvergensiDesa = NULL;
        }

        $data["dataFilter"]             = $dataFilter;
        $data["capaianKonvergensi"]     = $capaianKonvergensi;
        $data["tingkatKonvergensiDesa"] = $tingkatKonvergensiDesa;
        $data["dataGrup"]               = $dataGrup;

        $data["batasBulanBawah"]        = $batasBulanBawah;
        $data["batasBulanAtas"]         = $batasBulanAtas;
        $data["_tahun"]                 = $tahun;
        $data['bulananAnak']            = $bulananAnak;
        $data['dataTahun']              = $dataTahun;
        $data['kuartal']                = $kuartal;

        return $data;
    }
}
