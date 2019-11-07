<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        if ($bulanan_anak["dataGrup"] != NULL) {
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
        } else {
            $dataNoKia = [];
            $jumlahKekRisti = 0;
            $jumlahGiziBukanNormal = 0;
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
        $data['aktif']                  = "scorcard";
        return $this->loadView('scorcard-konvergensi.show-scorcard', $data);
    }

    public function export($kuartal = NULL, $tahun = NULL)
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
            redirect(base_url('scorcard-konvergensi-desa/export/') . $kuartal . '/' . $tahun);
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

        $jumlahKekRisti         = 0;
        $jumlahGiziBukanNormal  = 0;

        if ($bulanan_anak["dataGrup"] != NULL) {
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
            foreach ($ibu_hamil["dataFilter"] as $item) {
                if ($item["user"]["status_kehamilan"] != "NORMAL") {
                    $jumlahKekRisti++;
                }
            }
            
            foreach ($bulanan_anak["dataFilter"] as $item) {
                if ($item["umur_dan_gizi"]["status_gizi"] != "N") {
                    $jumlahGiziBukanNormal++;
                }
            }
        } else {
            $dataNoKia                  = [];
            $ibu_hamil["dataFilter"]    = [];
            $bulanan_anak["dataFilter"] = [];
        }            

        $inputFileType  = 'Xlsx';
        $inputFileName  = "assets/template/scorcard.xlsx";
        $reader         = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet    = $reader->load($inputFileName);
        $worksheet      = $spreadsheet->getActiveSheet();

        //SET DATA
        $worksheet->getCell('G6')->setValue(': ' . $tahun);
        $worksheet->getCell('G7')->setValue(': ' . get_kuartal($kuartal)["ke"] . " (" . get_kuartal($kuartal)["bulan"] . ")");                    

        $worksheet->getCell('D12')->setValue(sizeof($dataNoKia));
        $worksheet->getCell('F12')->setValue(sizeof($ibu_hamil["dataFilter"]));
        $worksheet->getCell('G12')->setValue($jumlahKekRisti);
        $worksheet->getCell('H12')->setValue(sizeof($bulanan_anak["dataFilter"]));
        $worksheet->getCell('I12')->setValue($jumlahGiziBukanNormal);

        $worksheet->getCell('D15')->setValue(sizeof($bulanan_anak["dataFilter"]));
        $worksheet->getCell('E15')->setValue($tikar["H"]);
        $worksheet->getCell('F15')->setValue($tikar["K"]);
        $worksheet->getCell('H15')->setValue($tikar["M"]);

        $worksheet->getCell('G18')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["periksa_kehamilan"]["Y"]);
        $worksheet->getCell('I18')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["periksa_kehamilan"]["persen"]);
        $worksheet->getCell('G19')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pil_fe"]["Y"]);
        $worksheet->getCell('I19')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pil_fe"]["persen"]);
        $worksheet->getCell('G20')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pemeriksaan_nifas"]["Y"]);
        $worksheet->getCell('I20')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pemeriksaan_nifas"]["persen"]);
        $worksheet->getCell('G21')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["konseling_gizi"]["Y"]);
        $worksheet->getCell('I21')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["konseling_gizi"]["persen"]);
        $worksheet->getCell('G22')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kunjungan_rumah"]["Y"]);
        $worksheet->getCell('I22')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kunjungan_rumah"]["persen"]);
        $worksheet->getCell('G23')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["akses_air_bersih"]["Y"]);
        $worksheet->getCell('I23')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["akses_air_bersih"]["persen"]);
        $worksheet->getCell('G24')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kepemilikan_jamban"]["Y"]);
        $worksheet->getCell('I24')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kepemilikan_jamban"]["persen"]);
        $worksheet->getCell('G25')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["jaminan_kesehatan"]["Y"]);
        $worksheet->getCell('I25')->setValue($ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["jaminan_kesehatan"]["persen"]);

        $worksheet->getCell('G26')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["imunisasi"]["Y"]);
        $worksheet->getCell('I26')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["imunisasi"]["persen"]);
        $worksheet->getCell('G27')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_berat_badan"]["Y"]);
        $worksheet->getCell('I27')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_berat_badan"]["persen"]);
        $worksheet->getCell('G28')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_tinggi_badan"]["Y"]);
        $worksheet->getCell('I28')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_tinggi_badan"]["persen"]);
        $worksheet->getCell('I29')->setValue("xx.xx");
        $worksheet->getCell('G30')->setValue("xx.xx");
        $worksheet->getCell('H30')->setValue("xx.xx");
        $worksheet->getCell('I30')->setValue("xx.xx");
        $worksheet->getCell('G31')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["kunjungan_rumah"]["Y"]);
        $worksheet->getCell('I31')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["kunjungan_rumah"]["persen"]);
        $worksheet->getCell('G32')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["air_bersih"]["Y"]);
        $worksheet->getCell('I32')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["air_bersih"]["persen"]);
        $worksheet->getCell('G33')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jamban_sehat"]["Y"]);
        $worksheet->getCell('I33')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jamban_sehat"]["persen"]);
        $worksheet->getCell('G34')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["akta_lahir"]["Y"]);
        $worksheet->getCell('I34')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["akta_lahir"]["persen"]);
        $worksheet->getCell('G35')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jaminan_kesehatan"]["Y"]);
        $worksheet->getCell('I35')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jaminan_kesehatan"]["persen"]);
        $worksheet->getCell('G36')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengasuhan_paud"]["Y"]);
        $worksheet->getCell('I36')->setValue($bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengasuhan_paud"]["persen"]);

        $worksheet->getCell('G37')->setValue("x.xx");
        $worksheet->getCell('I37')->setValue("x.xx");

        $JLD_IbuHamil   = $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["jumlah_diterima"];
        $JLD_Anak       = $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["jumlah_diterima"];

        $JYSD_IbuHamil  = $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["jumlah_seharusnya"];
        $JYSD_Anak      = $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["jumlah_seharusnya"];

        $PERSEN_IbuHamil = $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["persen"];
        $PERSEN_Anak     = $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["persen"];

        $JLD_TOTAL      = (int) $JLD_IbuHamil + (int) $JLD_Anak;
        $JYSD_TOTAL     = (int) $JYSD_IbuHamil + (int) $JYSD_Anak;

        if($JYSD_TOTAL){
            $KONV_TOTAL     = number_format($JLD_TOTAL / $JYSD_TOTAL * 100, 2);
        } else {
            $KONV_TOTAL     = number_format(0, 2);
        }        

        $worksheet->getCell('E41')->setValue($JLD_IbuHamil);
        $worksheet->getCell('F41')->setValue($JYSD_IbuHamil);
        $worksheet->getCell('H41')->setValue($PERSEN_IbuHamil);
        $worksheet->getCell('E42')->setValue($JLD_Anak);
        $worksheet->getCell('F42')->setValue($JYSD_Anak);
        $worksheet->getCell('H42')->setValue($PERSEN_Anak);
        $worksheet->getCell('E43')->setValue($JLD_TOTAL);
        $worksheet->getCell('F43')->setValue($JYSD_TOTAL);
        $worksheet->getCell('H43')->setValue($KONV_TOTAL);

        //SAVE AND DOWNLOAD
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'SCORCARD_KONVERGENSI_DESA_' . strtoupper($kuartal . "_" . $tahun . "_" . date("H_i_s"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
