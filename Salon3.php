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

// Salon adını aldıktan sonra, salon adından salon ID'yi ve koltuk sayısını almak için bir sorgu yapabilirsiniz.
$sorgusalonid = $db->prepare("SELECT salonID, koltukSayisi FROM salonlar WHERE salonAdi = :salonAdi");
$sorgusalonid->bindParam(':salonAdi', $sayfaAdi, PDO::PARAM_STR); // salonAdi'nin bir string olduğunu belirtin
$sorgusalonid->execute();
$salonRow = $sorgusalonid->fetch(PDO::FETCH_ASSOC);

if ($salonRow) {
    // Eğer bir sonuç varsa, salon ID ve koltuk sayısını alın
    $salonID = $salonRow['salonID'];
    $koltukSayisi = $salonRow['koltukSayisi'];

    // Ardından film sorgusunu gerçekleştirin
    $sorguFilm = $db->prepare("SELECT filmAdi FROM filmler WHERE salonID = :salonID");
    $sorguFilm->bindParam(':salonID', $salonID, PDO::PARAM_INT); // salonID'nin bir tamsayı olduğunu belirtin
    $sorguFilm->execute();
    $filmAdi = $sorguFilm->fetch(PDO::FETCH_ASSOC);

    // İşlemlerinizi devam ettirin...
} else {
    // Eğer salon ID bulunamazsa uygun bir hata işleme stratejisi geliştirebilirsiniz.
    echo "Salon ID bulunamadı.";
}

$currentHour = date("H");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Salon 1</title>
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
    <div class="ortala">
        <div class="screen"></div>
        <div class="altalta filmadicanlandir"><?php echo $filmAdi['filmAdi']." "."(".$koltukSayisi.")";?></div>
    </div>
        <div class="container1">
            <div id="seans12" style="display:flex">
                <div class="sira">
                    <div class="harfler">A</div>
                    <div class="harfler">B</div>
                    <div class="harfler">C</div>
                    <div class="harfler">D</div>
                    <div class="harfler">E</div>
                    <div class="harfler">F</div>
                    <div class="harfler">G</div>
                    <div class="harfler">H</div>
                    <div class="harfler"></div>
                </div>
                <?php
                    // Sıra sayısı
                    $siraSayisi = 11;

                    // Koltuk sayısı her sırada
                    $koltukSayisi = 8;

                    // Başlangıç değeri
                    $baslangicDegeri = 969;

                    // Seat divlerine id atama işlemi
                    for ($i = 1; $i <= $siraSayisi; $i++) {
                        echo '<div class="sira">';

                        for ($j = 1; $j <= $koltukSayisi; $j++) {
                            $seatID =$baslangicDegeri;
                            echo '<div class="seat" id="' . $seatID . '"></div>';
                            $baslangicDegeri++;

                        }

                        echo '<div class="harfler">' . $i . '</div>';
                        echo '</div>';
                    }
                ?>
            </div>

            <div id="seans14" style="display:none">
                <div class="sira">
                    <div class="harfler">A</div>
                    <div class="harfler">B</div>
                    <div class="harfler">C</div>
                    <div class="harfler">D</div>
                    <div class="harfler">E</div>
                    <div class="harfler">F</div>
                    <div class="harfler">G</div>
                    <div class="harfler">H</div>
                    <div class="harfler"></div>
                </div>
                <?php
                    // Sıra sayısı
                    $siraSayisi = 11;

                    // Koltuk sayısı her sırada
                    $koltukSayisi = 8;

                    // Başlangıç değeri
                    $baslangicDegeri = 1057;

                    // Seat divlerine id atama işlemi
                    for ($i = 1; $i <= $siraSayisi; $i++) {
                        echo '<div class="sira">';

                        for ($j = 1; $j <= $koltukSayisi; $j++) {
                            $seatID =$baslangicDegeri;
                            echo '<div class="seat" id="' . $seatID . '"></div>';
                            $baslangicDegeri++;

                        }

                        echo '<div class="harfler">' . $i . '</div>';
                        echo '</div>';
                    }
                ?>
            </div>

            <div id="seans16" style="display:none">
                <div class="sira">
                    <div class="harfler">A</div>
                    <div class="harfler">B</div>
                    <div class="harfler">C</div>
                    <div class="harfler">D</div>
                    <div class="harfler">E</div>
                    <div class="harfler">F</div>
                    <div class="harfler">G</div>
                    <div class="harfler">H</div>
                    <div class="harfler"></div>
                </div>
                <?php
                    // Sıra sayısı
                    $siraSayisi = 11;

                    // Koltuk sayısı her sırada
                    $koltukSayisi = 8;

                    // Başlangıç değeri
                    $baslangicDegeri = 1145;

                    // Seat divlerine id atama işlemi
                    for ($i = 1; $i <= $siraSayisi; $i++) {
                        echo '<div class="sira">';

                        for ($j = 1; $j <= $koltukSayisi; $j++) {
                            $seatID =$baslangicDegeri;
                            echo '<div class="seat" id="' . $seatID . '"></div>';
                            $baslangicDegeri++;

                        }

                        echo '<div class="harfler">' . $i . '</div>';
                        echo '</div>';
                    }
                ?>
            </div>

            <div id="seans18" style="display:none">
                <div class="sira">
                    <div class="harfler">A</div>
                    <div class="harfler">B</div>
                    <div class="harfler">C</div>
                    <div class="harfler">D</div>
                    <div class="harfler">E</div>
                    <div class="harfler">F</div>
                    <div class="harfler">G</div>
                    <div class="harfler">H</div>
                    <div class="harfler"></div>
                </div>
                <?php
                    // Sıra sayısı
                    $siraSayisi = 11;

                    // Koltuk sayısı her sırada
                    $koltukSayisi = 8;

                    // Başlangıç değeri
                    $baslangicDegeri = 1233;

                    // Seat divlerine id atama işlemi
                    for ($i = 1; $i <= $siraSayisi; $i++) {
                        echo '<div class="sira">';

                        for ($j = 1; $j <= $koltukSayisi; $j++) {
                            $seatID =$baslangicDegeri;
                            echo '<div class="seat" id="' . $seatID . '"></div>';
                            $baslangicDegeri++;
                        }

                        echo '<div class="harfler">' . $i . '</div>';
                        echo '</div>';
                    }
                ?>

            </div>
            <div id="seanslarbitti" style="display:none" class="uyari"><p>Bugünün Seansları Bitti. <br><br> Yarın Tekrar Deneyin.</p></div>
            <div class="altalta">
                <!-- Seans butonları -->
                <form action="" method="POST">
                <div><input type="button" onclick="seans12göster()" class="formsubmit <?php echo ($currentHour >= 12) ? 'tiklanamazbuton' : ''; ?>" value="12:00 Seansı"></div><br>
        <div><input type="button" onclick="seans14göster()" class="formsubmit <?php echo ($currentHour >= 14) ? 'tiklanamazbuton' : ''; ?>" value="14:00 Seansı"></div><br>
        <div><input type="button" onclick="seans16göster()" class="formsubmit <?php echo ($currentHour >= 16) ? 'tiklanamazbuton' : ''; ?>" value="16:00 Seansı"></div><br>
        <div><input type="button" onclick="seans18göster()" class="formsubmit <?php echo ($currentHour >= 18) ? 'tiklanamazbuton' : ''; ?>" value="18:00 Seansı"></div>
                </form>
            </div>
        </div>


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
    <p class="ortala text">
        <span id="count">0</span>&nbsp;Adet Koltuk İçin Hesaplanan Ücret &nbsp;<span id="amount">0</span> &nbsp;TL
    </p>
    <div class="ortala">
    <form id="satinalmaForm" method="POST" action="satinal.php">
        <!-- Form içeriği buraya eklenecek -->
        <input type="submit" class="formsubmit1" value="Satın Al">
    </form>
    </div>
