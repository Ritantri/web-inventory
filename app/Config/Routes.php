<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(true);

$routes->get('/login', 'Auth::login');
/* ================= DASHBOARD ================= */
$routes->get('/', 'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/download-stok-menipis', 'Dashboard::downloadStokMenipis');

/* ================= DATA BARANG ================ */
$routes->get('/barang', 'Barang::index');
$routes->get('/barang/create', 'Barang::create');
$routes->post('/barang/store', 'Barang::store');
$routes->get('/barang/edit/(:num)', 'Barang::edit/$1');
$routes->post('/barang/update/(:num)', 'Barang::update/$1');
$routes->get('/barang/delete/(:num)', 'Barang::delete/$1');

/* ================= TRANSAKSI ================= */
$routes->get('/transaksi', 'Transaksi::index');
$routes->post('/transaksi/masuk', 'Transaksi::masuk');
$routes->post('/transaksi/keluar', 'Transaksi::keluar');

/* ================= RIWAYAT ================= */
$routes->get('/stock', 'Stock::index');
$routes->get('/stock/export', 'Stock::export');

/*================= LOGIN ==================== */
$routes->post('/auth/prosesLogin', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

/*================= PENGATURAN ============== */
$routes->get('pengaturan', 'Pengaturan::index');
$routes->get('/dashboard', 'Dashboard::index');

/*================= GANTI PASSWORD ============= */
$routes->get('/ganti_password','Auth::gantiPassword');
$routes->post('/auth/proses-ganti-password','Auth::prosesGantiPassword');