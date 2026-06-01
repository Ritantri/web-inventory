<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRAKINDO Inventory - Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<div class="topbar">
    <div class="logo">
        <i class="fa-solid fa-boxes-stacked"></i>
        <div>
            <h1>TRAKINDO Inventory</h1>
            <span>Sistem Pengelolaan Inventory</span>
        </div>
    </div>

    <div class="top-right">
        <a href="<?= base_url('pengaturan') ?>">
            <i class="fa-solid fa-user-circle"></i> Trakindo
        </a>
    </div>
</div>

<!-- NAVIGATION -->
<div class="nav">
    <a href="<?= base_url('/dashboard') ?>" class="active">
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

<!-- CONTENT -->
<div class="container">

    <h1>Dashboard Inventory</h1>
    <p class="subtitle">Monitoring dan analisis stok barang secara real-time</p>

    <!-- FILTER -->
    <form method="get" class="filter-container" id="filterForm">

        <input type="date" name="tanggal" value="<?= esc($filter_tanggal ?? '') ?>"
               title="Filter berdasarkan tanggal">

        <select name="item" title="Filter berdasarkan item">
            <option value="">Semua Item</option>
            <?php foreach($list_barang as $b): ?>
                <option value="<?= esc($b['nama']) ?>"
                    <?= ($filter_item ?? '') == $b['nama'] ? 'selected' : '' ?>>
                    <?= esc($b['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="cost_center" title="Filter berdasarkan cost center">
            <option value="">Semua Cost Center</option>
            <?php foreach($list_cost_center as $cc): ?>
                <?php if(!empty($cc['cost_center'])): ?>
                    <option value="<?= esc($cc['cost_center']) ?>"
                        <?= ($filter_cost_center ?? '') == $cc['cost_center'] ? 'selected' : '' ?>>
                        <?= esc($cc['cost_center']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <input type="text" name="search" placeholder="Cari barang, kategori..."
               value="<?= esc($filter_search ?? '') ?>">

        <button type="submit" class="filter-btn">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>

        <a href="<?= base_url('dashboard') ?>" class="reset-btn">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>

    </form>

    <!-- STATISTIC CARDS -->
    <div class="cards">

        <div class="card card-light">
            <h3><i class="fa-solid fa-boxes-stacked"></i> Total Jenis Barang</h3>
            <h1><?= $total_jenis ?></h1>
            <span>Barang aktif terdaftar</span>
        </div>

        <div class="card card-dark">
            <h3><i class="fa-solid fa-warehouse"></i> Total Stok Keseluruhan</h3>
            <h1><?= number_format($total_stok, 0, ',', '.') ?></h1>
            <span>Unit barang tersedia</span>
        </div>

        <div class="card card-warning">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Stok Menipis</h3>
            <h1><?= $stok_menipis ?></h1>
            <span>Perlu segera direstock</span>
        </div>

        <div class="card card-yellow">
            <h3><i class="fa-solid fa-tags"></i> Total Kategori</h3>
            <h1><?= $total_kategori ?></h1>
            <span>Kategori berbeda</span>
        </div>

    </div>


    <!-- ================= CHARTS ================= -->

    <div class="chart-wrapper">

        <!-- GRAFIK 1: LINE CHART - Pergerakan Stok Masuk & Keluar -->
        <div class="chart-card">
            <h3>
                <i class="fa-solid fa-arrow-trend-up"></i>
                Pergerakan Stok (7 Hari)
            </h3>
            <canvas id="stokChart"></canvas>
        </div>

        <!-- GRAFIK 2: DOUGHNUT/BAR CHART - Jumlah Barang per Kategori -->
        <div class="chart-card dark">
            <h3>
                <i class="fa-solid fa-layer-group"></i>
                Jumlah Barang per Kategori
            </h3>
            <canvas id="kategoriChart"></canvas>
        </div>

    </div>

    <!-- GRAFIK 3: HORIZONTAL BAR CHART - Barang Keluar + Cost Center -->
    <div class="chart-card full-width">
        <h3>
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
            Barang Keluar per Cost Center
        </h3>
        <canvas id="barangKeluarChart"></canvas>
    </div>


    <!-- STOK MENIPIS TABLE -->
    <div class="stok-warning">

        <div class="stok-header">
            <div class="stok-title">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Peringatan Stok Menipis</span>
            </div>

            <a href="<?= base_url('dashboard/download-stok-menipis') ?>" class="excel-btn">
                <i class="fa-solid fa-file-excel"></i>
                Download Excel
            </a>
        </div>

        <table class="stok-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data_stok_menipis)): ?>
                    <?php foreach($data_stok_menipis as $item): ?>
                    <tr>
                        <td><?= esc($item['kode']) ?></td>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['kategori']) ?></td>
                        <td><?= $item['stok'] ?></td>
                        <td><?= $item['stok_min'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Semua stok aman — tidak ada yang menipis 🎉</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// ===============================
// CHART DEFAULTS
// ===============================

Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = '#666';

// ===============================
// GRAFIK 1: PERGERAKAN STOK
// ===============================

const ctx1 = document.getElementById('stokChart');

new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= $labels_7hari ?>,
        datasets: [
            {
                label: 'Barang Masuk',
                data: <?= $stok_masuk ?>,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 3
            },
            {
                label: 'Barang Keluar',
                data: <?= $stok_keluar ?>,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#ef4444',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 20,
                    font: { weight: '500' }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(26,26,46,0.9)',
                titleFont: { weight: '600' },
                padding: 12,
                cornerRadius: 10,
                displayColors: true,
                usePointStyle: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { padding: 10 }
            },
            x: {
                grid: { display: false },
                ticks: { padding: 10 }
            }
        }
    }
});


// ===============================
// GRAFIK 2: JUMLAH BARANG PER KATEGORI
// ===============================

const ctx2 = document.getElementById('kategoriChart');

const kategoriLabels = <?= $kategori_labels ?>;
const kategoriData = <?= $kategori_jumlah ?>;

const kategoriColors = [
    '#ffd000', '#1a1a2e', '#3b82f6', '#ef4444', '#10b981',
    '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'
];

new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: kategoriLabels,
        datasets: [{
            data: kategoriData,
            backgroundColor: kategoriColors.slice(0, kategoriLabels.length),
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        cutout: '55%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 16,
                    font: { size: 12, weight: '500' }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(26,26,46,0.9)',
                titleFont: { weight: '600' },
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    label: function(context) {
                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                        let pct = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                        return ` ${context.label}: ${context.parsed} item (${pct}%)`;
                    }
                }
            }
        }
    }
});


// ===============================
// GRAFIK 3: BARANG KELUAR + COST CENTER
// ===============================

const ctx3 = document.getElementById('barangKeluarChart');

const keluarLabels = <?= $barang_keluar_label ?>;
const keluarData = <?= $barang_keluar_total ?>;
const keluarColors = <?= $barang_keluar_colors ?>;

new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: keluarLabels,
        datasets: [{
            label: 'Jumlah Keluar',
            data: keluarData,
            backgroundColor: keluarColors.length > 0 ? keluarColors : ['#3b82f6'],
            borderRadius: 8,
            borderSkipped: false,
            barThickness: 28,
            maxBarThickness: 36
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(26,26,46,0.9)',
                titleFont: { weight: '600' },
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    label: function(context) {
                        return ` Total Keluar: ${context.parsed.x} unit`;
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { padding: 10 }
            },
            y: {
                grid: { display: false },
                ticks: {
                    padding: 10,
                    font: { size: 12 }
                }
            }
        }
    }
});

</script>

</body>
</html>