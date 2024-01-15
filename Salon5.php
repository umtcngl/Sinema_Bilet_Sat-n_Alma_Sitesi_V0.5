<?php
include("db_baglanti.php");
session_start();

if (isset($_SESSION['hesap'])) {
    // Yönlendirme için hedef URL
    $hedefURL = "kullanici.php";
    $baglantiMetni = $_SESSION['hesap'];
    $baglantiIkon = "fas fa-user";
} else {
    $baglantiMetni = "GİRİŞ YAP";
    $baglantiIkon = "fas fa-sign-in-alt";
}

//--------------------------------------------------------------------
$sayfaAdi = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

$sorgusalonid = $db->prepare("SELECT salonID,sirasayisi,sutunsayisi,ucret FROM salonlar WHERE salonAdi = :salonAdi");
$sorgusalonid->bindParam(':salonAdi', $sayfaAdi, PDO::PARAM_STR);
$sorgusalonid->execute();
$salonRow = $sorgusalonid->fetch(PDO::FETCH_ASSOC);

if ($salonRow) {
    // Eğer bir sonuç varsa, salon ID ve koltuk sayısını alın
    $salonID = $salonRow['salonID'];
    $sirasayisi = $salonRow['sirasayisi'];
    $sutunsayisi = $salonRow['sutunsayisi'];
    $ucret = $salonRow['ucret'];
    // Ardından film sorgusunu gerçekleştirin
    $sorguFilm = $db->prepare("SELECT filmAdi FROM filmler WHERE salonID = :salonID");
    $sorguFilm->bindParam(':salonID', $salonID, PDO::PARAM_INT);
    $sorguFilm->execute();
    $filmAdi = $sorguFilm->fetch(PDO::FETCH_ASSOC);

    // İşlemlerinizi devam ettirin...
} else {
    // Eğer salon ID bulunamazsa uygun bir hata işleme stratejisi geliştirebilirsiniz.
    echo "Salon ID bulunamadı.";
}

date_default_timezone_set('Europe/Istanbul');

// Seansları çek
$sorguSeanslar = $db->prepare("SELECT * FROM seanslar WHERE salonID = :salonID");
$sorguSeanslar->bindParam(':salonID', $salonID, PDO::PARAM_INT);
$sorguSeanslar->execute();
$seanslar = $sorguSeanslar->fetchAll(PDO::FETCH_ASSOC);

