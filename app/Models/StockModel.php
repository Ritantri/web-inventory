<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'transaksi';

    public function getLogs()
    {
        return $this->db->table('transaksi')
            ->select('
                transaksi.tanggal,
                transaksi.jenis,
                barang.kode as kode_barang,
                barang.nama as nama_barang,
                transaksi.jumlah,
                transaksi.stok_sebelum,
                transaksi.stok_sesudah,
                transaksi.petugas,
                transaksi.no_referensi as keterangan
            ')
            ->join('barang','barang.id = transaksi.barang_id')
            ->orderBy('transaksi.tanggal','DESC')
            ->get()
            ->getResultArray();
    }

    public function totalMasuk()
    {
        return $this->db->table('transaksi')
            ->selectSum('jumlah')
            ->where('jenis','masuk')
            ->get()
            ->getRowArray();
    }

    public function totalKeluar()
    {
        return $this->db->table('transaksi')
            ->selectSum('jumlah')
            ->where('jenis','keluar')
            ->get()
            ->getRowArray();
    }
}