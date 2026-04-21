<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$id_trx = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if($id_trx && $status == 'Lunas') {
    $update = mysqli_query($conn, "UPDATE transaksi SET status = 'Lunas' WHERE id = '$status'");
    
    if($update) {
        echo "<script>
                alert('Pembayaran berhasil dikonfirmasi! Transaksi kini berstatus Lunas.');
                window.location='dashboard_owner.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengupdate status!');
                window.location='dashboard_owner.php';
              </script>";
    }
} else {
    header("Location: dashboard_owner.php");
    exit;
}
?>