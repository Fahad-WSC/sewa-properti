<?php
require 'koneksi.php';
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$id_trx = $_GET['id'] ?? null;

if($id_trx){

    // 1. Ubah status transaksi jadi Lunas
    mysqli_query($conn, "UPDATE transaksi SET status = 'Lunas' WHERE id = '$id_trx'");

    // 2. Ambil properti_id dari transaksi
    $ambil = mysqli_query($conn, "SELECT properti_id FROM transaksi WHERE id = '$id_trx'");
    $data = mysqli_fetch_assoc($ambil);
    $properti_id = $data['properti_id'];

    // 3. Update status properti jadi TIDAK TERSEDIA
    mysqli_query($conn, "UPDATE properti SET status = 'TIDAK TERSEDIA' WHERE id = '$properti_id'");

    echo "<script>
            alert('Pembayaran dikonfirmasi. Properti sekarang tidak tersedia.');
            window.location='dashboard_owner.php';
          </script>";

} else {
    header("Location: dashboard_owner.php");
    exit;
}
?>