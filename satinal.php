<?php
include("db_baglanti.php");
session_start();

//--------------------------------------------------------------------
if (isset($_POST["koltukID"])) {
    try {
        // Seçilen koltukları dizi olarak al
        $selectedSeatIDs = explode(",", $_POST["koltukID"]);
        $selectedSeatCount = count($selectedSeatIDs);
        // Koltuk fiyatını belirtin (örnek olarak 50 TL)
        $koltukFiyati = 50;

        $kullaniciBakiye = $_SESSION['bakiye'];

        // Toplam ücreti hesapla
        $toplamUcret = $selectedSeatCount * $koltukFiyati;

        // Kontrol: Kullanıcı bakiyesi yeterli değilse işlem yapma
        if ($kullaniciBakiye < $toplamUcret) {
            die(); // İşlemi durdur
        }


        $kullaniciBakiye-=$toplamUcret;

        // Seçilen her koltuğun durumunu güncelle ve bilet bilgilerini ekle
        foreach ($selectedSeatIDs as $koltukID) {
            $isReserved = 1; // veya 0, duruma göre değiştirin
        
            // Koltuk durumunu güncelle
            $stmt = $db->prepare("UPDATE koltuklar SET durum = :durum WHERE koltukID = :koltukID");
            $stmt->bindParam(':durum', $isReserved, PDO::PARAM_INT);
            $stmt->bindParam(':koltukID', $koltukID, PDO::PARAM_INT);
            $stmt->execute();
        
            $sorguKoltuk1 = $db->prepare("SELECT filmID , salonID , seans FROM koltuklar WHERE koltukID = :koltukID");
            $sorguKoltuk1->bindParam(':koltukID', $koltukID, PDO::PARAM_INT);
            $sorguKoltuk1->execute();
            $salonfilmbilgisi = $sorguKoltuk1->fetch(PDO::FETCH_ASSOC);
        
            // Bilet bilgilerini ekle
            $filmID= $salonfilmbilgisi['filmID'];
            $salonID= $salonfilmbilgisi['salonID'];
            $seans= $salonfilmbilgisi['seans'];
            $tarih = date("Y-m-d H:i:s"); // Şu anki tarihi alabilirsiniz
            $kullaniciId = $_SESSION['kullanici_id'];
            
            $stmt = $db->prepare("INSERT INTO biletler (kullaniciID, filmID, salonID, koltukID, tarih, seans) VALUES (:kullaniciID, :filmID, :salonID, :koltukID, :tarih,:seans)");
            $stmt->bindParam(':kullaniciID', $kullaniciId, PDO::PARAM_INT);
            $stmt->bindParam(':filmID', $filmID, PDO::PARAM_INT);
            $stmt->bindParam(':salonID', $salonID, PDO::PARAM_INT);
            $stmt->bindParam(':koltukID', $koltukID, PDO::PARAM_INT);
            $stmt->bindParam(':tarih', $tarih, PDO::PARAM_STR);
            $stmt->bindParam(':seans', $seans, PDO::PARAM_STR);
            $stmt->execute();
        }

        // Veritabanındaki bakiyeyi güncelle
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $kullaniciId = $_SESSION['kullanici_id'];
        $bakiyeGuncelleSorgusu = $db->prepare("UPDATE users SET bakiye = :bakiye WHERE id = :kullaniciId");
        $bakiyeGuncelleSorgusu->bindParam(':bakiye', $kullaniciBakiye, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->execute();
        echo "İşlem başarılı";
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
    $_SESSION['bakiye'] = $kullaniciBakiye;
}
?>