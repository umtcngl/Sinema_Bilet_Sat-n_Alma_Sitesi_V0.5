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

// Düzenlenecek kullanıcının ID'sini al
$filmID = isset($_POST['edit_film_id']) ? $_POST['edit_film_id'] : null;

// Düzenlenecek kullanıcının bilgilerini çek
if ($filmID) {
    $filmsorgusu = $db->prepare("SELECT * FROM filmler WHERE filmID = :filmID");
    $filmsorgusu->bindParam(':filmID', $filmID, PDO::PARAM_INT);
    $filmsorgusu->execute();
    $editFilm = $filmsorgusu->fetch(PDO::FETCH_ASSOC);

    if (!$editFilm) {
        header("Location: admin_salonlar.php");
        exit();
    }
} else {

    header("Location: admin_salonlar.php");
    exit();
}

$uyariMesaji = ""; // Uyarı mesajını tutacak değişken

// edit formu gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    // Formdan gelen veriler
    $filmID = $_POST['edit_film_id'];
    $filmAdi = $_POST['filmAdi'];
    $yonetmen = $_POST['yonetmen'];
    $afis = $_POST['afis'];
    $aciklama = $_POST['aciklama'];
    $salonID = ($_POST['salonID'] !== '') ? $_POST['salonID'] : null;

    // Eğer salonID boş değilse ve var olup olmadığını kontrol et
    if (!empty($salonID)) {
        $checkSalonQuery = $db->prepare("SELECT 1 FROM salonlar WHERE salonID = :salonID");
        $checkSalonQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
        $checkSalonQuery->execute();

        $checkfilm = $db->prepare("SELECT * FROM filmler WHERE salonID = :salonID LIMIT 1");
        $checkfilm->bindParam(':salonID', $salonID, PDO::PARAM_INT);
        $checkfilm->execute();
        if($checkfilm->fetch()){
            $uyariMesaji = "Belirtilen Salon ID Başka Bir Filme Atanmış.";
        }else{
            // Eğer salonID mevcut değilse, uyarı mesajını ayarla ve form işlemlerini durdur
            if (!$checkSalonQuery->fetchColumn()) {
                $uyariMesaji = "Belirtilen Salon ID bulunamadı.";
            }else{
                // Veritabanında güncelleme yap
            $updateQuery = $db->prepare("UPDATE filmler SET filmAdi = :filmAdi, yonetmen = :yonetmen, afis = :afis, aciklama = :aciklama, salonID = :salonID WHERE filmID = :filmID");
            $updateQuery->bindParam(':filmID', $filmID, PDO::PARAM_INT);
            $updateQuery->bindParam(':filmAdi', $filmAdi, PDO::PARAM_STR);
            $updateQuery->bindParam(':yonetmen', $yonetmen, PDO::PARAM_STR);
            $updateQuery->bindParam(':afis', $afis, PDO::PARAM_STR);
            $updateQuery->bindParam(':aciklama', $aciklama, PDO::PARAM_STR);
            $updateQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
            $updateQuery->execute();
            // Başarıyla güncellendiyse kullanıcıları listeleme sayfasına yönlendir
            header("Location: admin_filmler.php");
            exit();
            }
        }
        
    }else{
        // Veritabanında güncelleme yap
        $updateQuery = $db->prepare("UPDATE filmler SET filmAdi = :filmAdi, yonetmen = :yonetmen, afis = :afis, aciklama = :aciklama, salonID = :salonID WHERE filmID = :filmID");
        $updateQuery->bindParam(':filmID', $filmID, PDO::PARAM_INT);
        $updateQuery->bindParam(':filmAdi', $filmAdi, PDO::PARAM_STR);
        $updateQuery->bindParam(':yonetmen', $yonetmen, PDO::PARAM_STR);
        $updateQuery->bindParam(':afis', $afis, PDO::PARAM_STR);
        $updateQuery->bindParam(':aciklama', $aciklama, PDO::PARAM_STR);
        $updateQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
        $updateQuery->execute();
        // Başarıyla güncellendiyse kullanıcıları listeleme sayfasına yönlendir
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
    <title>Filmleri Düzenle</title>
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
            top: 120px;
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
<header class="header">
    <div class="btn1"><a href="index.php"><i class="fas fa-home"></i>ANA SAYFA</a></div>
    <div class="btn1"><a href="iletisim.php"><i class="fas fa-address-card"></i>HAKKIMIZDA</a></div>
    <div class="btn1"><a href="<?php echo isset($_SESSION['hesap']) ? $hedefURL : 'girisyap.php'; ?>">
        <i class="<?php echo $baglantiIkon; ?>"></i>
        <span class="girisyapyazisindex"><?php echo $baglantiMetni; ?></span></a>
    </div>

    <div class="btn1 bakiye-div"><a href="bakiye.php"><i class="fas fa-coins"></i>Bakiyeniz: <span style="color: gold;"><?php echo $_SESSION['bakiye']; ?></span></a></div>
</header>
<!-- MENU SONU -->

<div class="containergiris">
<div class="back-button" onclick="location.href='admin_filmler.php';"><i class="fas fa-arrow-left"></i></div>
    <!-- Kullanıcı Düzenleme Formu -->
    <form method="POST" action="">
        <input type="hidden" name="edit_film_id" value="<?php echo $editFilm['filmID']; ?>">

        <label>Film Adı:</label>
        <input type="text" name="filmAdi" value="<?php echo $editFilm['filmAdi']; ?>" required>

        <label>Salon ID :&nbsp;&nbsp;<?php if (!empty($uyariMesaji)) {echo "<span style='color: red;'>$uyariMesaji</span>";}
        ?></label>
        <input type="number" name="salonID" value="<?php echo $editFilm['salonID']; ?>">

        <label>Yönetmen :</label>
        <input type="text" name="yonetmen" value="<?php echo $editFilm['yonetmen']; ?>" required>

        <label>Tür :</label>
        <input type="text" name="tur" value="<?php echo $editFilm['tur']; ?>" required>

        <label>Resim URL :</label>
        <input type="text" name="afis" value="<?php echo $editFilm['afis']; ?>" required>

        <label>Açıklama :</label>
        <input type="text" name="aciklama" value="<?php echo $editFilm['aciklama']; ?>" required><br>


        <input type="submit" class="formsubmit" name="edit" value="Kaydet">
    </form>
    <!-- Kullanıcı Düzenleme Formu SONU -->
</div>

</body>
</html>
