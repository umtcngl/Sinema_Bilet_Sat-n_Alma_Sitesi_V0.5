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
    <style>
        p{
        color: white;
        font-size: 18px;
        word-spacing:2px;
        line-height: 2;
        }
        .vurgu{
            color:gold
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

        <div class="btn1 bakiye-div" <?php if (!isset($_SESSION['hesap'])) echo 'style="display: none;"'; ?>><a href="bakiye.php"><i class="fas fa-coins"></i>Bakiyeniz: <span style="color: gold;"><?php echo $_SESSION['bakiye']; ?></span></a></div>

</header>
<!-- MENU SONU -->
<div class="container1111">
    <div class="anketimsi">
        <h1 style="text-align:center">Site Hakkında</h1>
        <p><span class="vurgu">ÖNEMLİ NOT</span> : Eğer Perşembe günü bilet bakıyorsanız. İleri tarihli bilet alamıyormuyum gibi düşünebilirsiniz.Fakat öyle değil tarih şu şekilde bir sonraki cuma ya kadar (Cuma Dahil Değil!) alabiliyorsunuz. Cuma Günleri genelde Vizyona filmler girdiği için çakışma olmaması açısından bu şekilde yaptım.</p><br>

        <p class="vurgu">Her salonun koltuk sayısını , seanslarını , ücretini ve tabiki o salonda hangi filmin olacağını ADMİN PANELİNDEN ayarlayabiliyoruz. SALON EKLE ve FİLM EKLE özellikleri de var.</p><br>

        <p>Aldığınız Bileti zamanı geçmeden iptal ederseniz. Ödediğiniz Ücretin <span class="vurgu">Yarısını</span> Alırsınız.</p><br>
        
        <p>Admin salon ekle butonuna bastığı anda veri tabanında salon ekleniyor ve aynı anda varsayılan seans larda seanslar tablosuna ekleniyor. <span class="vurgu">Salon Eklenirken Salon1.php sayfasının kodları referans alınır.(Salon1 i silemezsiniz bu yüzden)</span></p><br>

        <p>Salon sil de ise salon siliniyor. hem o salona ait bir film varsa o film o salondan kaldırılıyor(salonID sini boş bırakırsanız bir filmin o film salonda yayınlanmaz.anasayfadaki slaytta da gözükmez. <span class="vurgu">Bir nevi vizyondan kaldırılmış gibi olur.</span> ).o salona ait seanslarda seanslar tablosundan silinir.</p>

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