<?php
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$id_trx = $_GET['id'] ?? null;

$query = "SELECT transaksi.*, users.nama as nama_penyewa, properti.nama_properti 
          FROM transaksi 
          JOIN users ON transaksi.tenant_id = users.id 
          JOIN properti ON transaksi.properti_id = properti.id 
          WHERE transaksi.id = '$id_trx'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='dashboard_owner.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pembayaran | Sewa Properti</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #10b981;
            --danger: #f43f5e;
            --dark: #0f172a;
            --slate-500: #64748b;
            --bg-body: #f8fafc;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top right, #e2e8f0, #f8fafc);
            color: var(--dark);
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            padding: 20px;
        }

        .container { 
            max-width: 480px; 
            width: 100%; 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            padding: 40px; 
            border-radius: 28px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        h2 { 
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 24px;
            background: linear-gradient(to right, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            padding: 20px;
            border-radius: 18px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .info-row:last-child { margin-bottom: 0; }

        .label { font-size: 0.85rem; color: var(--slate-500); font-weight: 600; }
        .value { font-size: 0.95rem; color: var(--dark); font-weight: 700; }

        .status-badge {
            background: #fef2f2;
            color: var(--danger);
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 12px;
            display: block;
        }

        .img-preview {
            position: relative;
            background: #f1f5f9;
            border-radius: 20px;
            padding: 10px;
            border: 2px dashed #cbd5e1;
            transition: 0.3s;
            overflow: hidden;
            aspect-ratio: 4/3;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-preview:hover { border-color: var(--primary); transform: scale(1.01); }

        img { 
            width: 100%; 
            height: 100%;
            object-fit: contain;
            border-radius: 12px;
            cursor: zoom-in;
        }

        .btn-group { display: flex; flex-direction: column; gap: 14px; margin-top: 32px; }

        .btn {
            padding: 16px;
            text-decoration: none;
            border-radius: 16px;
            font-weight: 700;
            text-align: center;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-success { 
            background: var(--primary); 
            color: white; 
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-success:hover { background: var(--primary-hover); transform: translateY(-2px); }

        .btn-outline { 
            background: transparent; 
            color: var(--slate-500);
            border: 1px solid #e2e8f0;
        }

        .btn-outline:hover { background: #f1f5f9; color: var(--dark); }

        .empty-text { color: var(--slate-500); font-size: 0.85rem; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <h2>Detail Transaksi</h2>
    
    <div class="info-card">
        <div class="info-row">
            <span class="label">Penyewa</span>
            <span class="value"><?= htmlspecialchars($data['nama_penyewa']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Properti</span>
            <span class="value"><?= htmlspecialchars($data['nama_properti']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Status</span>
            <span class="status-badge"><?= $data['status'] ?></span>
        </div>
    </div>
    
    <span class="section-title">Bukti Pembayaran</span>
    <div class="img-preview">
        <?php if($data['bukti_bayar']): ?>
            <img src="/uploads/<?= $data['bukti_bayar'] ?>" alt="Bank transfer receipt showing the amount paid, transaction date, sender and receiver account details, and confirmation of successful payment for property rental." onclick="window.open(this.src)">
        <?php else: ?>
            <span class="empty-text">Belum ada lampiran bukti.</span>
        <?php endif; ?>
    </div>
    
    <div class="btn-group">
        <?php if($data['bukti_bayar']): ?>
            <a href="proses_konfirmasi.php?id=<?= $data['id'] ?>&status=Lunas" class="btn btn-success">Konfirmasi Lunas</a>
        <?php endif; ?>
        <a href="dashboard_owner.php" class="btn btn-outline">Kembali</a>
    </div>
</div>

</body>
</html>