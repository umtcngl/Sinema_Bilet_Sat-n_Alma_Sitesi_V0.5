<?php
include("db_baglanti.php");
session_start();

if (isset($_POST["koltukID"]) && isset($_POST["sayfaAdi"]) && isset($_POST["seans"]) && isset($_POST["secilentarih"]) && isset($_POST["filmAdi"]) && isset($_POST["ucret"]) ) {
    try {
        $selectedSeatIDs = explode(",", $_POST["koltukID"]);
        $selectedSeatCount = count($selectedSeatIDs);

        $koltukFiyati = $_POST["ucret"];
        $kullaniciBakiye = $_SESSION['bakiye'];

        $toplamUcret = $selectedSeatCount * $koltukFiyati;

        if ($kullaniciBakiye < $toplamUcret) {
            die(); // İşlemi durdur
        }

        $kullaniciBakiye -= $toplamUcret;

        foreach ($selectedSeatIDs as $koltukID) {
            $salonAdi = $_POST['sayfaAdi'];
            $filmAdi = $_POST['filmAdi'];
            
            $secilentarih = $_POST["secilentarih"];
            $seans = $_POST["seans"];
            $tarih = date("Y-m-d H:i:s"); // Şu anki tarihi alabilirsiniz
            $kullaniciId = $_SESSION['kullanici_id'];

            $stmt = $db->prepare("INSERT INTO biletler (kullaniciID, filmAdi, salonAdi, bilet_tarihi, islem_tarihi, seans, koltuk,odenenucret) VALUES (:kullaniciID, :filmAdi, :salonAdi, :bilet_tarihi, :islem_tarihi, :seans, :koltukID,:odenenucret)");
            $stmt->bindParam(':kullaniciID', $kullaniciId, PDO::PARAM_INT);
            $stmt->bindParam(':filmAdi', $filmAdi, PDO::PARAM_STR);
            $stmt->bindParam(':salonAdi', $salonAdi, PDO::PARAM_STR);
            $stmt->bindParam(':bilet_tarihi', $secilentarih, PDO::PARAM_STR);
            $stmt->bindParam(':islem_tarihi', $tarih, PDO::PARAM_STR);
            $stmt->bindParam(':seans', $seans, PDO::PARAM_STR);
            $stmt->bindParam(':koltukID', $koltukID, PDO::PARAM_STR);
            $stmt->bindParam(':odenenucret', $koltukFiyati, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Veritabanındaki bakiyeyi güncelle
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $kullaniciId = $_SESSION['kullanici_id'];
        $bakiyeGuncelleSorgusu = $db->prepare("UPDATE users SET bakiye = :bakiye WHERE id = :kullaniciId");
        $bakiyeGuncelleSorgusu->bindParam(':bakiye', $kullaniciBakiye, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->execute();

        $_SESSION['bakiye'] = $kullaniciBakiye;

    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
}
?>
