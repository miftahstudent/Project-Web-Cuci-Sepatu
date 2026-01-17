<?php
session_start();
require_once "../config/koneksi.php";

// proteksi admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// ambil data ringkasan
$q_layanan  = mysqli_query($conn, "SELECT COUNT(*) total FROM layanan");
$total_layanan = mysqli_fetch_assoc($q_layanan)['total'];

$q_pesanan  = mysqli_query($conn, "SELECT COUNT(*) total FROM pesanan");
$total_pesanan = mysqli_fetch_assoc($q_pesanan)['total'];

$q_proses = mysqli_query($conn, "
    SELECT COUNT(*) total FROM pesanan 
    WHERE status != 'selesai'
");
$pesanan_aktif = mysqli_fetch_assoc($q_proses)['total'];

$q_user = mysqli_query($conn, "
    SELECT COUNT(*) total FROM users WHERE role='pembeli'
");
$total_user = mysqli_fetch_assoc($q_user)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">FreshShoes. <small>(Admin)</small></div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="../admin/layanan.php">Kelola Layanan</a></li>
        <li><a href="../admin/pesanan.php">Kelola Pesanan</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</nav>

<!-- CONTENT -->
<div class="container">
    <h1>Halo, Admin 👋</h1>
    <p>Selamat datang di dashboard admin.</p>

    <div class="cards">
        <div class="card">
            <h3>Total Layanan</h3>
            <p><?= $total_layanan; ?></p>
        </div>

        <div class="card">
            <h3>Total Pesanan</h3>
            <p><?= $total_pesanan; ?></p>
        </div>

        <div class="card">
            <h3>Pesanan Aktif</h3>
            <p><?= $pesanan_aktif; ?></p>
        </div>

        <div class="card">
            <h3>Total Pembeli</h3>
            <p><?= $total_user; ?></p>
        </div>
    </div>

    <div class="actions">
        <a href="../admin/layanan.php" class="btn">Kelola Layanan</a>
        <a href="../admin/pesanan.php" class="btn secondary">Kelola Pesanan</a>
    </div>
</div>

</body>
</html>
