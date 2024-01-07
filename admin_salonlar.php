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

// Tüm salonları çek
$salonlarsorgusu = $db->prepare("SELECT * FROM salonlar order by salonID");
$salonlarsorgusu->execute();
$salonlar = $salonlarsorgusu->fetchAll(PDO::FETCH_ASSOC);


// Silme formu gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sil'])) {
    $silSalonID = $_POST['silSalonID'];

    // Veritabanından salonu sil
    $deleteQuery = $db->prepare("DELETE FROM salonlar WHERE salonID = :salonID");
    $deleteQuery->bindParam(':salonID', $silSalonID, PDO::PARAM_INT);
    $deleteQuery->execute();

    // Silinen salonun sayfa adını oluştur (örneğin salon1.php)
    $silinenSayfaAdi = "Salon" . $silSalonID . ".php";

    // Eğer sayfa varsa, sil
    if (file_exists($silinenSayfaAdi)) {
        unlink($silinenSayfaAdi);
    }

    // Seansları sil
    $deleteSeansQuery = $db->prepare("DELETE FROM seanslar WHERE salonID = :salonID");
    $deleteSeansQuery->bindParam(':salonID', $silSalonID, PDO::PARAM_INT);
    $deleteSeansQuery->execute();

    // Film tablosunda, silinen salonID'ye sahip olan filmlerin salonID değerini null yap
    $updateFilmlerQuery = $db->prepare("UPDATE filmler SET salonID = NULL WHERE salonID = :salonID");
    $updateFilmlerQuery->bindParam(':salonID', $silSalonID, PDO::PARAM_INT);
    $updateFilmlerQuery->execute();

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
    <title>Admin Salonlar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        table {
            color:white;
            margin: 0 auto; /* Tabloyu yatayda ortala */
            width: 80%; /* Tablo genişliğini ayarla, isteğe bağlı */
            border-collapse: collapse; /* Tablo kenarlıklarını birleştir */
            border:none;
            border-radius:40px;
            overflow: hidden;
        }

        th, td {
            padding: 8px; /* Hücre iç boşluğu */
            text-align: center; /* Metni ortaya hizala */
        }
        th{
            color:gold;
        }
        tr:nth-child(even) {
        background-color: rgba(100, 100, 100, 0.5); /* Gri tonunda çift sıradaki satır arkaplan rengi */
        }

        tr:nth-child(odd) {
            background-color: rgba(50, 50, 50, 0.5); /* Beyaz tonunda tek sıradaki satır arkaplan rengi */
        }
        .slni{
            color:white;
            margin-left:10px
        }
        .slni:hover{
            color:gold;
            transform: scale(1.7);
        }
        .slni:active{
            color:#3b82b1;
            transform: scale(1.4);
        }
        .eklebuton{
            position:absolute;
            left:150px;
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
<div class="biletlerimsinifi">
<div class="ortala">
<input type="button" class="formsubmit eklebuton" value="Salon Ekle" onclick="location.href='admin_salon_ekle.php';">
    <h1 style="color: gold;">Salonlar</h1>
</div>
    <!-- Kullanıcı Listesi Tablosu -->
<table>
    <thead>
        <tr>
            <th>Salon ID</th>
            <th>Salon Adı</th>
            <th>Sıra Sayisi</th>
            <th>Sutün Sayisi</th>
            <th>Ücret</th>
            <th>Düzenle</th>
            <th>Sil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($salonlar as $salon): ?>
            <tr>
                <td><?php echo $salon['salonID']; ?></td>
                <td><?php echo $salon['salonAdi'];
                 echo '<a href="' . $salon['salonAdi'] . '.php" class="buton"><i class="fas fa-arrow-right slni"></i></a>';
                 ?></td>
                <td><?php echo $salon['sirasayisi']; ?></td>
                <td><?php echo $salon['sutunsayisi']; ?></td>
                <td><?php echo $salon['ucret']; ?></td>
                <td>
                    <form method="POST" action="admin_salon_edit.php">
                        <input type="hidden" name="edit_salon_id" value="<?php echo $salon['salonID']; ?>">
                        <input type="submit" class="formsubmit" name="düzenle" value="Düzenle">
                    </form>
                </td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="silSalonID" value="<?php echo $salon['salonID']; ?>">
                        <input type="submit" class="formsubmit1" name="sil" value="Sil">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Kullanıcı Listesi Tablosu SONU -->
</div>

</body>
</html>
