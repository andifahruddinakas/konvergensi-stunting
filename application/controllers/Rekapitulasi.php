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

    public function get_data_ibu_hamil($kuartal = NULL, $tahun = NULL)
    {
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

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("ibu_hamil")->result();

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
        $data["_tahun"]                 = $tahun;
        $data['ibuHamil']               = $ibuHamil;
        $data['dataTahun']              = $dataTahun;
        $data['kuartal']                = $kuartal;
        $data['title']                  = "Rekapitulasi Hasil Pemantauan 3 Bulananan Bagi Ibu Hamil";

        return $data;
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

        $data = $this->get_data_ibu_hamil($kuartal, $tahun);

        return $this->loadView('rekapitulasi.ibu-hamil', $data);
    }

    public function export_ibu_hamil($kuartal = NULL, $tahun = NULL)
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
            redirect(base_url('rekapitulasi/export-ibu-hamil/') . $kuartal . '/' . $tahun);
        }

        $data = $this->get_data_ibu_hamil($kuartal, $tahun);

        $styleJudul = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal'    => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'      => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'      => TRUE
            ]
        ];

        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $styleIsi = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal'    => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'      => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'      => TRUE
            ]
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('FORMULIR 3A');

        //PAGE SETUP
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        //MERGE CELL
        $sheet->mergeCells('A1:Q1');
        $sheet->mergeCells('A3:A5');
        $sheet->mergeCells('B3:B5');
        $sheet->mergeCells('C3:C5');
        $sheet->mergeCells('D3:D5');
        $sheet->mergeCells('E3:N3');
        $sheet->mergeCells('O3:Q4');
        $sheet->mergeCells('E4:F4');
        $sheet->mergeCells('G4:N4');

        //SET VALUE
        $sheet->setCellValue('A1', 'FORMULIR 3.A. REKAPITULASI HASIL PEMANTAUAN 3 (TIGA) BULANAN BAGI  IBU HAMIL');
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'No Register (KIA)');
        $sheet->setCellValue('C3', 'Nama Ibu');
        $sheet->setCellValue('D3', 'Status Kehamilan (NORMAL/KEK/RISTI)');
        $sheet->setCellValue('E3', 'KUARTAL KE 4 BULAN Oktober S/D BULAN Desember');
        $sheet->setCellValue('O3', 'Tingkat Konvergensi Indikator');
        $sheet->setCellValue('E4', 'Usia Kehamilan dan Persalinan');
        $sheet->setCellValue('G4', 'Status Penerimaan Indikator');
        $sheet->setCellValue('E5', 'Usia Kehamilan (Bulan)');
        $sheet->setCellValue('F5', 'Tanggal Melahirkan  (Tgl/Bln/Thn)');
        $sheet->setCellValue('G5', 'Pemeriksaan Kehamilan');
        $sheet->setCellValue('H5', 'Dapat & Konsumsi Pil Fe');
        $sheet->setCellValue('I5', 'Pemeriksaan Nifas');
        $sheet->setCellValue('J5', 'Konseling Gizi (Kelas IH)');
        $sheet->setCellValue('K5', 'Kunjungan Rumah');
        $sheet->setCellValue('L5', 'Kepemilikan Akses Air Bersih');
        $sheet->setCellValue('M5', 'Kepemilikan Jamban');
        $sheet->setCellValue('N5', 'Jaminan Kesehatan');
        $sheet->setCellValue('O5', 'Jumlah Diterima Lengkap');
        $sheet->setCellValue('P5', 'Jumlah Seharusnya');
        $sheet->setCellValue('Q5', '%');

        //SET ROW HEIGH
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getRowDimension('5')->setRowHeight(120);

        //SET ORIENTATION
        $sheet->getStyle('E5:P5')->getAlignment()->setTextRotation(90);

        //RESIZE WIDTH
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(23);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('Q')->setWidth(7);
        foreach (range('G', 'P') as $kolom) {
            $sheet->getColumnDimension($kolom)->setWidth(5);
        }

        //SET INDEX
        foreach (range('A', 'Q') as $kolom) {
            $sheet->setCellValue($kolom . '6', strtolower($kolom));
        }        

        //SET DATA
        if (!$data["dataFilter"]) {
            $sheet->mergeCells('A7:Q7');
            $sheet->setCellValue('A7', 'Data Tidak Ditemukan!');
        } else {
            $batasBaris = 6;
            $no = 1;
            foreach ($data["dataFilter"] as $item) {
                $barisSekarang = $batasBaris + $no;
                $sheet->setCellValue('A' . $barisSekarang, $no);
                $sheet->setCellValue('B' . $barisSekarang, $item['user']['no_kia']);
                $sheet->setCellValue('C' . $barisSekarang, $item['user']['nama_ibu']);
                $sheet->setCellValue('D' . $barisSekarang, $item['user']['status_kehamilan']);
                $sheet->setCellValue('E' . $barisSekarang, $item['user']['usia_kehamilan']);
                $sheet->setCellValue('F' . $barisSekarang, $item['user']['tanggal_melahirkan'] == '-' ? $item['user']['tanggal_melahirkan'] : shortdate_indo($item['user']['tanggal_melahirkan']));
                $sheet->setCellValue('G' . $barisSekarang, $item['indikator']['periksa_kehamilan']);
                $sheet->setCellValue('H' . $barisSekarang, $item['indikator']['pil_fe']);
                $sheet->setCellValue('I' . $barisSekarang, $item['indikator']['pemeriksaan_nifas']);
                $sheet->setCellValue('J' . $barisSekarang, $item['indikator']['konseling_gizi']);
                $sheet->setCellValue('K' . $barisSekarang, $item['indikator']['kunjungan_rumah']);
                $sheet->setCellValue('L' . $barisSekarang, $item['indikator']['akses_air_bersih']);
                $sheet->setCellValue('M' . $barisSekarang, $item['indikator']['kepemilikan_jamban']);
                $sheet->setCellValue('N' . $barisSekarang, $item['indikator']['jaminan_kesehatan']);
                $sheet->setCellValue('O' . $barisSekarang, $item['konvergensi_indikator']['jumlah_diterima_lengkap']);
                $sheet->setCellValue('P' . $barisSekarang, $item['konvergensi_indikator']['jumlah_seharusnya']);
                $sheet->setCellValue('Q' . $barisSekarang, $item['konvergensi_indikator']['persen']);
                $no++;
            }
        }
    
        //SET FOOTER
        $curentHighRow = (int) $sheet->getHighestRow();
        if($data["dataFilter"]){
            $sheet->mergeCells('A' . ($curentHighRow + 1) . ':'. 'C'. ($curentHighRow + 3));
            $sheet->mergeCells('D' . ($curentHighRow + 1) . ':'. 'F'. ($curentHighRow + 1));
            $sheet->mergeCells('D' . ($curentHighRow + 2) . ':'. 'F'. ($curentHighRow + 2));
            $sheet->mergeCells('D' . ($curentHighRow + 3) . ':'. 'F'. ($curentHighRow + 3));
            $sheet->mergeCells('O' . ($curentHighRow + 1) . ':'. 'O'. ($curentHighRow + 3));
            $sheet->mergeCells('P' . ($curentHighRow + 1) . ':'. 'P'. ($curentHighRow + 3));
            $sheet->mergeCells('Q' . ($curentHighRow + 1) . ':'. 'Q'. ($curentHighRow + 3));

            $sheet->setCellValue('A' . ($curentHighRow + 1), "Tingkat Capaian Konvergensi");
            $sheet->setCellValue('D' . ($curentHighRow + 1), "Jumlah Diterima");
            $sheet->setCellValue('D' . ($curentHighRow + 2), "Jumlah Seharusnya");
            $sheet->setCellValue('D' . ($curentHighRow + 3), "%");

            $capaianKonvergensi     = $data["capaianKonvergensi"]; 
            $tingkatKonvergensiDesa = $data["tingkatKonvergensiDesa"];

            $sheet->setCellValue('G' . ($curentHighRow + 1), $capaianKonvergensi["periksa_kehamilan"]["Y"]);
            $sheet->setCellValue('H' . ($curentHighRow + 1), $capaianKonvergensi["pil_fe"]["Y"]);
            $sheet->setCellValue('I' . ($curentHighRow + 1), $capaianKonvergensi["pemeriksaan_nifas"]["Y"]);
            $sheet->setCellValue('J' . ($curentHighRow + 1), $capaianKonvergensi["konseling_gizi"]["Y"]);
            $sheet->setCellValue('K' . ($curentHighRow + 1), $capaianKonvergensi["kunjungan_rumah"]["Y"]);
            $sheet->setCellValue('L' . ($curentHighRow + 1), $capaianKonvergensi["akses_air_bersih"]["Y"]);
            $sheet->setCellValue('M' . ($curentHighRow + 1), $capaianKonvergensi["kepemilikan_jamban"]["Y"]);
            $sheet->setCellValue('N' . ($curentHighRow + 1), $capaianKonvergensi["jaminan_kesehatan"]["Y"]);

            $sheet->setCellValue('G' . ($curentHighRow + 2), $capaianKonvergensi["periksa_kehamilan"]["jumlah_seharusnya"]);
            $sheet->setCellValue('H' . ($curentHighRow + 2), $capaianKonvergensi["pil_fe"]["jumlah_seharusnya"]);
            $sheet->setCellValue('I' . ($curentHighRow + 2), $capaianKonvergensi["pemeriksaan_nifas"]["jumlah_seharusnya"]);
            $sheet->setCellValue('J' . ($curentHighRow + 2), $capaianKonvergensi["konseling_gizi"]["jumlah_seharusnya"]);
            $sheet->setCellValue('K' . ($curentHighRow + 2), $capaianKonvergensi["kunjungan_rumah"]["jumlah_seharusnya"]);
            $sheet->setCellValue('L' . ($curentHighRow + 2), $capaianKonvergensi["akses_air_bersih"]["jumlah_seharusnya"]);
            $sheet->setCellValue('M' . ($curentHighRow + 2), $capaianKonvergensi["kepemilikan_jamban"]["jumlah_seharusnya"]);
            $sheet->setCellValue('N' . ($curentHighRow + 2), $capaianKonvergensi["jaminan_kesehatan"]["jumlah_seharusnya"]);

            $sheet->setCellValue('G' . ($curentHighRow + 3), $capaianKonvergensi["periksa_kehamilan"]["persen"]);
            $sheet->setCellValue('H' . ($curentHighRow + 3), $capaianKonvergensi["pil_fe"]["persen"]);
            $sheet->setCellValue('I' . ($curentHighRow + 3), $capaianKonvergensi["pemeriksaan_nifas"]["persen"]);
            $sheet->setCellValue('J' . ($curentHighRow + 3), $capaianKonvergensi["konseling_gizi"]["persen"]);
            $sheet->setCellValue('K' . ($curentHighRow + 3), $capaianKonvergensi["kunjungan_rumah"]["persen"]);
            $sheet->setCellValue('L' . ($curentHighRow + 3), $capaianKonvergensi["akses_air_bersih"]["persen"]);
            $sheet->setCellValue('M' . ($curentHighRow + 3), $capaianKonvergensi["kepemilikan_jamban"]["persen"]);
            $sheet->setCellValue('N' . ($curentHighRow + 3), $capaianKonvergensi["jaminan_kesehatan"]["persen"]);

            $sheet->setCellValue('O' . ($curentHighRow + 1), $tingkatKonvergensiDesa["jumlah_diterima"]);
            $sheet->setCellValue('P' . ($curentHighRow + 1), $tingkatKonvergensiDesa["jumlah_seharusnya"]);
            $sheet->setCellValue('Q' . ($curentHighRow + 1), $tingkatKonvergensiDesa["persen"]);
        }

        //SET BORDER AND ALIGNMENT DATA
        $sheet->getStyle('A1:Q6')->applyFromArray($styleJudul);
        $sheet->getStyle('A3:Q' . $sheet->getHighestRow())->applyFromArray($styleBorder);
        $sheet->getStyle('A7:Q' . $sheet->getHighestRow())->applyFromArray($styleIsi);
        $sheet->getStyle('B7:C' . $sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        //SAVE AND DOWNLOAD
        $writer = new Xlsx($spreadsheet);
        $filename = 'FORMULIR_3A_REKAPITULASI_HASIL_PEMANTAUAN_3_BULANAN_BAGI_IBU_HAMIL_KUARTAL_' . strtoupper($kuartal . "_" . $tahun . "_" . date("H_i_s"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
