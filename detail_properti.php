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
$status_properti = $properti['status'] ?? 'TERSEDIA';
$is_available = ($status_properti == 'TERSEDIA');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail <?php echo htmlspecialchars($properti['nama_properti']); ?></title>
    <style>
        :root {
            --primary-red: #d11212;
            --dark-red: #a00d0d;
            --btn-sewa: #28a745;
            --btn-sewa-hover: #218838;
            --bg-color: #f4f4f4;
            --text-main: #333;
            --text-muted: #777;
        }

        body { 
            font-family: Arial, sans-serif; 
            background-color: var(--bg-color); 
            margin: 0; 
            padding: 40px 20px; 
            color: var(--text-main);
        }

        .detail-container { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.1); 
            max-width: 800px; 
            margin: 0 auto; 
        }

        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn-kembali { 
            color: var(--primary-red); 
            text-decoration: none; 
            font-weight: bold; 
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-kembali:hover { 
            text-decoration: underline;
        }

        .badge-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
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

        .image-placeholder {
            width: 100%;
            height: 350px;
            background-color: #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        h1 {
            margin-top: 0;
            color: var(--text-main);
            margin-bottom: 5px;
            font-size: 28px;
        }

        .alamat {
            color: var(--text-muted);
            font-size: 15px;
            margin-bottom: 20px;
        }

        .harga-wrapper {
            margin-bottom: 25px;
        }

        .harga {
            font-size: 28px;
            color: var(--primary-red);
            font-weight: bold;
        }

        .harga span {
            font-size: 16px;
            color: var(--text-muted);
            font-weight: normal;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #eee;
            margin-bottom: 30px;
        }

        .info-item {
            text-align: center;
        }

        .info-item .icon {
            font-size: 20px;
            margin-bottom: 8px;
            display: block;
        }

        .info-item strong {
            display: block;
            color: var(--text-muted);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-item span {
            font-size: 16px;
            font-weight: bold;
            color: var(--text-main);
        }

        h3 {
            color: var(--text-main);
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
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
            background: var(--btn-sewa);
            color: white;
            padding: 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 18px;
            box-sizing: border-box;
            border: none;
            cursor: pointer;
        }

        .btn-sewa:hover {
            background: var(--btn-sewa-hover);
        }

        .btn-disabled {
            background: #ccc;
            cursor: not-allowed;
            color: #fff;
        }

        .btn-disabled:hover {
            background: #ccc;
        }
    </style>
</head>
<body>

    <div class="detail-container">
        <div class="header-nav">
            <a href="katalog_properti.php" class="btn-kembali">← Kembali ke Katalog</a>
            <?php if($is_available): ?>
                <span class="badge-status status-tersedia">Tersedia</span>
            <?php else: ?>
                <span class="badge-status status-tidak">Sudah Disewa</span>
            <?php endif; ?>
        </div>
        
        <div class="image-placeholder">
            <span>[Area Foto Properti]</span>
        </div>

        <h1><?php echo htmlspecialchars($properti['nama_properti']); ?></h1>
        
        <div class="alamat">
             <?php echo !empty($properti['alamat']) ? htmlspecialchars($properti['alamat']) : 'Alamat belum dilengkapi.'; ?>
        </div>

        <div class="harga-wrapper">
            <div class="harga"><?php echo $harga_rp; ?> <span>/ bulan</span></div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="icon"></span>
                <strong>Tipe Properti</strong>
                <span><?php echo ucfirst($properti['tipe']); ?></span>
            </div>
            <div class="info-item">
                <span class="icon"></span>
                <strong>Kamar Tidur</strong>
                <span><?php echo $properti['kamar']; ?> Kamar</span>
            </div>
            <div class="info-item">
                <span class="icon"></span>
                <strong>Kamar Mandi</strong>
                <span><?php echo ucfirst($properti['kamar_mandi']); ?></span>
            </div>
        </div>

        <h3>Deskripsi Lengkap</h3>
        <div class="deskripsi"><?php echo htmlspecialchars($properti['deskripsi']); ?></div>

        <?php if($is_available): ?>
            <a href="proses_sewa.php?id=<?php echo $id_properti; ?>" class="btn-sewa">Sewa Sekarang</a>
        <?php else: ?>
            <button class="btn-sewa btn-disabled" disabled>Properti Tidak Tersedia</button>
        <?php endif; ?>
    </div>

</body>
</html>