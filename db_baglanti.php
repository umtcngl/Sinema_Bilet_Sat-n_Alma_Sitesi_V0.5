<?php
$servername = "localhost";
$dbname = "sinema_bilet";
$Name = "root";
$Pass = "";

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname;", $Name, $Pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>
