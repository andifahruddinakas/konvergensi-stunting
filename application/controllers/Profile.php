<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $dataUser   = $this->m_data->getWhere("id_user", $this->session->userdata("login")->id_user);
        $dataUser   = $this->m_data->getData("user")->row();
        
        $data["user"]   = $dataUser;
        return $this->loadView('profile.show-profile', $data);
    }

    public function update_biodata(){
        $nama       = $this->input->post("nama");
        $username   = $this->input->post("username");
        $no_hp      = $this->input->post("no_hp");
        $alamat     = $this->input->post("alamat");

        $data       = [
            "nama_lengkap"  => $nama,
            "username"       => $username,
            "nomor_hp"      => $no_hp,
            "alamat"        => $alamat
        ];
        if(strlen($username) >= 5){
            $updateData = $this->m_data->update("user", $data, ["id_user" => $this->session->userdata("login")->id_user]);
            if ($updateData == 1) {
                $this->session->set_flashdata("sukses", "Mengedit data $nama pada database");
            } else {
                $this->session->set_flashdata("gagal", $this->m_data->getError());
            }
        } else {
            $this->session->set_flashdata("gagal", "Username Minimal 5 Karakter!");
        }
        
        redirect(base_url("profile"));
    }

    public function update_password(){
        $password_sekarang          = $this->input->post("password_sekarang");
        $password_baru              = $this->input->post("password_baru");
        $konfirmasi_password_baru   = $this->input->post("konfirmasi_password_baru");

        $cekPass    = $this->m_data->getWhere("id_user", $this->session->userdata("login")->id_user);
        $cekPass    = $this->m_data->getWhere("password", md5($password_sekarang));
        $cekPass    = $this->m_data->getData("user")->row();

        if($cekPass){
            if(strlen($password_baru) >= 8){
                if($password_baru == $konfirmasi_password_baru){
                    $updateData = $this->m_data->update("user", ["password" => md5($password_baru)], ["id_user" => $this->session->userdata("login")->id_user]);
                    if ($updateData == 1) {
                        $this->session->set_flashdata("sukses", "Ubah password pada database");
                    } else {
                        $this->session->set_flashdata("gagal", $this->m_data->getError());
                    }      
                } else {                
                    $this->session->set_flashdata("gagal", "Konfirmasi Password Baru Salah!");
                }
            } else {
                $this->session->set_flashdata("gagal", "Password minimal 8 Karakter!");
            }            
        } else {
            $this->session->set_flashdata("gagal", "Password lama yang anda masukan salah!");
        }
        redirect(base_url("profile"));
    }
}