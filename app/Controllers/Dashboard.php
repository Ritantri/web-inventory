<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends BaseController
{

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to('/login');
        }

        $barang = new BarangModel();
        $transaksi = new TransaksiModel();

        // =========================
        // FILTER PARAMETERS
        // =========================

        $tanggal     = $this->request->getGet('tanggal');
        $item        = $this->request->getGet('item');
        $cost_center = $this->request->getGet('cost_center');
        $search      = $this->request->getGet('search');

        // =========================
        // CARD STATISTICS
        // =========================

        $total_jenis = $barang->countAll();

        $total_stok = $barang
            ->selectSum('stok')
            ->first()['stok'] ?? 0;

        $stok_menipis = $barang
            ->where('stok <= stok_min')
            ->countAllResults();

        $data_stok_menipis = $barang
            ->where('stok <= stok_min')
            ->findAll();

        $total_kategori = $barang
            ->select('kategori')
            ->distinct()
            ->countAllResults();

        // =========================
        // GRAFIK 1: PERGERAKAN STOK (MASUK vs KELUAR)
        // =========================

        $labels = [];
        $stok_masuk_data = [];
        $stok_keluar_data = [];

        // Determine date range: if filter tanggal set, show 7 days around it, else last 7 days
        $baseDate = $tanggal ? $tanggal : date('Y-m-d');

        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days", strtotime($baseDate)));
            $labels[] = date('d M', strtotime($tgl));

            // Query Masuk
            $qMasuk = $transaksi
                ->select('SUM(transaksi.jumlah) as total')
                ->join('barang', 'barang.id = transaksi.barang_id', 'left')
                ->where('DATE(transaksi.tanggal)', $tgl)
                ->where('transaksi.jenis', 'masuk');

            if ($item) {
                $qMasuk->where('barang.nama', $item);
            }
            if ($search) {
                $qMasuk->groupStart()
                    ->like('barang.nama', $search)
                    ->orLike('barang.kategori', $search)
                    ->groupEnd();
            }

            $masuk = $qMasuk->first()['total'] ?? 0;
            $stok_masuk_data[] = (int)$masuk;

            // Query Keluar
            $qKeluar = $transaksi
                ->select('SUM(transaksi.jumlah) as total')
                ->join('barang', 'barang.id = transaksi.barang_id', 'left')
                ->where('DATE(transaksi.tanggal)', $tgl)
                ->where('transaksi.jenis', 'keluar');

            if ($item) {
                $qKeluar->where('barang.nama', $item);
            }
            if ($cost_center) {
                $qKeluar->where('transaksi.cost_center', $cost_center);
            }
            if ($search) {
                $qKeluar->groupStart()
                    ->like('barang.nama', $search)
                    ->orLike('barang.kategori', $search)
                    ->orLike('transaksi.cost_center', $search)
                    ->groupEnd();
            }

            $keluar = $qKeluar->first()['total'] ?? 0;
            $stok_keluar_data[] = (int)$keluar;
        }

        // =========================
        // GRAFIK 2: JUMLAH BARANG PER KATEGORI
        // =========================

        $kategoriQuery = $barang
            ->select('kategori, COUNT(*) as jumlah_item, SUM(stok) as total_stok')
            ->groupBy('kategori');

        if ($item) {
            $kategoriQuery->where('nama', $item);
        }
        if ($search) {
            $kategoriQuery->groupStart()
                ->like('nama', $search)
                ->orLike('kategori', $search)
                ->groupEnd();
        }

        $kategori = $kategoriQuery->findAll();

        $kategori_labels = [];
        $kategori_jumlah = [];

        foreach ($kategori as $k) {
            $kategori_labels[] = $k['kategori'] ?? 'Tanpa Kategori';
            $kategori_jumlah[] = (int)$k['jumlah_item'];
        }

        // =========================
        // GRAFIK 3: BARANG KELUAR (per barang + cost center)
        // =========================

        $barangKeluarQuery = $transaksi
            ->select('
                barang.nama as nama_barang,
                transaksi.cost_center,
                SUM(transaksi.jumlah) as total_keluar
            ')
            ->join('barang', 'barang.id = transaksi.barang_id')
            ->where('transaksi.jenis', 'keluar');

        if ($tanggal) {
            $barangKeluarQuery->where('DATE(transaksi.tanggal)', $tanggal);
        }

        if ($item) {
            $barangKeluarQuery->where('barang.nama', $item);
        }

        if ($cost_center) {
            $barangKeluarQuery->where('transaksi.cost_center', $cost_center);
        }

        if ($search) {
            $barangKeluarQuery->groupStart()
                ->like('barang.nama', $search)
                ->orLike('barang.kategori', $search)
                ->orLike('transaksi.cost_center', $search)
                ->groupEnd();
        }

        $barangKeluar = $barangKeluarQuery
            ->groupBy('barang.nama, transaksi.cost_center')
            ->orderBy('total_keluar', 'DESC')
            ->findAll();

        $barang_keluar_labels = [];
        $barang_keluar_totals = [];
        $barang_keluar_colors = [];

        // Generate color palette for bars
        $colorPalette = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
            '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1',
            '#14b8a6', '#e11d48', '#0ea5e9', '#a855f7', '#22c55e'
        ];

        $idx = 0;
        foreach ($barangKeluar as $bk) {
            $label = $bk['nama_barang'] . ' (' . ($bk['cost_center'] ?? 'Tanpa CC') . ')';
            $barang_keluar_labels[] = $label;
            $barang_keluar_totals[] = (int)$bk['total_keluar'];
            $barang_keluar_colors[] = $colorPalette[$idx % count($colorPalette)];
            $idx++;
        }

        // =========================
        // DROPDOWN FILTER DATA
        // =========================

        $list_barang = $barang->findAll();

        $list_cost_center = $transaksi
            ->select('cost_center')
            ->where('cost_center IS NOT NULL')
            ->where('cost_center !=', '')
            ->groupBy('cost_center')
            ->findAll();

        // =========================
        // PASS DATA TO VIEW
        // =========================

        $data = [
            'total_jenis'        => $total_jenis,
            'total_stok'         => $total_stok,
            'stok_menipis'       => $stok_menipis,
            'data_stok_menipis'  => $data_stok_menipis,
            'total_kategori'     => $total_kategori,

            // Grafik 1 - Pergerakan stok
            'labels_7hari'       => json_encode($labels),
            'stok_masuk'         => json_encode($stok_masuk_data),
            'stok_keluar'        => json_encode($stok_keluar_data),

            // Grafik 2 - Jumlah barang per kategori
            'kategori_labels'    => json_encode($kategori_labels),
            'kategori_jumlah'    => json_encode($kategori_jumlah),

            // Grafik 3 - Barang keluar
            'barang_keluar_label'  => json_encode($barang_keluar_labels),
            'barang_keluar_total'  => json_encode($barang_keluar_totals),
            'barang_keluar_colors' => json_encode($barang_keluar_colors),

            // Filter dropdowns
            'list_barang'        => $list_barang,
            'list_cost_center'   => $list_cost_center,

            // Preserve filter values
            'filter_tanggal'     => $tanggal,
            'filter_item'        => $item,
            'filter_cost_center' => $cost_center,
            'filter_search'      => $search,
        ];

        return view('dashboard/index', $data);
    }


    // DOWNLOAD EXCEL STOK MENIPIS
    public function downloadStokMenipis()
    {
        if (!session()->get('login')) {
            return redirect()->to('/login');
        }

        $barang = new BarangModel();

        $data = $barang
            ->where('stok <= stok_min')
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Kode');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Kategori');
        $sheet->setCellValue('D1', 'Stok');
        $sheet->setCellValue('E1', 'Stok Minimum');

        $row = 2;

        foreach ($data as $item) {
            $sheet->setCellValue('A'.$row, $item['kode']);
            $sheet->setCellValue('B'.$row, $item['nama']);
            $sheet->setCellValue('C'.$row, $item['kategori']);
            $sheet->setCellValue('D'.$row, $item['stok']);
            $sheet->setCellValue('E'.$row, $item['stok_min']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $filename = 'stok-menipis.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}