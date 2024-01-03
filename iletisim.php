<?php
session_start();
unset($_SESSION['oncekiHafizaSayi']);
unset($_SESSION['oncekiOrtadakiSayi']);
if(isset($_SESSION['hesap'])) {
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";
}
else{
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Hakkımızda</title>
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

        <div class="btn1 bakiye-div" <?php if (!isset($_SESSION['hesap'])) echo 'style="display: none;"'; ?>><a href="bakiye.php"><i class="fas fa-coins"></i>Bakiyeniz: <span style="color: gold;"><?php echo $_SESSION['bakiye']; ?></span></a></div>

</header>
<!-- MENU SONU -->
<div class="container1111">
    <div class="anketimsi">
        <h1 style="color:white">Site Hakkında</h1>
        

    </div>
    <div class="iletisimbaglanti">
        <h1 style="color:white">Bana Ulaşın</h1>
        <div class="adivi">
        <a href="mailto:umitcn.gl@gmail.com"><i class="fas fa-envelope"></i>&nbsp;&nbsp;&nbsp;umitcn.gl@gmail.com</a>
        </div>
        <div class="adivi">
        <a href="https://github.com/umtcngl"target="_blank"><i class="fas fa-cat"></i>&nbsp;&nbsp;&nbsp;Github</a>
        </div>
    </div>
</div>
</body>
</html>