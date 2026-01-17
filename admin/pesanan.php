<?php
session_start();
require_once "../config/koneksi.php";

//role
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

//search
$search = isset($_GET['search']) ? $_GET['search'] : "";

//hapus pesanan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan='$id'");
    header("Location: pesanan.php");
    exit;
}

//edit data
$data_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan='$id'");
    $data_edit = mysqli_fetch_assoc($q);
}

//update pesanan
if (isset($_POST['update_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $id_layanan = $_POST['id_layanan'];
    $jumlah     = $_POST['jumlah'];
    $alamat     = $_POST['alamat'];
    $status     = $_POST['status'];

// ambil harga
    $q = mysqli_query($conn, "SELECT harga FROM layanan WHERE id_layanan='$id_layanan'");
    $l = mysqli_fetch_assoc($q);
    $total = $l['harga'] * $jumlah;

    mysqli_query($conn, "
        UPDATE pesanan SET
            id_layanan='$id_layanan',
            jumlah='$jumlah',
            alamat='$alamat',
            status='$status',
            total_harga='$total'
        WHERE id_pesanan='$id_pesanan'
    ");

    header("Location: pesanan.php");
    exit;
}

//ambil data pesanan
$pesanan = mysqli_query($conn, "
    SELECT 
        p.*,
        u.nama AS nama_pembeli,
        l.nama_layanan,
        l.harga
    FROM pesanan p
    JOIN users u ON p.id_user = u.id_user
    JOIN layanan l ON p.id_layanan = l.id_layanan
    WHERE 
        u.nama LIKE '%$search%' OR
        l.nama_layanan LIKE '%$search%'
    ORDER BY p.id_pesanan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin-pesanan.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">FreshShoes. <small>(Admin)</small></div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="layanan.php">Kelola Layanan</a></li>
        <li><a href="pesanan.php">Kelola Pesanan</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h1>Kelola Pesanan</h1>

    <!-- SEARCH -->
    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Cari pembeli / layanan"
               value="<?= htmlspecialchars($search) ?>">
        <button class="btn">Cari</button>
    </form>

    <!-- TABEL -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pembeli</th>
                    <th>Layanan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=1; while ($row = mysqli_fetch_assoc($pesanan)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_pembeli'] ?></td>
                    <td><?= $row['nama_layanan'] ?></td>
                    <td>Rp <?= number_format($row['harga']) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>Rp <?= number_format($row['total_harga']) ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td>
                        <span class="badge <?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="?edit=<?= $row['id_pesanan'] ?>" class="btn-small">Edit</a>
                        <a href="?hapus=<?= $row['id_pesanan'] ?>" 
                           class="btn-small btn-danger"
                           onclick="return confirm('Hapus pesanan ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- FORM EDIT -->
    <?php if ($data_edit): ?>
    <div class="edit-card">
        <h3>Edit Pesanan</h3>

        <form method="POST" class="edit-grid">
            <input type="hidden" name="id_pesanan" value="<?= $data_edit['id_pesanan'] ?>">

            <div class="form-group">
                <label>Layanan</label>
                <select name="id_layanan">
                    <?php
                    $layanan = mysqli_query($conn, "SELECT * FROM layanan WHERE status='aktif'");
                    while ($l = mysqli_fetch_assoc($layanan)):
                    ?>
                    <option value="<?= $l['id_layanan'] ?>"
                        <?= $data_edit['id_layanan']==$l['id_layanan']?'selected':'' ?>>
                        <?= $l['nama_layanan'] ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="<?= $data_edit['jumlah'] ?>" min="1">
            </div>

            <div class="form-group full">
                <label>Alamat</label>
                <textarea name="alamat"><?= $data_edit['alamat'] ?></textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="menunggu" <?= $data_edit['status']=='menunggu'?'selected':'' ?>>Menunggu</option>
                    <option value="diproses" <?= $data_edit['status']=='diproses'?'selected':'' ?>>Diproses</option>
                    <option value="dicuci" <?= $data_edit['status']=='dicuci'?'selected':'' ?>>Dicuci</option>
                    <option value="selesai" <?= $data_edit['status']=='selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </div>

            <div class="form-actions">
                <button class="btn" name="update_pesanan">Simpan</button>
                <a href="pesanan.php" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
