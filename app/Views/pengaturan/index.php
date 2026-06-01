<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengaturan - TRAKINDO Inventory</title>

<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:#f4f6f9;
}

/* ===== TOPBAR ===== */

.topbar{
background:linear-gradient(to right,#000,#111);
color:#ffcc00;
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 40px;
}

.logo{
display:flex;
align-items:center;
gap:12px;
}

.logo i{
font-size:28px;
}

.logo h1{
font-size:18px;
margin:0;
}

.logo span{
font-size:12px;
color:#ddd;
}

.top-right{
font-size:15px;
display:flex;
align-items:center;
gap:8px;
}

/* ===== CONTAINER ===== */

.container{
padding:40px;
}

/* ===== Pengaturan ===== */

.settings-wrapper{
max-width:900px;
margin:auto;
}

.settings-card{
background:white;
border-radius:20px;
padding:35px;
box-shadow:0px 8px 25px rgba(0,0,0,0.08);
}

.settings-title{
display:flex;
align-items:center;
gap:10px;
font-size:22px;
font-weight:600;
margin-bottom:30px;
}

/* MENU */

.settings-menu{
display:grid;
grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
gap:20px;
}

.settings-item{
background:#fffdf7;
border:2px solid #ffcc00;
border-radius:15px;
padding:30px;
text-align:center;
text-decoration:none;
color:#333;
font-weight:500;
transition:0.25s;
}

.settings-item i{
font-size:32px;
margin-bottom:12px;
color:black;
}

.settings-item:hover{
background:#ffcc00;
color:black;
transform:translateY(-5px);
box-shadow:0px 8px 20px rgba(0,0,0,0.15);
}

</style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">

<div class="logo">
<i class="fa-solid fa-box"></i>
<div>
<h1>TRAKINDO Inventory</h1>
<span>Sistem Pengelolaan Inventory</span>
</div>
</div>

<div class="top-right">
<i class="fa-solid fa-user"></i> Admin
</div>

</div>


<div class="container">

<div class="settings-wrapper">

<div class="settings-card">

<div class="settings-title">
<i class="fa-solid fa-gear"></i> Pengaturan Sistem
</div>

<div class="settings-menu">

<a href="<?= base_url ('/ganti_password') ?>" class="settings-item">
<i class="fa-solid fa-key"></i>
<br>
Ganti Password
</a>

<a href="<?= base_url ('/dashboard') ?>" class="settings-item">
<i class="fa-solid fa-user"></i>
<br>
Profil Saya
</a>

<a href="<?= base_url('login') ?>" class="settings-item">
<i class="fa-solid fa-right-from-bracket"></i>
<br>
Logout
</a>

</div>

</div>

</div>

</div>

</body>
</html>