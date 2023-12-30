<?php
include("db_baglanti.php");

$stmt = $db->prepare("SELECT koltukID FROM koltuklar WHERE durum = 1");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($result);
?>
