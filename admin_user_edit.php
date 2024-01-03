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

// Düzenlenecek kullanıcının ID'sini al
$editUserId = isset($_POST['edit_user_id']) ? $_POST['edit_user_id'] : null;

// Düzenlenecek kullanıcının bilgilerini çek
if ($editUserId) {
    $kullanicisorgusu = $db->prepare("SELECT * FROM users WHERE id = :editUserId");
    $kullanicisorgusu->bindParam(':editUserId', $editUserId, PDO::PARAM_INT);
    $kullanicisorgusu->execute();
    $editUser = $kullanicisorgusu->fetch(PDO::FETCH_ASSOC);

    // Eğer kullanıcı bulunamazsa veya ID geçerli değilse, ana kullanıcı listesi sayfasına yönlendir
    if (!$editUser) {
        header("Location: admin_kullanicilar.php");
        exit();
    }
} else {
    // ID yoksa, ana kullanıcı listesi sayfasına yönlendir
    header("Location: admin_kullanicilar.php");
    exit();
}

// edit formu gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    // Formdan gelen veriler
    $kullaniciAdi = $_POST['kullaniciadi'];
    $sifre = $_POST['sifre'];
    $bakiye = $_POST['bakiye'];
    $kullanici_rol = $_POST['kullanici_rol'];
    // Diğer alanları ekleyebilirsiniz.

    // Veritabanında güncelleme yap
    $updateQuery = $db->prepare("UPDATE users SET kullaniciadi = :kullaniciadi, sifre = :sifre, bakiye = :bakiye ,kullanici_rol = :kullanici_rol WHERE id = :editUserId");
    $updateQuery->bindParam(':kullaniciadi', $kullaniciAdi, PDO::PARAM_STR);
    $updateQuery->bindParam(':sifre', $sifre, PDO::PARAM_STR);
    $updateQuery->bindParam(':bakiye', $bakiye, PDO::PARAM_INT);
    $updateQuery->bindParam(':kullanici_rol', $kullanici_rol, PDO::PARAM_INT);
    $updateQuery->bindParam(':editUserId', $editUserId, PDO::PARAM_INT);
    $updateQuery->execute();

    // Başarıyla güncellendiyse kullanıcıları listeleme sayfasına yönlendir
    header("Location: admin_kullanicilar.php");
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
    <title>Kullanıcı Düzenle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .containergiris {
    max-width: 400px;
    margin:70px auto;
    background-color: grey;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
    display: flex;
    flex-direction: column;
    }

    label {
    margin-bottom: 5px;
    }

    input {
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    }
    .back-button {
            position: absolute;
            top: 150px;
            left: 500px;
            font-size: 30px;
            color:gold;
            cursor: pointer;
        }
    .back-button:hover {
        color: #6da8cd;
        transform: scale(1.2);
        transition: transform 0.3s;
    }
    .back-button:active{
        color:gold;
        transform: scale(1.0);
        transition: transform 0.3s;
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

<div class="containergiris">
<div class="back-button" onclick="history.go(-1);"><i class="fas fa-arrow-left"></i></div>
    <!-- Kullanıcı Düzenleme Formu -->
    <form method="POST" action="">
        <input type="hidden" name="edit_user_id" value="<?php echo $editUser['id']; ?>">
        <label>Kullanıcı Adı:</label>
        <input type="text" name="kullaniciadi" value="<?php echo $editUser['kullaniciadi']; ?>" required><br>

        <label>Şifre:</label>
        <input type="password" name="sifre" value="<?php echo $editUser['sifre']; ?>" required><br>

        <label>Bakiye:</label>
        <input type="text" name="bakiye" value="<?php echo $editUser['bakiye']; ?>" required><br>
        <label>Kullanıcı Rol:</label>
        <input type="text" name="kullanici_rol" value="<?php echo $editUser['kullanici_rol']; ?>" required><br>

        <input type="submit" class="formsubmit" name="edit" value="Kaydet">
    </form>
    <!-- Kullanıcı Düzenleme Formu SONU -->
</div>

</body>
</html>
