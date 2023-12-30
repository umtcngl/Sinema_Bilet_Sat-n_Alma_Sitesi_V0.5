<?php
include 'db_baglanti.php';

try {
    // films tablosundan gerekli bilgileri seç
    $stmt = $db->prepare("SELECT filmAdi, afis, aciklama , salonID FROM filmler");
    $stmt->execute();

    // fetchAll kullanarak tüm sonuçları bir dizi olarak al
    $films = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sonuçları JSON formatında geri döndür
    echo json_encode($films);
} catch (PDOException $e) {
    // Hata durumunda hatayı yakala ve yazdır
    echo "Filmleri alma hatası: " . $e->getMessage();
}
?>
