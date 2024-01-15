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

$filmlersorgusu = $db->prepare("SELECT * FROM filmler ORDER BY CASE WHEN salonID IS NULL THEN 1 ELSE 0 END, salonID");
$filmlersorgusu->execute();
$filmler = $filmlersorgusu->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sil'])) {
    $filmID = isset($_POST['sil_film_id']) ? $_POST['sil_film_id'] : null;

    $deleteQuery = $db->prepare("DELETE FROM filmler WHERE filmID = :filmID");
    $deleteQuery->bindParam(':filmID', $filmID, PDO::PARAM_INT);
    $deleteQuery->execute();

    header("Location: admin_filmler.php");
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
    <title>Admin Filmler</title>
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
        .eklebuton{
            position:absolute;
            left:150px;
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
    <input type="button" class="formsubmit eklebuton" value="Film Ekle" onclick="location.href='admin_film_ekle.php';">
    <h1 style="color: gold;">Filmler</h1>
    <label class="altalta">
            <input type="checkbox" id="anaCheckbox">Salon ID si NULL Olanları Gösterme 
    </label>
</div>
    <!-- Kullanıcı Listesi Tablosu -->
<table>
    <thead>
        <tr>
            <th>Film ID</th>
            <th>Film Adı</th>
            <th>Yönetmen</th>
            <th>Tür</th>
            <th>Salon ID</th>
            <th>Düzenle</th>
            <th>Sil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filmler as $film): ?>
            <tr>
                <td><?php echo $film['filmID'];   ?></td>
                <td>
                    <?php
                    echo $film['filmAdi'];
                    if ($film['salonID'] !== null) {
                        echo '<a href="' .'Salon'. $film['salonID'] . '.php" class="buton"><i class="fas fa-arrow-right slni"></i></a>';
                    }
                    ?>
                </td>
                <td><?php echo $film['yonetmen']; ?></td>
                <td><?php echo $film['tur'];      ?></td>
                <td><?php echo $film['salonID'];  ?></td>
                <td>
                    <form method="POST" action="admin_film_edit.php">
                        <input type="hidden" name="edit_film_id" value="<?php echo $film['filmID']; ?>">
                        <input type="submit" class="formsubmit" name="düzenle" value="Düzenle">
                    </form>
                </td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="sil_film_id" value="<?php echo $film['filmID']; ?>">
                        <input type="submit" class="formsubmit1" name="sil" value="Sil">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Kullanıcı Listesi Tablosu SONU -->
</div>
<script>
    document.getElementById('anaCheckbox').addEventListener('change', function() {
        var tiklanamazSatirlar = document.querySelectorAll('tbody tr');
        tiklanamazSatirlar.forEach(function(tr) {
            var salonIDCell = tr.cells[4]; // Salon ID'nin bulunduğu hücre
            var salonIDValue = salonIDCell.innerText.trim();

            if (document.getElementById('anaCheckbox').checked) {
                // Checkbox seçili ise, salonID'si NULL olanları gizle
                if (salonIDValue === '' || salonIDValue === 'NULL') {
                    tr.style.display = 'none';
                } else {
                    tr.style.display = 'table-row';
                }
            } else {
                // Checkbox seçili değilse, tüm satırları göster
                tr.style.display = 'table-row';
            }
        });
    });

    // Sayfa yüklendiğinde checkbox durumunu kontrol et
    anaCheckbox.checked = true;
    anaCheckbox.dispatchEvent(new Event('change'));
</script>
</body>
</html>
