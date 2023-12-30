<?php
session_start();
include("db_baglanti.php");

if (!isset($_SESSION['hesap'])) {
    header("Location: girisyap.php");
    exit();
}
if (isset($_SESSION['hesap'])) {
    // Yönlendirme için hedef URL
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";
} else {
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}
if (!isset($biletler)) {
    $biletler = array(); // veya $biletler = null;
}

// Veritabanından bilet verilerini çekme
$kullaniciId = $_SESSION['kullanici_id'];
// Biletleri çek ve tarihe göre artan sırala
$sorguBiletler = $db->prepare("SELECT *
                               FROM biletler
                               WHERE biletler.kullaniciID = :kullaniciId
                               ORDER BY biletler.biletID DESC");
$sorguBiletler->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
$sorguBiletler->execute();
$biletler = $sorguBiletler->fetchAll(PDO::FETCH_ASSOC);



// Her bilet için film ve salon bilgilerini çek
foreach ($biletler as &$bilet) {
    $filmID = $bilet['filmID'];
    $salonID = $bilet['salonID'];
    // Film bilgisini çek
    $sorguFilm = $db->prepare("SELECT filmAdi FROM filmler WHERE filmID = :filmID");
    $sorguFilm->bindParam(':filmID', $filmID, PDO::PARAM_INT);
    $sorguFilm->execute();
    $filmBilgisi = $sorguFilm->fetch(PDO::FETCH_ASSOC);
    $bilet['filmAdi'] = $filmBilgisi['filmAdi'];

    // Salon bilgisini çek
    $sorguSalon = $db->prepare("SELECT salonAdi FROM salonlar WHERE salonID = :salonID");
    $sorguSalon->bindParam(':salonID', $salonID, PDO::PARAM_INT);
    $sorguSalon->execute();
    $salonBilgisi = $sorguSalon->fetch(PDO::FETCH_ASSOC);
    $bilet['salonAdi'] = $salonBilgisi['salonAdi'];

    // Koltuk bilgisini çek
    if (isset($bilet['koltukID'])) {
        $koltukID = $bilet['koltukID'];
        $sorguKoltuk = $db->prepare("SELECT siraNumarasi, koltukNumarasi, seans FROM koltuklar WHERE koltukID = :koltukID");
        $sorguKoltuk->bindParam(':koltukID', $koltukID, PDO::PARAM_INT);
        $sorguKoltuk->execute();
        $koltukBilgisi = $sorguKoltuk->fetch(PDO::FETCH_ASSOC);
        $bilet['siraNumarasi'] = $koltukBilgisi['siraNumarasi'];
        $bilet['koltukNumarasi'] = $koltukBilgisi['koltukNumarasi'];
        $bilet['seans'] = $koltukBilgisi['seans'];

        // Saat formatını değiştirme
        $bilet['seans'] = date("H:i", strtotime($koltukBilgisi['seans']));
    }
}



// İptal butonuna tıklanıldığında
if (isset($_POST['iptal'])) {
    $iptalBiletID = $_POST['iptal_bilet_id'];

    // Bilet bilgilerini çekme
    $sorguIptal = $db->prepare("SELECT * FROM biletler WHERE biletID = :biletID");
    $sorguIptal->bindParam(':biletID', $iptalBiletID, PDO::PARAM_INT);
    $sorguIptal->execute();
    $iptalBilet = $sorguIptal->fetch(PDO::FETCH_ASSOC);

    if ($iptalBilet) {
        // Koltuk durumunu güncelle
        $koltukIDSorgu = $db->prepare("UPDATE koltuklar SET durum = 0 WHERE koltukID = :koltukID");
        $koltukIDSorgu->bindParam(':koltukID', $iptalBilet['koltukID'], PDO::PARAM_INT);
        $koltukIDSorgu->execute();

        // Bakiye iadesi için tam bilet ücretini kullan
        $iptalBakiye = 50; // Tam bilet ücreti
        $kullaniciBakiye = $_SESSION['bakiye'] + $iptalBakiye;

        // Veritabanındaki bakiyeyi güncelle
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $kullaniciId = $_SESSION['kullanici_id'];
        $bakiyeGuncelleSorgusu = $db->prepare("UPDATE users SET bakiye = :bakiye WHERE id = :kullaniciId");
        $bakiyeGuncelleSorgusu->bindParam(':bakiye', $kullaniciBakiye, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->execute();

        // Session bakiyesini güncelle
        $_SESSION['bakiye'] = $kullaniciBakiye;

        // Bilet kaydını sil
        $silmeSorgusu = $db->prepare("DELETE FROM biletler WHERE biletID = :biletID");
        $silmeSorgusu->bindParam(':biletID', $iptalBiletID, PDO::PARAM_INT);
        $silmeSorgusu->execute();

        // Sayfayı yenile
        header("Location: biletlerim.php");
        exit();
    }
}


// Koltukları A1 Gibi Değiştirme
function koltukIDyiHarfeCevir($siraNumarasi, $koltukNumarasi)
{
    if ($koltukNumarasi <= 0 || $koltukNumarasi > 8) {
        return "Geçersiz Koltuk";
    }

    $harfler = range('A', 'Z'); // Alfabe harfleri
    $harfIndex = $koltukNumarasi - 1; // Sıra numarasını alfabede bir indeks haline getir

    // Koltuk numarasını harfle eşleştir
    $harf = $harfler[$harfIndex];
    $koltukHarfli = $harf . $siraNumarasi;

    return $koltukHarfli;
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Biletlerim Sayfası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        table {
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

<!-- Biletler Tablosu -->
<div class="biletlerimsinifi">
    <div class="ortala">
        <h1 style="color: gold;">Biletlerim</h1>
        <label class="altalta">
            <input type="checkbox" id="anaCheckbox">Tarihi Geçmiş Biletleri Gösterme
        </label>
    </div>
    <table>
        <thead>
        <tr>
            <th>Bilet ID</th>
            <th>Film Adı</th>
            <th>Salon Adı</th>
            <th>Koltuk</th>
            <th>Seans</th>
            <th>İşlem Tarihi</th>
            <th>İptal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($biletler as &$bilet): ?>
            <tr>
                <td><?php echo $bilet['biletID']; ?></td>
                <td>
                    <?php
                    if (array_key_exists('filmAdi', $bilet)) {
                        echo $bilet['filmAdi'];
                    } else {
                        echo "Film Adı Bulunamadı";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (array_key_exists('salonAdi', $bilet)) {
                        $salonAdi = $bilet['salonAdi'];
                        echo $salonAdi;
                    
                        // Buton eklemek
                        echo '<a href="' . $salonAdi . '.php" class="buton"><i class="fas fa-arrow-right slni"></i></a>';
                    } else {
                        echo "Salon Adı Bulunamadı";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (array_key_exists('siraNumarasi', $bilet) && array_key_exists('koltukNumarasi', $bilet)) {
                        echo '<strong>' . koltukIDyiHarfeCevir($bilet['siraNumarasi'], $bilet['koltukNumarasi']) . '</strong>';
                    } else {
                        echo "Koltuk Bilgisi Bulunamadı";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (array_key_exists('seans', $bilet)) {
                        echo $bilet['seans'];
                    } else {
                        echo "Seans Bilgisi Bulunamadı";
                    }
                    ?>
                </td>
                <td><?php echo $bilet['tarih']; ?></td>
                <td>
                <?php
                date_default_timezone_set('Europe/Istanbul');
                // İşlem tarihini ve şuanki tarihi al
                $islemTarihi = new DateTime($bilet['tarih']);
                $suankiTarih = new DateTime();
                $formatlanmişislemTarihi=$islemTarihi->format('Y-m-d');
                $formatlanmişsuankiTarih=$suankiTarih->format('Y-m-d');
                /*
                // İşlem tarihi ve seans saatini ekrana yazdır
                echo "İşlem Tarihi: " . $islemTarihi->format('Y-m-d') . "<br>";
                echo "Şuanki Tarih: " . $suankiTarih->format('Y-m-d') . "<br>";
                echo "Seans Saati: " . $bilet['seans'] . "<br>";
                echo date("H:i"). "<br>";
                */

                // İşlem tarihi ve seans saatini karşılaştır
                if ($formatlanmişislemTarihi > $formatlanmişsuankiTarih || ($formatlanmişislemTarihi == $formatlanmişsuankiTarih && $bilet['seans'] > date("H:i"))) {
                    // İptal edilebilir durumda ise
                    echo '
                        <form method="POST" action="">
                            <input type="hidden" name="iptal_bilet_id" value="' . $bilet['biletID'] . '">
                            <input type="submit" class="formsubmit1" name="iptal" value="İptal">
                        </form>';
                } else {
                    // İptal edilemez durumda ise
                    echo '<button class="tiklanamazbuton" disabled>İptal</button>';
                }
                ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('anaCheckbox').addEventListener('change', function() {
    var tiklanamazSatirlar = document.querySelectorAll('.tiklanamazbuton').forEach(function(button) {
        var satir = button.closest('tr'); // Satırı bul
        if (document.getElementById('anaCheckbox').checked) {
            // Ana checkbox seçili ise, tiklanamaz satırları göster
            satir.style.display = 'none';
        } else {
            // Ana checkbox seçili değilse, tiklanamaz satırları gizle
            satir.style.display = 'table-row';
        }
    });
});

// Sayfa yüklendiğinde checkbox durumunu kontrol et
anaCheckbox.checked = true; // Checkbox'u işaretli yap
anaCheckbox.dispatchEvent(new Event('change'));
</script>


</body>
</html>
