<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$query = "SELECT p.*, pr.nama_properti, pr.harga 
          FROM pesanan p 
          JOIN properti pr ON p.properti_id = pr.id 
          ORDER BY p.tanggal_pesan DESC";

try {
    $result = mysqli_query($conn, $query); 
} catch (mysqli_sql_exception $e) {
    $result = false; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Admin</title>
    <style>
    :root {
            --primary-red: #e74c3c;
            --dark-red: #c0392b;
            --sidebar-bg: #2c3e50;
            --light-bg: #f4f7f6;
            --text-dark: #2c3e50;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Roboto, sans-serif; 
        }

        body { 
            background-color: var(--light-bg); 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
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
            font-size: 18px;
            color: #333;
        }

        .content { 
            padding: 40px; 
            overflow-y: auto; 
            flex: 1; 
        }
        
        .table-container { 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 25px rgba(0,0,0,0.04); 
            overflow: hidden; 
        }

        .table-header-title { 
            padding: 20px 30px; 
            border-bottom: 1px solid #f1f2f6; 
            font-weight: 700; 
            color: #333; 
            font-size: 18px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-cetak {
            padding: 10px 20px; 
            background: var(--primary-red); 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            font-weight: bold;
        }

        .btn-cetak:hover {
            background: var(--dark-red);
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th { 
            background-color: #fcfdfe; 
            color: #777; 
            padding: 18px 30px; 
            text-transform: uppercase; 
            font-size: 12px; 
            text-align: left; 
        }

        td { 
            padding: 18px 30px; 
            border-bottom: 1px solid #f1f2f6; 
            font-size: 14px; 
            color: #444;
        }
        
        .badge { 
            padding: 5px 10px; 
            border-radius: 20px; 
            font-weight: 700; 
            font-size: 11px; 
        }

        .status-berhasil { background: #eef9f1; color: #27ae60; }
        .status-pending { background: #fff8e1; color: #f39c12; }
        .status-batal { background: #fdf2f2; color: #e74c3c; }

        .kosong { 
            text-align: center; 
            padding: 50px; 
            color: #888; 
            font-style: italic; 
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">PANEL ADMIN</div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="kelola_user.php">Kelola Pengguna</a>
            <a href="kelola_properti.php">Data Properti</a>
            <a href="laporan_transaksi.php" class="active">Laporan Transaksi</a>
            <a href="pengaturan_admin.php">Pengaturan</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            Laporan Transaksi Keseluruhan
        </header>
        <main class="content">
            <div class="table-container">
                <div class="table-header-title">
                    <span>Semua Riwayat Transaksi Sewa</span>
                    <button class="btn-cetak" onclick="window.print()">Cetak Laporan</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Properti</th>
                            <th>Penyewa</th>
                            <th>Total Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!$result || mysqli_num_rows($result) == 0): 
                        ?>
                            <tr><td colspan="6" class="kosong">Belum ada data transaksi di sistem.</td></tr>
                        <?php 
                        else:
                            $no=1; 
                            while($row = mysqli_fetch_assoc($result)): 
                                $status = $row['status'] ?? 'pending';
                                $badge_class = 'status-pending';
                                if(strtolower($status) == 'berhasil' || strtolower($status) == 'lunas') $badge_class = 'status-berhasil';
                                if(strtolower($status) == 'batal') $badge_class = 'status-batal';
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_pesan'])); ?></td>
                            <td><strong><?= htmlspecialchars($row['nama_properti']); ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_penyewa'] ?? 'Tenant'); ?></td>
                            <td style="color:#d11212; font-weight:bold">Rp <?= number_format($row['total_harga'] ?? $row['harga'],0,',','.'); ?></td>
                            <td><span class="badge <?= $badge_class; ?>"><?= strtoupper($status); ?></span></td>
                        </tr>
                        <?php 
                            endwhile; 
                        endif; 
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>