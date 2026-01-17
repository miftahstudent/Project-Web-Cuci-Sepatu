<?php
session_start();
require_once "../config/koneksi.php";

//role
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: ../auth/login.php");
    exit;
}

//ambil data layanan aktif
$layanan = mysqli_query($conn, "
    SELECT * FROM layanan 
    WHERE status='aktif'
    ORDER BY nama_layanan ASC
");

//proses simpan pesan
if (isset($_POST['pesan'])) {
    $id_user    = $_SESSION['id_user'];
    $id_layanan = $_POST['id_layanan'];
    $jumlah     = $_POST['jumlah'];
    $alamat     = $_POST['alamat'];
    $catatan    = $_POST['catatan'];

// ambil harga layanan
    $q = mysqli_query($conn, "
        SELECT harga FROM layanan WHERE id_layanan='$id_layanan'
    ");
    $data = mysqli_fetch_assoc($q);

    $total = $data['harga'] * $jumlah;

// simpan pesanan
    mysqli_query($conn, "
        INSERT INTO pesanan
        (id_user, id_layanan, tanggal_pesan, alamat, jumlah, total_harga, status, catatan)
        VALUES
        ('$id_user', '$id_layanan', CURDATE(), '$alamat', '$jumlah', '$total', 'menunggu', '$catatan')
    ");

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/pembeli-pesan.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">FreshShoes.</div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pesan.php">Buat Pesanan</a></li>
        <li><a href="../pembeli/status.php">Status Pesanan</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <h1>Buat Pesanan</h1>

    <div class="form-box">
        <form method="POST">

            <label>Layanan</label>
            <select name="id_layanan" required>
                <option value="">-- Pilih Layanan --</option>
                <?php while ($l = mysqli_fetch_assoc($layanan)): ?>
                    <option value="<?= $l['id_layanan'] ?>">
                        <?= $l['nama_layanan'] ?> 
                        (Rp <?= number_format($l['harga']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Jumlah Sepatu</label>
            <input type="number" name="jumlah" min="1" value="1" required>

            <label>Alamat</label>
            <textarea name="alamat" required></textarea>

            <label>Catatan (opsional)</label>
            <textarea name="catatan"></textarea>

            <button type="submit" name="pesan" class="btn">
                Pesan Sekarang
            </button>

        </form>
    </div>

</div>

</body>
</html>
