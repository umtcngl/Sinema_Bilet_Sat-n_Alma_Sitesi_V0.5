<?php
session_start();
include("db_baglanti.php");

if(isset($_SESSION['hesap'])) {
    // Yönlendirme için hedef URL
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";
}
else{
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}

if ($_POST) {
    if (isset($_POST['kullaniciadi1'])) {
        $kullaniciadi = $_POST['kullaniciadi1'];
        $sifre = $_POST['sifre1'];

        if (empty($kullaniciadi) || empty($sifre)) {
            $usernameeror = "Tüm Alanları Doldurun!!!";
            echo '<script>document.querySelector(".myspan").textContent = ' . $usernameeror . ';</script>';
        }
        else{
            $Control = $db->prepare("SELECT * FROM users WHERE kullaniciadi = ? AND sifre = ?");
            $Control->execute(array($kullaniciadi, $sifre));

            if ($Control->rowCount() > 0) {
                $_SESSION['hesap'] = $kullaniciadi;
                header("Location: kullanici.php");
            } else {
                $usernameeror = "Kullanıcı adı veya şifre hatalı!";
                echo '<script>document.querySelector(".myspan").textContent = ' . $usernameeror . ';</script>';
            }
        }
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
    <title>Giriş Yap</title>
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
</header>
<!-- MENU SONU -->
    <div class="containergiris">
        <div class="content1">
            <div id="girisdiv"class="girisyapform">
                <h1>GİRİŞ  YAP</h1>
                <form  method="POST">
                    <input name="kullaniciadi1"type="text" placeholder="Kullanıcı Adınızı Giriniz..."><br><br>
                    <input name="sifre1"type="password" placeholder="Şifrenizi Giriniz..."><br><br>
                    <input class="formsubmit"type="submit" value="Giriş"><br><br>
                    <input class="formsubmit" type="button" value="Kaydol" onclick="redirectToPage('kayitol.php')">
                </form><br>
                <span class="myspan"><?php echo isset($usernameeror) ? $usernameeror : ''; ?></span>
            </div>
        </div>
    </div>
    <script>
        function redirectToPage(pageUrl) {
            window.location.href = pageUrl;
        }
    </script>
</body>
</html>