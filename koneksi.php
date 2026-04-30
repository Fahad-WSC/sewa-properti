<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = 'root';
$pass = 'root'; 
$db   = 'db_sewa_properti';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

class DBSessionHandler implements SessionHandlerInterface {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function open($path, $name): bool { return true; }
    public function close(): bool { return true; }
    public function read($id): string {
        $id = mysqli_real_escape_string($this->conn, $id);
        $res = mysqli_query($this->conn, "SELECT data FROM sessions WHERE id='$id' AND expires > NOW()");
        if ($row = mysqli_fetch_assoc($res)) return $row['data'];
        return '';
    }
    public function write($id, $data): bool {
        $id   = mysqli_real_escape_string($this->conn, $id);
        $data = mysqli_real_escape_string($this->conn, $data);
        mysqli_query($this->conn, "REPLACE INTO sessions (id, data, expires) VALUES ('$id', '$data', DATE_ADD(NOW(), INTERVAL 2 HOUR))");
        return true;
    }
    public function destroy($id): bool {
        $id = mysqli_real_escape_string($this->conn, $id);
        mysqli_query($this->conn, "DELETE FROM sessions WHERE id='$id'");
        return true;
    }
    public function gc($max_lifetime): int|false {
        mysqli_query($this->conn, "DELETE FROM sessions WHERE expires < NOW()");
        return mysqli_affected_rows($this->conn);
    }
}

$handler = new DBSessionHandler($conn);
session_set_save_handler($handler, true);

// Koneksi.php yang handle session_start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>