<?php
session_start();
require 'koneksi.php'; // Panggil koneksi database

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
            position: sticky;
            top: 0;
            z-index: 100;
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
            padding: 20px;
            border-radius: 8px;
            width: 250px;
            position: sticky;
            top: 80px;
        }

        .sidebar-filter h3 {
            margin-top: 0;
            font-size: 18px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-filter {
            background: #333;
            color: white;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        .content-area {
            flex: 1;
        }

        .content-area h2 {
            margin-top: 0;
            color: #333;
        }

        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .property-image {
            width: 100%;
            height: 180px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
        }

        .property-info {
            padding: 15px;
        }

        .property-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .property-price {
            color: #d11212;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .property-features {
            font-size: 12px;
            color: #555;
            margin-bottom: 15px;
            background: #f9f9f9;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .btn-sewa {
            display: block;
            text-align: center;
            background: #d11212;
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 13px;
        }

        .kosong {
            grid-column: 1 / -1;
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Sewa Properti</div>
        <div class="menu">
            <span>Halo, <?php echo $_SESSION['nama']; ?>!</span>
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
                    <input type="number" name="harga_max" placeholder="Misal: 5000000" value="<?php if(isset($_GET['harga_max'])) echo $_GET['harga_max']; ?>">
                </div>
                <button type="submit" class="btn-filter">Terapkan Filter</button>
            </form>
        </div>

        <div class="content-area">
            <h2>Rekomendasi Properti</h2>
            
            <div class="property-grid">
                <?php 
                if(mysqli_num_rows($result) == 0) {
                    echo "<div class='kosong'>Maaf, tidak ada properti yang cocok dengan filter kamu. Coba ubah pencarian.</div>";
                } else {
                    while($row = mysqli_fetch_assoc($result)) {
                        $tipe_label = ucfirst($row['tipe']);
                        $km_label = ucfirst($row['kamar_mandi']);
                        // Format angka ke format Rupiah
                        $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                        
                        echo "
                        <div class='property-card'>
                            <div class='property-image'>[Foto {$tipe_label}]</div>
                            <div class='property-info'>
                                <h3 class='property-title'>{$row['nama_properti']}</h3>
                                <div class='property-price'>{$harga_rp}</div>
                                <div class='property-features'>
                                     Tipe: {$tipe_label}<br>
                                     {$row['kamar']} Kamar Tidur<br>
                                     Kamar Mandi {$km_label}
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