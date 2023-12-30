<?php
session_start();
include("db_baglanti.php");

if ($_POST) {
    if (isset($_POST['cikis'])) {
        session_unset();
        session_destroy();
        header("Location: girisyap.php");
    }
}

if (isset($_SESSION['hesap'])) {
    // Yönlendirme için hedef URL
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";

    $kullaniciAdi = $_SESSION['hesap'];

    // Veritabanından kullanıcı bilgilerini çekme
    $sorgu = $db->prepare("SELECT id, bakiye FROM users WHERE kullaniciadi = :kullaniciadi");
    $sorgu->bindParam(':kullaniciadi', $kullaniciAdi);
    $sorgu->execute();

    // Fetch modu kullanarak veriyi çekme
    $sonuc = $sorgu->fetch(PDO::FETCH_ASSOC);

    // Kullanıcı bilgilerini $_SESSION'a at
    $_SESSION['kullanici_id'] = $sonuc['id'];
    $_SESSION['bakiye'] = $sonuc['bakiye'];

    $kullaniciId = $_SESSION['kullanici_id'];
    $bugun = date("Y-m-d");

    // Kullanıcının son giriş tarihini al
    $sorgu2 = $db->prepare("SELECT son_giris_tarihi FROM users WHERE id = :kullaniciId");
    $sorgu2->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
    $sorgu2->execute();
    $sonuc = $sorgu2->fetch(PDO::FETCH_ASSOC);

    // Eğer sorgu sonucu boş değilse devam et
    if ($sonuc) {
        $sonGirisTarihi = $sonuc['son_giris_tarihi'];

        // Eğer bugün ilk girişi ise günlük bonus ekleyin
        if ($sonGirisTarihi != $bugun) {
            $bonusMiktari = 50; // İstediğiniz bonus miktarını ayarlayın

            // Bonusu ekleyin ve son giriş tarihini güncelleyin
            $bonusEkleSorgusu = $db->prepare("UPDATE users SET bakiye = bakiye + :bonusMiktari, son_giris_tarihi = :bugun WHERE id = :kullaniciId");
            $bonusEkleSorgusu->bindParam(':bonusMiktari', $bonusMiktari, PDO::PARAM_INT);
            $bonusEkleSorgusu->bindParam(':bugun', $bugun);
            $bonusEkleSorgusu->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
            $bonusEkleSorgusu->execute();

            // Session içindeki bakiyeyi güncelle
            $_SESSION['bakiye'] += $bonusMiktari;
        }
    }
    $kullaniciBakiye = $_SESSION['bakiye'];
} else {
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}


/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

if (isset($_SESSION['hesap']) && isset($_POST['hesap_sil'])) {
    // Giriş başarılı ise
    $kullaniciAdi = $_SESSION['hesap'];

    $sorgu = $db->prepare("SELECT id FROM users WHERE kullaniciadi = :kullaniciadi");
    $sorgu->bindParam(':kullaniciadi', $kullaniciAdi);
    $sorgu->execute();

    $sonuc = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($sonuc) {
        // Kullanıcı bulundu
        $kullaniciID = $sonuc['id'];

        // Hesabı silme işlemi
        $silmeSorgusu = "DELETE FROM users WHERE id = :kullaniciID";
        $silmeSorgu = $db->prepare($silmeSorgusu);
        $silmeSorgu->bindParam(':kullaniciID', $kullaniciID, PDO::PARAM_INT);

        try {
            $silmeSorgu->execute();
            session_unset();
            session_destroy();
            header("Location: girisyap.php");
        } catch (PDOException $e) {
            echo "Hesap silme hatası: " . $e->getMessage();
            error_log("PDO Hata: " . $e->getMessage(), 0);
        }
    } else {
        echo "Kullanıcı bulunamadı.";
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
    <title>Kullanıcı Sayfası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    <div id="cikisdiv" class="cikisyap">
        <form action="" method="POST">
            <span style="font-size: 30px;color: white;">Hoş Geldin&nbsp;&nbsp;<span style="font-size: 30px; color: gold;">
                <?php echo isset($_SESSION['hesap']) ? $_SESSION['hesap'] : ''; ?>
            </span> !!!</span><br><br><br>
            <input type="submit" class="formsubmit" value="Çıkış Yap" name="cikis" id="cikisyap">
            <br><br><br>
            <input type="button" class="formsubmit" value="Biletlerim" onclick="location.href='biletlerim.php';">
            <br><br><br>
            <div id="silmeButonDiv">
                <input type="button" onclick="silmebutonlarinigöster()" class="formsubmit1" value="Hesabımı Sil !!!">
            </div>
            <br><br><br><br>

            <div id="eminmisindivi" class="eminmisindivi" style="display:none">
            <input type="submit" name="hesap_sil" class="formsubmit1" value="Hesabımı Silin">
            <br><br><br>
            <input type="button" onclick="silmebutonlarinigizle()" class="formsubmit" value="İptal">
            </div>
        </form>
    </div>
</div>
<script>
        window.onload = function() {
            var kullaniciAdi = "<?php echo $_SESSION['hesap'];?>";

            // Kullanıcı adı "admin" ise butonu gizle
            if (kullaniciAdi === "admin") {
                document.getElementById("silmeButonDiv").style.display = "none";
            }
        }
        function silmebutonlarinigöster(){
            var eminmisindivi = document.getElementById("eminmisindivi");

            eminmisindivi.style.display = "block";

            alert("DİKKAT: Bütün bakiyenizle birlikte hesabınız silinecektir!");
            
        }
        function silmebutonlarinigizle(){
            var eminmisindivi = document.getElementById("eminmisindivi");;
            eminmisindivi.style.display = "none";
            
        }

    </script>
</body>
</html>
