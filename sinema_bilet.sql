-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 02 Oca 2024, 12:41:07
-- Sunucu sürümü: 10.4.28-MariaDB
-- PHP Sürümü: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `sinema_bilet`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `biletler`
--

CREATE TABLE `biletler` (
  `biletID` int(11) NOT NULL,
  `kullaniciID` int(11) DEFAULT NULL,
  `filmAdi` varchar(255) DEFAULT NULL,
  `salonAdi` varchar(255) DEFAULT NULL,
  `bilet_tarihi` date DEFAULT NULL,
  `islem_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `seans` time DEFAULT NULL,
  `koltuk` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `biletler`
--

INSERT INTO `biletler` (`biletID`, `kullaniciID`, `filmAdi`, `salonAdi`, `bilet_tarihi`, `islem_tarihi`, `seans`, `koltuk`) VALUES
(85, 19, 'TESTERE X', 'Salon1', '2024-01-01', '2024-01-01 19:21:55', '18:00:00', 'A1'),
(86, 19, 'TESTERE X', 'Salon1', '2024-01-01', '2024-01-01 19:21:55', '18:00:00', 'A2'),
(87, 19, 'TESTERE X', 'Salon1', '2024-01-01', '2024-01-01 19:21:55', '18:00:00', 'A3'),
(89, 19, 'WHO AM I', 'Salon2', '2024-01-05', '2024-01-01 19:47:50', '18:00:00', 'H5'),
(90, 19, 'WHO AM I', 'Salon2', '2024-01-05', '2024-01-01 19:47:50', '18:00:00', 'H6'),
(91, 19, 'WHO AM I', 'Salon2', '2024-01-05', '2024-01-01 19:47:50', '18:00:00', 'H7'),
(93, 19, 'WHO AM I', 'Salon2', '2024-01-05', '2024-01-01 20:02:04', '18:00:00', 'H8'),
(96, 19, 'TESTERE X', 'Salon1', '2024-01-02', '2024-01-02 09:05:47', '16:00:00', 'H5'),
(97, 19, 'TESTERE X', 'Salon1', '2024-01-02', '2024-01-02 09:05:47', '16:00:00', 'H6'),
(98, 19, 'TESTERE X', 'Salon1', '2024-01-02', '2024-01-02 09:05:47', '16:00:00', 'H7'),
(100, 19, 'TESTERE X', 'Salon1', '2024-01-02', '2024-01-02 09:29:36', '18:00:00', 'B2'),
(101, 19, 'TESTERE X', 'Salon1', '2024-01-02', '2024-01-02 09:29:36', '18:00:00', 'B3'),
(102, 19, 'TESTERE X', 'Salon1', '2024-01-02', '2024-01-02 09:29:36', '18:00:00', 'H11');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `filmler`
--

CREATE TABLE `filmler` (
  `filmID` int(11) NOT NULL,
  `filmAdi` varchar(255) DEFAULT NULL,
  `yonetmen` varchar(255) DEFAULT NULL,
  `tur` varchar(255) DEFAULT NULL,
  `salonID` int(11) DEFAULT NULL,
  `afis` varchar(255) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `filmler`
--

INSERT INTO `filmler` (`filmID`, `filmAdi`, `yonetmen`, `tur`, `salonID`, `afis`, `aciklama`) VALUES
(1, 'TESTERE X', 'Kevin Greutert', 'Gerilim', 1, 'img/2.jpg', 'Testere 10, kendisini dolandıranlardan intikam almak için harekete geçen Jigsaw’un hikayesini konu ediyor. Kanser hastası olan John, bütün umudunu kaybetmiştir. Deneysel ve çok riskli bir ameliyat olmaya karar veren John, bunun için Meksika’ya doğru yola koyulur. Ancak onun hastalığını tedavi etmeye yönelik bu ameliyat tamamen düzmecedir. En savunmasız anında dolandırılan John, intikam almak için en iyi yaptığı işe geri döner. John, kendisini dolandıranları delice ve usta işi tuzaklarıyla sınamak için harekete geçer.'),
(2, 'WHO AM I', 'Baran bo Odar', 'Gerilim , Suç', 2, 'img/3.jpg', 'Toplumla fazla bir ilişkisi bulunmayan genç bir hacker olan Benjamin (Tom Schilling), Max (Elyas M\'Barek) adında bir başka hacker\'la tanışır. Stephan ve Paul adlı yazılım ve donanım uzmanlarıyla birlikte CLAY adında bir hacker\'lar grubu kurarlar. CLAY son derece yıkıcı faaliyetleriyle adını duyurmaktadır. Europol ve istihbarat teşkilatlarını peşine takan grup için işler yolunda gitse de onları hedefine alan karanlık bir hacker grubu, onlarla her yoldan rekabet etmeye çalışacaktır.'),
(3, 'OPPENHEIMER', 'Christopher Nolan', 'Gerilim', 3, 'img/4.jpg', 'Amerikalı fizikçi Julius Robert Oppenheimer\'ın hayatına odaklanılan filmde, Julius Robert Oppenheimer’ın, İkinci Dünya Savaşı sırasında atom bombasının geliştirilme sürecindeki rolü gözler önüne seriliyor. New Mexico\'daki Los Alamos Ulusal Laboratuvarında, o ve ekibi, uzun çalışmaların ardından bir nükleer silah geliştirmesinin ardından Oppenheimer \'atom bombasının babası\' ilan edilir. Ancak ölümcül icadının Hiroşima ve Nagazaki\'de kullanılacak olması, Oppenheimer\'ın kendisini projeden uzaklaştırmasına neden olur. Savaş sona ermek üzereyken, Lewis Strauss\'un ortak kurduğu ABD Atom Enerjisi Ajansı\'nın danışmanı olan Robert Oppenheimer, nükleer enerjinin uluslararası kontrolüne ve nükleer silahlanma yarışına karşı olduğunu savunur ve bu nedenle ABD tarafından hedef haline gelir.'),
(4, 'THE BATMAN', 'Matt Reeves', 'Aksiyon , Suç', 4, 'img/5.jpg', 'The Batman, suçluların kalplerine korku salan Batman\'in Riddler isimli bir seri katille mücadelesini konu ediyor. Batman olarak iki yıl sokaklarda dolaşmak ve suçlulara korku salmak Bruce Wayne\'i Gotham City\'nin karanlığının kalbine sürükledi. Gizemli bir seri katil Riddler, şehrin seçkinlerini hedef alıp bir dizi sadist ve hain saldırı gerçekleştirdiğinde Batman, Riddler\'in izini sürmeye başlar. İpuçlarının peşinden giden Batman\'in yolu bu süreçte Catwoman olarak bilinen Selina Kyle, Penguen olarak da bilinen Oswald Cobblepot ve Carmine Falcone gibi karakterlerle kesişir. Batman kurduğu yeni ilişkilerin de yardımıyla suçluların maskesini düşürmek ve Gotham Şehri’ni eski huzuruna kavuşturmak için zorlu bir mücadeleye girişir.');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `salonlar`
--

CREATE TABLE `salonlar` (
  `salonID` int(11) NOT NULL,
  `salonAdi` varchar(255) DEFAULT NULL,
  `koltukSayisi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `salonlar`
--

INSERT INTO `salonlar` (`salonID`, `salonAdi`, `koltukSayisi`) VALUES
(1, 'Salon1', 88),
(2, 'Salon2', 88),
(3, 'Salon3', 88),
(4, 'Salon4', 88);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `kullaniciadi` varchar(20) NOT NULL,
  `sifre` varchar(20) NOT NULL,
  `bakiye` decimal(10,0) NOT NULL DEFAULT 0,
  `son_giris_tarihi` date NOT NULL DEFAULT '2023-01-01',
  `kullanici_rol` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `kullaniciadi`, `sifre`, `bakiye`, `son_giris_tarihi`, `kullanici_rol`) VALUES
(1, 'admin', 'admin123', 10150, '2023-12-27', 1),
(19, 'Ümitcan', '123', 700, '2024-01-02', 3),
(29, 'Murat', '123', 430, '2023-12-25', 3),
(32, 'ahmad', '123', 0, '2023-12-26', 3);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `biletler`
--
ALTER TABLE `biletler`
  ADD PRIMARY KEY (`biletID`),
  ADD KEY `kullaniciID` (`kullaniciID`);

--
-- Tablo için indeksler `filmler`
--
ALTER TABLE `filmler`
  ADD PRIMARY KEY (`filmID`);

--
-- Tablo için indeksler `salonlar`
--
ALTER TABLE `salonlar`
  ADD PRIMARY KEY (`salonID`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `biletler`
--
ALTER TABLE `biletler`
  MODIFY `biletID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Tablo için AUTO_INCREMENT değeri `filmler`
--
ALTER TABLE `filmler`
  MODIFY `filmID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `salonlar`
--
ALTER TABLE `salonlar`
  MODIFY `salonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
