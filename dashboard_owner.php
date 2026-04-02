<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$owner_id = $_SESSION['user_id'];

$query = "SELECT * FROM properti WHERE owner_id = '$owner_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner - Sewa Properti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        
        .navbar {
            background-color: #333;
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

        .btn-tambah {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background: #218838;
        }

        /* Styling Tabel */
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

        .action-links a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 5px;
        }

        .btn-edit { background: #ffc107; color: #333; }
        .btn-hapus { background: #dc3545; color: white; }
        
        .kosong {
            text-align: center;
            padding: 30px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Panel Owner</div>
        <div class="menu">
            <span>Halo, <?php echo $_SESSION['nama']; ?>!</span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="header-dashboard">
            <h2>Properti Saya</h2>
            <a href="tambah_properti.php" class="btn-tambah">+ Tambah Properti</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Properti</th>
                    <th>Tipe</th>
                    <th>Harga Sewa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='5' class='kosong'>Belum ada properti yang ditambahkan.</td></tr>";
                } else {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                        $tipe = ucfirst($row['tipe']);
                        
                        echo "<tr>
                                <td>{$no}</td>
                                <td><strong>{$row['nama_properti']}</strong></td>
                                <td>{$tipe}</td>
                                <td>{$harga_rp}</td>
                                <td class='action-links'>
                                    <a href='#' class='btn-edit' onclick=\"alert('Fitur Edit belum dibuat')\">Edit</a>
                                    <a href='#' class='btn-hapus' onclick=\"alert('Fitur Hapus belum dibuat')\">Hapus</a>
                                </td>
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