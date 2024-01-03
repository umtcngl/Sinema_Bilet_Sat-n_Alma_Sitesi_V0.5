<?php
include 'db_baglanti.php';

try {
    // films tablosundan gerekli bilgileri seç
    $stmt = $db->prepare("SELECT filmAdi, afis, aciklama , salonID FROM filmler ORDER BY salonID");
    $stmt->execute();
    // fetchAll kullanarak tüm sonuçları bir dizi olarak al
    $films = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // SalonID'si null olanları filtrele
    $filteredFilms = array_filter($films, function($film) {
        return $film['salonID'] !== null;
    });

    // Sonuçları JSON formatında geri döndür
    echo json_encode(array_values($filteredFilms));
} catch (PDOException $e) {
    // Hata durumunda hatayı yakala ve yazdır
    echo "Filmleri alma hatası: " . $e->getMessage();
}
?>
