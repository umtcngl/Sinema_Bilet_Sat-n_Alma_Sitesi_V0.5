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

// Tüm kullanıcıları çek
$kullanicisorgusu = $db->prepare("SELECT * FROM users order by id");
$kullanicisorgusu->execute();
$users = $kullanicisorgusu->fetchAll(PDO::FETCH_ASSOC);



// Kullanıcı silme işlemini gerçekleştir
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sil'])) {
    $userIdToDelete = isset($_POST['sil_user_id']) ? $_POST['sil_user_id'] : null;

    // Kullanıcıyı silme işlemi
    $deleteQuery = $db->prepare("DELETE FROM users WHERE id = :userId");
    $deleteQuery->bindParam(':userId', $userIdToDelete, PDO::PARAM_INT);
    $deleteQuery->execute();

    // Kullanıcılar sayfasına yönlendir
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
    <title>Admin Kullanıcılar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        table {
            color:white;
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
    <h1 style="color: gold;">Kullanıcılar</h1>
</div>
    <!-- Kullanıcı Listesi Tablosu -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Şifre</th>
            <th>Bakiye</th>
            <th>Son Giriş Tarihi</th>
            <th>Kullanıcı Rol</th>
            <th>Düzenle</th>
            <th>Sil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['kullaniciadi']; ?></td>
                <td><?php echo $user['sifre']; ?></td>
                <td><?php echo $user['bakiye']; ?></td>
                <td><?php echo $user['son_giris_tarihi']; ?></td>
                <td><?php echo $user['kullanici_rol']; ?></td>
                <td>
                    <form method="POST" action="admin_user_edit.php">
                        <input type="hidden" name="edit_user_id" value="<?php echo $user['id']; ?>">
                        <input type="submit" class="formsubmit" name="düzenle" value="Düzenle">
                    </form>
                </td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="sil_user_id" value="<?php echo $user['id']; ?>">
                        <input type="submit" class="formsubmit1" name="sil" value="Sil">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Kullanıcı Listesi Tablosu SONU -->
</div>

</body>
</html>
