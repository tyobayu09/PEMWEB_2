<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\BooksModel;

class Books extends BaseController
{
    protected $bukuModel;
    public function __construct()
    {
        $this->bukuModel = new BooksModel();
    }
    public function index()
    {
        $buku = $this->bukuModel->findAll();
        $data = [
            'title' => 'Daftar Buku',
            'buku' => $buku
        ];

        //$bukuModel = new \App\Models\BooksModel();

        return view('books/index', $data);
    }
}
