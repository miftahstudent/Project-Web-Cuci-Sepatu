<?php
session_start();
require_once "config/koneksi.php";

// kalau sudah login, langsung redirect
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: pembeli/dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jasa Cuci Sepatu</title>
    <link rel="stylesheet" href="assets/css/landing.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">FreshShoes.</div>
    <ul class="nav-menu">
    <li><a href="#">Beranda</a></li>
    <li><a href="#layanan">Layanan</a></li>
    <li><a href="#tentang">Tentang Kami</a></li>
    <li><a href="#kontak">Kontak</a></li>
        <li><a href="auth/login.php" class="btn-login">Login</a></li>
    </ul>
</nav>

<!-- HERO SECTION -->
 <section class="hero" style="background:url('assets/img/hero.jpg') center/cover no-repeat;">
<section class="hero">
    <div class="overlay"></div>

    <div class="hero-content">
        <span class="subtitle">Sepatu Bersih, Percaya Diri</span>
        <h1>
            Rawat Sepatumu<br>
            Seperti Baru Setiap Saat
        </h1>
        <p>
            Layanan cuci sepatu profesional, cepat, dan terpercaya.
            Cocok untuk semua jenis sepatu kesayanganmu.
        </p>

        <div class="hero-btn">
            <a href="auth/register.php" class="btn-primary">Daftar Sekarang</a>
            <a href="#layanan" class="btn-secondary">Lihat Layanan</a>
        </div>
    </div>
</section>

<!--LAYANAN -->
<section id="layanan" class="section layanan-section">
    <h2 class="section-title">Layanan Kami</h2>

    <div class="layanan-grid">
        <div class="layanan-card">
            <img src="assets/img/layanan/basic.jpg">
            <h3>Basic Clean</h3>
            <p>Cuci ringan untuk sepatu harian</p>
            <span>Rp 30.000</span>

            <a href="auth/login.php" class="btn-pesan">
            Pesan Sekarang
             </a>
        </div>

        <div class="layanan-card">
            <img src="assets/img/layanan/deep.jpg">
            <h3>Deep Clean</h3>
            <p>Pembersihan menyeluruh</p>
            <span>Rp 50.000</span>

            <a href="auth/login.php" class="btn-pesan">
            Pesan Sekarang
             </a>
        </div>

        <div class="layanan-card">
            <img src="assets/img/layanan/whitening.jpg">
            <h3>Whitening</h3>
            <p>Putihkan bagian sol</p>
            <span>Rp 40.000</span>

            <a href="auth/login.php" class="btn-pesan">
            Pesan Sekarang
             </a>
        </div>

        <div class="layanan-card">
            <img src="assets/img/layanan/repair.png">
            <h3>Minor Repair</h3>
            <p>Perbaikan ringan sepatu</p>
            <span>Rp 60.000</span>

            <a href="auth/login.php" class="btn-pesan">
            Pesan Sekarang
             </a>
        </div>
    </div>
</section>

<!-- TENTANG KAMI -->
<section id="tentang" class="section tentang-section">
    <div class="tentang-content">
        <h2 class="section-title">Tentang Kami</h2>
        <p>
            FreshShoes adalah jasa cuci sepatu profesional yang berfokus pada
            kualitas, kebersihan, dan kepuasan pelanggan.
            Kami menggunakan teknik dan bahan terbaik untuk merawat sepatu
            kesayangan Anda.
        </p>
    </div>
</section>

<!-- KONTAK -->
<section id="kontak" class="section kontak-section">
    <h2 class="section-title">Kontak Kami</h2>

    <div class="kontak-box">
        <p><b>Alamat:</b> Jl. Lembang No. 10</p>
        <p><b>WhatsApp:</b> 0812-3456-7890</p>
        <p><b>Email:</b> freshshoes@gmail.com</p>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2025 FreshShoes. All rights reserved.</p>
</footer>

</body>
</html>
