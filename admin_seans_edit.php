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

$salonID = isset($_POST['edit_salon_id']) ? $_POST['edit_salon_id'] : null;

if ($salonID) {
    $seanssorgusu = $db->prepare("SELECT * FROM seanslar WHERE salonID = :salonID");
    $seanssorgusu->bindParam(':salonID', $salonID, PDO::PARAM_INT);
    $seanssorgusu->execute();
    $editSeans = $seanssorgusu->fetch(PDO::FETCH_ASSOC);

    if (!$editSeans) {
        header("Location: admin_seanslar.php");
        exit();
    }
} else {
    header("Location: admin_seanslar.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    try {
        $salonID = $_POST['edit_salon_id'];
        $seans1 = $_POST['seans1'];
        $seans2 = $_POST['seans2'];
        $seans3 = $_POST['seans3'];
        $seans4 = $_POST['seans4'];
        $seans5 = $_POST['seans5'];
        $seans6 = $_POST['seans6'];
        $seans7 = $_POST['seans7'];
        $seans8 = $_POST['seans8'];

        $updateQuery = $db->prepare("UPDATE seanslar SET seans1 = :seans1, seans2 = :seans2, seans3 = :seans3, seans4 = :seans4, seans5 = :seans5, seans6 = :seans6, seans7 = :seans7, seans8 = :seans8 WHERE salonID = :salonID");
        $updateQuery->bindParam(':salonID', $salonID, PDO::PARAM_INT);
        $updateQuery->bindParam(':seans1', $seans1, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans2', $seans2, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans3', $seans3, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans4', $seans4, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans5', $seans5, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans6', $seans6, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans7', $seans7, PDO::PARAM_STR);
        $updateQuery->bindParam(':seans8', $seans8, PDO::PARAM_STR);
        $updateQuery->execute();
        
        header("Location: admin_seanslar.php");
        exit();
        
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
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
    <title>Seansları Düzenle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .containergiris {
            max-width: 400px;
            margin: 70px auto;
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
            color: gold;
            cursor: pointer;
        }

        .back-button:hover {
            color: #6da8cd;
            transform: scale(1.2);
            transition: transform 0.3s;
        }

        .back-button:active {
            color: gold;
            transform: scale(1.0);
            transition: transform 0.3s;
        }
        .flexcontainer{
            display:flex;
        }
        .sagdanmargin{
            margin-right:50px;
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

    <div class="btn1 bakiye-div"><a href="bakiye.php"><i class="fas fa-coins"></i>Bakiyeniz: <span
                    style="color: gold;"><?php echo $_SESSION['bakiye']; ?></span></a></div>
</header>
<!-- MENU SONU -->

<div class="containergiris">
    <div class="back-button" onclick="location.href='admin_seanslar.php'"><i class="fas fa-arrow-left"></i></div>
    <form method="POST" action="">
        <input type="hidden" name="edit_salon_id" value="<?php echo $editSeans['salonID']; ?>">

        <div class="flexcontainer">
        <div class="sagdanmargin">
        <label>Seans 1 :</label><br>
        <input type="time" name="seans1" value="<?php echo $editSeans['seans1']; ?>"><br>
        <label>Seans 2 :</label><br>
        <input type="time" name="seans2" value="<?php echo $editSeans['seans2']; ?>"><br>
        <label>Seans 3 :</label><br>
        <input type="time" name="seans3" value="<?php echo $editSeans['seans3']; ?>"><br>
        <label>Seans 4 :</label><br>
        <input type="time" name="seans4" value="<?php echo $editSeans['seans4']; ?>">
        </div>
        <div>
        <label>Seans 5 :</label><br>
        <input type="time" name="seans5" value="<?php echo $editSeans['seans5']; ?>"><br>
        <label>Seans 6 :</label><br>
        <input type="time" name="seans6" value="<?php echo $editSeans['seans6']; ?>"><br>
        <label>Seans 7 :</label><br>
        <input type="time" name="seans7" value="<?php echo $editSeans['seans7']; ?>"><br>
        <label>Seans 8 :</label><br>
        <input type="time" name="seans8" value="<?php echo $editSeans['seans8']; ?>">
        </div></div>


        <input type="submit" class="formsubmit" name="edit" value="Kaydet">
    </form>
    
</div>

</body>
</html>
