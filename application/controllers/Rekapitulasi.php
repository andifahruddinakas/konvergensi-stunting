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

        $data          = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);
        $data['title'] = "Rekapitulasi Hasil Pemantauan 3 Bulananan Bagi Ibu Hamil";

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

        $data = $this->rekap->get_data_ibu_hamil($kuartal, $tahun);

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
        $sheet->setCellValue('E3', 'KUARTAL KE ' . $data["kuartal"] . ' BULAN ' . strtoupper(bulan($data["batasBulanBawah"])) . ' S/D BULAN ' . strtoupper(bulan($data["batasBulanAtas"]) . " " . $data["_tahun"]));
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
        if ($data["dataFilter"]) {
            $sheet->mergeCells('A' . ($curentHighRow + 1) . ':' . 'C' . ($curentHighRow + 3));
            $sheet->mergeCells('D' . ($curentHighRow + 1) . ':' . 'F' . ($curentHighRow + 1));
            $sheet->mergeCells('D' . ($curentHighRow + 2) . ':' . 'F' . ($curentHighRow + 2));
            $sheet->mergeCells('D' . ($curentHighRow + 3) . ':' . 'F' . ($curentHighRow + 3));
            $sheet->mergeCells('O' . ($curentHighRow + 1) . ':' . 'O' . ($curentHighRow + 3));
            $sheet->mergeCells('P' . ($curentHighRow + 1) . ':' . 'P' . ($curentHighRow + 3));
            $sheet->mergeCells('Q' . ($curentHighRow + 1) . ':' . 'Q' . ($curentHighRow + 3));

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

    public function bulanan_anak($kuartal = NULL, $tahun = NULL)
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
            redirect(base_url('rekapitulasi/bulanan-anak/') . $kuartal . '/' . $tahun);
        }

        $data           = $this->rekap->get_data_bulanan_anak($kuartal, $tahun);        
        $data['title']  = "Rekapitulasi Hasil Pemantauan 3 Bulananan Bagi Anak 0-2 Tahun";

        return $this->loadView('rekapitulasi.bulanan-anak', $data);
    }

    public function export_bulanan_anak($kuartal = NULL, $tahun = NULL)
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
            redirect(base_url('rekapitulasi/export-bulanan-anak/') . $kuartal . '/' . $tahun);
        }

        $data = $this->rekap->get_data_bulanan_anak($kuartal, $tahun);

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
        $sheet->setTitle('FORMULIR 3B');

        //PAGE SETUP
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        //MERGE CELL
        $sheet->mergeCells('A1:S1');
        $sheet->mergeCells('A3:A5');
        $sheet->mergeCells('B3:B5');
        $sheet->mergeCells('C3:C5');
        $sheet->mergeCells('D3:D5');
        $sheet->mergeCells('E3:P3');
        $sheet->mergeCells('Q3:S4');
        $sheet->mergeCells('E4:F4');
        $sheet->mergeCells('G4:P4');

        //SET VALUE
        $sheet->setCellValue('A1', 'FORMULIR 3.B REKAPITULASI HASIL PEMANTAUAN 3 (TIGA) BULANAN BAGI ANAK 0-2 TAHUN');
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'No Register (KIA)');
        $sheet->setCellValue('C3', 'Nama Anak');
        $sheet->setCellValue('D3', 'Jenis Kelamin (L/P)');
        $sheet->setCellValue('E3', 'KUARTAL KE ' . $data["kuartal"] . ' BULAN ' . strtoupper(bulan($data["batasBulanBawah"])) . ' S/D BULAN ' . strtoupper(bulan($data["batasBulanAtas"]) . " " . $data["_tahun"]));
        $sheet->setCellValue('Q3', 'Tingkat Konvergensi Indikator');
        $sheet->setCellValue('E4', 'Umur dan Status Gizi');
        $sheet->setCellValue('G4', 'Indikator Layanan');
        $sheet->setCellValue('E5', 'Umur (Bulan)');
        $sheet->setCellValue('F5', '(Normal/Buruk/Kurang/Stunting)');
        $sheet->setCellValue('G5', 'Pemberian Imunisasi Dasar');
        $sheet->setCellValue('H5', 'Pengukuran Berat Badan');
        $sheet->setCellValue('I5', 'Pengukuran Tinggi Badan');
        $sheet->setCellValue('J5', 'Konseling Gizi Bagi Orang Tua');
        $sheet->setCellValue('K5', 'Kunjungan Rumah');
        $sheet->setCellValue('L5', 'Kepemilikan Akses Air Bersih');
        $sheet->setCellValue('M5', 'Kepemilikan Jamban Sehat');
        $sheet->setCellValue('N5', 'Akta Lahir');
        $sheet->setCellValue('O5', 'Jaminan Kesehatan');
        $sheet->setCellValue('P5', 'Pengasuhan (PAUD)');
        $sheet->setCellValue('Q5', 'Jumlah Diterima Lengkap');
        $sheet->setCellValue('R5', 'Jumlah Seharusnya');
        $sheet->setCellValue('S5', '%');

        //SET ROW HEIGH
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getRowDimension('5')->setRowHeight(120);

        //SET ORIENTATION
        $sheet->getStyle('D3')->getAlignment()->setTextRotation(90);
        $sheet->getStyle('E5:R5')->getAlignment()->setTextRotation(90);

        //RESIZE WIDTH
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(23);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(8);
        $sheet->getColumnDimension('S')->setWidth(7);
        foreach (range('G', 'R') as $kolom) {
            $sheet->getColumnDimension($kolom)->setWidth(5);
        }

        //SET INDEX
        foreach (range('A', 'S') as $kolom) {
            $sheet->setCellValue($kolom . '6', strtolower($kolom));
        }

        //SET DATA
        if (!$data["dataFilter"]) {
            $sheet->mergeCells('A7:S7');
            $sheet->setCellValue('A7', 'Data Tidak Ditemukan!');
        } else {
            $batasBaris = 6;
            $no = 1;
            foreach ($data["dataFilter"] as $item) {
                $barisSekarang = $batasBaris + $no;
                $sheet->setCellValue('A' . $barisSekarang, $no);
                $sheet->setCellValue('B' . $barisSekarang, $item['user']['no_kia']);
                $sheet->setCellValue('C' . $barisSekarang, $item['user']['nama_anak']);
                $sheet->setCellValue('D' . $barisSekarang, $item['user']['jenis_kelamin']);
                $sheet->setCellValue('E' . $barisSekarang, $item['umur_dan_gizi']['umur_bulan']);
                $sheet->setCellValue('F' . $barisSekarang, $item['umur_dan_gizi']['status_gizi']);
                $sheet->setCellValue('G' . $barisSekarang, $item['indikator']['imunisasi']);
                $sheet->setCellValue('H' . $barisSekarang, $item['indikator']['pengukuran_berat_badan']);
                $sheet->setCellValue('I' . $barisSekarang, $item['indikator']['pengukuran_tinggi_badan']);
                $sheet->setCellValue('J' . $barisSekarang, $item['indikator']['konseling_gizi']);
                $sheet->setCellValue('K' . $barisSekarang, $item['indikator']['kunjungan_rumah']);
                $sheet->setCellValue('L' . $barisSekarang, $item['indikator']['air_bersih']);
                $sheet->setCellValue('M' . $barisSekarang, $item['indikator']['jamban_sehat']);
                $sheet->setCellValue('N' . $barisSekarang, $item['indikator']['akta_lahir']);
                $sheet->setCellValue('O' . $barisSekarang, $item['indikator']['jaminan_kesehatan']);
                $sheet->setCellValue('P' . $barisSekarang, $item['indikator']['pengasuhan_paud']);
                $sheet->setCellValue('Q' . $barisSekarang, $item['tingkat_konvergensi_indikator']['jumlah_diterima_lengkap']);
                $sheet->setCellValue('R' . $barisSekarang, $item['tingkat_konvergensi_indikator']['jumlah_seharusnya']);
                $sheet->setCellValue('S' . $barisSekarang, $item['tingkat_konvergensi_indikator']['persen']);
                $no++;
            }
        }

        //SET FOOTER
        $curentHighRow = (int) $sheet->getHighestRow();
        if ($data["dataFilter"]) {
            $sheet->mergeCells('A' . ($curentHighRow + 1) . ':' . 'C' . ($curentHighRow + 3));
            $sheet->mergeCells('D' . ($curentHighRow + 1) . ':' . 'F' . ($curentHighRow + 1));
            $sheet->mergeCells('D' . ($curentHighRow + 2) . ':' . 'F' . ($curentHighRow + 2));
            $sheet->mergeCells('D' . ($curentHighRow + 3) . ':' . 'F' . ($curentHighRow + 3));
            $sheet->mergeCells('Q' . ($curentHighRow + 1) . ':' . 'Q' . ($curentHighRow + 3));
            $sheet->mergeCells('R' . ($curentHighRow + 1) . ':' . 'R' . ($curentHighRow + 3));
            $sheet->mergeCells('S' . ($curentHighRow + 1) . ':' . 'S' . ($curentHighRow + 3));

            $sheet->setCellValue('A' . ($curentHighRow + 1), "Tingkat Capaian Konvergensi");
            $sheet->setCellValue('D' . ($curentHighRow + 1), "Jumlah Diterima");
            $sheet->setCellValue('D' . ($curentHighRow + 2), "Jumlah Seharusnya");
            $sheet->setCellValue('D' . ($curentHighRow + 3), "%");

            $capaianKonvergensi     = $data["capaianKonvergensi"];
            $tingkatKonvergensiDesa = $data["tingkatKonvergensiDesa"];

            $sheet->setCellValue('G' . ($curentHighRow + 1), $capaianKonvergensi["imunisasi"]["jumlah_diterima"]);
            $sheet->setCellValue('H' . ($curentHighRow + 1), $capaianKonvergensi["pengukuran_berat_badan"]["jumlah_diterima"]);
            $sheet->setCellValue('I' . ($curentHighRow + 1), $capaianKonvergensi["pengukuran_tinggi_badan"]["jumlah_diterima"]);
            $sheet->setCellValue('J' . ($curentHighRow + 1), $capaianKonvergensi["konseling_gizi"]["jumlah_diterima"]);
            $sheet->setCellValue('K' . ($curentHighRow + 1), $capaianKonvergensi["kunjungan_rumah"]["jumlah_diterima"]);
            $sheet->setCellValue('L' . ($curentHighRow + 1), $capaianKonvergensi["air_bersih"]["jumlah_diterima"]);
            $sheet->setCellValue('M' . ($curentHighRow + 1), $capaianKonvergensi["jamban_sehat"]["jumlah_diterima"]);
            $sheet->setCellValue('N' . ($curentHighRow + 1), $capaianKonvergensi["akta_lahir"]["jumlah_diterima"]);
            $sheet->setCellValue('O' . ($curentHighRow + 1), $capaianKonvergensi["jaminan_kesehatan"]["jumlah_diterima"]);
            $sheet->setCellValue('P' . ($curentHighRow + 1), $capaianKonvergensi["pengasuhan_paud"]["jumlah_diterima"]);

            $sheet->setCellValue('G' . ($curentHighRow + 2), $capaianKonvergensi["imunisasi"]["jumlah_seharusnya"]);
            $sheet->setCellValue('H' . ($curentHighRow + 2), $capaianKonvergensi["pengukuran_berat_badan"]["jumlah_seharusnya"]);
            $sheet->setCellValue('I' . ($curentHighRow + 2), $capaianKonvergensi["pengukuran_tinggi_badan"]["jumlah_seharusnya"]);
            $sheet->setCellValue('J' . ($curentHighRow + 2), $capaianKonvergensi["konseling_gizi"]["jumlah_seharusnya"]);
            $sheet->setCellValue('K' . ($curentHighRow + 2), $capaianKonvergensi["kunjungan_rumah"]["jumlah_seharusnya"]);
            $sheet->setCellValue('L' . ($curentHighRow + 2), $capaianKonvergensi["air_bersih"]["jumlah_seharusnya"]);
            $sheet->setCellValue('M' . ($curentHighRow + 2), $capaianKonvergensi["jamban_sehat"]["jumlah_seharusnya"]);
            $sheet->setCellValue('N' . ($curentHighRow + 2), $capaianKonvergensi["akta_lahir"]["jumlah_seharusnya"]);
            $sheet->setCellValue('O' . ($curentHighRow + 2), $capaianKonvergensi["jaminan_kesehatan"]["jumlah_seharusnya"]);
            $sheet->setCellValue('P' . ($curentHighRow + 2), $capaianKonvergensi["pengasuhan_paud"]["jumlah_seharusnya"]);

            $sheet->setCellValue('G' . ($curentHighRow + 3), $capaianKonvergensi["imunisasi"]["persen"]);
            $sheet->setCellValue('H' . ($curentHighRow + 3), $capaianKonvergensi["pengukuran_berat_badan"]["persen"]);
            $sheet->setCellValue('I' . ($curentHighRow + 3), $capaianKonvergensi["pengukuran_tinggi_badan"]["persen"]);
            $sheet->setCellValue('J' . ($curentHighRow + 3), $capaianKonvergensi["konseling_gizi"]["persen"]);
            $sheet->setCellValue('K' . ($curentHighRow + 3), $capaianKonvergensi["kunjungan_rumah"]["persen"]);
            $sheet->setCellValue('L' . ($curentHighRow + 3), $capaianKonvergensi["air_bersih"]["persen"]);
            $sheet->setCellValue('M' . ($curentHighRow + 3), $capaianKonvergensi["jamban_sehat"]["persen"]);
            $sheet->setCellValue('N' . ($curentHighRow + 3), $capaianKonvergensi["akta_lahir"]["persen"]);
            $sheet->setCellValue('O' . ($curentHighRow + 3), $capaianKonvergensi["jaminan_kesehatan"]["persen"]);
            $sheet->setCellValue('P' . ($curentHighRow + 3), $capaianKonvergensi["pengasuhan_paud"]["persen"]);

            $sheet->setCellValue('Q' . ($curentHighRow + 1), $tingkatKonvergensiDesa["jumlah_diterima"]);
            $sheet->setCellValue('R' . ($curentHighRow + 1), $tingkatKonvergensiDesa["jumlah_seharusnya"]);
            $sheet->setCellValue('S' . ($curentHighRow + 1), $tingkatKonvergensiDesa["persen"]);
        }

        //SET BORDER AND ALIGNMENT DATA
        $sheet->getStyle('A1:S6')->applyFromArray($styleJudul);
        $sheet->getStyle('A3:S' . $sheet->getHighestRow())->applyFromArray($styleBorder);
        $sheet->getStyle('A7:S' . $sheet->getHighestRow())->applyFromArray($styleIsi);
        $sheet->getStyle('B7:C' . $sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        //SAVE AND DOWNLOAD
        $writer = new Xlsx($spreadsheet);
        $filename = 'FORMULIR_3B_REKAPITULASI_HASIL_PEMANTAUAN_3_BULANAN_BAGI_ANAK_0_2_TAHUN_KUARTAL_' . strtoupper($kuartal . "_" . $tahun . "_" . date("H_i_s"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
