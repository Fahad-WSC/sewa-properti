<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.php");
    exit;
}

$tenant_id = $_SESSION['user_id'];

$query_pesanan = "SELECT transaksi.*, properti.nama_properti 
                  FROM transaksi 
                  JOIN properti ON transaksi.properti_id = properti.id 
                  WHERE transaksi.tenant_id = '$tenant_id' 
                  ORDER BY transaksi.id DESC";
$result_pesanan = mysqli_query($conn, $query_pesanan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tenant - Sewa Properti</title>
   <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
        }

        .navbar { 
            background-color: #d11212; 
            padding: 15px 40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            color: white; 
        }

        .navbar .logo { 
            font-size: 22px; 
            font-weight: bold; 
        }

        .navbar .menu a { 
            color: white; 
            text-decoration: none; 
            margin-left: 20px; 
            font-size: 14px; 
            font-weight: bold; 
        }

        .navbar .menu a.aktif { 
            text-decoration: underline; 
        }

        .container { 
            padding: 40px; 
            max-width: 1000px; 
            margin: 0 auto; 
        }

        .header-dashboard { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
        }

        .header-dashboard h2 { 
            color: #333; 
            margin: 0; 
        }

        .btn-cari { 
            background: #333; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: bold; 
        }

        .btn-cari:hover { 
            background: #555; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.1); 
            border-radius: 8px; 
            overflow: hidden; 
        }

        th, td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }

        th { 
            background-color: #f8f9fa; 
            color: #333; 
        }

        tr:hover { 
            background-color: #f1f1f1; 
        }
        
        .status-badge { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: bold; 
        }

        .menunggu { 
            background-color: #fff3cd; 
            color: #856404; 
        }

        .disetujui { 
            background-color: #d4edda; 
            color: #155724; 
        }

        .ditolak { 
            background-color: #f8d7da; 
            color: #721c24; 
        }

        .kosong { 
            text-align: center; 
            padding: 40px; 
            color: #777; 
        }

        .kosong p { 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Sewa Properti</div>
        <div class="menu">
            <span>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</span>
            <a href="katalog_properti.php">Cari Properti</a>
            <a href="dashboard_tenant.php" class="aktif">Pesanan Saya</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="header-dashboard">
            <h2>Daftar Pesanan Saya</h2>
            <a href="katalog_properti.php" class="btn-cari">Cari Properti Lagi</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No Pesanan</th>
                    <th>Nama Properti</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($result_pesanan) == 0) {
                    echo "<tr>
                            <td colspan='5' class='kosong'>
                                <h3>Belum ada properti yang kamu sewa.</h3>
                                <p>Cari rumah, apartemen, atau kosan impianmu sekarang!</p>
                            </td>
                          </tr>";
                } else {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result_pesanan)) {
                        $status_class = '';
                        if($row['status'] == 'Menunggu Konfirmasi') $status_class = 'menunggu';
                        elseif($row['status'] == 'Disetujui') $status_class = 'disetujui';
                        elseif($row['status'] == 'Ditolak') $status_class = 'ditolak';

                        $tanggal = date('d M Y', strtotime($row['tanggal_sewa']));

                        echo "<tr>
                                <td>#TRX-00{$row['id']}</td>
                                <td><strong>{$row['nama_properti']}</strong></td>
                                <td>{$tanggal}</td>
                                <td><span class='status-badge {$status_class}'>{$row['status']}</span></td>
                                <td><a href='detail_properti.php?id={$row['properti_id']}' style='color:#d11212; font-weight:bold; text-decoration:none;'>Lihat Properti</a></td>
                              </tr>";
                        $no++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>