<?php 
namespace App\Controllers;
class page extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Selamat datang | Unipdu Press',
            'tes' => ['Satu, dua, tiga']
        ];

        return view ('page/home', $data);
        
    }
    
    public function about()
    {
        $data= [
            'title' => 'Tentang kami | Unipdu Press',
        ];
        
        return view ('page/about', $data);
        
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact | Unipdu Press',
            'alamat' => [
                ['tipe' => 'Rumah', 'alamat' => 'Desa Peterongan no 28', 'kota' => 'Jombang'],
                ['tipe' => 'Kantor', 'alamat' => 'Kompleks Ponpes Darul Ulum Peterongan', 'kota' => 'Jombang']
            ]
        ];
        return view('page/contact', $data);
    }
}