<?php
include("db_baglanti.php");
session_start();

try {
    // Seans ve tarih bilgilerini al
    $seansValue = isset($_POST['seans']) ? $_POST['seans'] : null;
    $selectedTarih = isset($_POST['secilentarih']) ? $_POST['secilentarih'] : null;
    $salonAdi = isset($_POST['sayfaAdi']) ? $_POST['sayfaAdi'] : null;

    if ($seansValue && $selectedTarih && $salonAdi) {
        // Seçilen tarih ve seans için rezerve edilmiş koltukları getir
        $stmt = $db->prepare("SELECT koltuk FROM biletler WHERE seans = :seans AND bilet_tarihi = :secilentarih AND salonAdi = :salonAdi");
        $stmt->bindParam(':seans', $seansValue, PDO::PARAM_STR); // PDO::PARAM_STR ekledik
        $stmt->bindParam(':secilentarih', $selectedTarih, PDO::PARAM_STR);
        $stmt->bindParam(':salonAdi', $salonAdi, PDO::PARAM_STR);
        $stmt->execute();
        $reservedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($reservedSeats) {
            // Eğer rezerve edilmiş koltuklar varsa, JSON formatında gönder
            echo json_encode($reservedSeats);
        } else {
            // Eğer rezerve edilmiş kontrol için gerekli bilgileri gönder.
            echo json_encode([$seansValue,$selectedTarih,$salonAdi]);
        }
    } else {
        // Eğer seans veya tarih bilgisi eksikse, hata mesajı gönder
        echo json_encode(["error" => "Seans, tarih veya salon bilgisi eksik"]);
    }
} catch (PDOException $e) {
    // PDO istisnasını ele al
    echo json_encode(["error" => "Veritabanı hatası: " . $e->getMessage()]);
}
?>
