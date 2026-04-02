<?php
session_start();

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Penyewa</title>
</head>
<body style="font-family: Arial; padding: 50px;">
    <h1>Selamat Datang, <?php echo $_SESSION['nama']; ?>!</h1>
    <br>
    <a href="logout.php" style="color: red;">Logout</a>
</body>
</html>