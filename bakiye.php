<?php
session_start();
include("db_baglanti.php");

// Kullanıcı girişi kontrolü
if (isset($_SESSION['hesap'])) {
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";
} else {
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}

// Oyun için sayıları oluştur
if (!isset($_SESSION['ortadakiSayi']) || !isset($_SESSION['hafizaSayi'])) {
    $_SESSION['ortadakiSayi'] = rand(1, 20);
    $_SESSION['hafizaSayi'] = rand(1, 20);

    while ($_SESSION['ortadakiSayi'] == $_SESSION['hafizaSayi']) {
        $_SESSION['ortadakiSayi'] = rand(1, 20);
        $_SESSION['hafizaSayi'] = rand(1, 20);
    }
}
$mesaj = ''; // Mesajı saklamak için boş bir string
// Önceki sayıları oturumdan al
$oncekiOrtadakiSayi = isset($_SESSION['oncekiOrtadakiSayi']) ? $_SESSION['oncekiOrtadakiSayi'] : 0;
$oncekiHafizaSayi = isset($_SESSION['oncekiHafizaSayi']) ? $_SESSION['oncekiHafizaSayi'] : 0;
$tahminUcreti = 50;
// Tahmin işlemi kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tahmin'])) {
    $kullaniciBakiye = $_SESSION['bakiye'];
    $tahmin = $_POST['tahmin'];

    if ($kullaniciBakiye >= $tahminUcreti) {
        $kullaniciBakiye -= $tahminUcreti;

        if (($_SESSION['ortadakiSayi'] < $_SESSION['hafizaSayi'] && $tahmin == 'Büyük') || ($_SESSION['ortadakiSayi'] > $_SESSION['hafizaSayi'] && $tahmin == 'Küçük')) {
            // Kazandınız
            $kullaniciBakiye += 2 * $tahminUcreti;
            $mesaj = "{$tahminUcreti} TL KAZANDINIZ!!! Ortadaki: {$_SESSION['ortadakiSayi']}, Hafızadaki sayı: {$_SESSION['hafizaSayi']}, Tahmininiz : $tahmin";
        } else {
            // Kaybettiniz
            $mesaj = "{$tahminUcreti} TL Kaybettiniz. Ortadaki: {$_SESSION['ortadakiSayi']}, Hafızadaki sayı: {$_SESSION['hafizaSayi']}, Tahmininiz : $tahmin";
        }

        // Veritabanındaki bakiyeyi güncelle
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $kullaniciId = $_SESSION['kullanici_id'];
        $bakiyeGuncelleSorgusu = $db->prepare("UPDATE users SET bakiye = :bakiye WHERE id = :kullaniciId");
        $bakiyeGuncelleSorgusu->bindParam(':bakiye', $kullaniciBakiye, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->bindParam(':kullaniciId', $kullaniciId, PDO::PARAM_INT);
        $bakiyeGuncelleSorgusu->execute();

        // Yeni sayıları oturuma kaydet
        $_SESSION['oncekiOrtadakiSayi'] = $_SESSION['ortadakiSayi'];
        $_SESSION['oncekiHafizaSayi'] = $_SESSION['hafizaSayi'];

        // Yeni sayıları oluştur
        $_SESSION['ortadakiSayi'] = rand(1, 20);
        $_SESSION['hafizaSayi'] = rand(1, 20);

        while ($_SESSION['ortadakiSayi'] == $_SESSION['hafizaSayi']) {
            $_SESSION['ortadakiSayi'] = rand(1, 20);
            $_SESSION['hafizaSayi'] = rand(1, 20);
        }
    } else {
        $mesaj= "Yetersiz bakiye! Tahminde bulunmak için yeterli paranız yok.";
    }

    // Kullanıcının güncellenmiş bakiyesini oturuma kaydet
    $_SESSION['bakiye'] = $kullaniciBakiye;
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Bakiye Sayfası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            color: white;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        #bakiye-div {
            font-size: 18px;
            margin-bottom: 20px;
        }

        #oyun-container {
            margin-top: 20px;
        }

        #ortadaki-sayi {
            font-size: 24px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        form {
        text-align: center;
        }
        form button {
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        form button:hover {
            transform: scale(1.1);
        }

        form button:active {
            transform: scale(1);
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
    <br><br><br><br><br>
    <div class="container">
        <h1>Sayı Tahmin Oyunu (1-20)</h1>
        <p>Tahmin Ücreti : <?php echo $tahminUcreti ?></p>
        <div id="bakiye-div">Bakiyeniz: <span><?php echo $_SESSION['bakiye']; ?></span></div>
        <div id="oyun-container">
            <p id="ortadaki-sayi">???</p>
            <form method="POST" action="">
                <button type="submit" name="tahmin" value="Büyük">Büyük</button>
                <button type="submit" name="tahmin" value="Küçük" style="background-color: #FF5A5F">Küçük</button>
            </form>

        </div>
    </div><br><br><br>
    <div id="mesaj">
        <?php echo $mesaj; ?>
    </div>

    <script>
        var ortadakiSayi = <?php echo $_SESSION['ortadakiSayi']; ?>;
        document.getElementById('ortadaki-sayi').innerText = ortadakiSayi;
    </script>
</body>

</html>
