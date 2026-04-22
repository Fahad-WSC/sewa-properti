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
$total_properti = mysqli_num_rows($result);

$q_sewa = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi 
                               JOIN properti ON transaksi.properti_id = properti.id 
                               WHERE properti.owner_id = '$owner_id' 
                               AND transaksi.status IN ('Disetujui', 'Validasi Bayar', 'Lunas')");
$data_sewa = mysqli_fetch_assoc($q_sewa);
$properti_disewa = $data_sewa['total'];

$q_duit = mysqli_query($conn, "SELECT SUM(properti.harga) as total FROM transaksi 
                               JOIN properti ON transaksi.properti_id = properti.id 
                               WHERE properti.owner_id = '$owner_id' 
                               AND transaksi.status IN ('Disetujui', 'Validasi Bayar', 'Lunas')");
$data_duit = mysqli_fetch_assoc($q_duit);
$estimasi_pendapatan = "Rp " . number_format($data_duit['total'] ?? 0, 0, ',', '.');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard | Sewa Properti</title>
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
            justify-content: space-between;
            padding: 0 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .user-info {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 16px;
        }

        .content {
            padding: 40px;
            overflow-y: auto;
            flex: 1;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h2 {
            color: var(--text-dark);
            font-weight: 700;
        }

        .btn-tambah {
            background: var(--primary-red);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.2);
        }

        .btn-tambah:hover {
            background: var(--dark-red);
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border-left: 6px solid var(--primary-red);
        }

        .stat-card.orange {
            border-left-color: #f39c12;
        }

        .stat-card.green {
            border-left-color: #27ae60;
        }

        .stat-card h3 {
            font-size: 13px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .table-header-title {
            padding: 20px 30px;
            border-bottom: 1px solid #f1f2f6;
            font-weight: 700;
            color: var(--text-dark);
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

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #fafbfc;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 11px;
        }

        .status-waiting {
            background: #fff9e6;
            color: #f39c12;
        }

        .status-approved {
            background: #eef9f1;
            color: #27ae60;
        }

        .status-rejected {
            background: #fdf2f2;
            color: #e74c3c;
        }

        .status-payment {
            background: #e3f2fd;
            color: #2980b9;
        }

        .prop-tersedia {
            background: #eef9f1;
            color: #27ae60;
        }

        .prop-tidak {
            background: #fdf2f2;
            color: #e74c3c;
        }

        .action-links a {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            margin-right: 5px;
            color: white;
            display: inline-block;
            transition: 0.2s;
        }

        .btn-edit {
            background: #f1c40f;
            color: #333 !important;
        }

        .btn-hapus {
            background: #e74c3c;
        }

        .btn-terima {
            background: #2ecc71;
        }

        .btn-cek {
            background: #3498db;
        }

        .action-links a:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .kosong {
            text-align: center;
            padding: 50px;
            color: #bdc3c7;
            font-style: italic;
        }

         .status-lunas {
            background-color: #28a745; 
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">PANEL OWNER</div>
        <div class="sidebar-menu">
            <a href="dashboard_owner.php" class="active">Dashboard</a>
            <a href="properti_saya.php"> Properti Saya</a>
            <a href="laporan_sewa.php"> Laporan Sewa</a>
            <a href="pengaturan_owner.php"> Pengaturan</a>
            <a href="logout.php" class="logout"> Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="user-info">Halo, <?php echo htmlspecialchars($nama_owner); ?>!</div>
            <div>
                <span style="background: var(--primary-red); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);">
                    <?php echo substr(htmlspecialchars($nama_owner), 0, 1); ?>
                </span>
            </div>
        </header>

        <main class="content">
            <div class="page-header">
                <h2>Ringkasan Properti</h2>
                <a href="tambah_properti.php" class="btn-tambah">+ Properti Baru</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Properti</h3>
                    <div class="value"><?php echo $total_properti; ?></div>
                </div>
                <div class="stat-card orange">
                    <h3>Properti Disewa</h3>
                    <div class="value"><?php echo $properti_disewa; ?></div>
                </div>
                <div class="stat-card green">
                    <h3>Estimasi Cuan</h3>
                    <div class="value"><?php echo $estimasi_pendapatan; ?></div>
                </div>
            </div>

            <div class="table-container" style="border-top: 4px solid #f39c12;">
                <div class="table-header-title">Pesanan Masuk Terbaru</div>
                <table>
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th>Nama Properti</th>
                            <th>Tanggal Sewa</th>
                            <th>Status</th>
                            <th style="text-align: center;">Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                  <?php
                    $query_masuk = "SELECT 
                    transaksi.id AS id_trx, 
                    transaksi.tanggal_sewa, 
                    transaksi.status AS status_trx, 
                    transaksi.bukti_bayar, 
                    users.nama AS nama_penyewa, 
                    properti.nama_properti 
                    FROM transaksi 
                    JOIN users ON transaksi.tenant_id = users.id 
                    JOIN properti ON transaksi.properti_id = properti.id 
                    WHERE properti.owner_id = '$owner_id'
                    ORDER BY transaksi.id DESC LIMIT 5";
                    $res_masuk = mysqli_query($conn, $query_masuk);

if(mysqli_num_rows($res_masuk) == 0) {
    echo "<tr><td colspan='5' class='kosong'>Tidak ada aktivitas pesanan baru.</td></tr>";
} else {
    while($row_m = mysqli_fetch_assoc($res_masuk)) {
        $tgl = date('d M Y', strtotime($row_m['tanggal_sewa']));
        $status_asli = isset($row_m['status_trx']) ? trim($row_m['status_trx']) : 'Menunggu Konfirmasi';
        $status_lower = strtolower($status_asli);
        $status_class = '';

        if (strpos($status_lower, 'lunas') !== false) {
            $status_class = 'status-lunas';
        } elseif (strpos($status_lower, 'bayar') !== false || strpos($status_lower, 'validasi') !== false) {
            $status_class = 'status-payment';
        } elseif (strpos($status_lower, 'setuju') !== false) {
            $status_class = 'status-approved';
        } elseif (strpos($status_lower, 'tolak') !== false) {
            $status_class = 'status-rejected';
        } else {
            $status_class = 'status-waiting';
        }

        if (!empty($row_m['bukti_bayar']) && $status_class == 'status-approved') {
            $status_class = 'status-payment';
            $status_asli = 'Validasi Bayar';
        }

        if ($status_class == 'status-waiting') {
            $tombol_aksi = "<a href='update_status.php?id={$row_m['id_trx']}&status=Disetujui' class='btn-terima'>Terima</a> " .
                           "<a href='update_status.php?id={$row_m['id_trx']}&status=Ditolak' class='btn-hapus'>Tolak</a>";
        } elseif ($status_class == 'status-payment') {
            $tombol_aksi = "<a href='cek_pembayaran.php?id={$row_m['id_trx']}' class='btn-cek'>Cek Bayar</a>";
        } elseif ($status_class == 'status-lunas') {
            $tombol_aksi = "<span style='color: #28a745; font-size: 11px; font-weight: bold;'>Lunas</span>";
        } else {
            $tombol_aksi = "<span style='color: #bdc3c7; font-size: 11px;'>Tindakan Selesai</span>";
        }

        echo "<tr>
                <td><strong>" . htmlspecialchars($row_m['nama_penyewa']) . "</strong></td>
                <td>" . htmlspecialchars($row_m['nama_properti']) . "</td>
                <td>{$tgl}</td>
                <td><span class='badge {$status_class}'>" . htmlspecialchars($status_asli) . "</span></td>
                <td class='action-links' style='text-align: center;'>{$tombol_aksi}</td>
              </tr>";
    }
}
?>
    
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <div class="table-header-title">Kelola Daftar Properti</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Properti & Lokasi</th>
                            <th>Tipe</th>
                            <th>Harga Sewa</th>
                            <th>Status</th>
                            <th style="text-align: center;">Opsi Pengaturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($result) == 0) {
                            echo "<tr><td colspan='6' class='kosong'>Kamu belum memiliki properti. Yuk mulai tambah!</td></tr>";
                        } else {
                            $no = 1;
                            mysqli_data_seek($result, 0); 
                            while($row = mysqli_fetch_assoc($result)) {
                                $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                                $status_properti = isset($row['status']) ? $row['status'] : 'TERSEDIA';
                                $badge_prop = ($status_properti == 'TERSEDIA') ? 'prop-tersedia' : 'prop-tidak';
                                $alamat_singkat = !empty($row['alamat']) ? htmlspecialchars(substr($row['alamat'], 0, 35)) . '...' : 'Alamat belum diisi';

                                echo "<tr>
                                        <td width='50'>{$no}</td>
                                        <td>
                                            <strong>" . htmlspecialchars($row['nama_properti']) . "</strong><br>
                                            <span style='font-size: 11px; color: #7f8c8d;'> {$alamat_singkat}</span>
                                        </td>
                                        <td>" . ucfirst($row['tipe']) . "</td>
                                        <td style='color: #27ae60; font-weight: 700;'>{$harga_rp}</td>
                                        <td><span class='badge {$badge_prop}'>{$status_properti}</span></td>
                                        <td class='action-links' style='text-align: center;'>
                                            <a href='edit_properti.php?id={$row['id']}' class='btn-edit'>Edit</a>
                                            <a href='hapus_properti.php?id={$row['id']}' class='btn-hapus' onclick=\"return confirm('Hapus properti ini?');\">Hapus</a>
                                        </td>
                                      </tr>";
                                $no++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>