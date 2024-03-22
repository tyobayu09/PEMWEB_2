<?php namespace App\Controllers;
class Page extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Selamat datang | Unipdu Press',
            'tes' => ['satu', 'dua'. 'tiga']
        ];
        echo view ('layout/header',$data);
        echo view ('pages/home');
        echo view ('layout/footer');
    }
    
    public function about()
    {
        $data = [
            'title' => 'Tentang kami | Unipdu Press',
            'tes' => ['satu', 'dua'. 'tiga']
        ];
        echo view ('layout/header',$data);
        echo view ('pages/about');
        echo view ('layout/footer');
    }

}