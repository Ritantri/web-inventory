<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;

class Transaksi extends BaseController
{
    protected $barang;
    protected $transaksi;

    public function __construct()
    {
        $this->barang = new BarangModel();
        $this->transaksi = new TransaksiModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('search');

        if ($keyword) {
            $transaksi = $this->transaksi
                ->select('transaksi.*, barang.kode, barang.nama')
                ->join('barang', 'barang.id = transaksi.barang_id')
                ->groupStart()
                ->like('barang.nama', $keyword)
                ->orLike('barang.kode', $keyword)
                ->orLike('no_referensi', $keyword)
                ->orLike('cost_center', $keyword)
                ->groupEnd()
                ->orderBy('tanggal', 'DESC')
                ->findAll();
        } else {
            $transaksi = $this->transaksi->getAllTransaksi();
        }

        $data = [
            'transaksi' => $transaksi,
            'barang' => $this->barang->findAll(),
            'totalTransaksi' => $this->transaksi->countAll(),
            'totalMasuk' => $this->transaksi->totalMasuk(),
            'totalKeluar' => $this->transaksi->totalKeluar(),
        ];

        return view('transaksi/index', $data);
    }

    public function masuk()
    {
        $barang_id = $this->request->getPost('barang_id');
        $barang = $this->barang->find($barang_id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $jumlah = (int)$this->request->getPost('jumlah');

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih dari 0');
        }

        $stokSebelum = $barang['stok'];
        $stokSesudah = $stokSebelum + $jumlah;

        $this->transaksi->save([
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis' => 'masuk',
            'barang_id' => $barang['id'],
            'jumlah' => $jumlah,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'no_referensi' => 'IN-' . time(),
            'petugas' => trim($this->request->getPost('petugas'))
        ]);

        $this->barang->update($barang['id'], [
            'stok' => $stokSesudah
        ]);

        return redirect()->to('/transaksi')
            ->with('success', 'Barang masuk berhasil ditambahkan');
    }

    public function keluar()
    {
        $barang_id = $this->request->getPost('barang_id');
        $barang = $this->barang->find($barang_id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $jumlah = (int)$this->request->getPost('jumlah');

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih dari 0');
        }

        $stokSebelum = $barang['stok'];

        if ($jumlah > $stokSebelum) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        $stokSesudah = $stokSebelum - $jumlah;

        $this->transaksi->save([
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis' => 'keluar',
            'barang_id' => $barang['id'],
            'jumlah' => $jumlah,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'no_referensi' => 'OUT-' . time(),
            'petugas' => trim($this->request->getPost('petugas')),
            'nama_request' => trim($this->request->getPost('nama_request')),
            'cost_center' => trim($this->request->getPost('cost_center')),
            'nama_atasan' => trim($this->request->getPost('nama_atasan')),
            'no_so_job_cost' => trim($this->request->getPost('no_so_job_cost')),
            'keterangan' => trim($this->request->getPost('keterangan')),
        ]);

        $this->barang->update($barang['id'], [
            'stok' => $stokSesudah
        ]);

        return redirect()->to('/transaksi')
            ->with('success', 'Barang keluar berhasil ditambahkan');
    }
}