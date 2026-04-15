<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.php");
    exit;
}

$query_sql = "SELECT * FROM properti WHERE 1=1";

if(isset($_GET['tipe']) && $_GET['tipe'] != "") {
    $tipe = mysqli_real_escape_string($conn, $_GET['tipe']);
    $query_sql .= " AND tipe = '$tipe'";
}

if(isset($_GET['kamar']) && $_GET['kamar'] != "") {
    $kamar = mysqli_real_escape_string($conn, $_GET['kamar']);
    if($kamar == "3") {
        $query_sql .= " AND kamar >= 3"; 
    } else {
        $query_sql .= " AND kamar = '$kamar'";
    }
}

if(isset($_GET['kamar_mandi']) && $_GET['kamar_mandi'] != "") {
    $kamar_mandi = mysqli_real_escape_string($conn, $_GET['kamar_mandi']);
    $query_sql .= " AND kamar_mandi = '$kamar_mandi'";
}

if(isset($_GET['harga_max']) && $_GET['harga_max'] != "") {
    $harga_max = mysqli_real_escape_string($conn, $_GET['harga_max']);
    $query_sql .= " AND harga <= '$harga_max'";
}

$query_sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query_sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Properti - Sewa Properti</title>
    <style>
        :root {
            --primary-red: #d11212;
            --dark-red: #a00d0d;
            --bg-color: #f4f4f4;
            --text-main: #333;
            --text-muted: #777;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            color: var(--text-main);
        }

        .navbar {
            background-color: var(--primary-red);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            transition: 0.2s;
        }

        .navbar .menu a:hover {
            color: #ffcccc;
        }

        .main-container {
            display: flex;
            padding: 30px 40px;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            align-items: flex-start;
        }

        .sidebar-filter {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 250px;
            position: sticky;
            top: 90px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .sidebar-filter h3 {
            margin-top: 0;
            font-size: 18px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: var(--text-main);
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: bold;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary-red);
        }

        .btn-filter {
            background: var(--text-main);
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }

        .btn-filter:hover {
            background: #000;
        }

        .content-area {
            flex: 1;
        }

        .content-area h2 {
            margin-top: 0;
            color: var(--text-main);
            margin-bottom: 20px;
        }

        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
            position: relative;
        }

        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .property-image {
            width: 100%;
            height: 180px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            position: relative;
        }

        .badge-status {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status-tersedia {
            background: #eef9f1;
            color: #27ae60;
        }

        .status-tidak {
            background: #fdf2f2;
            color: var(--primary-red);
        }

        .property-info {
            padding: 20px;
        }

        .property-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 8px 0;
            color: var(--text-main);
        }

        .property-address {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .property-price {
            color: var(--primary-red);
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .property-features {
            font-size: 13px;
            color: var(--text-main);
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .property-features div {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-sewa {
            display: block;
            text-align: center;
            background: var(--primary-red);
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-sewa:hover {
            background: var(--dark-red);
        }

        .kosong {
            grid-column: 1 / -1;
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            color: var(--text-muted);
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Sewa Properti</div>
        <div class="menu">
            <span>Halo, <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Tenant'); ?>!</span>
            <a href="katalog_properti.php">Cari Properti</a>
            <a href="dashboard_tenant.php">Pesanan Saya</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-container">
        
        <div class="sidebar-filter">
            <h3>Filter Pencarian</h3>
            <form action="" method="GET">
                <div class="filter-group">
                    <label>Tipe Properti</label>
                    <select name="tipe">
                        <option value="">Semua Tipe</option>
                        <option value="rumah" <?php if(isset($_GET['tipe']) && $_GET['tipe'] == 'rumah') echo 'selected'; ?>>Rumah</option>
                        <option value="apartemen" <?php if(isset($_GET['tipe']) && $_GET['tipe'] == 'apartemen') echo 'selected'; ?>>Apartemen</option>
                        <option value="kos" <?php if(isset($_GET['tipe']) && $_GET['tipe'] == 'kos') echo 'selected'; ?>>Kamar Kos</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Jumlah Kamar Tidur</label>
                    <select name="kamar">
                        <option value="">Bebas</option>
                        <option value="1" <?php if(isset($_GET['kamar']) && $_GET['kamar'] == '1') echo 'selected'; ?>>1 Kamar</option>
                        <option value="2" <?php if(isset($_GET['kamar']) && $_GET['kamar'] == '2') echo 'selected'; ?>>2 Kamar</option>
                        <option value="3" <?php if(isset($_GET['kamar']) && $_GET['kamar'] == '3') echo 'selected'; ?>>3+ Kamar</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Kamar Mandi</label>
                    <select name="kamar_mandi">
                        <option value="">Bebas</option>
                        <option value="dalam" <?php if(isset($_GET['kamar_mandi']) && $_GET['kamar_mandi'] == 'dalam') echo 'selected'; ?>>Kamar Mandi Dalam</option>
                        <option value="luar" <?php if(isset($_GET['kamar_mandi']) && $_GET['kamar_mandi'] == 'luar') echo 'selected'; ?>>Kamar Mandi Luar</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Harga Maksimal (Rp)</label>
                    <input type="number" name="harga_max" placeholder="Misal: 5000000" value="<?php if(isset($_GET['harga_max'])) echo htmlspecialchars($_GET['harga_max']); ?>">
                </div>
                <button type="submit" class="btn-filter">Terapkan Filter</button>
            </form>
        </div>

        <div class="content-area">
            <h2>Rekomendasi Properti</h2>
            
            <div class="property-grid">
                <?php 
                if(mysqli_num_rows($result) == 0) {
                    echo "<div class='kosong'>Maaf, tidak ada properti yang cocok dengan filter kamu. Coba sesuaikan ulang pencarian.</div>";
                } else {
                    while($row = mysqli_fetch_assoc($result)) {
                        $tipe_label = ucfirst($row['tipe']);
                        $km_label = ucfirst($row['kamar_mandi']);
                        $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                        
                        $alamat_singkat = !empty($row['alamat']) ? htmlspecialchars(substr($row['alamat'], 0, 35)) . '...' : 'Alamat belum dilengkapi';
                        
                        $status_properti = $row['status'] ?? 'TERSEDIA';
                        $badge_class = ($status_properti == 'TERSEDIA') ? 'status-tersedia' : 'status-tidak';
                        $status_text = ($status_properti == 'TERSEDIA') ? 'Tersedia' : 'Sudah Disewa';
                        
                        echo "
                        <div class='property-card'>
                            <div class='property-image'>
                                <span class='badge-status {$badge_class}'>{$status_text}</span>
                                [Foto {$tipe_label}]
                            </div>
                            <div class='property-info'>
                                <h3 class='property-title'>" . htmlspecialchars($row['nama_properti']) . "</h3>
                                <div class='property-address'> {$alamat_singkat}</div>
                                <div class='property-price'>{$harga_rp} <span style='font-size: 12px; color: #777; font-weight: normal;'>/ bln</span></div>
                                
                                <div class='property-features'>
                                    <div> {$tipe_label}</div>
                                    <div> {$row['kamar']} Kamar</div>
                                    <div style='grid-column: 1 / -1;'> KM {$km_label}</div>
                                </div>
                                
                                <a href='detail_properti.php?id={$row['id']}' class='btn-sewa'>Lihat Detail</a>
                            </div>
                        </div>
                        ";
                    }
                }
                ?>
            </div>
        </div>

    </div>

</body>
</html>