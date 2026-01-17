<?php
session_start();
require_once "../config/koneksi.php";

//role
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

//ambil data pesanan
$pesanan = mysqli_query($conn, "
    SELECT 
        p.*,
        l.nama_layanan
    FROM pesanan p
    JOIN layanan l ON p.id_layanan = l.id_layanan
    WHERE p.id_user='$id_user'
    ORDER BY p.id_pesanan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/pembeli-status.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">FreshShoes.</div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pesan.php">Buat Pesanan</a></li>
        <li><a href="status.php">Status Pesanan</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <h1>Status Pesanan</h1>

    <?php if (mysqli_num_rows($pesanan) == 0): ?>
        <p>Kamu belum memiliki pesanan.</p>
    <?php else: ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Layanan</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=1; while ($row = mysqli_fetch_assoc($pesanan)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['tanggal_pesan'] ?></td>
                    <td><?= $row['nama_layanan'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>Rp <?= number_format($row['total_harga']) ?></td>
                    <td>
                        <span class="badge <?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>

</div>

</body>
</html>
