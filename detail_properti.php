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
$user_id = $_SESSION['user_id'];

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

$cek = mysqli_query($conn, "SELECT * FROM transaksi 
    WHERE tenant_id='$user_id' 
    AND properti_id='$id_properti' 
    AND status='Lunas'");
$sudah_sewa = mysqli_num_rows($cek) > 0;

if(isset($_POST['kirim_ulasan'])) {
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];

    $cek_ulasan = mysqli_query($conn, "SELECT * FROM ulasan 
        WHERE properti_id='$id_properti' 
        AND tenant_id='$user_id'");

    if(mysqli_num_rows($cek_ulasan) > 0){
        mysqli_query($conn, "UPDATE ulasan 
            SET rating='$rating', komentar='$komentar' 
            WHERE properti_id='$id_properti' 
            AND tenant_id='$user_id'");
    } else {
        mysqli_query($conn, "INSERT INTO ulasan(properti_id, tenant_id, rating, komentar) 
            VALUES('$id_properti','$user_id','$rating','$komentar')");
    }

    echo "<script>alert('Ulasan berhasil disimpan'); location.href='detail_properti.php?id=$id_properti';</script>";
}

$ulasan = mysqli_query($conn, "SELECT u.*, us.nama 
    FROM ulasan u 
    JOIN users us ON u.tenant_id = us.id 
    WHERE u.properti_id='$id_properti' 
    ORDER BY u.id DESC");

$avg = mysqli_query($conn, "SELECT AVG(rating) as rata FROM ulasan WHERE properti_id='$id_properti'");
$data_avg = mysqli_fetch_assoc($avg);
$rata = round($data_avg['rata'],1);
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

        .info-item strong {
            display: block;
            color: var(--text-muted);
            font-size: 13px;
            margin-bottom: 5px;
        }

        h3 {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .btn-sewa {
            display: block;
            width: 100%;
            background: var(--btn-sewa);
            color: white;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-disabled { background: #ccc; }
        .ulasan-box { margin-top: 40px; border-top: 2px solid #eee; padding-top: 20px; }
        .rating { color: orange; }
        textarea, select { width:100%; margin-top:10px; padding:8px; }
        .btn-ulasan { margin-top:10px; padding:10px; background: var(--primary-red); color:white; border:none; }
        .item-ulasan { margin-top:15px; border-bottom:1px solid #eee; padding-bottom:10px; }
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
    
    <div class="image-placeholder">[Area Foto Properti]</div>

    <h1><?php echo htmlspecialchars($properti['nama_properti']); ?></h1>
    
    <div class="alamat">
        <?php echo htmlspecialchars($properti['alamat']); ?>
    </div>

    <div class="harga"><?php echo $harga_rp; ?></div>

    <?php if($is_available): ?>
        <a href="proses_sewa.php?id=<?php echo $id_properti; ?>" class="btn-sewa">Sewa Sekarang</a>
    <?php else: ?>
        <button class="btn-sewa btn-disabled">Tidak Tersedia</button>
    <?php endif; ?>

    <div class="ulasan-box">
        <h3>Rating ⭐ <?php echo $rata ?: 0; ?>/5</h3>

        <?php if($sudah_sewa): ?>
        <form method="POST">
            <select name="rating" required>
                <option value="">Pilih Rating</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>

            <textarea name="komentar" placeholder="Tulis ulasan..." required></textarea>
            <button name="kirim_ulasan" class="btn-ulasan">Kirim</button>
        </form>
        <?php else: ?>
            <p style="color:gray;">Sewa dulu untuk memberi ulasan</p>
        <?php endif; ?>

        <h3>Ulasan Penyewa</h3>

        <?php while($u = mysqli_fetch_assoc($ulasan)): ?>
            <div class="item-ulasan">
                <strong><?php echo htmlspecialchars($u['nama']); ?></strong><br>
                <span class="rating"><?php echo str_repeat("⭐", $u['rating']); ?></span>
                <p><?php echo htmlspecialchars($u['komentar']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

</div>

</body>
</html>