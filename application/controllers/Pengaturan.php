<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Pengaturan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(); 
        if($this->session->userdata("login")->level !== "super_admin"){
            redirect(base_url());
        }
    }

    public function index()
    {
        redirect(base_url());
    }

    public function posyandu(){    
        $posyandu   = $this->m_data->getData("posyandu")->result();

        $data["aktif"]      = "pengaturan";
        $data['posyandu']   = $posyandu;
        $data['title']      = "Pengaturan Posyandu";        
        return $this->loadView('pengaturan.posyandu', $data);
    }

    public function insertPosyandu(){
        $nama_posyandu      = $this->input->post('nama_posyandu');
        $alamat_posyandu    = $this->input->post('alamat_posyandu');

        $data = array(
            "nama_posyandu"        => $nama_posyandu,
            "alamat_posyandu"      => $alamat_posyandu
        );
        $insertIbuHamil = $this->m_data->insert("posyandu", $data);
        if ($insertIbuHamil) {
            $this->session->set_flashdata("sukses", "Menyimpan data pada pemantauan bulanan ibu hamil");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        $this->posyandu();
    }

    public function edit_posyandu(){
        $id_posyandu        = $this->input->post('id_posyandu');
        $nama_posyandu      = $this->input->post('nama_posyandu');
        $alamat_posyandu    = $this->input->post('alamat_posyandu');

        $data = array(
            "nama_posyandu"     => $nama_posyandu,
            "alamat_posyandu"   => $alamat_posyandu
        );

        $updateData = $this->m_data->update("posyandu", $data, ["id_posyandu" => $id_posyandu]);
        if ($updateData == 1) {
            $this->session->set_flashdata("sukses", "Mengedit data $nama_posyandu pada database");
        } else {
            $this->session->set_flashdata("gagal", $this->m_data->getError());
        }
        redirect(base_url("pengaturan/posyandu"));
    }

    public function hapus_posyandu(){
        $id_posyandu        = $this->input->post('id_posyandu');
        $hapus              = $this->m_data->delete(array("id_posyandu" => $id_posyandu), "posyandu");
        if ($hapus > 0) {
            $this->session->set_flashdata("sukses", "Data berhasil di hapus dari database");
        } else {
            $this->session->set_flashdata("gagal", "Gagal menghapus posyandu karena posyandu tersebut masih memiliki data yang lain");
        }
        redirect(base_url("pengaturan/posyandu"));
    }

    public function kpm(){
        $posyandu   = $this->m_data->getData("posyandu")->result();

        $kpm        = $this->m_data->select([
            "user.id_user as id_user",
            "user.id_posyandu as id_posyandu",
            "user.nama_lengkap as nama_lengkap",
            "user.username as username",
            "posyandu.nama_posyandu as nama_posyandu"
        ]);
        $kpm        = $this->m_data->getJoin("posyandu", "user.id_posyandu = posyandu.id_posyandu", "INNER");
        $kpm        = $this->m_data->getWhere("level", "admin");
        $kpm        = $this->m_data->getData("user")->result();

        // d($kpm);

        $data["aktif"]      = "pengaturan";
        $data['posyandu']   = $posyandu;
        $data['kpm']        = $kpm;
        $data['title']      = "Pengaturan Kader Pembangunan Manusia";        
        return $this->loadView('pengaturan.kpm', $data);
    }

    public function insertKpm(){
        $nama_kpm           = $this->input->post('nama_kpm');
        $posyandu           = $this->input->post('posyandu');
        $username_kpm       = $this->input->post('username_kpm');
        $pass_kpm           = $this->input->post('pass_kpm');
        $konfirmpass_kpm    = $this->input->post('konfirmpass_kpm');

        $data = array(
            "id_posyandu"       => $posyandu,
            "nama_lengkap"      => $nama_kpm,
            "username"          => $username_kpm,
            "password"          => md5($pass_kpm),
            "level"             => "admin"
        );

        if($pass_kpm == $konfirmpass_kpm){
            $insertKpm = $this->m_data->insert("user", $data);
            if ($insertKpm) {
                $this->session->set_flashdata("sukses", "Menyimpan data pada Kader Pembangunan Manusia");
            } else {
                $this->session->set_flashdata("gagal", "Username sudah terdaftar, silahkan gunakan username lainnya");
            }
        } else {
            $this->session->set_flashdata("gagal", "Konfirmasi Password salah!");
        }
            
        redirect(base_url("pengaturan/kpm"));
    }
}