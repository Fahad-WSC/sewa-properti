<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])) {
    header("Location: katalog_properti.php");
    exit;
}

$id_properti = $_GET['id'];

$query = "SELECT * FROM properti WHERE id = '$id_properti'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    echo "<script>alert('Properti tidak ditemukan!'); window.location='katalog_properti.php';</script>";
    exit;
}

$properti = mysqli_fetch_assoc($result);
$harga_rp = "Rp " . number_format($properti['harga'], 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail <?php echo $properti['nama_properti']; ?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 40px; 
        }

        .detail-container { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.1); 
            max-width: 800px; 
            margin: 0 auto; 
        }

        .btn-kembali { 
            display: inline-block; 
            margin-bottom: 20px; 
            color: #d11212; 
            text-decoration: none; 
            font-weight: bold; 
        }

        .btn-kembali:hover { 
            text-decoration: underline; 
        }

        .image-placeholder {
            width: 100%;
            height: 350px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        h1 {
            margin-top: 0;
            color: #333;
            margin-bottom: 5px;
        }

        .harga {
            font-size: 26px;
            color: #d11212;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #eee;
            margin-bottom: 25px;
        }

        .info-item strong {
            display: block;
            color: #777;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-item span {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        h3 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .deskripsi {
            line-height: 1.6;
            color: #444;
            margin-bottom: 40px;
            white-space: pre-wrap; 
        }

        .btn-sewa {
            display: block;
            width: 100%;
            text-align: center;
            background: #28a745;
            color: white;
            padding: 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 18px;
            box-sizing: border-box;
        }

        .btn-sewa:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <div class="detail-container">
        <a href="katalog_properti.php" class="btn-kembali">← Kembali ke Katalog</a>
        
        <div class="image-placeholder">
            [Area Foto Properti]
        </div>

        <h1><?php echo $properti['nama_properti']; ?></h1>
        <div class="harga"><?php echo $harga_rp; ?> / bulan</div>

        <div class="info-grid">
            <div class="info-item">
                <strong>Tipe Properti</strong>
                <span><?php echo ucfirst($properti['tipe']); ?></span>
            </div>
            <div class="info-item">
                <strong>Kamar Tidur</strong>
                <span><?php echo $properti['kamar']; ?> Kamar</span>
            </div>
            <div class="info-item">
                <strong>Kamar Mandi</strong>
                <span><?php echo ucfirst($properti['kamar_mandi']); ?></span>
            </div>
        </div>

        <h3>Deskripsi Lengkap</h3>
        <div class="deskripsi"><?php echo htmlspecialchars($properti['deskripsi']); ?></div>

        <<a href="proses_sewa.php?id=<?php echo $id_properti; ?>" class="btn-sewa">Sewa Sekarang</a>
    </div>

</body>
</html>