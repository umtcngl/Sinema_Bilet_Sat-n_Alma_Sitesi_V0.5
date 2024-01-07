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
$salonlarsorgusu = $db->prepare("SELECT * FROM salonlar");
$salonlarsorgusu->execute();
$salonlar = $salonlarsorgusu->fetchAll(PDO::FETCH_ASSOC);

// Mevcut seansları çek
$seanslarsorgusu = $db->prepare("SELECT * FROM seanslar order by salonID");
$seanslarsorgusu->execute();
$seanslar = $seanslarsorgusu->fetchAll(PDO::FETCH_ASSOC);

// Mevcut salonID'leri listesi
$mevcutSalonIDListesi = array_column($salonlar, 'salonID');

// Tabloda olan salonID'leri listesi
$tabloSalonIDListesi = array_column($seanslar, 'salonID');

// Yeni salonlar tespit edilir
$yeniSalonlar = array_diff($mevcutSalonIDListesi, $tabloSalonIDListesi);

// Yeni salonları seanslar tablosuna eklenir
foreach ($yeniSalonlar as $yeniSalonID) {
    $ekleQuery = $db->prepare("INSERT INTO seanslar (salonID) VALUES (:salonID)");
    $ekleQuery->bindParam(':salonID', $yeniSalonID, PDO::PARAM_INT);
    $ekleQuery->execute();
}

// Güncellenmiş seansları çek
$seanslarsorgusu = $db->prepare("SELECT * FROM seanslar order by salonID");
$seanslarsorgusu->execute();
$seanslar = $seanslarsorgusu->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Admin Seanslar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        table {
            color: white;
            margin: 0 auto; /* Tabloyu yatayda ortala */
            width: 80%; /* Tablo genişliğini ayarla, isteğe bağlı */
            border-collapse: collapse; /* Tablo kenarlıklarını birleştir */
            border: none;
            border-radius: 40px;
            overflow: hidden;
        }

        th, td {
            padding: 8px; /* Hücre iç boşluğu */
            text-align: center; /* Metni ortaya hizala */
        }

        th {
            color: gold;
        }

        tr:nth-child(even) {
            background-color: rgba(100, 100, 100, 0.5); /* Gri tonunda çift sıradaki satır arkaplan rengi */
        }

        tr:nth-child(odd) {
            background-color: rgba(50, 50, 50, 0.5); /* Beyaz tonunda tek sıradaki satır arkaplan rengi */
        }

        .slni {
            color: white;
            margin-left: 10px
        }

        .slni:hover {
            color: gold;
            transform: scale(1.7);
        }

        .slni:active {
            color: #3b82b1;
            transform: scale(1.4);
        }
        .flexcontainer {
        display: flex;
        flex-wrap: wrap;
        
        }

        .flexcontainer > div {

            flex: 1;
            margin: 10px;
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
        <h1 style="color: gold;">Seanslar</h1>
    </div>
    <table>
        <thead>
        <tr>
            <th>Salon ID</th>
            <th>Salon Adı</th>
            <th>Seanslar</th>
            <th>Düzenle</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($seanslar as $seans): ?>
            <tr>
                <td><?php echo $seans['salonID']; ?></td>
                <td><?php echo "Salon" . $seans['salonID']; ?></td>
                <td>
                    <div class='flexcontainer'>
                        <?php
                        $seansCount = 0;
                        foreach ($seans as $key => $value) {
                            // Seans sütunlarını kontrol et
                            if (strpos($key, 'seans') !== false && !empty($value)) {
                                echo "<div>";
                                echo $value."<hr>";
                                echo "</div>";
                                $seansCount++;

                                // Her 4 seansın sonunda yeni bir div başlat
                                if ($seansCount % 4 == 0) {
                                    echo "</div><div class='flexcontainer' style='            border-radius:25px;color:gold'>";
                                }
                            }
                        }
                        ?>
                    </div>
                </td>

                <td>
                    <form method="POST" action="admin_seans_edit.php">
                        <input type="hidden" name="edit_salon_id" value="<?php echo $seans['salonID']; ?>">
                        <input type="submit" class="formsubmit" name="düzenle" value="Düzenle">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
