<?php

namespace App\Controllers;

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
        $data = [
            'title' => 'Daftar Buku',
            'buku' => $this->bukuModel->getBuku()
        ];

        return view('books/index', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Buku',
            'buku' => $this->bukuModel->getBuku($slug)
        ];

        if (empty($data['buku'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Buku ' . $slug . ' tidak ditemukan');
        }

        return view('books/detail', $data);
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Edit Data Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'buku' => $this->bukuModel->getBuku($slug)
        ];

        return view('books/edit', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Tambah Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
        ];

        return view('books/create', $data);
    }

    public function update($id)
    {
        $bukuLama = $this->bukuModel->getBuku($this->request->getVar('slug'));
        if ($bukuLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[books.judul]';
        }

        //validasi
        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} buku harus di isi',
                    'is_unique' => '{field} buku sudah terdaftar'
                ]
            ],
            'penulis' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} buku harus di isi',
                    'is_unique' => '{field} buku sudah terdaftar'
                ]
            ],
            'penerbit' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} buku harus di isi',
                    'is_unique' => '{field} buku sudah terdaftar'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,2048]|mime_in[sampul,image/png,image/jpeg]|is_image[sampul]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar (maksimal 2MB)',
                    'mime_in' => 'Ekstensi gambar tidak valid (jpg, jpeg, png)',
                    'is_image' => 'Yang Anda unggah bukan gambar'
                ]
            ]
        ])) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->to('/books/edit/' . $this->request->getVar('slug'))->withInput();
        }


        $fileSampul = $this->request->getFile('sampul');

        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            $namaSampul = $fileSampul->getRandomName();
            $fileSampul->move('img', $namaSampul);
            if ($this->request->getVar('sampulLama') != 'default.png') {
                unlink('img/' . $this->request->getVar('sampulLama'));
            }
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah');

        return redirect()->to('/books');
    }

    public function delete($id)
    {
        $this->bukuModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');

        return redirect()->to('/books');
    }

    public function save()
    {
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[books.judul]',
                'errors' => [
                    'required' => '{field} buku harus di isi',
                    'is_unique' => '{field} buku sudah terdaftar'
                ]
            ],
            'penulis' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} buku harus di isi'
                ]
            ],
            'penerbit' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} buku harus di isi'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,2048]|mime_in[sampul,image/png,image/jpeg]|is_image[sampul]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar (maksimal 2MB)',
                    'mime_in' => 'Ekstensi gambar tidak valid (jpg, jpeg, png)',
                    'is_image' => 'Yang Anda unggah bukan gambar'
                ]
            ]
        ])) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->to('/books/create')->withInput();


            $fileSampul = $this->request->getFile('sampul');
            $namaSampul = $fileSampul->getRandomName();
            $fileSampul->move('img', $namaSampul);

            $slug = url_title($this->request->getVar('judul'), '-', true);
            $this->bukuModel->save([
                'judul' => $this->request->getVar('judul'),
                'slug' => $slug,
                'penulis' => $this->request->getVar('penulis'),
                'penerbit' => $this->request->getVar('penerbit'),
                'sampul' => $namaSampul
            ]);

            session()->setFlashdata('pesan', 'Data berhasil ditambahkan');

            return redirect()->to('/books');
        }
    }
}
