<?php
session_start();
require_once "../config/koneksi.php";

// proteksi sederhana
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: ../auth/login.php");
    exit;
}

// ambil data ringkasan pesanan
$id_user = $_SESSION['id_user'];

$query_total   = mysqli_query($conn, "SELECT COUNT(*) total FROM pesanan WHERE id_user='$id_user'");
$total_pesanan = mysqli_fetch_assoc($query_total)['total'];

$query_proses  = mysqli_query($conn, "
    SELECT COUNT(*) total FROM pesanan 
    WHERE id_user='$id_user' AND status != 'selesai'
");
$pesanan_aktif = mysqli_fetch_assoc($query_proses)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pembeli</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">FreshShoes.</div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="../pembeli/pesan.php">Buat Pesanan</a></li>
        <li><a href="../pembeli/status.php">Status Pesanan</a></li>
        <li><a href="../auth/logout.php">Logout</a>
</li>
    </ul>
</nav>

<!-- CONTENT -->
<div class="container">
    <h1>Halo, <?= $_SESSION['nama']; ?> 👋</h1>
    <p>Selamat datang di dashboard pembeli.</p>

    <div class="cards">
        <div class="card">
            <h3>Total Pesanan</h3>
            <p><?= $total_pesanan; ?></p>
        </div>
        <div class="card">
            <h3>Pesanan Aktif</h3>
            <p><?= $pesanan_aktif; ?></p>
        </div>
    </div>

    <div class="actions">
        <a href="../pembeli/pesan.php" class="btn">+ Buat Pesanan</a>
    </div>
</div>

</body>
</html>
