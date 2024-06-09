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
            'buku'  => $this->bukuModel->getBuku()
        ];

        return view('books/index', $data);
    }

    public function detail($slug) {
        
        $data = [
            'title' => 'Detail Buku',
            'buku'  => $this->bukuModel->getBuku($slug)
        ];

        if (empty($data['buku'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Buku' . $slug . 'Tidak ditemukan');
        }

        return view('books/detail', $data);
    }

    public function create()
    {

        $data = [
            'title' => 'Form Tambah Buku',
            'validation' => session()->getFlashdata('validation') ?? \config\Services::validation()
        ];

        return view('books/create', $data);
    }

    public function save()
    {
        if (
            !$this->validate([
                'judul' => [
                    'rules' => 'required|is_unique[books.judul]',
                    'errors' => [
                        'required' => '{field} buku harus diisi',
                        'is_unique'=> '{field} buku sudah dimasukkan'
                    ]
                ],
                'penulis' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} buku harus diisi'
                    ]
                ],
                'penerbit' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} buku harus diisi',
                    ]
                ],
                'sampul' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} buku harus diisi',
                    ]
                ]

            ])
        ) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->to('/books/create')->withInput();
        }
        
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil ditambahkan');

        return redirect()->to('/books');
    }

    public function delete($id)
    {
        $this->bukuModel->delete($id);

        session()->setFlashdata('pesan', 'data berhasil dihapus');

        return redirect()->to('/books');
    }
    public function edit($slug)
    {
        //session();

        $data = [
            'title' => 'Form Edit Data Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'buku' => $this->bukuModel->getBuku($slug)
        ];

        return view('books/edit', $data);
    }

    public function update($id)
    {
        //fungsi cek judul buku yang ada
        $bukuLama = $this->bukuModel->getBuku($this->request->getVar('slug'));
        if ($bukuLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[books.judul]';
        }
        //validasi Input
        if (
            !$this->validate([
                'judul' => [
                    'rules' => 'required|is_unique[books.judul]',
                    'errors' => [
                        'required' => '{field} buku harus diisi',
                        'is_unique' => '{field} buku sudah dimasukkan'
                    ]
                ],
                'penulis' => [
                    'rules' => 'required|is_unique[books.penulis]',
                    'errors' => [
                        'required' => '{field} buku harus diisi',
                        'is_unique' => '{field} sudah dimasukkan'
                    ]
                ],
                'penerbit' => [
                    'rules' => 'required|is_unique[books.penerbit]',
                    'errors' => [
                        'required' => '{field} buku harus diisi',
                        'is_unique' => '{field} sudah dimasukkan'
                    ]
                ]
            ])
        ) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->to('/books/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')

        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah');

        return redirect()->to('/books');
    }
}