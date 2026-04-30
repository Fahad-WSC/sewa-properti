<?php
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$owner_id = $_SESSION['user_id'];
$nama_owner = $_SESSION['nama'] ?? 'Owner';

$query = "SELECT * FROM properti WHERE owner_id = '$owner_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Properti Saya - Sewa Properti</title>
    </head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">PANEL OWNER</div>
        <div class="sidebar-menu">
            <a href="dashboard_owner.php">Dashboard</a>
            <a href="properti_saya.php" class="active">Properti Saya</a>
            <a href="laporan_sewa.php">Laporan Sewa</a>
            <a href="pengaturan_owner.php">Pengaturan</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="user-info">Manajemen Properti</div>
            <a href="tambah_properti.php" class="btn-tambah">+ Properti Baru</a>
        </header>

        <main class="content">
            <div class="table-container">
                <div class="table-header-title">Semua Properti Anda</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Properti</th>
                            <th>Tipe</th>
                            <th>Harga Sewa</th>
                            <th>Status Akun</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $row['nama_properti']; ?></strong></td>
                            <td><?= ucfirst($row['tipe']); ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><span class="badge status-approved">Aktif</span></td>
                            <td class="action-links" style="text-align: center;">
                                <a href="edit_properti.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                                <a href="hapus_properti.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>