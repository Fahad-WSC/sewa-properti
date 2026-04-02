<?php
session_start();

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrator</title>
</head>
<body style="font-family: Arial; padding: 50px; background-color: #f4f4f4;">
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px;">
        <h1 style="color: #333;">Selamat Datang, Admin <?php echo $_SESSION['nama']; ?>!</h1>
        <br><br>
        <a href="logout.php" style="background: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Logout</a>
    </div>
</body>
</html>