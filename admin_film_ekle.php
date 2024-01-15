<?php
session_start();
include("db_baglanti.php");

if (isset($_SESSION['hesap'])) {
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";
    $kullaniciAdi = $_SESSION['hesap'];
} else {
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}

if ($_SESSION['kullanici_rol'] != 1) {
    header("Location: kullanici.php");
    exit();
}
$uyariMesaji ='';
// Yeni film ekleme formu gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {

    $filmAdi = $_POST['filmAdi'];
    $yonetmen = $_POST['yonetmen'];
    $afis = $_POST['afis'];
    $aciklama = $_POST['aciklama'];
    $salonID = ($_POST['salonID'] !== '') ? $_POST['salonID'] : null;
    $tur = $_POST['tur'];

    // Eğer salonID boş değilse ve var olup olmadığını kontrol et
    if (!empty($salonID)) {
        $checkSalonQuery = $db->prepare("SELECT 1 FROM salonlar WHERE salonID = :salonID");
        $checkSalonQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
        $checkSalonQuery->execute();

        // Eğer salonID mevcut değilse, uyarı mesajını ayarla ve form işlemlerini durdur
        if (!$checkSalonQuery->fetchColumn()) {
            $uyariMesaji = "Belirtilen Salon ID bulunamadı.";
        } else {
            // Veritabanına yeni film ekle
            $insertQuery = $db->prepare("INSERT INTO filmler (filmAdi, yonetmen, afis, aciklama, salonID, tur) VALUES (:filmAdi, :yonetmen, :afis, :aciklama, :salonID, :tur)");
            $insertQuery->bindParam(':filmAdi', $filmAdi, PDO::PARAM_STR);
            $insertQuery->bindParam(':yonetmen', $yonetmen, PDO::PARAM_STR);
            $insertQuery->bindParam(':afis', $afis, PDO::PARAM_STR);
            $insertQuery->bindParam(':aciklama', $aciklama, PDO::PARAM_STR);
            $insertQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
            $insertQuery->bindParam(':tur', $tur, PDO::PARAM_STR);
            $insertQuery->execute();

            header("Location: admin_filmler.php");
            exit();
        }
    } else {
        // Eğer salonID boşsa, sadece veritabanına yeni film ekle
        $insertQuery = $db->prepare("INSERT INTO filmler (filmAdi, yonetmen, afis, aciklama, salonID, tur) VALUES (:filmAdi, :yonetmen, :afis, :aciklama, :salonID, :tur)");
        $insertQuery->bindParam(':filmAdi', $filmAdi, PDO::PARAM_STR);
        $insertQuery->bindParam(':yonetmen', $yonetmen, PDO::PARAM_STR);
        $insertQuery->bindParam(':afis', $afis, PDO::PARAM_STR);
        $insertQuery->bindParam(':aciklama', $aciklama, PDO::PARAM_STR);
        $insertQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
        $insertQuery->bindParam(':tur', $tur, PDO::PARAM_STR);
        $insertQuery->execute();

        header("Location: admin_filmler.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Film Ekle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .containergiris {
    max-width: 400px;
    margin:40px auto;
    background-color: grey;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
    display: flex;
    flex-direction: column;
    }

    label {
    margin-bottom: 5px;
    }

    input {
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    }
    .back-button {
            position: absolute;
            top: 80px;
            left: 500px;
            font-size: 30px;
            color:gold;
            cursor: pointer;
        }
    .back-button:hover {
        color: #6da8cd;
        transform: scale(1.2);
        transition: transform 0.3s;
    }
    .back-button:active{
        color:gold;
        transform: scale(1.0);
        transition: transform 0.3s;
    }
</style>
</head>
<body>
<!-- MENU -->
<!-- ... -->
<!-- MENU SONU -->

<div class="containergiris">
    <div class="back-button" onclick="history.go(-1);"><i class="fas fa-arrow-left"></i></div>
    <!-- Yeni Film Ekleme Formu -->
    <form method="POST" action="">
        <!-- Gerekli input alanları buraya eklenir -->
        <label>Film Adı:</label>
        <input type="text" name="filmAdi" required>

        <label>Salon ID :&nbsp;&nbsp;<?php if (!empty($uyariMesaji)) {echo "<span style='color: red;'>$uyariMesaji</span>";}
        ?></label>
        <input type="number" name="salonID">

        <label>Yönetmen :</label>
        <input type="text" name="yonetmen" required>

        <label>Tür :</label>
        <input type="text" name="tur" required>

        <label>Resim URL :</label>
        <input type="text" name="afis" required>

        <label>Açıklama :</label>
        <input type="text" name="aciklama" required><br>

        <input type="submit" class="formsubmit" name="ekle" value="Ekle">
    </form>
    <!-- Yeni Film Ekleme Formu SONU -->
</div>

</body>
</html>
