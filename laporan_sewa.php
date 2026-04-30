<?php
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$owner_id = $_SESSION['user_id'];

$query = "SELECT transaksi.*, users.nama as penyewa, properti.nama_properti 
          FROM transaksi 
          JOIN users ON transaksi.tenant_id = users.id 
          JOIN properti ON transaksi.properti_id = properti.id 
          WHERE properti.owner_id = '$owner_id' 
          AND transaksi.status = 'Lunas'
          ORDER BY transaksi.id DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sewa - Owner</title>
   <style>
    :root { 
        --primary-red: #e74c3c; 
        --sidebar-bg: #2c3e50; 
        --bg-light: #f4f7f6;
    }

    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
        font-family: 'Segoe UI', sans-serif; 
    }

    body { 
        background-color: var(--bg-light); 
        display: flex;
        height: 100vh; 
        width: 100vw;
        overflow: hidden; 
    }

    .sidebar { 
        width: 260px; 
        min-width: 260px; 
        background-color: var(--sidebar-bg); 
        color: white; 
        display: flex; 
        flex-direction: column; 
        z-index: 10;
    }

    .sidebar-header { 
        height: 70px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 18px; 
        font-weight: 800; 
        background-color: #1a252f; 
        color: var(--primary-red); 
        letter-spacing: 2px; 
    }

    .sidebar-menu { flex: 1; padding: 20px 0; }

    .sidebar-menu a { 
        display: block; 
        padding: 15px 25px; 
        color: #bdc3c7; 
        text-decoration: none; 
        transition: 0.2s; 
        font-size: 14px; 
    }

    .sidebar-menu a:hover, 
    .sidebar-menu a.active { 
        background-color: #34495e; 
        color: var(--primary-red); 
        border-left: 5px solid var(--primary-red); 
    }

    .sidebar-menu .logout { 
        margin-top: 30px; 
        border-top: 1px solid #34495e; 
        color: #e74c3c; 
    }


    .main-wrapper { 
        flex: 1; 
        display: flex; 
        flex-direction: column; 
        overflow: hidden; 
    }

    .topbar { 
        height: 70px; 
        background-color: white; 
        display: flex; 
        align-items: center; 
        padding: 0 40px; 
        box-shadow: 0 2px 15px rgba(0,0,0,0.05); 
        font-weight: bold; 
    }

    .content { 
        padding: 40px; 
        overflow-y: auto; 
        flex: 1; 
    }

    .table-container { 
        background: white; 
        border-radius: 12px; 
        overflow: hidden; 
        border-top: 5px solid #27ae60; 
        width: 100%; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .table-header-title { 
        padding: 20px 30px; 
        border-bottom: 1px solid #f1f2f6; 
        font-weight: 700; 
        font-size: 18px; 
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
    }

    th { 
        background-color: #fcfdfe; 
        color: #95a5a6; 
        padding: 18px 30px; 
        font-size: 12px; 
        text-align: left; 
    }

    td { 
        padding: 18px 30px; 
        border-bottom: 1px solid #f1f2f6; 
        font-size: 14px; 
    }
</style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">PANEL OWNER</div>
        <div class="sidebar-menu">
            <a href="dashboard_owner.php">Dashboard</a>
            <a href="properti_saya.php">Properti Saya</a>
            <a href="laporan_sewa.php" class="active">Laporan Sewa</a>
            <a href="pengaturan_owner.php">Pengaturan</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">Riwayat Transaksi Berhasil</header>
        <main class="content">
            <div class="table-container">
                <div class="table-header-title">Laporan Pendapatan</div>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Penyewa</th>
                            <th>Properti</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
if(mysqli_num_rows($result) == 0){
    echo "<tr><td colspan='4' style='text-align:center; color:#999;'>Belum ada transaksi selesai</td></tr>";
} else {
    while($row = mysqli_fetch_assoc($result)): 
?>
    <tr>
        <td><?= date('d M Y', strtotime($row['tanggal_sewa'])); ?></td>
        <td><strong><?= htmlspecialchars($row['penyewa']); ?></strong></td>
        <td><?= htmlspecialchars($row['nama_properti']); ?></td>
        <td><span style="color:#27ae60; font-weight:bold">Lunas</span></td>
    </tr>
<?php 
    endwhile;
}
?>
</tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>