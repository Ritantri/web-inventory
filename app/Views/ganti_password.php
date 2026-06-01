<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ganti Password - TRAKINDO Inventory</title>

<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:#f4f6f9;
}

/* TOPBAR */

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

/* CONTAINER */

.container{
padding:40px;
}

/* FORM */

.form-wrapper{
max-width:500px;
margin:auto;
}

.form-card{
background:white;
border-radius:20px;
padding:35px;
box-shadow:0px 8px 25px rgba(0,0,0,0.08);
}

.form-title{
display:flex;
align-items:center;
gap:10px;
font-size:22px;
font-weight:600;
margin-bottom:30px;
}

.form-group{
margin-bottom:20px;
}

.form-group label{
font-weight:500;
display:block;
margin-bottom:6px;
}

.form-group input{
width:100%;
padding:10px;
border-radius:8px;
border:1px solid #ccc;
font-size:14px;
}

/* BUTTON */

.btn{
background:#ffcc00;
border:none;
padding:12px;
border-radius:10px;
font-weight:600;
cursor:pointer;
width:100%;
transition:0.2s;
}

.btn:hover{
background:#e6b800;
}

.back{
margin-top:15px;
display:block;
text-align:center;
text-decoration:none;
color:#555;
font-size:14px;
}

.back:hover{
text-decoration:underline;
}

/* NOTIFICATION */

.notif{
width:90%;
max-width:900px;
margin:20px auto;
padding:15px 20px;
border-radius:12px;
font-weight:500;
display:flex;
align-items:center;
gap:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
animation:slideDown 0.4s ease;
}

.notif.success{
background:#e8f8ef;
color:#1e7e34;
border-left:5px solid #28a745;
}

.notif.error{
background:#fdeaea;
color:#b02a37;
border-left:5px solid #dc3545;
}

@keyframes slideDown{
from{
opacity:0;
transform:translateY(-10px);
}
to{
opacity:1;
transform:translateY(0);
}
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
<span>Sistem Pengolahan Inventory</span>
</div>
</div>

<div class="top-right">
<i class="fa-solid fa-user"></i> <?= session()->get('username') ?>
</div>

</div>

<!-- NOTIFIKASI -->

<?php if(session()->getFlashdata('success')): ?>
<div class="notif success">
<i class="fa-solid fa-circle-check"></i>
<?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
<div class="notif error">
<i class="fa-solid fa-triangle-exclamation"></i>
<?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>


<div class="container">

<div class="form-wrapper">

<div class="form-card">

<div class="form-title">
<i class="fa-solid fa-key"></i> Ganti Password
</div>

<form action="<?= base_url('/auth/proses-ganti-password') ?>" method="post">

<div class="form-group">
<label>Password Lama</label>
<input type="password" name="password_lama" required>
</div>

<div class="form-group">
<label>Password Baru</label>
<input type="password" name="password_baru" required>
</div>

<div class="form-group">
<label>Konfirmasi Password</label>
<input type="password" name="konfirmasi" required>
</div>

<button class="btn" type="submit">
<i class="fa-solid fa-floppy-disk"></i> Simpan Password
</button>

</form>

<a href="<?= base_url('/dashboard') ?>" class="back">
<i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
</a>

</div>

</div>

</div>

</body>
</html>