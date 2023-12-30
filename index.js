const afisElement = document.querySelector(".afis");
const baslikElement = document.querySelector(".baslik");
const aciklamaElement = document.querySelector(".aciklama");
const solOk = document.querySelector(".soldüzenle");
const sagOk = document.querySelector(".sagdüzenle");
const biletAlButon = document.querySelector(".bilet-al-buton");

let films = [];
let currentIndex = 0;
let intervalID;

// Filmleri almak için AJAX kullanın
function getFilms() {
    fetch('get_films.php')
        .then(response => response.json())
        .then(data => {
            films = data;
            gosterAfişi(currentIndex);
            intervalID = setInterval(otomatikAfişDegisimi, 10000);
        })
        .catch(error => console.error('Filmleri alırken bir hata oluştu: ', error));
}

function gosterAfişi(index) {
    afisElement.src = films[index].afis;
    baslikElement.textContent = films[index].filmAdi;
    aciklamaElement.textContent = films[index].aciklama;
    biletAlButon.href = "Salon"+films[index].salonID+".php";
}

solOk.addEventListener("click", function () {
    currentIndex = (currentIndex - 1 + films.length) % films.length;
    gosterAfişi(currentIndex);
    sifirlaZamani();
});

sagOk.addEventListener("click", function () {
    currentIndex = (currentIndex + 1) % films.length;
    gosterAfişi(currentIndex);
    sifirlaZamani();
});

function otomatikAfişDegisimi() {
    currentIndex = (currentIndex + 1) % films.length;
    gosterAfişi(currentIndex);
}

function sifirlaZamani() {
    clearInterval(intervalID);
    intervalID = setInterval(otomatikAfişDegisimi, 10000);
}

// Filmleri al
getFilms();
