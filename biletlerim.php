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

// Veritabanından bilet verilerini çekme
$kullaniciId = $_SESSION['kullanici_id'];

// Biletleri çek ve tarihe göre artan sırala
$sorguBiletler = $db->prepare("SELECT biletID, filmAdi, salonAdi, bilet_tarihi, islem_tarihi, seans, koltuk
                               FROM biletler
                               WHERE biletler.kullaniciID = :kullaniciId
                               ORDER BY biletler.biletID DESC");
$sorguBiletler->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
$sorguBiletler->execute();
$biletler = $sorguBiletler->fetchAll(PDO::FETCH_ASSOC);

// İptal butonuna tıklanıldığında
if (isset($_POST['iptal'])) {
    $iptalBiletID = $_POST['iptal_bilet_id'];

    // Bilet bilgilerini çekme
    $sorguIptal = $db->prepare("SELECT * FROM biletler WHERE biletID = :biletID AND kullaniciID = :kullaniciId");
    $sorguIptal->bindParam(':biletID', $iptalBiletID, PDO::PARAM_INT);
    $sorguIptal->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
    $sorguIptal->execute();
    $iptalBilet = $sorguIptal->fetch(PDO::FETCH_ASSOC);

    if ($iptalBilet) {
        // Bakiye iadesi için tam bilet ücretini kullan
        $iptalBakiye = 50; // Tam bilet ücreti
        $kullaniciBakiye = $_SESSION['bakiye'] + $iptalBakiye;

        // Veritabanındaki bakiyeyi güncelle
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
            <th>Bilet Tarihi</th>
            <th>İşlem Tarihi</th>
            <th>İptal</th>
        </tr>
        </thead>
        <tbody>
                <?php foreach ($biletler as &$bilet): ?>
                    <tr>
                        <td><?php echo $bilet['biletID']; ?></td>
                        <td><?php echo $bilet['filmAdi']; ?></td>
                        <td><?php echo $bilet['salonAdi']; ?></td>
                        <td><?php echo $bilet['koltuk']; ?></td>
                        <td><?php echo $bilet['seans']; ?></td>
                        <td><?php echo $bilet['bilet_tarihi']; ?></td>
                        <td><?php echo $bilet['islem_tarihi']; ?></td>
                        <td>
                            <?php
                            date_default_timezone_set('Europe/Istanbul');
                            // İşlem tarihini ve şuanki tarihi al
                            $islemTarihi = new DateTime($bilet['bilet_tarihi']);
                            $suankiTarih = new DateTime();
                            $formatlanmisIslemTarihi = $islemTarihi->format('Y-m-d');
                            $formatlanmisSuankiTarih = $suankiTarih->format('Y-m-d');

                            // İşlem tarihi ve seans saatini karşılaştır
                            if ($formatlanmisIslemTarihi > $formatlanmisSuankiTarih || ($formatlanmisIslemTarihi == $formatlanmisSuankiTarih && $bilet['seans'] > date("H:i"))) {
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