$seansSutunlari = [];
foreach ($seanslar as $seans) {
    foreach ($seans as $key => $value) {
        if (strpos($key, 'seans') !== false && $value !== '00:00:00') {
            $seansSaat = date('H:i', strtotime($value));
            $seansSutunlari[] = $seansSaat;
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
    <title><?php echo $sayfaAdi;?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        form input {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        form input:hover {
            transform: scale(1.1);
        }

        form input:active {
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

        <div class="btn1 bakiye-div" <?php if (!isset($_SESSION['hesap'])) echo 'style="display: none;"'; ?>><a href="bakiye.php"><i class="fas fa-coins"></i>Bakiyeniz: <span style="color: gold;"><?php echo $_SESSION['bakiye']; ?></span></a></div>
</header>
<!-- MENU SONU -->
<div class="salon yukaridanbosluk">
    <div>
    <div class="screen">
        <div class="ortala filmadicanlandir">PERDE</div>
    </div>
    <div class="container1">
        <div class="yanyana">
            <div class="sira">
            <?php
            $harfler = range('A', 'Z');
            for ($k = 0; $k < $sirasayisi; $k++) {
                echo '<div class="harfler">' . $harfler[$k] . '</div>';
            }
            echo '<div class="harfler"></div>';
            ?>
            </div>
            <?php
            for ($i = 1; $i <= $sutunsayisi; $i++) {
                echo '<div class="sira">';
                for ($j = 1; $j <= $sirasayisi; $j++) {
                    $koltukID = $harfler[$j - 1] . $i;
                    echo '<div class="seat" id="' . $koltukID . '"></div>';
                }

                echo '<div class="harfler">' . $i . '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    </div>





    <div class="container2">
    <div class="ortala"><div class="filmadicanlandir"><?php echo $filmAdi['filmAdi'];?></div></div><br><br>
        <div class="seans_container">
            <!-- Seans butonları -->
            <form id="seansForm" method="POST">
            <input type="date" id="tarih" name="tarih" value="<?php echo date('Y-m-d'); ?>" required onkeydown="return false"><br><br>

            <div>
                <?php
                $sutunSayisi = 2; // Her satırda kaç seans saatı gösterileceğini belirleyin
                $seansCount = 0;

                foreach ($seansSutunlari as $seansSaat) {
                    echo '<div>';
                    echo '<input type="radio" id="seans' . $seansSaat . '" name="seans" value="' . $seansSaat . '" class="formradio">';
                    echo '<label for="seans' . $seansSaat . '">' . $seansSaat . ' Seansı</label>';
                    echo '</div>';
                    $seansCount++;

                    // Belirlenen sütun sayısına ulaşıldığında yeni bir satıra geç
                    if ($seansCount % $sutunSayisi == 0) {
                        echo '<br>';
                    }
                }
                ?>
            </div>


            </form>
        </div>
    </div>
</div>
<div class="marginleft">
    <ul class="info">
        <li>
            <div class="seat selected"></div>
            <small>Seçili</small>
        </li>
        <li>
            <div class="seat"></div>
            <small>Boş</small>
        </li>
        <li>
            <div class="seat reserved"></div>
            <small>Dolu</small>
        </li>
    </ul>
    <div>
    <p class="ortala text">
        <span id="count">0</span>&nbsp;Adet Koltuk İçin Hesaplanan Ücret &nbsp;<span id="amount">0</span> &nbsp;TL
    </p>
    </div>
    <div class="ortala text">
    <form id="satinalmaForm" method="POST" action="satinal.php">
        <input type="submit" class="formsubmit1" value="Satın Al" id="satinalButton">
    </form>
    </div>
</div>
<script>
const container = document.querySelector(".container1");
const count = document.getElementById('count');
const amount = document.getElementById('amount');
const buyButton = document.getElementById('satinalButton'); // Satın al butonunun id'sini buraya ekleyin

let selectedSeatIDs = [];

container.addEventListener('click', function (e) {
    if (e.target.classList.contains('seat') && !e.target.classList.contains('reserved')) {
        e.target.classList.toggle('selected');
        updateCounts();
    }
});

function updateCounts() {
    let selectedSeats = container.querySelectorAll('.seat.selected');
    selectedSeatIDs = Array.from(selectedSeats).map(seat => seat.id);

    let selectedSeatCount = selectedSeats.length;
    count.innerText = selectedSeatCount;
    amount.innerText = selectedSeatCount * <?php echo $ucret;?>;

    console.log('Seçilen Koltuk ID\'leri:', selectedSeatIDs);
}

const seansForm = document.getElementById('seansForm');

seansForm.addEventListener('change', function () {
    // Seçili seansı al
    const selectedSeans = document.querySelector('input[name="seans"]:checked');

    // Eğer seans seçilmişse, seans değerini al; seçili değilse "Seans seçilmedi" olarak ata
    const seansValue = selectedSeans ? selectedSeans.value + ':00' : null;

    // Seçili tarihi al, ve JavaScript Date objesine çevir
    const selectedTarih = new Date(document.getElementById('tarih').value);
    const tarihString = selectedTarih.toISOString().split('T')[0];
    // Sayfa adını PHP'den al
    const sayfaAdi = "<?php echo $sayfaAdi; ?>";

    // Şuanki zamanı PHP'den al, ve JavaScript Date objesine çevir
    const currentTime = new Date("<?php date_default_timezone_set('Europe/Istanbul'); echo date("Y-m-d H:i"); ?>");

    // Seçili seansın tarihini ve saatini bir araya getir
    const selectedDateTime = new Date(selectedTarih.getFullYear(), selectedTarih.getMonth(), selectedTarih.getDate(),
        parseInt(seansValue.split(':')[0]), parseInt(seansValue.split(':')[1]));

    // Tarihi ve saatleri kontrol et
    if ((!selectedDateTime) || selectedDateTime < currentTime) {
        // Eğer seans veya tarih seçilmemişse veya seçili tarih ve saat, şuanki tarihten ve saatten önceyse
        // Satın alma butonuna tiklanamazbuton class'ını ekle
        buyButton.classList.add('tiklanamazbuton');
    } else {
        // Aksi takdirde tiklanamazbuton class'ını kaldır
        buyButton.classList.remove('tiklanamazbuton');
    }

    const reservedSeats = container.querySelectorAll('.seat.reserved');
    reservedSeats.forEach(reservedSeat => {
        reservedSeat.classList.remove('reserved');
    });

    fetch('koltuk_kontrol.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `sayfaAdi=${sayfaAdi}&seans=${seansValue}&secilentarih=${tarihString}`,
    })
    .then(response => response.json())
    .then(data => {
        console.log('Gelen Veriler:', data); // Gelen verileri konsolda göster
        data.forEach(koltukID => {
            const koltukElement = document.getElementById(koltukID);
            if (koltukElement) {
                koltukElement.classList.add('reserved');
            }
        });
    })
    .catch(error => {
        console.error('Hata:', error);
    });

});



document.addEventListener('DOMContentLoaded', function() {
    buyButton.classList.add('tiklanamazbuton');
    var today = new Date();
    var minDate = new Date(today);
    minDate.setDate(today.getDate());

    var daysUntilNextFriday = 5 - today.getDay();
    var nextFriday = new Date(today.getFullYear(), today.getMonth(), today.getDate() + daysUntilNextFriday);

    var maxDate = nextFriday.toISOString().split('T')[0];

    document.getElementById('tarih').setAttribute('min', minDate.toISOString().split('T')[0]);
    document.getElementById('tarih').setAttribute('max', maxDate);
});

function satinalmaFormunuGonder(e) {
    e.preventDefault();

    if (selectedSeatIDs.length > 0) {
        const selectedSeans = document.querySelector('input[name="seans"]:checked');
        const seansValue = selectedSeans ? selectedSeans.value : "Seans seçilmedi";
        const selectedTarih = document.getElementById('tarih').value;
        const filmAdi = "<?php echo $filmAdi['filmAdi']; ?>";
        const sayfaAdi = "<?php echo $sayfaAdi; ?>";
        const koltukIDler = selectedSeatIDs.join(',');
        const ucret =<?php echo $ucret;?> ;

        fetch('satinal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `sayfaAdi=${sayfaAdi}&seans=${seansValue}&secilentarih=${selectedTarih}&koltukID=${koltukIDler}&filmAdi=${filmAdi}&ucret=${ucret}`,
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch(error => {
            console.error('Hata:', error);
        });
    } else {
        alert('Lütfen en az bir koltuk seçin.');
    }
}

document.getElementById('satinalButton').addEventListener('click', function (e) {
    e.preventDefault();
    satinalmaFormunuGonder(new Event('dummy'));
});

</script>
</body>
</html>