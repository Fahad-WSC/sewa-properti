<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

$query_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$row_users = mysqli_fetch_assoc($query_users);
$total_users = $row_users['total'];

$query_properti = mysqli_query($conn, "SELECT COUNT(*) as total FROM properti");
$row_properti = mysqli_fetch_assoc($query_properti);
$total_properti = $row_properti['total'];

$total_transaksi = 0; 

$query_recent_users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sewa Properti</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #111; 
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #d11212; 
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            flex: 1;
            padding-top: 20px;
        }

        .sidebar-menu a {
            display: block;
            padding: 15px 25px;
            color: #ddd;
            text-decoration: none;
            font-size: 15px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #222;
            color: white;
            border-left-color: #d11212;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #333;
        }

        .btn-logout {
            display: block;
            text-align: center;
            background-color: transparent;
            color: #d11212;
            border: 1px solid #d11212;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background-color: #d11212;
            color: white;
        }

        .main-content {
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
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .topbar-title {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-size: 14px;
            color: #555;
            font-weight: bold;
        }

        .content-area {
            padding: 30px;
            overflow-y: auto;
            flex: 1;
        }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            background: white;
            padding: 25px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-top: 4px solid #d11212;
        }

        .stat-title {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #111;
        }

        .panel {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .panel-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            background-color: #fafafa;
        }

        .panel-header h3 {
            font-size: 16px;
            color: #333;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        th {
            background-color: #fff;
            color: #555;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge.admin { background-color: #fde8e8; color: #d11212; }
        .badge.owner { background-color: #e6f4ea; color: #1e8e3e; }
        .badge.tenant { background-color: #e8f0fe; color: #1a73e8; }

        .btn-action {
            text-decoration: none;
            color: #d11212;
            font-weight: bold;
            font-size: 13px;
        }
        .btn-action:hover { text-decoration: underline; }

        .kosong {
            text-align: center;
            color: #777;
            padding: 20px;
        }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            SEWA PROPERTI
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php" class="active">Dashboard</a>
            <a href="kelola_user.php">Kelola Pengguna</a>
            <a href="kelola_properti.php">Data Properti</a>
            <a href="laporan.php">Laporan Transaksi</a>
            <a href="pengaturan.php">Pengaturan</a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="topbar">
            <div class="topbar-title">Administrator Panel</div>
            <div class="user-profile">
                <span class="user-name">Halo, <?php echo $nama_admin; ?></span>
                <div style="width: 35px; height: 35px; background: #d11212; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                </div>
            </div>
        </div>

        <div class="content-area">
            
            <h2 style="margin-bottom: 20px; color: #333; font-size: 22px;">Ringkasan Sistem</h2>

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">Total Pengguna</div>
                    <div class="stat-value"><?php echo $total_users; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-card">
                        <div class="stat-title">Total Properti</div>
                        <div class="stat-value"><?php echo $total_properti; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card" >
                         <div class="stat-title">Total Transaksi</div>
                         <div class="stat-value"><?php echo $total_transaksi; ?></div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Pendaftaran User Terbaru</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query_recent_users) > 0) {
                            while($row = mysqli_fetch_assoc($query_recent_users)) {
                                $nama = htmlspecialchars($row['nama']);
                                $email = isset($row['email']) ? htmlspecialchars($row['email']) : '-';
                                $role = htmlspecialchars($row['role']);
                                
                                $badge_class = strtolower($role);
                                
                                echo "<tr>
                                        <td><strong>{$nama}</strong></td>
                                        <td>{$email}</td>
                                        <td><span class='badge {$badge_class}'>".ucfirst($role)."</span></td>
                                        <td><a href='#' class='btn-action'>Detail</a></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='kosong'>Belum ada data user.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>