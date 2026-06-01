<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barang;

    public function __construct()
    {
        $this->barang = new BarangModel();
    }

    public function index()
    {
        $data['barang'] = $this->barang->findAll();
        return view('barang/index', $data);
    }

    public function create()
    {
        return view('barang/create');
    }

    public function store()
    {
        $this->barang->save([
            'kode' => $this->request->getPost('kode'),
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'merk' => $this->request->getPost('merk'),
            'stok' => $this->request->getPost('stok'),
            'stok_min' => $this->request->getPost('stok_min'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'lokasi' => $this->request->getPost('lokasi'),
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/barang');
    }

    public function edit($id)
    {
        $data['barang'] = $this->barang->find($id);
        return view('barang/edit', $data);
    }

    public function update($id)
    {
        $this->barang->update($id, $this->request->getPost());
        return redirect()->to('/barang');
    }

    public function delete($id)
    {
        $this->barang->delete($id);
        return redirect()->to('/barang');
    }
}