<?php
require 'koneksi.php';
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$query = "SELECT 
            transaksi.*, 
            users.nama AS penyewa, 
            properti.nama_properti,
            properti.harga
          FROM transaksi
          JOIN users ON transaksi.tenant_id = users.id
          JOIN properti ON transaksi.properti_id = properti.id
          ORDER BY transaksi.id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Transaksi - Admin</title>

<style>
:root {
    --primary-red: #e74c3c;
    --dark-red: #c0392b;
    --sidebar-bg: #2c3e50;
    --light-bg: #f4f7f6;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: var(--light-bg);
    display: flex;
    height: 100vh;
}

 .sidebar { 
            width: 260px; 
            background-color: var(--sidebar-bg); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            transition: all 0.3s;
        }

        .sidebar-header { 
            height: 70px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 18px; 
            font-weight: 800; 
            background-color: #1a252f; 
            letter-spacing: 2px; 
            color: var(--primary-red);
            border-bottom: 1px solid #34495e;
        }

        .sidebar-menu { 
            flex: 1; 
            padding: 20px 0; 
        }

        .sidebar-menu a { 
            display: flex;
            align-items: center;
            padding: 15px 25px; 
            color: #bdc3c7; 
            text-decoration: none; 
            transition: 0.2s; 
            font-size: 14px;
            font-weight: 500;
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

        .sidebar-menu .logout:hover { 
            background-color: var(--primary-red); 
            color: white; 
        }

.main-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.topbar {
    height: 70px;
    background: white;
    display: flex;
    align-items: center;
    padding: 0 30px;
    font-weight: bold;
}

.content {
    padding: 30px;
    overflow-y: auto;
}

.table-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.table-header-title {
    padding: 20px;
    display: flex;
    justify-content: space-between;
}

.btn-cetak {
    background: var(--primary-red);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
}

.status-berhasil { background:#d4edda; color:#155724; }
.status-pending { background:#fff3cd; color:#856404; }
.status-batal { background:#f8d7da; color:#721c24; }

.kosong {
    text-align:center;
    padding:40px;
    color:#888;
}
</style>
</head>

<body>

<div class="sidebar">
    <div class="sidebar-header">PANEL ADMIN</div>
    <div class="sidebar-menu">
        <a href="dashboard_admin.php">Dashboard</a>
        <a href="kelola_user.php">Kelola Pengguna</a>
        <a href="kelola_properti.php">Data Properti</a>
        <a href="laporan_transaksi.php" class="active">Laporan Transaksi</a>
        <a href="pengaturan_admin.php">Pengaturan</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar">Laporan Transaksi</div>

    <div class="content">
        <div class="table-container">
            <div class="table-header-title">
                <span>Semua Transaksi</span>
                <button class="btn-cetak" onclick="window.print()">Cetak</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Properti</th>
                        <th>Penyewa</th>
                        <th>Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                if(!$result || mysqli_num_rows($result) == 0){
                    echo "<tr><td colspan='6' class='kosong'>Belum ada transaksi</td></tr>";
                } else {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)){

                        $status = strtolower(trim($row['status']));
                        $badge = 'status-pending';

                        if(strpos($status,'lunas') !== false){
                            $badge = 'status-berhasil';
                        } elseif(strpos($status,'tolak') !== false){
                            $badge = 'status-batal';
                        }

                        echo "<tr>
                                <td>".$no++."</td>
                                <td>".date('d M Y', strtotime($row['tanggal_sewa']))."</td>
                                <td>".htmlspecialchars($row['nama_properti'])."</td>
                                <td>".htmlspecialchars($row['penyewa'])."</td>
                                <td>Rp ".number_format($row['harga'],0,',','.')."</td>
                                <td><span class='badge $badge'>".strtoupper($row['status'])."</span></td>
                              </tr>";
                    }
                }
                ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

</body>
</html>