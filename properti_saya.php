<?php
session_start();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properti Saya - Owner</title>
    <style>
        :root { --primary-red: #e74c3c; --dark-red: #c0392b; --sidebar-bg: #2c3e50; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        
        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: white; display: flex; flex-direction: column; }
        .sidebar-header { height: 70px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; background-color: #1a252f; color: var(--primary-red); letter-spacing: 2px; }
        .sidebar-menu { flex: 1; padding: 20px 0; }
        .sidebar-menu a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; transition: 0.2s; font-size: 14px; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #34495e; color: var(--primary-red); border-left: 5px solid var(--primary-red); }
        .sidebar-menu .logout { margin-top: 30px; border-top: 1px solid #34495e; color: #e74c3c; }
        
        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { height: 70px; background-color: white; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        .content { padding: 40px; overflow-y: auto; flex: 1; }
        
        .table-container { background: white; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.04); overflow: hidden; }
        .table-header-title { padding: 20px 30px; border-bottom: 1px solid #f1f2f6; font-weight: 700; color: #2c3e50; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #fcfdfe; color: #95a5a6; padding: 18px 30px; text-transform: uppercase; font-size: 12px; text-align: left; }
        td { padding: 18px 30px; border-bottom: 1px solid #f1f2f6; font-size: 14px; }
        
        .btn-tambah { background: var(--primary-red); color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .action-links a { text-decoration: none; padding: 8px 15px; border-radius: 6px; font-size: 12px; font-weight: 700; color: white; margin-right: 5px; }
        .btn-edit { background: #f1c40f; color: #333 !important; }
        .btn-hapus { background: #e74c3c; }
        .badge { background: #eef9f1; color: #27ae60; padding: 5px 10px; border-radius: 20px; font-weight: 700; font-size: 11px; }
    </style>
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
            <div style="font-weight:bold">Manajemen Properti</div>
            <a href="tambah_properti.php" class="btn-tambah">+ Properti Baru</a>
        </header>
        <main class="content">
            <div class="table-container">
                <div class="table-header-title">Daftar Semua Properti</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Properti</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $row['nama_properti']; ?></strong></td>
                            <td><?= ucfirst($row['tipe']); ?></td>
                            <td style="color:#27ae60; font-weight:bold">Rp <?= number_format($row['harga'],0,',','.'); ?></td>
                            <td><span class="badge">Aktif</span></td>
                            <td class="action-links">
                                <a href="edit_properti.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                                <a href="hapus_properti.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Hapus?')">Hapus</a>
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