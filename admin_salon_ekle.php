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

// Yeni salon ekleme formu gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    // En yüksek salonID'yi bul
    $maxIDQuery = $db->query("SELECT MAX(salonID) AS maxID FROM salonlar");
    $maxIDResult = $maxIDQuery->fetch(PDO::FETCH_ASSOC);
    $lastInsertId = $maxIDResult['maxID'];

    // Yeni salonun ID'sini belirle
    $salonID = $lastInsertId + 1;

    $salonAdi =  "Salon" . $salonID;
    $sirasayisi = $_POST['sirasayisi'];
    $sutunsayisi = $_POST['sutunsayisi'];
    $ucret = $_POST['ucret'];

    // Veritabanına yeni salon ekle
    $insertQuery = $db->prepare("INSERT INTO salonlar VALUES (:salonID,:salonAdi, :sirasayisi, :sutunsayisi, :ucret)");
    $insertQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
    $insertQuery->bindParam(':salonAdi', $salonAdi, PDO::PARAM_STR);
    $insertQuery->bindParam(':sirasayisi', $sirasayisi, PDO::PARAM_INT);
    $insertQuery->bindParam(':sutunsayisi', $sutunsayisi, PDO::PARAM_INT);
    $insertQuery->bindParam(':ucret', $ucret, PDO::PARAM_INT);
    $insertQuery->execute();

    // Salonun sayfa adını oluştur (örneğin salon1.php)
    $sayfaAdi = "Salon" . $salonID . ".php";

    // Oluşturulan sayfa adıyla bir PHP dosyası oluştur
    $dosyaIcerigi =file_get_contents("Salon1.php");

    file_put_contents($sayfaAdi, $dosyaIcerigi);

    header("Location: admin_salonlar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Salon Ekle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .containergiris {
        max-width: 400px;
        margin:100px auto;
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
        top: 140px;
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
    <!-- Yeni Salon Ekleme Formu -->
    <form method="POST" action="">
        <!-- Gerekli input alanları buraya eklenir -->

        <label>Sıra Sayısı :</label>
        <input type="number" name="sirasayisi" required>

        <label>Sütun Sayısı :</label>
        <input type="number" name="sutunsayisi" required>

        <label>Ücret :</label>
        <input type="text" name="ucret" required><br>

        <input type="submit" class="formsubmit" name="ekle" value="Ekle">
    </form>
    <!-- Yeni Salon Ekleme Formu SONU -->
</div>

</body>
</html>
