<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = 'root';
$pass = 'root';
$db   = 'db_sewa_properti';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>