<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kode',
        'nama',
        'kategori',
        'merk',
        'stok',
        'stok_min',
        'harga_beli',
        'lokasi',
        'status'
    ];
    public function getStokMenipis()
{
    return $this->where('stok <= stok_minimum')
                ->findAll();
}

}