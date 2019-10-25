<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
        $ibuHamil = $this->m_data->order_by("ibu_hamil.created_at", "ASC");
        $ibuHamil = $this->m_data->getData("ibu_hamil")->result();

        $dataTahun = $this->m_data->select("YEAR(created_at) as tahun");
        $dataTahun = $this->m_data->distinct();
        $dataTahun = $this->m_data->getData("ibu_hamil")->result();

        $data["_bulan"]     = $bulan;
        $data["_tahun"]     = $tahun;
        $data['ibuHamil']   = $ibuHamil;
        $data['dataTahun']  = $dataTahun;
        $data['bulan']      = bulan($bulan);
        $data['title']      = "Pemantauan Bulanan Ibu Hamil";
        return $this->loadView('pemantauan.ibu-hamil', $data);
    }

    public function getDataByNoKia($no_kia)
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

    public function hapus_data()
    {
        $id_ibu_hamil   = $this->input->post('id_ibu_hamil'); 
        $hapus          = $this->m_data->delete(array("id_ibu_hamil" => $id_ibu_hamil),"ibu_hamil");
        if($hapus > 0){
            $this->session->set_flashdata("sukses", "Data berhasil di hapus dari database");
        } else {
            $this->session->set_flashdata("gagal", "Terjadi kesalahan saat menghapus data");
        }
        $this->ibu_hamil();
    }

    public function insertData()
    {
        $no_kia                 = $this->input->post('no_kia');
        $nama_ibu               = $this->input->post('nama_ibu');
        $status_kehamilan       = $this->input->post('status_kehamilan');
        $perkiraan_lahir        = $this->input->post('perkiraan_lahir');
        $usia_kehamilan         = $this->input->post('usia_kehamilan');
        $tanggal_melahirkan     = $this->input->post('tanggal_melahirkan') == "" ? NULL : $this->input->post('tanggal_melahirkan');
        $pemeriksaan_kehamilan  = $this->input->post('pemeriksaan_kehamilan');
        $pil_fe                 = $this->input->post('pil_fe');
        $pemeriksaan_nifas      = $this->input->post('pemeriksaan_nifas');
        $konseling_gizi         = $this->input->post('konseling_gizi');
        $kunjungan_rumah        = $this->input->post('kunjungan_rumah');
        $air_bersih             = $this->input->post('air_bersih');
        $kepemilikan_jamban     = $this->input->post('kepemilikan_jamban');
        $jaminan_kesehatan      = $this->input->post('jaminan_kesehatan');

        $data = array(
            "no_kia"                => $no_kia,
            "status_kehamilan"      => $status_kehamilan,
            "hari_perkiraan_lahir"  => $perkiraan_lahir,
            "usia_kehamilan"        => $usia_kehamilan,
            "tanggal_melahirkan"    => $tanggal_melahirkan,
            "pemeriksaan_kehamilan" => $pemeriksaan_kehamilan,
            "konsumsi_pil_fe"       => $pil_fe,
            "pemeriksaan_nifas"     => $pemeriksaan_nifas,
            "konseling_gizi"        => $konseling_gizi,
            "kunjungan_rumah"       => $kunjungan_rumah,
            "akses_air_bersih"      => $air_bersih,
            "kepemilikan_jamban"    => $kepemilikan_jamban,
            "jaminan_kesehatan"     => $jaminan_kesehatan
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
                    $this->m_data->update("kia", ["nama_ibu" => $nama_ibu, "updated_at" => date("Y-m-d H:i:s")], ["no_kia" => $no_kia]);
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
        //CEK DULU BULAN INI UDAH INPUT BELUM
        $cekInput = $this->m_data->getWhere("no_kia", $data["no_kia"]);
        $cekInput = $this->m_data->getWhere("MONTH(created_at)", date('m'));
        $cekInput = $this->m_data->getWhere("YEAR(created_at)", date('Y'));
        $cekInput = $this->m_data->getData("ibu_hamil")->num_rows();
        if ($cekInput > 0) {
            $this->session->set_flashdata("gagal", "Maaf data ibu pada bulan ini sudah diinputkan");
        } else {
            $insertIbuHamil = $this->m_data->insert("ibu_hamil", $data);
            if ($insertIbuHamil) {
                $this->session->set_flashdata("sukses", "Menyimpan data pada pemantauan bulanan ibu hamil");
            } else {
                $this->session->set_flashdata("gagal", $this->m_data->getError());
            }
        }
        $this->ibu_hamil();
    }

    public function edit_ibu_hamil(){
        $id_ibu_hamil           = $this->input->post('id_ibu_hamil');
        $nama_ibu               = $this->input->post('nama_ibu');
        $status_kehamilan       = $this->input->post('status_kehamilan');
        $perkiraan_lahir        = $this->input->post('perkiraan_lahir');
        $usia_kehamilan         = $this->input->post('usia_kehamilan');
        $tanggal_melahirkan     = $this->input->post('tanggal_melahirkan') == "" ? NULL : $this->input->post('tanggal_melahirkan');
        $pemeriksaan_kehamilan  = $this->input->post('pemeriksaan_kehamilan');
        $pil_fe                 = $this->input->post('pil_fe');
        $pemeriksaan_nifas      = $this->input->post('pemeriksaan_nifas');
        $konseling_gizi         = $this->input->post('konseling_gizi');
        $kunjungan_rumah        = $this->input->post('kunjungan_rumah');
        $air_bersih             = $this->input->post('air_bersih');
        $kepemilikan_jamban     = $this->input->post('kepemilikan_jamban');
        $jaminan_kesehatan      = $this->input->post('jaminan_kesehatan');

        $data = array(
            "status_kehamilan"      => $status_kehamilan,
            "hari_perkiraan_lahir"  => $perkiraan_lahir,
            "usia_kehamilan"        => $usia_kehamilan,
            "tanggal_melahirkan"    => $tanggal_melahirkan,
            "pemeriksaan_kehamilan" => $pemeriksaan_kehamilan,
            "konsumsi_pil_fe"       => $pil_fe,
            "pemeriksaan_nifas"     => $pemeriksaan_nifas,
            "konseling_gizi"        => $konseling_gizi,
            "kunjungan_rumah"       => $kunjungan_rumah,
            "akses_air_bersih"      => $air_bersih,
            "kepemilikan_jamban"    => $kepemilikan_jamban,
            "jaminan_kesehatan"     => $jaminan_kesehatan
        );

        $updateData             = $this->m_data->update("ibu_hamil", $data, ["id_ibu_hamil" => $id_ibu_hamil]);
        if($updateData == 1){
            $this->session->set_flashdata("sukses", "Mengedit data ibu $nama_ibu pada database");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        
        $this->ibu_hamil();
    }
}
