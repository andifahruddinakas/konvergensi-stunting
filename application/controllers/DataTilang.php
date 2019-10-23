<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class DataTilang extends MY_Controller
{
    public function ___construct()
    {
        parent::___construct();
    }

    public function index()
    {
        return $this->loadView('data-tilang.show');
    }

    public function importTilang()
    {
        if (!empty($_FILES["file_excel"]["name"])) {
            $extension = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = new Csv();
            } else if ($extension == 'xlsx') {
                $reader = new Xlsx();
            } else if ($extension == 'xls') {
                $reader = new Xls();
            } else {
                echo json_encode("error slur");
                die;
            }

            $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $aktifSheet = $spreadsheet->getActiveSheet();
            // die(json_encode(is_numeric($spreadsheet->getActiveSheet()->getCell('A3298')->getValue())));

            // die(json_encode(count($allDataInSheet)));

            $data = array();
            $data_error = array();
            $sukses = 0;
            $gagal = 0;
            $awalData = 11;
            for ($i = $awalData; $i <= count($allDataInSheet); $i++) {
                if (
                    !is_numeric($aktifSheet->getCell('A' . $i)->getValue()) ||
                    $aktifSheet->getCell('A' . $i)->getValue() == "JUMLAH"
                ) {
                    break;
                } else {
                    // $tgl_penitipan = $aktifSheet->getCell('F' . $i)->getValue();
                    $tgl_penitipan = $allDataInSheet[$i]["F"];
                    $tgl_penitipan = str_replace('/', '-', $tgl_penitipan);
                    $tgl_penitipan = date("Y-m-d", strtotime($tgl_penitipan));

                    // $tgl_putusan = $aktifSheet->getCell('H' . $i)->getValue();
                    $tgl_putusan = $allDataInSheet[$i]["H"];
                    $tgl_putusan = str_replace('/', '-', $tgl_putusan);
                    $tgl_putusan = date("Y-m-d", strtotime($tgl_putusan));

                    $item = array(
                        "nama_terpidana" => $aktifSheet->getCell('B' . $i)->getValue(),
                        "no_reg_tilang" => $aktifSheet->getCell('C' . $i)->getValue(),
                        "alamat_terpidana" => $aktifSheet->getCell('D' . $i)->getValue(),
                        "nomor_briva" => $aktifSheet->getCell('E' . $i)->getValue(),
                        "tgl_penitipan" => $tgl_penitipan,
                        "jumlah_penitipan" => $aktifSheet->getCell('G' . $i)->getValue(),
                        "tgl_putusan" => $tgl_putusan,
                        "denda" => $aktifSheet->getCell('I' . $i)->getValue(),
                        "biaya_perkara" => $aktifSheet->getCell('J' . $i)->getValue(),
                        "sudah_diambil" => 0,
                        "posisi" => "kejaksaan"
                    );
                    $insert = $this->m_data->insert("daftar_terpidana", $item);
                    if ($insert) {
                        $sukses++;
                    } else {
                        $gagal++;
                        $itemGagal = array(
                            "no"    => $aktifSheet->getCell('A' . $i)->getValue(),
                            "no_reg_tilang" => $aktifSheet->getCell('C' . $i)->getValue(),
                            "alasan" => $this->m_data->getError()
                        );
                        array_push($data_error, $itemGagal);
                    }
                }
            }
            
            if ($gagal > 0){
                echo json_encode(array(
                    "sukses" => $sukses,
                    "gagal"  => $gagal,
                    "detail_gagal" => $data_error
                ));
            } else {
                echo json_encode(array(
                    "sukses" => $sukses,
                    "gagal"  => $gagal
                ));
            }
           
        } else {
            echo "AW";
        }
    }
}
