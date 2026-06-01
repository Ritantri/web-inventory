<!DOCTYPE html>
<html>
<head>
    <title>Transaksi </title>
    <link rel="stylesheet" href="<?= base_url('assets/css/barang.css') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<div class="topbar">
    <div class="logo">
        <i class="fa-solid fa-box"></i>
        <div>
            <h1>TRAKINDO Inventory </h1>
            <span>Sistem Pengelolaan Inventory</span>
        </div>
    </div>

    <div class="top-right">
        <i class="fa-solid fa-box"></i> Trakindo 
    </div>
</div>

<!-- NAVIGATION -->
<div class="nav">
    <a href="<?= base_url('/dashboard') ?>" class="<?= uri_string() == 'dashboard' ? 'active' : '' ?>">
        <i class="fa-solid fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?= base_url('/barang') ?>" class="<?= uri_string() == 'barang' ? 'active' : '' ?>">
        <i class="fa-solid fa-cube"></i> Data Barang
    </a>
    <a href="<?= base_url('/transaksi') ?>" class="<?= uri_string() == 'transaksi' ? 'active' : '' ?>">
        <i class="fa-solid fa-arrow-right-arrow-left"></i> Transaksi
    </a>
    <a href="<?= base_url('/stock') ?>" class="<?= uri_string() == 'stock' ? 'active' : '' ?>">
        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
    </a>
</div>

<div class="container">

    <div class="header">
        <div>
            <h1>Manajemen Data Barang</h1>
            <p>Kelola master data barang inventory</p>
        </div>
        <a href="/barang/create" class="btn-add">+ Tambah Barang</a>
    </div>

    <div class="card">
        <h3>Daftar Barang (<?= count($barang) ?>)</h3>

        <table>
            <thead>
                <tr>
                    <th>SAP Material ID</th>
                    <th>Material </th>
                    <th>Category</th>
                    <th>Merk</th>
                    <th>Stok</th>
                    <th>Min</th>
                    <th>Harga Satuan</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($barang as $b): ?>
                <tr>
                    <td><?= $b['kode'] ?></td>
                    <td><?= $b['nama'] ?></td>
                    <td><?= $b['kategori'] ?></td>
                    <td><?= $b['merk'] ?></td>
                    <td class="<?= $b['stok'] <= $b['stok_min'] ? 'danger' : '' ?>">
                        <?= $b['stok'] ?>
                    </td>
                    <td><?= $b['stok_min'] ?></td>
                    <td>Rp <?= number_format($b['harga_beli'],0,',','.') ?></td>
                    <td><?= $b['lokasi'] ?></td>
                    <td>
                        <span class="badge"><?= $b['status'] ?></span>
                    </td>
                    <td>
                        <a href="/barang/edit/<?= $b['id'] ?>" class="edit">Edit</a>
                        <a href="/barang/delete/<?= $b['id'] ?>" 
   class="delete" 
   onclick="return confirm('Yakin ingin menghapus data ini?')">
   Hapus
</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>