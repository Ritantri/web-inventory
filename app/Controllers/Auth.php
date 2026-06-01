<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{

    // MENAMPILKAN HALAMAN LOGIN
    public function login()
    {
        return view('login');
    }

    // PROSES LOGIN
    public function prosesLogin()
    {
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if($user){

            if($password == $user['password']){

                session()->set([
                    'login' => true,
                    'user_id' => $user['id'],
                    'username' => $user['username']
                ]);

                return redirect()->to('/dashboard');

            }else{
                echo "Password salah";
            }

        }else{
            echo "Username tidak ditemukan";
        }
    }

    // HALAMAN GANTI PASSWORD
    public function gantiPassword()
    {
        if(!session()->get('login')){
            return redirect()->to('/login');
        }

        return view('ganti_password');
    }

    // PROSES GANTI PASSWORD
    public function prosesGantiPassword()
    {
        $model = new UserModel();

        $user_id = session()->get('user_id');

        $password_lama = $this->request->getPost('password_lama');
        $password_baru = $this->request->getPost('password_baru');
        $konfirmasi = $this->request->getPost('konfirmasi');

        $user = $model->find($user_id);

        if($password_lama != $user['password']){
            echo "Password lama salah";
            return;
        }

        if($password_baru != $konfirmasi){
            echo "Konfirmasi password tidak sama";
            return;
        }

        $model->update($user_id,[
            'password' => $password_baru
        ]);

        echo "Password berhasil diganti";
    }

}