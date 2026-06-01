<?php

namespace App\Models;
use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tanggal',
        'jenis',
        'barang_id',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'no_referensi',
        'petugas',
        'keterangan',
        'cost_center',
        'nama_request',
        'nama_atasan',
        'no_so_job_cost'
    ];

    public function getAllTransaksi()
    {
        return $this->select('transaksi.*, barang.kode, barang.nama')
                    ->join('barang', 'barang.id = transaksi.barang_id')
                    ->orderBy('tanggal', 'DESC')
                    ->findAll();
    }

    public function totalMasuk()
    {
        $result = $this->where('jenis','masuk')
                       ->selectSum('jumlah')
                       ->first();

        return $result['jumlah'] ?? 0;
    }

    public function totalKeluar()
    {
        $result = $this->where('jenis','keluar')
                       ->selectSum('jumlah')
                       ->first();

        return $result['jumlah'] ?? 0;
    }
}