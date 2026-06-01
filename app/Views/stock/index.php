<!DOCTYPE html>
<html>
<head>
    <title>Stock Inventory </title>
    <link rel="stylesheet" href="<?= base_url('assets/css/stock.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
<h1>Riwayat Pergerakan Stok</h1>
<p>Histori lengkap transaksi barang masuk dan keluar</p>
</div>

<a href="/stock/export?tanggal_awal=<?= $tanggal_awal ?>&tanggal_akhir=<?= $tanggal_akhir ?>&search=<?= $search ?>" 
class="btn-export">
<i class="fa-solid fa-file-export"></i>
Export Excel
</a>

</div>

<div class="cards">

<div class="card masuk">
<div class="icon"><i class="fa-solid fa-circle-down"></i></div>
<h3>Total Barang Masuk</h3>
<h2><?= $masuk ?> unit</h2>
</div>

<div class="card keluar">
<div class="icon"><i class="fa-solid fa-circle-up"></i></div>
<h3>Total Barang Keluar</h3>
<h2><?= $keluar ?> unit</h2>
</div>

<div class="card selisih">
<div class="icon"><i class="fa-solid fa-filter"></i></div>
<h3>Selisih</h3>
<h2>+<?= $selisih ?> unit</h2>
</div>

</div>


<div class="table-card">

<h3>Daftar Log</h3>

<div class="filter-card">

<div class="filter-title">
<i class="fa-solid fa-filter"></i>
<span>Filter & Pencarian</span>
</div>

<form method="get" class="filter-grid">

<div class="form-group">
<label>Cari</label>
<input type="text" name="search" placeholder="Kode/Nama barang, Petugas..." value="<?= $search ?>">
</div>

<div class="form-group">
<label>Jenis Transaksi</label>
<select name="jenis">
<option value="">Semua</option>
<option value="masuk">Masuk</option>
<option value="keluar">Keluar</option>
</select>
</div>

<div class="form-group">
<label>Dari Tanggal</label>
<input type="date" name="tanggal_awal" value="<?= $tanggal_awal ?>">
</div>

<div class="form-group">
<label>Sampai Tanggal</label>
<input type="date" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">
</div>

<div class="filter-action">
<button type="submit" class="btn-filter">
<i class="fa-solid fa-magnifying-glass"></i> Terapkan
</button>

<a href="/stock" class="btn-reset">
Reset
</a>
</div>

</form>

</div>

<table>

<thead>
<tr>
<th>Tanggal</th>
<th>Jenis</th>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Jumlah</th>
<th>Stok Sebelum</th>
<th>Stok Sesudah</th>
<th>Petugas</th>
</tr>
</thead>

<tbody>

<?php foreach($logs as $row): ?>

<tr>

<td><?= date('d M Y',strtotime($row['tanggal'])) ?></td>

<td>
<span class="badge <?= $row['jenis'] ?>">
<?= ucfirst($row['jenis']) ?>
</span>
</td>

<td><?= $row['kode_barang'] ?></td>
<td><?= $row['nama_barang'] ?></td>

<td class="<?= $row['jenis'] ?>">
<?= $row['jenis']=='masuk'?'+':'' ?><?= $row['jumlah'] ?>
</td>

<td><?= $row['stok_sebelum'] ?></td>
<td><?= $row['stok_sesudah'] ?></td>
<td><?= $row['petugas'] ?></td>

</tr>

<?php endforeach ?>

</tbody>
</table>
</div>
</div>
</body>
</html>