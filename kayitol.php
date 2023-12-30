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
    if (isset($_POST['kullaniciadi'])) {
        $kullaniciadi = $_POST['kullaniciadi'];
        $sifre = $_POST['sifre'];
        $sifreT = $_POST['sifreT'];

        if (empty($kullaniciadi) || empty($sifre) || empty($sifreT)) {
            $new_text = "Tüm Alanları Doldurun!!!";
            echo '<script>document.querySelector(".myspan").textContent = ' . $new_text . ';</script>';
        } else {
            if ($sifre == $sifreT) {
                $Control = $db->prepare("SELECT * FROM users WHERE kullaniciadi=?");
                $Control->execute(array($kullaniciadi));
                if ($Control->rowCount() == 0) {
                    $Add = $db->prepare("INSERT INTO users SET kullaniciadi=?,sifre=?");
                    $Add->execute(array($kullaniciadi, $sifre));
                    header("Location:girisyap.php");
                } else {
                    $new_text = "Bu Kullanıcı Adı Alınmış!!!";
                    echo '<script>document.querySelector(".myspan").textContent = ' . $new_text . ';</script>';
                }
            } else {
                $new_text = "Şifreler Uyuşmuyor!!!";
                echo '<script>document.querySelector(".myspan").textContent = ' . $new_text . ';</script>';
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
            <div id="kayitdiv"class="kayitolform">
                <h1>KAYIT OL</h1>
                <form method="POST"class="formdüzenle">
                    <input name="kullaniciadi"type="text" placeholder="Kullanıcı Adı Giriniz..."><br><br>
                    <input name="sifre"type="password" placeholder="Şifre Giriniz..."><br><br>
                    <input name="sifreT"type="password" placeholder="Şifreyi Tekrar Giriniz..."><br><br>
                    <input class="formsubmit"type="submit" value="Kayıt"><br><br>
                </form>
                <input class="formsubmit"type="submit" value="Hesabın var mı?"onclick="redirectToPage('girisyap.php')"><br><br>
                <span class="myspan"><?php echo isset($new_text) ? $new_text : ''; ?></span>
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