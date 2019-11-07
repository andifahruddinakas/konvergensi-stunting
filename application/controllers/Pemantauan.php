<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class Pemantauan extends MY_Controller
{
    public function ___construct()
    {
        parent::___construct();
    }

    public function index()
    {
        redirect(base_url());
    }

    public function ibu_hamil($bulan = NULL, $tahun = NULL)
    {
        if ($bulan == NULL || $tahun == NULL) {
            redirect(base_url('pemantauan/ibu-hamil/') . date('m') . '/' . date('Y'));
        }

        $ibuHamil = $this->m_data->getJoin("kia", "ibu_hamil.no_kia = kia.no_kia", "INNER");
        $ibuHamil = $this->m_data->getWhere("MONTH(ibu_hamil.created_at)", $bulan);
        $ibuHamil = $this->m_data->getWhere("YEAR(ibu_hamil.created_at)", $tahun);
        $ibuHamil = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        $ibuHamil = $this->m_data->order_by("ibu_hamil.created_at", "ASC");
        $ibuHamil = $this->m_data->getData("ibu_hamil")->result();

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("ibu_hamil")->result();

        $data["aktif"]      = "pemantauan";
        $data["_bulan"]     = $bulan;
        $data["_tahun"]     = $tahun;
        $data['ibuHamil']   = $ibuHamil;
        $data['dataTahun']  = $dataTahun;
        $data['bulan']      = bulan($bulan);
        $data['title']      = "Pemantauan Bulanan Ibu Hamil";
        return $this->loadView('pemantauan.ibu-hamil', $data);
    }

    public function getDataByNoKia($no_kia = NULL)
    {
        $data   = $this->m_data->getWhere('no_kia', $no_kia);
        $data   = $this->m_data->getData('kia')->row();

        if ($data) {
            echo json_encode(array(
                "status" => 1,
                "data" => $data
            ));
        } else {
            echo json_encode(array(
                "status" => 0,
                "data" => "Data Tidak Ditemukan"
            ));
        }
    }

    public function insertDataIbuHamil()
    {
        $no_kia                 = $this->input->post('no_kia');
        $nama_ibu               = $this->input->post('nama_ibu');
        $status_kehamilan       = $this->input->post('status_kehamilan');
        $perkiraan_lahir        = $this->input->post('perkiraan_lahir');
        $usia_kehamilan         = $this->input->post('usia_kehamilan');
        $tanggal_melahirkan     = $this->input->post('tanggal_melahirkan') == "" ? NULL : $this->input->post('tanggal_melahirkan');
        $pemeriksaan_kehamilan  = $this->input->post('pemeriksaan_kehamilan');
        $pil_fe                 = $this->input->post('pil_fe');
        $butir_pil_fe           = $this->input->post('butir_pil_fe');
        $pemeriksaan_nifas      = $this->input->post('pemeriksaan_nifas');
        $konseling_gizi         = $this->input->post('konseling_gizi');
        $kunjungan_rumah        = $this->input->post('kunjungan_rumah');
        $air_bersih             = $this->input->post('air_bersih');
        $kepemilikan_jamban     = $this->input->post('kepemilikan_jamban');
        $jaminan_kesehatan      = $this->input->post('jaminan_kesehatan');

        $data = array(
            "no_kia"                => $no_kia,
            "status_kehamilan"      => $status_kehamilan,
            "usia_kehamilan"        => $usia_kehamilan,
            "tanggal_melahirkan"    => $tanggal_melahirkan,
            "pemeriksaan_kehamilan" => $pemeriksaan_kehamilan,
            "konsumsi_pil_fe"       => $pil_fe,
            "butir_pil_fe"          => $butir_pil_fe,
            "pemeriksaan_nifas"     => $pemeriksaan_nifas,
            "konseling_gizi"        => $konseling_gizi,
            "kunjungan_rumah"       => $kunjungan_rumah,
            "akses_air_bersih"      => $air_bersih,
            "kepemilikan_jamban"    => $kepemilikan_jamban,
            "jaminan_kesehatan"     => $jaminan_kesehatan,
            "id_user"               => $this->session->userdata("login")->id_user,
            "id_posyandu"           => $this->session->userdata("login")->id_posyandu
        );

        $cekInput = $this->m_data->getWhere("no_kia", $data["no_kia"]);
        $cekInput = $this->m_data->getWhere("MONTH(created_at)", date('m'));
        $cekInput = $this->m_data->getWhere("YEAR(created_at)", date('Y'));
        $cekInput = $this->m_data->getData("ibu_hamil")->num_rows();

        if ($cekInput > 0) {
            $this->session->set_flashdata("gagal", "Maaf data ibu $nama_ibu pada bulan ini sudah diinputkan!");
            return $this->ibu_hamil();
        } else {
            //CEK DI TABLE KIA DULU SLUUR
            $cekData = $this->m_data->getWhere("no_kia", $no_kia);
            $cekData = $this->m_data->getData("kia")->num_rows();
            if ($cekData > 0) {
                //SUDAH ADA DATA -> UPDATE KIA DULU -> TRUS INSERT
                if ($nama_ibu !== "") {
                    $this->m_data->update("kia", ["nama_ibu" => $nama_ibu, "hari_perkiraan_lahir"  => $perkiraan_lahir], ["no_kia" => $no_kia]);
                }
                $this->insert_ibu_hamil($data);
            } else {
                //BELUM ADA DATA -> INSERT KE TABLE KIA DULU
                $insertKia  = $this->m_data->insert("kia", array(
                    "no_kia"    => $no_kia,
                    "nama_ibu"  => $nama_ibu
                ));

                if ($insertKia) {
                    $this->insert_ibu_hamil($data);
                } else {
                    $this->session->set_flashdata("gagal", $this->m_data->getError());
                    return $this->ibu_hamil();
                }
            }
        }
    }

    public function insert_ibu_hamil($data)
    {
        $insertIbuHamil = $this->m_data->insert("ibu_hamil", $data);
        if ($insertIbuHamil) {
            $this->session->set_flashdata("sukses", "Menyimpan data pada pemantauan bulanan ibu hamil");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        $this->ibu_hamil();
    }

    public function hapus_data_ibu_hamil()
    {
        $id_ibu_hamil   = $this->input->post('id_ibu_hamil');
        $hapus          = $this->m_data->delete(array("id_ibu_hamil" => $id_ibu_hamil), "ibu_hamil");
        if ($hapus > 0) {
            $this->session->set_flashdata("sukses", "Data berhasil di hapus dari database");
        } else {
            $this->session->set_flashdata("gagal", "Terjadi kesalahan saat menghapus data");
        }
        $this->ibu_hamil();
    }

    public function edit_ibu_hamil()
    {
        $id_ibu_hamil           = $this->input->post('id_ibu_hamil');
        $nama_ibu               = $this->input->post('nama_ibu');
        $status_kehamilan       = $this->input->post('status_kehamilan');
        $perkiraan_lahir        = $this->input->post('perkiraan_lahir');
        $usia_kehamilan         = $this->input->post('usia_kehamilan');
        $tanggal_melahirkan     = $this->input->post('tanggal_melahirkan') == "" ? NULL : $this->input->post('tanggal_melahirkan');
        $pemeriksaan_kehamilan  = $this->input->post('pemeriksaan_kehamilan');
        $pil_fe                 = $this->input->post('pil_fe');
        $butir_pil_fe           = $this->input->post('butir_pil_fe');
        $pemeriksaan_nifas      = $this->input->post('pemeriksaan_nifas');
        $konseling_gizi         = $this->input->post('konseling_gizi');
        $kunjungan_rumah        = $this->input->post('kunjungan_rumah');
        $air_bersih             = $this->input->post('air_bersih');
        $kepemilikan_jamban     = $this->input->post('kepemilikan_jamban');
        $jaminan_kesehatan      = $this->input->post('jaminan_kesehatan');

        $data = array(
            "status_kehamilan"      => $status_kehamilan,
            "usia_kehamilan"        => $usia_kehamilan,
            "tanggal_melahirkan"    => $tanggal_melahirkan,
            "pemeriksaan_kehamilan" => $pemeriksaan_kehamilan,
            "konsumsi_pil_fe"       => $pil_fe,
            "butir_pil_fe"          => $butir_pil_fe,
            "pemeriksaan_nifas"     => $pemeriksaan_nifas,
            "konseling_gizi"        => $konseling_gizi,
            "kunjungan_rumah"       => $kunjungan_rumah,
            "akses_air_bersih"      => $air_bersih,
            "kepemilikan_jamban"    => $kepemilikan_jamban,
            "jaminan_kesehatan"     => $jaminan_kesehatan,
            "updated_at"            => date("Y-m-d H:i:s")
        );

        $updateData             = $this->m_data->update("ibu_hamil", $data, ["id_ibu_hamil" => $id_ibu_hamil]);
        if ($updateData == 1) {
            $this->session->set_flashdata("sukses", "Mengedit data ibu $nama_ibu pada database");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }

        $this->ibu_hamil();
    }

    public function export_ibu_hamil($bulan = NULL, $tahun = NULL)
    {
        if ($bulan == NULL || $tahun == NULL) {
            redirect(base_url('pemantauan/ibu-hamil/') . date('m') . '/' . date('Y'));
        }

        $ibuHamil = $this->m_data->getJoin("kia", "ibu_hamil.no_kia = kia.no_kia", "INNER");
        $ibuHamil = $this->m_data->getWhere("MONTH(ibu_hamil.created_at)", $bulan);
        $ibuHamil = $this->m_data->getWhere("YEAR(ibu_hamil.created_at)", $tahun);
        $ibuHamil = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        $ibuHamil = $this->m_data->order_by("ibu_hamil.created_at", "ASC");
        $ibuHamil = $this->m_data->getData("ibu_hamil")->result();

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
        $sheet->setTitle('IBU HAMIL');

        //PAGE SETUP
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        //MERGE CELL
        $sheet->mergeCells('A1:O1');
        $sheet->mergeCells('A3:A5');
        $sheet->mergeCells('B3:B5');
        $sheet->mergeCells('C3:C5');
        $sheet->mergeCells('D3:D5');
        $sheet->mergeCells('E3:E5');
        $sheet->mergeCells('F3:O3');
        $sheet->mergeCells('F4:G4');
        $sheet->mergeCells('H4:O4');

        //APPLY STYLE
        $sheet->getStyle('A1')->applyFromArray($styleJudul);
        $sheet->getStyle('A1')->applyFromArray($styleJudul);
        $sheet->getStyle('A3')->applyFromArray($styleJudul);
        $sheet->getStyle('B3')->applyFromArray($styleJudul);
        $sheet->getStyle('C3')->applyFromArray($styleJudul);
        $sheet->getStyle('D3')->applyFromArray($styleJudul);
        $sheet->getStyle('E3')->applyFromArray($styleJudul);
        $sheet->getStyle('F3')->applyFromArray($styleJudul);
        $sheet->getStyle('F4')->applyFromArray($styleJudul);
        $sheet->getStyle('H4')->applyFromArray($styleJudul);
        $sheet->getStyle('F5')->applyFromArray($styleJudul);
        $sheet->getStyle('G5')->applyFromArray($styleJudul);
        $sheet->getStyle('H5')->applyFromArray($styleJudul);
        $sheet->getStyle('I5')->applyFromArray($styleJudul);
        $sheet->getStyle('J5')->applyFromArray($styleJudul);
        $sheet->getStyle('K5')->applyFromArray($styleJudul);
        $sheet->getStyle('L5')->applyFromArray($styleJudul);
        $sheet->getStyle('M5')->applyFromArray($styleJudul);
        $sheet->getStyle('N5')->applyFromArray($styleJudul);
        $sheet->getStyle('O5')->applyFromArray($styleJudul);

        $sheet->setCellValue('A1', 'FORMULIR 2.A. PEMANTAUAN BULANAN IBU HAMIL');
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'No Register (KIA)');
        $sheet->setCellValue('C3', 'Nama Ibu');
        $sheet->setCellValue('D3', 'Status Kehamilan (NORMAL / KEK / RISTI)');
        $sheet->setCellValue('E3', 'Hari Perkiraan Lahir (Tgl/Bln/Thn)');
        $sheet->setCellValue('F3', 'BULAN : ' . strtoupper(bulan($bulan)) . " " . $tahun);
        $sheet->setCellValue('F4', 'Usia Kehamilan dan Persalinan');
        $sheet->setCellValue('H4', 'Status Penerimaan Indikator');
        $sheet->setCellValue('F5', 'Usia Kehamilan (Bulan)');
        $sheet->setCellValue('G5', 'Tanggal Melahirkan (Tgl/Bln/Thn)');
        $sheet->setCellValue('H5', 'Pemeriksaan Kehamilan');
        $sheet->setCellValue('I5', 'Dapat & Konsumsi Pil Fe');
        $sheet->setCellValue('J5', 'Pemeriksaan Nifas');
        $sheet->setCellValue('K5', 'Konseling Gizi (Kelas IH)');
        $sheet->setCellValue('L5', 'Kunjungan Rumah');
        $sheet->setCellValue('M5', 'Kepemilikan Akses Air Bersih');
        $sheet->setCellValue('N5', 'Kepemilikan Jamban');
        $sheet->setCellValue('O5', 'Jaminan Kesehatan');

        //SET ORIENTATION
        foreach (range('F', 'O') as $kolom) {
            $sheet->getStyle($kolom . '5')->getAlignment()->setTextRotation(90);
        }

        //RESIZE WIDTH
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(8);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(5);
        $sheet->getColumnDimension('I')->setWidth(5);
        $sheet->getColumnDimension('J')->setWidth(5);
        $sheet->getColumnDimension('K')->setWidth(5);
        $sheet->getColumnDimension('L')->setWidth(5);
        $sheet->getColumnDimension('M')->setWidth(5);
        $sheet->getColumnDimension('N')->setWidth(5);
        $sheet->getColumnDimension('O')->setWidth(5);

        //SET INDEX
        foreach (range('A', 'O') as $kolom) {
            $sheet->setCellValue($kolom . '6', strtolower($kolom));        
        }

        // //SET DATA
        $batasBaris = 6;
        $no = 1;
        foreach ($ibuHamil as $data) {            
            $barisSekarang = $batasBaris + $no;
            $sheet->setCellValue('A' . $barisSekarang, $no);
            $sheet->setCellValue('B' . $barisSekarang, $data->no_kia);
            $sheet->setCellValue('C' . $barisSekarang, $data->nama_ibu);
            $sheet->setCellValue('D' . $barisSekarang, $data->status_kehamilan);
            $sheet->setCellValue('E' . $barisSekarang, $data->hari_perkiraan_lahir == null ? "-" : shortdate_indo($data->hari_perkiraan_lahir));
            $sheet->setCellValue('F' . $barisSekarang, $data->usia_kehamilan);
            $sheet->setCellValue('G' . $barisSekarang, $data->tanggal_melahirkan == null ? "-" : shortdate_indo($data->tanggal_melahirkan));
            $sheet->setCellValue('H' . $barisSekarang, $data->pemeriksaan_kehamilan);
            $sheet->setCellValue('I' . $barisSekarang, $data->konsumsi_pil_fe);
            $sheet->setCellValue('J' . $barisSekarang, $data->pemeriksaan_nifas);
            $sheet->setCellValue('K' . $barisSekarang, $data->konseling_gizi);
            $sheet->setCellValue('L' . $barisSekarang, $data->kunjungan_rumah);
            $sheet->setCellValue('M' . $barisSekarang, $data->akses_air_bersih);
            $sheet->setCellValue('N' . $barisSekarang, $data->kepemilikan_jamban);
            $sheet->setCellValue('O' . $barisSekarang, $data->jaminan_kesehatan);
            $no++;
        }

        // //SET BORDER AND ALIGNMENT DATA
        $sheet->getStyle('A6:O6')->applyFromArray($styleJudul);
        $sheet->getStyle('A3:O' . $sheet->getHighestRow())->applyFromArray($styleBorder);
        $sheet->getStyle('A7:O' . $sheet->getHighestRow())->applyFromArray($styleIsi);
        $sheet->getStyle('B7:C' . $sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        //SAVE AND DOWNLOAD
        $writer = new Xlsx($spreadsheet);
        $filename = 'FORMULIR_2A_PEMANTAUAN_BULANAN_IBU_HAMIL_' . strtoupper(bulan($bulan) . "_" . $tahun . "_" . date("H_i_s"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    public function bulanan_anak($bulan = NULL, $tahun = NULL)
    {

        if ($bulan == NULL || $tahun == NULL) {
            redirect(base_url('pemantauan/bulanan-anak/') . date('m') . '/' . date('Y'));
        }
        
        $bulananAnak = $this->m_data->getJoin("kia", "bulanan_anak.no_kia = kia.no_kia", "INNER");
        $bulananAnak = $this->m_data->getWhere("MONTH(bulanan_anak.created_at)", $bulan);
        $bulananAnak = $this->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
        $bulananAnak = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        $bulananAnak = $this->m_data->order_by("bulanan_anak.created_at", "ASC");
        $bulananAnak = $this->m_data->getData("bulanan_anak")->result();

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("ibu_hamil")->result();

        $data["aktif"]          = "pemantauan";
        $data["_bulan"]         = $bulan;
        $data["_tahun"]         = $tahun;
        $data['bulananAnak']    = $bulananAnak;
        $data['dataTahun']      = $dataTahun;
        $data['bulan']          = bulan($bulan);
        $data['title']          = "Pemantauan Bulanan Anak 0 - 2 Tahun";
        return $this->loadView('pemantauan.bulanan-anak', $data);
    }

    public function insertDataBulananAnak()
    {
        $no_kia                     = $this->input->post('no_kia');
        $nama_anak                  = $this->input->post('nama_anak');
        $jenis_kelamin_anak         = $this->input->post('jenis_kelamin_anak');
        $tanggal_lahir_anak         = $this->input->post('tanggal_lahir_anak');
        $status_gizi                = $this->input->post('status_gizi');
        $umur_bulan                 = $this->input->post('umur_bulan');
        $status_tikar               = $this->input->post('status_tikar');
        $pemberian_imunisasi_dasar  = $this->input->post('pemberian_imunisasi_dasar');
        $pemberian_imunisasi_campak = $this->input->post('pemberian_imunisasi_campak');
        $pengukuran_berat_badan     = $this->input->post('pengukuran_berat_badan');
        $pengukuran_tinggi_badan    = $this->input->post('pengukuran_tinggi_badan');
        $konseling_gizi_ayah        = $this->input->post('konseling_gizi_ayah');
        $konseling_gizi_ibu         = $this->input->post('konseling_gizi_ibu');
        $kunjungan_rumah            = $this->input->post('kunjungan_rumah');
        $air_bersih                 = $this->input->post('air_bersih');
        $kepemilikan_jamban         = $this->input->post('kepemilikan_jamban');
        $akta_lahir                 = $this->input->post('akta_lahir');
        $jaminan_kesehatan          = $this->input->post('jaminan_kesehatan');
        $pengasuhan_paud            = $this->input->post('pengasuhan_paud');

        $data = array(
            "no_kia"                    => $no_kia,
            "status_gizi"               => $status_gizi,
            "umur_bulan"                => $umur_bulan,
            "status_tikar"              => $status_tikar,
            "pemberian_imunisasi_dasar" => $pemberian_imunisasi_dasar,
            "pemberian_imunisasi_campak" => $pemberian_imunisasi_campak,
            "pengukuran_berat_badan"    => $pengukuran_berat_badan,
            "pengukuran_tinggi_badan"   => $pengukuran_tinggi_badan,
            "konseling_gizi_ayah"       => $konseling_gizi_ayah,
            "konseling_gizi_ibu"        => $konseling_gizi_ibu,
            "kunjungan_rumah"           => $kunjungan_rumah,
            "air_bersih"                => $air_bersih,
            "kepemilikan_jamban"        => $kepemilikan_jamban,
            "akta_lahir"                => $akta_lahir,
            "jaminan_kesehatan"         => $jaminan_kesehatan,
            "pengasuhan_paud"           => $pengasuhan_paud,
            "id_user"               => $this->session->userdata("login")->id_user,
            "id_posyandu"           => $this->session->userdata("login")->id_posyandu
        );

        $cekInput = $this->m_data->getWhere("no_kia", $data["no_kia"]);
        $cekInput = $this->m_data->getWhere("MONTH(created_at)", date('m'));
        $cekInput = $this->m_data->getWhere("YEAR(created_at)", date('Y'));
        $cekInput = $this->m_data->getData("bulanan_anak")->num_rows();

        if ($cekInput > 0) {
            $this->session->set_flashdata("gagal", "Maaf data $nama_anak pada bulan ini sudah diinputkan!");
            return $this->bulanan_anak();
        } else {
            //CEK DI TABLE KIA DULU SLUUR
            $cekData = $this->m_data->getWhere("no_kia", $no_kia);
            $cekData = $this->m_data->getData("kia")->num_rows();
            if ($cekData > 0) {
                //SUDAH ADA DATA -> UPDATE KIA DULU -> TRUS INSERT
                if ($nama_anak !== "") {
                    $this->m_data->update(
                        "kia",
                        [
                            "nama_anak"             => $nama_anak,
                            "jenis_kelamin_anak"    => $jenis_kelamin_anak,
                            "tanggal_lahir_anak"    => $tanggal_lahir_anak,
                            "updated_at"            => date("Y-m-d H:i:s")
                        ],
                        [
                            "no_kia" => $no_kia
                        ]
                    );
                }
                $this->insert_bulanan_anak($data);
            } else {
                //BELUM ADA DATA -> INSERT KE TABLE KIA DULU
                $insertKia  = $this->m_data->insert(
                    "kia",
                    [
                        "no_kia"                => $no_kia,
                        "nama_anak"             => $nama_anak,
                        "jenis_kelamin_anak"    => $jenis_kelamin_anak,
                        "tanggal_lahir_anak"    => $tanggal_lahir_anak,
                    ]
                );

                if ($insertKia) {
                    $this->insert_bulanan_anak($data);
                } else {
                    $this->session->set_flashdata("gagal", $this->m_data->getError());
                    return $this->bulanan_anak();
                }
            }
        }
    }

    public function insert_bulanan_anak($data)
    {
        $insertBulananAnak = $this->m_data->insert("bulanan_anak", $data);
        if ($insertBulananAnak) {
            $this->session->set_flashdata("sukses", "Menyimpan data pada pemantauan bulanan anak 0-2 tahun");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        $this->bulanan_anak();
    }

    public function hapus_data_bulanan_anak()
    {
        $id_bulanan_anak    = $this->input->post('id_bulanan_anak');
        $hapus              = $this->m_data->delete(array("id_bulanan_anak" => $id_bulanan_anak), "bulanan_anak");
        if ($hapus > 0) {
            $this->session->set_flashdata("sukses", "Data berhasil di hapus dari database");
        } else {
            $this->session->set_flashdata("gagal", "Terjadi kesalahan saat menghapus data");
        }
        $this->bulanan_anak();
    }

    public function edit_bulanan_anak()
    {
        $id_bulanan_anak            = $this->input->post('id_bulanan_anak');
        $no_kia                     = $this->input->post('no_kia');
        $nama_anak                  = $this->input->post('nama_anak');
        $status_gizi                = $this->input->post('status_gizi');
        $umur_bulan                 = $this->input->post('umur_bulan');
        $status_tikar               = $this->input->post('status_tikar');
        $pemberian_imunisasi_dasar  = $this->input->post('pemberian_imunisasi_dasar');
        $pemberian_imunisasi_campak = $this->input->post('pemberian_imunisasi_campak');
        $pengukuran_berat_badan     = $this->input->post('pengukuran_berat_badan');
        $pengukuran_tinggi_badan    = $this->input->post('pengukuran_tinggi_badan');
        $konseling_gizi_ayah        = $this->input->post('konseling_gizi_ayah');
        $konseling_gizi_ibu         = $this->input->post('konseling_gizi_ibu');
        $kunjungan_rumah            = $this->input->post('kunjungan_rumah');
        $air_bersih                 = $this->input->post('air_bersih');
        $kepemilikan_jamban         = $this->input->post('kepemilikan_jamban');
        $akta_lahir                 = $this->input->post('akta_lahir');
        $jaminan_kesehatan          = $this->input->post('jaminan_kesehatan');
        $pengasuhan_paud            = $this->input->post('pengasuhan_paud');

        $data = array(
            "no_kia"                    => $no_kia,
            "status_gizi"               => $status_gizi,
            "umur_bulan"                => $umur_bulan,
            "status_tikar"              => $status_tikar,
            "pemberian_imunisasi_dasar" => $pemberian_imunisasi_dasar,
            "pemberian_imunisasi_campak" => $pemberian_imunisasi_campak,
            "pengukuran_berat_badan"    => $pengukuran_berat_badan,
            "pengukuran_tinggi_badan"   => $pengukuran_tinggi_badan,
            "konseling_gizi_ayah"       => $konseling_gizi_ayah,
            "konseling_gizi_ibu"        => $konseling_gizi_ibu,
            "kunjungan_rumah"           => $kunjungan_rumah,
            "air_bersih"                => $air_bersih,
            "kepemilikan_jamban"        => $kepemilikan_jamban,
            "akta_lahir"                => $akta_lahir,
            "jaminan_kesehatan"         => $jaminan_kesehatan,
            "pengasuhan_paud"           => $pengasuhan_paud,
            "updated_at"                => date("Y-m-d H:i:s")
        );

        $updateData             = $this->m_data->update("bulanan_anak", $data, ["id_bulanan_anak" => $id_bulanan_anak]);
        if ($updateData == 1) {
            $this->session->set_flashdata("sukses", "Mengedit data $nama_anak pada database");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }

        $this->bulanan_anak();
    }

    public function export_bulanan_anak($bulan = NULL, $tahun = NULL)
    {
        if ($bulan == NULL || $tahun == NULL) {
            redirect(base_url('pemantauan/bulanan-anak/') . date('m') . '/' . date('Y'));
        }

        $bulananAnak = $this->m_data->getJoin("kia", "bulanan_anak.no_kia = kia.no_kia", "INNER");
        $bulananAnak = $this->m_data->getWhere("MONTH(bulanan_anak.created_at)", $bulan);
        $bulananAnak = $this->m_data->getWhere("YEAR(bulanan_anak.created_at)", $tahun);
        $bulananAnak = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        $bulananAnak = $this->m_data->order_by("bulanan_anak.created_at", "ASC");
        $bulananAnak = $this->m_data->getData("bulanan_anak")->result();

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
        $sheet->setTitle('IBU HAMIL');

        //PAGE SETUP
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        //MERGE CELL
        $sheet->mergeCells('A1:S1');
        $sheet->mergeCells('A3:A6');
        $sheet->mergeCells('B3:B6');
        $sheet->mergeCells('C3:C6');
        $sheet->mergeCells('D3:D6');
        $sheet->mergeCells('E3:E6');
        $sheet->mergeCells('F3:F6');
        $sheet->mergeCells('G3:S3');
        $sheet->mergeCells('G4:H4');
        $sheet->mergeCells('I4:S4');
        $sheet->mergeCells('G5:G6');
        $sheet->mergeCells('H5:H6');
        $sheet->mergeCells('I5:I6');
        $sheet->mergeCells('J5:J6');
        $sheet->mergeCells('K5:K6');
        $sheet->mergeCells('L5:M5');
        $sheet->mergeCells('N5:N6');
        $sheet->mergeCells('O5:O6');
        $sheet->mergeCells('P5:P6');
        $sheet->mergeCells('Q5:Q6');
        $sheet->mergeCells('R5:R6');
        $sheet->mergeCells('S5:S6');

        //APPLY STYLE
        $sheet->getStyle('A1:S6')->applyFromArray($styleJudul);

        $sheet->setCellValue('A1', 'FORMULIR 2.B. PEMANTAUAN BULANAN ANAK 0-2 TAHUN');
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'No Register (KIA)');
        $sheet->setCellValue('C3', 'Nama Anak');
        $sheet->setCellValue('D3', 'Jenis Kelamin (L/P)');
        $sheet->setCellValue('E3', 'Tanggal Lahir Anak (Tgl/Bln/Thn)');
        $sheet->setCellValue('F3', 'Status Gizi Anak (Normal/Buruk/Kurang/Stunting)');
        $sheet->setCellValue('G3', 'BULAN : ' . strtoupper(bulan($bulan)) . " " . $tahun);
        $sheet->setCellValue('G4', 'Umur dan Status Tikar');
        $sheet->setCellValue('I4', 'Indikator Layanan');
        $sheet->setCellValue('G5', 'Umur (Bulan)');
        $sheet->setCellValue('H5', 'Hasil (M/K/H)');
        $sheet->setCellValue('I5', 'Pemberian Imunisasi Dasar');
        $sheet->setCellValue('J5', 'Pengukuran Berat Badan');
        $sheet->setCellValue('K5', 'Pengukuran Tinggi Badan');
        $sheet->setCellValue('L5', 'Konseling Gizi Bagi Orang Tua');
        $sheet->setCellValue('N5', 'Kunjungan Rumah');
        $sheet->setCellValue('O5', 'Kepemilikan Akses Air Bersih');
        $sheet->setCellValue('P5', 'Kepemilikan Jamban Sehat');
        $sheet->setCellValue('Q5', 'Akta Lahir');
        $sheet->setCellValue('R5', 'Jaminan Kesehatan');
        $sheet->setCellValue('S5', 'Pengasuhan (PAUD)');
        $sheet->setCellValue('L6', '(L)');
        $sheet->setCellValue('M6', '(P)');

        //SET ROW HEIGH
        $sheet->getRowDimension('5')->setRowHeight(100);

        //SET ORIENTATION
        $sheet->getStyle('D3')->getAlignment()->setTextRotation(90);
        $sheet->getStyle('G5')->getAlignment()->setTextRotation(90);
        $sheet->getStyle('H5')->getAlignment()->setTextRotation(90);
        $sheet->getStyle('I5:S5')->getAlignment()->setTextRotation(90);
        $sheet->getStyle('L5')->getAlignment()->setTextRotation(0);

        //RESIZE WIDTH
        foreach (range('I', 'K') as $kolom) {
            $sheet->getColumnDimension($kolom)->setWidth(5);
        }

        foreach (range('N', 'S') as $kolom) {
            $sheet->getColumnDimension($kolom)->setWidth(5);
        }
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('L')->setWidth(8);
        $sheet->getColumnDimension('M')->setWidth(8);
        $sheet->getColumnDimension('G')->setWidth(5);
        $sheet->getColumnDimension('H')->setWidth(5);

        // SET INDEX
        foreach (range('A', 'S') as $kolom) {
            $sheet->setCellValue($kolom . '7', strtolower($kolom));
        }

        //SET DATA
        $batasBaris = 7;
        $no = 1;
        foreach ($bulananAnak as $data) {
            $barisSekarang = $batasBaris + $no;
            $sheet->setCellValue('A' . $barisSekarang, $no);
            $sheet->setCellValue('B' . $barisSekarang, $data->no_kia);
            $sheet->setCellValue('C' . $barisSekarang, $data->nama_anak);
            $sheet->setCellValue('D' . $barisSekarang, $data->jenis_kelamin_anak);
            $sheet->setCellValue('E' . $barisSekarang, shortdate_indo($data->tanggal_lahir_anak));
            $sheet->setCellValue('F' . $barisSekarang, $data->status_gizi);
            $sheet->setCellValue('G' . $barisSekarang, $data->umur_bulan);
            $sheet->setCellValue('H' . $barisSekarang, $data->status_tikar);
            $sheet->setCellValue('I' . $barisSekarang, $data->pemberian_imunisasi_dasar);
            $sheet->setCellValue('J' . $barisSekarang, $data->pengukuran_berat_badan);
            $sheet->setCellValue('K' . $barisSekarang, $data->pengukuran_tinggi_badan);
            $sheet->setCellValue('L' . $barisSekarang, $data->konseling_gizi_ayah);
            $sheet->setCellValue('M' . $barisSekarang, $data->konseling_gizi_ibu);
            $sheet->setCellValue('N' . $barisSekarang, $data->kunjungan_rumah);
            $sheet->setCellValue('O' . $barisSekarang, $data->air_bersih);
            $sheet->setCellValue('P' . $barisSekarang, $data->kepemilikan_jamban);
            $sheet->setCellValue('Q' . $barisSekarang, $data->akta_lahir);
            $sheet->setCellValue('R' . $barisSekarang, $data->jaminan_kesehatan);
            $sheet->setCellValue('S' . $barisSekarang, $data->pengasuhan_paud);
            $no++;
        }

        //SET BORDER AND ALIGNMENT DATA
        $sheet->getStyle('A7:S7')->applyFromArray($styleJudul);
        $sheet->getStyle('A8:S' . $sheet->getHighestRow())->applyFromArray($styleIsi);
        $sheet->getStyle('A3:S' . $sheet->getHighestRow())->applyFromArray($styleBorder);
        $sheet->getStyle('B8:C' . $sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        //SAVE AND DOWNLOAD
        $writer = new Xlsx($spreadsheet);
        $filename = 'FORMULIR_2B_PEMANTAUAN_BULANAN_ANAK_0_2_TAHUN_' . strtoupper(bulan($bulan) . "_" . $tahun . "_" . date("H_i_s"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    public function sasaran_paud($tahun = NULL)
    {
        if ($tahun == NULL) {
            redirect(base_url('pemantauan/sasaran-paud/') . date('Y'));
        }

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("sasaran_paud")->result();

        $dataSasaranPaud    = $this->m_data->getWhere("YEAR(created_at)", $tahun);
        $dataSasaranPaud    = $this->m_data->getWhere("id_posyandu", $this->session->userdata("login")->id_posyandu);
        $dataSasaranPaud    = $this->m_data->getData("sasaran_paud")->result();

        $data["aktif"]              = "pemantauan";
        $data["_tahun"]             = $tahun;
        $data['dataTahun']          = $dataTahun;
        $data['dataSasaranPaud']    = $dataSasaranPaud;
        $data['title']              = "Pemantauan Layanan dan Sasaran PAUD Anak > 2 - 6 Tahun";
        return $this->loadView('pemantauan.sasaran-paud', $data);
    }

    public function insertSasaranPaud()
    {
        $no_rt                  = $this->input->post('no_rt');
        $nama_anak              = $this->input->post('nama_anak');
        $jenis_kelamin_anak     = $this->input->post('jenis_kelamin_anak');
        $usia_menurut_kategori  = $this->input->post('usia_menurut_kategori');
        $januari                = $this->input->post('januari');
        $februari               = $this->input->post('februari');
        $maret                  = $this->input->post('maret');
        $april                  = $this->input->post('april');
        $mei                    = $this->input->post('mei');
        $juni                   = $this->input->post('juni');
        $juli                   = $this->input->post('juli');
        $agustus                = $this->input->post('agustus');
        $september              = $this->input->post('september');
        $oktober                = $this->input->post('oktober');
        $november               = $this->input->post('november');
        $desember               = $this->input->post('desember');

        $data = array(
            "no_rt"                 => $no_rt,
            "nama_anak"             => $nama_anak,
            "jenis_kelamin"         => $jenis_kelamin_anak,
            "usia_menurut_kategori" => $usia_menurut_kategori,
            "januari"               => $januari,
            "februari"              => $februari,
            "maret"                 => $maret,
            "april"                 => $april,
            "mei"                   => $mei,
            "juni"                  => $juni,
            "juli"                  => $juli,
            "agustus"               => $agustus,
            "september"             => $september,
            "oktober"               => $oktober,
            "november"              => $november,
            "desember"              => $desember,
            "id_user"               => $this->session->userdata("login")->id_user,
            "id_posyandu"           => $this->session->userdata("login")->id_posyandu
        );

        $insertSasaranPaud = $this->m_data->insert("sasaran_paud", $data);
        if ($insertSasaranPaud) {
            $this->session->set_flashdata("sukses", "Menyimpan data pada pemantauan Layanan dan Sasaran PAUD Anak > 2 - 6 Tahun");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        $this->sasaran_paud();
    }

    public function export_sasaran_paud($tahun = NULL)
    {
        if ($tahun == NULL) {
            redirect(base_url('pemantauan/export-sasaran-paud/') . date('Y'));
        }

        $dataSasaranPaud    = $this->m_data->getWhere("YEAR(created_at)", $tahun);
        $dataSasaranPaud    = $dataSasaranPaud    = $this->m_data->getWhere("YEAR(created_at)", $tahun);
        $dataSasaranPaud    = $this->m_data->getData("sasaran_paud")->result();

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

        $styleIsi = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal'    => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'      => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'      => TRUE
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $inputFileType  = 'Xlsx';
        $inputFileName  =  "assets/template/sasaran_paud.xlsx";
        $reader         = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet    = $reader->load($inputFileName);
        $worksheet      = $spreadsheet->getActiveSheet();

        //SET DATA
        $worksheet->getCell('G5')->setValue('Tahun : ' . $tahun);
        if (sizeof($dataSasaranPaud) > 0) {
            $baris  = 8;
            $no     = 1;
            foreach ($dataSasaranPaud as $item) {
                $worksheet->getCell('A' . $baris)->setValue($no);
                $worksheet->getCell('B' . $baris)->setValue($item->no_rt);
                $worksheet->getCell('C' . $baris)->setValue($item->nama_anak);
                $worksheet->getCell('D' . $baris)->setValue($item->jenis_kelamin);
                $worksheet->getCell('E' . $baris)->setValue($item->usia_menurut_kategori    == "a"      ? "v" : "x");
                $worksheet->getCell('F' . $baris)->setValue($item->usia_menurut_kategori    == "b"      ? "v" : "x");
                $worksheet->getCell('G' . $baris)->setValue($item->januari                  == "belum"  ? "-" : $item->januari);
                $worksheet->getCell('H' . $baris)->setValue($item->februari                 == "belum"  ? "-" : $item->februari);
                $worksheet->getCell('I' . $baris)->setValue($item->maret                    == "belum"  ? "-" : $item->maret);
                $worksheet->getCell('J' . $baris)->setValue($item->april                    == "belum"  ? "-" : $item->april);
                $worksheet->getCell('K' . $baris)->setValue($item->mei                      == "belum"  ? "-" : $item->mei);
                $worksheet->getCell('L' . $baris)->setValue($item->juni                     == "belum"  ? "-" : $item->juni);
                $worksheet->getCell('M' . $baris)->setValue($item->juli                     == "belum"  ? "-" : $item->juli);
                $worksheet->getCell('N' . $baris)->setValue($item->agustus                  == "belum"  ? "-" : $item->agustus);
                $worksheet->getCell('O' . $baris)->setValue($item->september                == "belum"  ? "-" : $item->september);
                $worksheet->getCell('P' . $baris)->setValue($item->oktober                  == "belum"  ? "-" : $item->oktober);
                $worksheet->getCell('Q' . $baris)->setValue($item->november                 == "belum"  ? "-" : $item->november);
                $worksheet->getCell('R' . $baris)->setValue($item->desember                 == "belum"  ? "-" : $item->desember);
                $baris++;
                $no++;
            }
            $worksheet->getStyle('A8:R' . $worksheet->getHighestRow())->applyFromArray($styleIsi);
            $worksheet->getStyle('A8:R' . $worksheet->getHighestRow())->applyFromArray($styleIsi);
            $worksheet->getStyle('C8:C' . $worksheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        } else {
            $worksheet->mergeCells('A8:R8');
            $worksheet->getCell('A8')->setValue('Data Tidak Ditemukan!');
            $worksheet->getStyle('A8')->applyFromArray($styleJudul);
        }




        //SAVE AND DOWNLOAD
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'PEMANTAUAN_LAYANAN_DAN_SASARAN_PAUD_ANAK_2_6_TAHUN_' . strtoupper($tahun . "_" . date("H_i_s"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function hapus_sasaran_paud()
    {
        $id_sasaran_paud    = $this->input->post('id_sasaran_paud');
        $hapus              = $this->m_data->delete(array("id_sasaran_paud" => $id_sasaran_paud), "sasaran_paud");
        if ($hapus > 0) {
            $this->session->set_flashdata("sukses", "Data berhasil di hapus dari database");
        } else {
            $this->session->set_flashdata("gagal", "Terjadi kesalahan saat menghapus data");
        }
        $this->sasaran_paud();
    }

    public function edit_sasaran_paud()
    {
        $id_sasaran_paud        = $this->input->post('id_sasaran_paud');
        $no_rt                  = $this->input->post('no_rt');
        $nama_anak              = $this->input->post('nama_anak');
        $jenis_kelamin_anak     = $this->input->post('jenis_kelamin_anak');
        $usia_menurut_kategori  = $this->input->post('usia_menurut_kategori');
        $januari                = $this->input->post('januari');
        $februari               = $this->input->post('februari');
        $maret                  = $this->input->post('maret');
        $april                  = $this->input->post('april');
        $mei                    = $this->input->post('mei');
        $juni                   = $this->input->post('juni');
        $juli                   = $this->input->post('juli');
        $agustus                = $this->input->post('agustus');
        $september              = $this->input->post('september');
        $oktober                = $this->input->post('oktober');
        $november               = $this->input->post('november');
        $desember               = $this->input->post('desember');

        $data = array(
            "no_rt"                 => $no_rt,
            "nama_anak"             => $nama_anak,
            "jenis_kelamin"         => $jenis_kelamin_anak,
            "usia_menurut_kategori" => $usia_menurut_kategori,
            "januari"               => $januari,
            "februari"              => $februari,
            "maret"                 => $maret,
            "april"                 => $april,
            "mei"                   => $mei,
            "juni"                  => $juni,
            "juli"                  => $juli,
            "agustus"               => $agustus,
            "september"             => $september,
            "oktober"               => $oktober,
            "november"              => $november,
            "desember"              => $desember,
        );

        $updateData             = $this->m_data->update("sasaran_paud", $data, ["id_sasaran_paud" => $id_sasaran_paud]);
        if ($updateData == 1) {
            $this->session->set_flashdata("sukses", "Mengedit data $nama_anak pada database");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        $this->sasaran_paud();
    }
}
