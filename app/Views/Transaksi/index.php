<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Inventory - TRAKINDO</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/transaksi.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Tom Select for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Tom Select Custom Theme */
        .ts-wrapper .ts-control {
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #fafafa;
            color: #333;
            min-height: 46px;
            transition: all 0.3s ease;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #ffd000;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255,208,0,0.15);
        }
        .ts-wrapper .ts-dropdown {
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            margin-top: 4px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        .ts-wrapper .ts-dropdown .ts-dropdown-content {
            max-height: 240px;
            padding: 4px;
        }
        .ts-wrapper .ts-dropdown .option {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            transition: all 0.15s ease;
        }
        .ts-wrapper .ts-dropdown .option:hover,
        .ts-wrapper .ts-dropdown .active {
            background: linear-gradient(135deg, #fff8e1, #fffde7);
            color: #1a1a2e;
        }
        .ts-wrapper .ts-dropdown .option.active {
            background: linear-gradient(135deg, #ffd000, #ffaa00);
            color: #0a0a0a;
            font-weight: 600;
        }
        .ts-wrapper .ts-control > input {
            font-size: 14px;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: #333;
        }
        .ts-wrapper .ts-control > input::placeholder {
            color: #aaa;
        }
        /* No results message */
        .ts-wrapper .ts-dropdown .no-results {
            padding: 14px;
            text-align: center;
            color: #aaa;
            font-size: 13px;
        }
    </style>
</head>
<body>

<!-- ================= TOPBAR ================= -->
<div class="topbar">
    <div class="logo">
        <i class="fa-solid fa-boxes-stacked"></i>
        <div>
            <h1>TRAKINDO Inventory</h1>
            <span>Sistem Pengelolaan Inventory</span>
        </div>
    </div>

    <div class="top-right">
        <i class="fa-solid fa-user-circle"></i> Trakindo
    </div>
</div>
<!-- update -->
<!-- ================= NAV ================= -->
<div class="nav">
    <a href="<?= base_url('/dashboard') ?>">
        <i class="fa-solid fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?= base_url('/barang') ?>">
        <i class="fa-solid fa-cube"></i> Data Barang
    </a>
    <a href="<?= base_url('/transaksi') ?>" class="active">
        <i class="fa-solid fa-arrow-right-arrow-left"></i> Transaksi
    </a>
    <a href="<?= base_url('/stock') ?>">
        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
    </a>
</div>

<div class="wrapper">

    <div class="top-section">
        <div>
            <h1>Transaksi Stok Barang</h1>
            <p>Catat barang masuk dan keluar dengan mudah</p>
        </div>

        <div class="btn-group">
            <button class="btn btn-success" onclick="openModalMasuk()">
                <i class="fa-solid fa-circle-down"></i> Barang Masuk
            </button>
            <button class="btn btn-danger" onclick="openModalKeluar()">
                <i class="fa-solid fa-circle-up"></i> Barang Keluar
            </button>
        </div>
    </div>

    <div class="table-section">
        <h3><i class="fa-solid fa-clock-rotate-left" style="color:#ffd000"></i> Riwayat Transaksi</h3>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Stok Sebelum</th>
                        <th>Stok Sesudah</th>
                        <th>No Ref</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if(empty($transaksi)): ?>
                    <tr>
                        <td colspan="10" class="empty">Belum ada transaksi tercatat</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($transaksi as $t): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                        <td>
                            <span style="
                                background: <?= $t['jenis'] == 'masuk' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' ?>;
                                color: <?= $t['jenis'] == 'masuk' ? '#10b981' : '#ef4444' ?>;
                                padding: 4px 12px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: 600;
                            "><?= ucfirst($t['jenis']) ?></span>
                        </td>
                        <td><code style="background:#f5f5f5;padding:2px 8px;border-radius:6px;font-size:13px"><?= esc($t['kode']) ?></code></td>
                        <td><?= esc($t['nama']) ?></td>
                        <td style="font-weight:600;color:<?= $t['jenis'] == 'masuk' ? '#10b981' : '#ef4444' ?>">
                            <?= $t['jenis'] == 'masuk' ? '+' : '-' ?><?= $t['jumlah'] ?>
                        </td>
                        <td><?= $t['stok_sebelum'] ?></td>
                        <td><?= $t['stok_sesudah'] ?></td>
                        <td><code style="background:#f5f5f5;padding:2px 8px;border-radius:6px;font-size:13px"><?= esc($t['no_referensi']) ?></code></td>
                        <td><?= esc($t['petugas']) ?></td>

                        <td>
                            <button class="btn-detail"
                            onclick="showDetail(
                            '<?= esc($t['jenis']) ?>',
                            '<?= esc($t['nama']) ?>',
                            '<?= esc($t['jumlah']) ?>',
                            '<?= esc($t['no_referensi']) ?>',
                            '<?= esc($t['petugas']) ?>',
                            '<?= esc($t['nama_request'] ?? '-') ?>',
                            '<?= esc($t['cost_center'] ?? '-') ?>',
                            '<?= esc($t['nama_atasan'] ?? '-') ?>',
                            '<?= esc($t['no_so_job_cost'] ?? '-') ?>',
                            '<?= esc($t['keterangan'] ?? '-') ?>'
                            )">
                            <i class="fa-solid fa-eye"></i> Detail
                            </button>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL BARANG MASUK ================= -->
<div id="overlayMasuk" class="overlay" onclick="closeModalMasuk()"></div>

<div id="modalMasuk" class="modal">
<div class="modal-content">

<h2><i class="fa-solid fa-circle-down" style="color:#10b981"></i> Barang Masuk</h2>

<form action="<?= base_url('transaksi/masuk') ?>" method="post">

<div class="form-section-label">
    <i class="fa-solid fa-file-lines"></i> Informasi Referensi
</div>

<div class="form-row">
    <div class="form-group">
        <label>No Referensi</label>
        <input type="text" name="no_referensi" value="IN-<?= rand(100000,999999) ?>" readonly
               style="background:#f0f0f0;color:#888">
    </div>
    <div class="form-group">
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>">
    </div>
</div>

<div class="form-divider"></div>

<div class="form-section-label">
    <i class="fa-solid fa-cube"></i> Detail Barang
</div>

<div class="form-group">
    <label>Pilih Barang *</label>
    <select id="selectMasuk" name="barang_id" required placeholder="Ketik untuk mencari barang...">
        <option value="">— Ketik nama / kode barang —</option>
        <?php foreach($barang as $b): ?>
        <option value="<?= $b['id'] ?>">
            <?= esc($b['kode']) ?> — <?= esc($b['nama']) ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-row">
    <div class="form-group">
        <label>Jumlah *</label>
        <input type="number" name="jumlah" placeholder="Masukkan jumlah" min="1" required>
    </div>
    <div class="form-group">
        <label>Petugas</label>
        <input type="text" name="petugas" value="Admin Gudang">
    </div>
</div>

<div class="modal-actions">
    <button type="submit"><i class="fa-solid fa-check"></i> Simpan</button>
    <button type="button" onclick="closeModalMasuk()"><i class="fa-solid fa-xmark"></i> Batal</button>
</div>

</form>
</div>
</div>

<!-- ================= MODAL BARANG KELUAR ================= -->
<div id="overlayKeluar" class="overlay" onclick="closeModalKeluar()"></div>

<div id="modalKeluar" class="modal">
<div class="modal-content">

<h2><i class="fa-solid fa-circle-up" style="color:#ef4444"></i> Barang Keluar</h2>

<form action="<?= base_url('transaksi/keluar') ?>" method="post">

<div class="form-section-label">
    <i class="fa-solid fa-file-lines"></i> Informasi Referensi
</div>

<div class="form-row">
    <div class="form-group">
        <label>No Referensi</label>
        <input type="text" name="no_referensi" value="OUT-<?= rand(100000,999999) ?>" readonly
               style="background:#f0f0f0;color:#888">
    </div>
    <div class="form-group">
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>">
    </div>
</div>

<div class="form-divider"></div>

<div class="form-section-label">
    <i class="fa-solid fa-cube"></i> Detail Barang
</div>

<div class="form-group">
    <label>Pilih Barang *</label>
    <select id="selectKeluar" name="barang_id" required placeholder="Ketik untuk mencari barang...">
        <option value="">— Ketik nama / kode barang —</option>
        <?php foreach($barang as $b): ?>
        <option value="<?= $b['id'] ?>">
            <?= esc($b['kode']) ?> — <?= esc($b['nama']) ?> (Stok: <?= $b['stok'] ?>)
        </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-row">
    <div class="form-group">
        <label>Jumlah *</label>
        <input type="number" name="jumlah" placeholder="Masukkan jumlah" min="1" required>
    </div>
    <div class="form-group">
        <label>Petugas</label>
        <input type="text" name="petugas" value="Admin Gudang">
    </div>
</div>

<div class="form-divider"></div>

<div class="form-section-label">
    <i class="fa-solid fa-building"></i> Informasi Penerima
</div>

<div class="form-row">
    <div class="form-group">
        <label>Nama Request *</label>
        <input type="text" name="nama_request" placeholder="Nama yang meminta" required>
    </div>
    <div class="form-group">
        <label>Cost Center *</label>
        <input type="text" name="cost_center" placeholder="Kode cost center" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label>Nama Atasan *</label>
        <input type="text" name="nama_atasan" placeholder="Nama atasan" required>
    </div>
    <div class="form-group">
        <label>No SO / Job Cost *</label>
        <input type="text" name="no_so_job_cost" placeholder="Nomor SO / Job" required>
    </div>
</div>

<div class="form-group">
    <label>Keterangan</label>
    <textarea name="keterangan" placeholder="Tambahkan keterangan (opsional)"></textarea>
</div>

<div class="modal-actions">
    <button type="submit"><i class="fa-solid fa-check"></i> Simpan</button>
    <button type="button" onclick="closeModalKeluar()"><i class="fa-solid fa-xmark"></i> Batal</button>
</div>

</form>
</div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div id="overlayDetail" class="overlay" onclick="closeDetail()"></div>

<div id="modalDetail" class="modal">
<div class="modal-content">

<h2><i class="fa-solid fa-file-circle-info" style="color:#3b82f6"></i> Detail Transaksi</h2>

<div class="form-section-label">
    <i class="fa-solid fa-info-circle"></i> Informasi Umum
</div>

<div class="detail-row">
    <span class="detail-label">Jenis Transaksi</span>
    <span class="detail-value" id="d_jenis"></span>
</div>
<div class="detail-row">
    <span class="detail-label">Nama Barang</span>
    <span class="detail-value" id="d_nama"></span>
</div>
<div class="detail-row">
    <span class="detail-label">Jumlah</span>
    <span class="detail-value" id="d_jumlah"></span>
</div>
<div class="detail-row">
    <span class="detail-label">No Referensi</span>
    <span class="detail-value" id="d_ref"></span>
</div>
<div class="detail-row">
    <span class="detail-label">Petugas</span>
    <span class="detail-value" id="d_petugas"></span>
</div>

<div class="form-divider"></div>

<div class="form-section-label">
    <i class="fa-solid fa-building"></i> Informasi Penerima
</div>

<div class="detail-row">
    <span class="detail-label">Nama Request</span>
    <span class="detail-value" id="d_request"></span>
</div>
<div class="detail-row">
    <span class="detail-label">Cost Center</span>
    <span class="detail-value" id="d_cost"></span>
</div>
<div class="detail-row">
    <span class="detail-label">Nama Atasan</span>
    <span class="detail-value" id="d_atasan"></span>
</div>
<div class="detail-row">
    <span class="detail-label">No SO / Job</span>
    <span class="detail-value" id="d_so"></span>
</div>

<div class="form-divider"></div>

<div class="detail-row">
    <span class="detail-label">Keterangan</span>
    <span class="detail-value" id="d_keterangan"></span>
</div>

<div class="modal-actions">
    <button type="button" onclick="closeDetail()" style="flex:1;background:#1a1a2e;color:#ffd000">
        <i class="fa-solid fa-xmark"></i> Tutup
    </button>
</div>

</div>
</div>

<!-- ================= SCRIPT ================= -->
<script>

function showDetail(jenis,nama,jumlah,ref,petugas,request,cost,atasan,so,keterangan){
    document.getElementById("d_jenis").innerText = jenis.charAt(0).toUpperCase() + jenis.slice(1);
    document.getElementById("d_nama").innerText = nama;
    document.getElementById("d_jumlah").innerText = jumlah + ' unit';
    document.getElementById("d_ref").innerText = ref;
    document.getElementById("d_petugas").innerText = petugas;
    document.getElementById("d_request").innerText = request;
    document.getElementById("d_cost").innerText = cost;
    document.getElementById("d_atasan").innerText = atasan;
    document.getElementById("d_so").innerText = so;
    document.getElementById("d_keterangan").innerText = keterangan;

    document.getElementById("modalDetail").style.display = "flex";
    document.getElementById("overlayDetail").style.display = "block";
}

function closeDetail(){
    document.getElementById("modalDetail").style.display = "none";
    document.getElementById("overlayDetail").style.display = "none";
}

function openModalMasuk(){
    document.getElementById("modalMasuk").style.display = "flex";
    document.getElementById("overlayMasuk").style.display = "block";
}

function closeModalMasuk(){
    document.getElementById("modalMasuk").style.display = "none";
    document.getElementById("overlayMasuk").style.display = "none";
}

function openModalKeluar(){
    document.getElementById("modalKeluar").style.display = "flex";
    document.getElementById("overlayKeluar").style.display = "block";
}

function closeModalKeluar(){
    document.getElementById("modalKeluar").style.display = "none";
    document.getElementById("overlayKeluar").style.display = "none";
}

</script>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
// Initialize Tom Select on both dropdowns
let tsMasuk, tsKeluar;

function initTomSelect() {
    // Destroy existing instances if any
    if (tsMasuk) { tsMasuk.destroy(); tsMasuk = null; }
    if (tsKeluar) { tsKeluar.destroy(); tsKeluar = null; }

    const tsConfig = {
        maxOptions: null,
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        render: {
            no_results: function() {
                return '<div class="no-results">Barang tidak ditemukan</div>';
            }
        }
    };

    tsMasuk = new TomSelect('#selectMasuk', tsConfig);
    tsKeluar = new TomSelect('#selectKeluar', tsConfig);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initTomSelect);

// Refresh Tom Select focus when modal opens
const origOpenMasuk = openModalMasuk;
openModalMasuk = function() {
    origOpenMasuk();
    if (tsMasuk) setTimeout(() => tsMasuk.focus(), 100);
};

const origOpenKeluar = openModalKeluar;
openModalKeluar = function() {
    origOpenKeluar();
    if (tsKeluar) setTimeout(() => tsKeluar.focus(), 100);
};
</script>

</body>
</html>
