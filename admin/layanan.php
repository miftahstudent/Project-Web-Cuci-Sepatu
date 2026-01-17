<?php
session_start();
require_once "../config/koneksi.php";

//role
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

//proses tambah / edit
if (isset($_POST['simpan'])) {
    $nama      = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $harga     = $_POST['harga'];
    $estimasi  = $_POST['estimasi_hari'];
    $status    = $_POST['status'];

    if ($_POST['id_layanan'] == "") {
        // tambah
        mysqli_query($conn, "
            INSERT INTO layanan (nama_layanan, deskripsi, harga, estimasi_hari, status)
            VALUES ('$nama', '$deskripsi', '$harga', '$estimasi', '$status')
        ");
    } else {
        // edit
        $id = $_POST['id_layanan'];
        mysqli_query($conn, "
            UPDATE layanan SET
                nama_layanan='$nama',
                deskripsi='$deskripsi',
                harga='$harga',
                estimasi_hari='$estimasi',
                status='$status'
            WHERE id_layanan='$id'
        ");
    }

    header("Location: layanan.php");
    exit;
}

//hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM layanan WHERE id_layanan='$id'");
    header("Location: layanan.php");
    exit;
}

//edit data
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $q  = mysqli_query($conn, "SELECT * FROM layanan WHERE id_layanan='$id'");
    $edit = mysqli_fetch_assoc($q);
}

//data layanan
$layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id_layanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Layanan</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin-layanan.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">FreshShoes. <small>(Admin)</small></div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="layanan.php">Kelola Layanan</a></li>
        <li><a href="../admin/pesanan.php">Kelola Pesanan</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <h1>Kelola Layanan</h1>

    <!-- FORM -->
    <div class="form-box">
        <h3><?= $edit ? 'Edit Layanan' : 'Tambah Layanan' ?></h3>

        <form method="POST">
            <input type="hidden" name="id_layanan" value="<?= $edit['id_layanan'] ?? '' ?>">

            <input type="text" name="nama_layanan" placeholder="Nama Layanan"
                   value="<?= $edit['nama_layanan'] ?? '' ?>" required>

            <textarea name="deskripsi" placeholder="Deskripsi layanan"><?= $edit['deskripsi'] ?? '' ?></textarea>

            <input type="number" name="harga" placeholder="Harga"
                   value="<?= $edit['harga'] ?? '' ?>" required>

            <input type="number" name="estimasi_hari" placeholder="Estimasi (hari)"
                   value="<?= $edit['estimasi_hari'] ?? '' ?>" required>

            <select name="status">
                <option value="aktif" <?= ($edit['status'] ?? '') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= ($edit['status'] ?? '') == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>

            <button class="btn" type="submit" name="simpan">
                <?= $edit ? 'Update' : 'Tambah' ?> Layanan
            </button>
        </form>
    </div>

    <!-- TABEL -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Estimasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($layanan)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_layanan'] ?></td>
                    <td>Rp <?= number_format($row['harga']) ?></td>
                    <td><?= $row['estimasi_hari'] ?> hari</td>
                    <td>
                        <span class="badge <?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="?edit=<?= $row['id_layanan'] ?>" class="btn-small">Edit</a>
                        <a href="?hapus=<?= $row['id_layanan'] ?>"
                           class="btn-small btn-danger"
                           onclick="return confirm('Hapus layanan ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
