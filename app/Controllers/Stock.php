<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Stock extends BaseController
{

    public function index()
    {
        $model = new TransaksiModel();

        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $search = $this->request->getGet('search');
        $jenis = $this->request->getGet('jenis');

        $builder = $model->select('transaksi.*, barang.kode as kode_barang, barang.nama as nama_barang')
                         ->join('barang','barang.id = transaksi.barang_id');

        // FILTER TANGGAL
        if($tanggal_awal && $tanggal_akhir){
            $builder->where('tanggal >=', $tanggal_awal);
            $builder->where('tanggal <=', $tanggal_akhir);
        }

        // SEARCH
        if($search){
            $builder->groupStart()
                    ->like('barang.nama', $search)
                    ->orLike('barang.kode', $search)
                    ->orLike('petugas', $search)
                    ->groupEnd();
        }

        // FILTER JENIS
        if($jenis){
            $builder->where('jenis', $jenis);
        }

        $logs = $builder->orderBy('tanggal','DESC')->findAll();

        $masuk = $model->totalMasuk();
        $keluar = $model->totalKeluar();

        $data = [
            'logs' => $logs,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'selisih' => $masuk - $keluar,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'search' => $search,
            'jenis' => $jenis
        ];

        return view('stock/index',$data);
    }


    public function export()
    {
        $model = new TransaksiModel();

        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $search = $this->request->getGet('search');
        $jenis = $this->request->getGet('jenis');

        $builder = $model->select('transaksi.*, barang.kode as kode_barang, barang.nama as nama_barang')
                         ->join('barang','barang.id = transaksi.barang_id');

        if($tanggal_awal && $tanggal_akhir){
            $builder->where('tanggal >=', $tanggal_awal);
            $builder->where('tanggal <=', $tanggal_akhir);
        }

        if($search){
            $builder->groupStart()
                    ->like('barang.nama', $search)
                    ->orLike('barang.kode', $search)
                    ->orLike('petugas', $search)
                    ->groupEnd();
        }

        if($jenis){
            $builder->where('jenis', $jenis);
        }

        $logs = $builder->orderBy('tanggal','DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1','Tanggal');
        $sheet->setCellValue('B1','Jenis');
        $sheet->setCellValue('C1','Kode Barang');
        $sheet->setCellValue('D1','Nama Barang');
        $sheet->setCellValue('E1','Jumlah');
        $sheet->setCellValue('F1','Stok Sebelum');
        $sheet->setCellValue('G1','Stok Sesudah');
        $sheet->setCellValue('H1','Petugas');

        $row = 2;

        foreach($logs as $log){
            $sheet->setCellValue('A'.$row,$log['tanggal']);
            $sheet->setCellValue('B'.$row,$log['jenis']);
            $sheet->setCellValue('C'.$row,$log['kode_barang']);
            $sheet->setCellValue('D'.$row,$log['nama_barang']);
            $sheet->setCellValue('E'.$row,$log['jumlah']);
            $sheet->setCellValue('F'.$row,$log['stok_sebelum']);
            $sheet->setCellValue('G'.$row,$log['stok_sesudah']);
            $sheet->setCellValue('H'.$row,$log['petugas']);
            $row++;
        }

        $filename = "riwayat_stok.xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

}