<script>
const container = document.querySelector(".container1");
const count = document.getElementById('count');
const amount = document.getElementById('amount');

let selectedSeatIDs = []; // Yeni bir dizi ekleyin

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
    amount.innerText = selectedSeatCount * 50; // Her bir koltuk 50 TL olsun varsayalım
}

document.addEventListener("DOMContentLoaded", function () {
    // Durumu dolu olan koltuklara reserved class'ını ekle
    fetch('koltuk_kontrol.php')
        .then(response => response.json())
        .then(data => {
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

// Satın Alma Formunu Gönder
function satinalmaFormunuGonder(e) {
    e.preventDefault(); // Formun otomatik olarak submit olmasını engelle

    // En az bir koltuk seçilmiş mi diye kontrol et
    if (selectedSeatIDs.length > 0) {
        console.log(selectedSeatIDs); // Konsola seçilen koltuk ID'lerini yazdır

        // Seçilen koltuk ID'lerini ve rezervasyon durumunu PHP sayfasına gönder
        fetch('satinal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'koltukID=' + selectedSeatIDs.join(',') + '&isReserved=true',
        })
        .then(response => response.text())
        .then(data => {
            // PHP sayfasının cevabını burada işleyebilirsin
            console.log(data);

            // Sayfayı yeniden yükle veya başka bir işlem yapabilirsin
            location.reload();
        })
        .catch(error => {
            console.error('Hata:', error);
        });
    } else {
        alert('Lütfen en az bir koltuk seçin.');
    }
}


document.querySelector('#satinalmaForm').addEventListener('submit', function(e) {
    satinalmaFormunuGonder(e);
});


// Seans Seçme İşlemleri : 
function seans12göster(){
    var seans12divi = document.getElementById("seans12");
    var seans14divi = document.getElementById("seans14");
    var seans16divi = document.getElementById("seans16");
    var seans18divi = document.getElementById("seans18");

    seans12divi.style.display = "flex";
    seans14divi.style.display = "none";
    seans16divi.style.display = "none";
    seans18divi.style.display = "none";

    var seansbittidivi = document.getElementById("seanslarbitti");
    seansbittidivi.style.display="none";

}
function seans14göster(){
    var seans12divi = document.getElementById("seans12");
    var seans14divi = document.getElementById("seans14");
    var seans16divi = document.getElementById("seans16");
    var seans18divi = document.getElementById("seans18");

    seans12divi.style.display = "none";
    seans14divi.style.display = "flex";
    seans16divi.style.display = "none";
    seans18divi.style.display = "none";

    var seansbittidivi = document.getElementById("seanslarbitti");
    seansbittidivi.style.display="none";
}
function seans16göster(){
    var seans12divi = document.getElementById("seans12");
    var seans14divi = document.getElementById("seans14");
    var seans16divi = document.getElementById("seans16");
    var seans18divi = document.getElementById("seans18");

    seans12divi.style.display = "none";
    seans14divi.style.display = "none";
    seans16divi.style.display = "flex";
    seans18divi.style.display = "none";

    var seansbittidivi = document.getElementById("seanslarbitti");
    seansbittidivi.style.display="none";
}
function seans18göster(){
    var seans12divi = document.getElementById("seans12");
    var seans14divi = document.getElementById("seans14");
    var seans16divi = document.getElementById("seans16");
    var seans18divi = document.getElementById("seans18");

    seans12divi.style.display = "none";
    seans14divi.style.display = "none";
    seans16divi.style.display = "none";
    seans18divi.style.display = "flex";

    var seansbittidivi = document.getElementById("seanslarbitti");
    seansbittidivi.style.display="none";
}

document.addEventListener("DOMContentLoaded", function () {
    // Şu anki saat ve seans saatleri
    const currentTime = new Date();
    const seansSaatleri = [12, 14, 16, 18];

    // Satın al butonunu ve seans butonlarını seç
    const satinalBtn = document.querySelector('.formsubmit1');
    const seansButtons = document.querySelectorAll('.formsubmit');

    // Kontrol değişkeni
    let tümSeanslarTıklanabilir = true;

    // Her bir seans butonu için kontrol yap
    seansButtons.forEach((seansButton, index) => {
        // Eğer şu anki saat, seans saatinin geçmişse
        if (currentTime.getHours() >= seansSaatleri[index]) {
            // İlgili seans butonuna belirli bir class ekle
            seansButton.classList.add('tiklanamazbuton');
            // Kontrol değişkenini güncelle
            tümSeanslarTıklanabilir = false;
        }

        // Seans butonuna tıklanma olayını ekle
        seansButton.addEventListener('click', function () {
            // Eğer şu anki saat, seans saatinin geçmişse
            if (currentTime.getHours() >= seansSaatleri[index]) {
                // Satın al butonuna belirli bir class ekle
                satinalBtn.classList.add('tiklanamazbuton');
            } else {
                // Aksi takdirde, class'ı kaldır
                satinalBtn.classList.remove('tiklanamazbuton');
            }
            
            // Kontrol değişkenini güncelle
            tümSeanslarTıklanabilir = seansButtons.every(button => button.classList.contains('tiklanamazbuton'));
            
            // Tüm seanslar tıklanamazsa uyarı mesajını göster
            if (!tümSeanslarTıklanabilir) {
                const seansbittidivi = document.getElementById("seanslarbitti");
                seansbittidivi.style.display = "block";

                satinalBtn.classList.add('tiklanamazbuton');
            }
        });
    });

    // Tüm seanslar tıklanamazsa uyarı mesajını göster
    if (!tümSeanslarTıklanabilir) {
        const seansbittidivi = document.getElementById("seanslarbitti");
        seansbittidivi.style.display = "block";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Seans butonlarını seç
    const seansButtons = document.querySelectorAll('.formsubmit');

    // Kontrol değişkeni
    let tiklanabilirButonBulundu = false;

    // Her bir seans butonunu kontrol et
    seansButtons.forEach((seansButton) => {
        // Eğer tiklanamazbuton sınıfına sahip değilse
        if (!seansButton.classList.contains('tiklanamazbuton') && !tiklanabilirButonBulundu) {
            // Butonu tıkla
            seansButton.click();
            // Kontrol değişkenini güncelle
            tiklanabilirButonBulundu = true;
        }
    });
});


</script>
</body>
</html>