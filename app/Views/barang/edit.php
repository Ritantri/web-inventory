<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Barang - TRAKINDO Inventory</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
}

body {
    background: #f0f2f5;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(26,26,46,0.6);
    backdrop-filter: blur(8px);
    z-index: 1;
}

.modal-content {
    background: #fff;
    width: 100%;
    max-width: 780px;
    border-radius: 24px;
    padding: 40px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    z-index: 2;
    box-shadow: 0 25px 80px rgba(0,0,0,0.15);
    animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.97);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.close {
    position: absolute;
    right: 24px;
    top: 24px;
    font-size: 18px;
    cursor: pointer;
    color: #aaa;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: all 0.3s ease;
    text-decoration: none;
    background: #f5f5f5;
}

.close:hover {
    background: #eee;
    color: #333;
}

.modal-content h2 {
    margin-bottom: 28px;
    font-size: 24px;
    font-weight: 700;
    color: #1a1a2e;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.modal-content h2 i {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    padding: 10px;
    border-radius: 12px;
    font-size: 18px;
}

.form-section-label {
    font-size: 13px;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section-label i {
    color: #ffd000;
}

.form-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
    margin: 24px 0;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 13px;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-group input,
.form-group select {
    padding: 12px 16px;
    border-radius: 12px;
    border: 1.5px solid #e0e0e0;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    background: #fafafa;
    color: #333;
    transition: all 0.3s ease;
    outline: none;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #ffd000;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(255,208,0,0.15);
}

.full {
    grid-column: span 2;
}

.form-actions {
    margin-top: 28px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 24px;
    border-top: 2px solid #f0f0f0;
}

.btn {
    padding: 12px 28px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    box-shadow: 0 4px 15px rgba(59,130,246,0.3);
}

.btn-primary:hover {
    box-shadow: 0 8px 25px rgba(59,130,246,0.4);
}

.btn-secondary {
    background: #f0f0f0;
    text-decoration: none;
    color: #555;
}

.btn-secondary:hover {
    background: #e0e0e0;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .full {
        grid-column: span 1;
    }
    .modal-content {
        padding: 24px;
    }
}
</style>
</head>

<body>

<div class="modal-backdrop"></div>

<div class="modal-content">

<a href="/barang" class="close"><i class="fa-solid fa-xmark"></i></a>

<h2><i class="fa-solid fa-pen-to-square"></i> Edit Barang</h2>

<form action="/barang/update/<?= $barang['id'] ?>" method="post">

<div class="form-section-label">
    <i class="fa-solid fa-barcode"></i> Identitas Barang
</div>

<div class="form-grid">

<div class="form-group">
    <label>Kode Barang</label>
    <input type="text" name="kode" value="<?= esc($barang['kode']) ?>" required>
</div>

<div class="form-group">
    <label>Nama Barang</label>
    <input type="text" name="nama" value="<?= esc($barang['nama']) ?>" required>
</div>

<div class="form-group">
    <label>Kategori</label>
    <input type="text" name="kategori" value="<?= esc($barang['kategori']) ?>">
</div>

<div class="form-group">
    <label>Merk</label>
    <input type="text" name="merk" value="<?= esc($barang['merk']) ?>">
</div>

</div>

<div class="form-divider"></div>

<div class="form-section-label">
    <i class="fa-solid fa-warehouse"></i> Informasi Stok
</div>

<div class="form-grid">

<div class="form-group">
    <label>Stok</label>
    <input type="number" name="stok" value="<?= $barang['stok'] ?>">
</div>

<div class="form-group">
    <label>Stok Minimum</label>
    <input type="number" name="stok_min" value="<?= $barang['stok_min'] ?>">
</div>

<div class="form-group">
    <label>Harga Beli (Rp)</label>
    <input type="number" name="harga_beli" value="<?= $barang['harga_beli'] ?>">
</div>

<div class="form-group">
    <label>Lokasi</label>
    <input type="text" name="lokasi" value="<?= esc($barang['lokasi']) ?>">
</div>

<div class="form-group full">
    <label>Status</label>
    <select name="status">
        <option value="Aktif" <?= $barang['status'] == 'Aktif' ? 'selected' : '' ?>>✅ Aktif</option>
        <option value="Nonaktif" <?= $barang['status'] == 'Nonaktif' ? 'selected' : '' ?>>⛔ Nonaktif</option>
    </select>
</div>

</div>

<div class="form-actions">

<a href="/barang" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

<button type="submit" class="btn btn-primary">
    <i class="fa-solid fa-floppy-disk"></i> Update Barang
</button>

</div>

</form>

</div>

</body>
</html>