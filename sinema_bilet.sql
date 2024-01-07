-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 07 Oca 2024, 23:08:53
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
  `koltuk` varchar(20) DEFAULT NULL,
  `odenenucret` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `biletler`
--

INSERT INTO `biletler` (`biletID`, `kullaniciID`, `filmAdi`, `salonAdi`, `bilet_tarihi`, `islem_tarihi`, `seans`, `koltuk`, `odenenucret`) VALUES
(159, 1, 'TESTERE X', 'Salon1', '2024-01-07', '2024-01-07 10:26:45', '16:00:00', 'D4', 250),
(161, 1, 'TESTERE X', 'Salon1', '2024-01-07', '2024-01-07 10:26:45', '16:00:00', 'D6', 250),
(165, 1, 'WHO AM I', 'Salon2', '2024-01-07', '2024-01-07 10:27:16', '16:00:00', 'D4', 200),
(166, 1, 'WHO AM I', 'Salon2', '2024-01-07', '2024-01-07 10:27:16', '16:00:00', 'D5', 200),
(167, 1, 'WHO AM I', 'Salon2', '2024-01-07', '2024-01-07 10:27:16', '16:00:00', 'D6', 200),
(168, 1, 'WHO AM I', 'Salon2', '2024-01-07', '2024-01-07 10:27:16', '16:00:00', 'G7', 200),
(171, 1, 'OPPENHEIMER', 'Salon3', '2024-01-07', '2024-01-07 10:30:41', '23:55:00', 'D6', 150),
(172, 1, 'OPPENHEIMER', 'Salon3', '2024-01-07', '2024-01-07 10:30:41', '23:55:00', 'E6', 150),
(173, 1, 'TESTERE X', 'Salon1', '2024-01-08', '2024-01-07 19:19:59', '15:00:00', 'F5', 250),
(174, 1, 'TESTERE X', 'Salon1', '2024-01-08', '2024-01-07 19:19:59', '15:00:00', 'G5', 250),
(175, 1, 'TESTERE X', 'Salon1', '2024-01-08', '2024-01-07 19:19:59', '15:00:00', 'H5', 250),
(176, 1, 'TESTERE X', 'Salon1', '2024-01-08', '2024-01-07 19:19:59', '15:00:00', 'F6', 250),
(177, 1, 'TESTERE X', 'Salon1', '2024-01-08', '2024-01-07 19:19:59', '15:00:00', 'G6', 250),
(178, 1, 'TESTERE X', 'Salon1', '2024-01-08', '2024-01-07 19:20:00', '15:00:00', 'F7', 250),
(181, 1, 'WRATH OF MAN', 'Salon5', '2024-01-08', '2024-01-07 20:05:39', '15:00:00', 'E6', 80);

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
(1, 'TESTERE X', 'Kevin Greutert', 'Gerilim', 1, 'https://m.media-amazon.com/images/M/MV5BMmJhYjBkMzgtZGIwMC00YTAzLWE4NTQtYzVkNDVmYjIzODI0XkEyXkFqcGdeQXVyODQxMTI4MjM@._V1_FMjpg_UX1000_.jpg', 'Testere 10, kendisini dolandıranlardan intikam almak için harekete geçen Jigsaw’un hikayesini konu ediyor. Kanser hastası olan John, bütün umudunu kaybetmiştir. Deneysel ve çok riskli bir ameliyat olmaya karar veren John, bunun için Meksika’ya doğru yola koyulur. Ancak onun hastalığını tedavi etmeye yönelik bu ameliyat tamamen düzmecedir. En savunmasız anında dolandırılan John, intikam almak için en iyi yaptığı işe geri döner. John, kendisini dolandıranları delice ve usta işi tuzaklarıyla sınamak için harekete geçer.'),
(2, 'WHO AM I', 'Baran bo Odar', 'Gerilim , Suç', 2, 'https://m.media-amazon.com/images/M/MV5BYmRiYjQ0OGQtYTAzMi00OGVjLWE4YTQtM2Q4YjBlZTBhMWM5XkEyXkFqcGdeQXVyNDk3NzU2MTQ@._V1_.jpg', 'Toplumla fazla bir ilişkisi bulunmayan genç bir hacker olan Benjamin (Tom Schilling), Max (Elyas M\'Barek) adında bir başka hacker\'la tanışır. Stephan ve Paul adlı yazılım ve donanım uzmanlarıyla birlikte CLAY adında bir hacker\'lar grubu kurarlar. CLAY son derece yıkıcı faaliyetleriyle adını duyurmaktadır. Europol ve istihbarat teşkilatlarını peşine takan grup için işler yolunda gitse de onları hedefine alan karanlık bir hacker grubu, onlarla her yoldan rekabet etmeye çalışacaktır.'),
(3, 'OPPENHEIMER', 'Christopher Nolan', 'Gerilim', 3, 'https://tr.web.img2.acsta.net/pictures/23/05/08/09/02/2465323.jpg', 'Amerikalı fizikçi Julius Robert Oppenheimer\'ın hayatına odaklanılan filmde, Julius Robert Oppenheimer’ın, İkinci Dünya Savaşı sırasında atom bombasının geliştirilme sürecindeki rolü gözler önüne seriliyor. New Mexico\'daki Los Alamos Ulusal Laboratuvarında, o ve ekibi, uzun çalışmaların ardından bir nükleer silah geliştirmesinin ardından Oppenheimer \'atom bombasının babası\' ilan edilir. Ancak ölümcül icadının Hiroşima ve Nagazaki\'de kullanılacak olması, Oppenheimer\'ın kendisini projeden uzaklaştırmasına neden olur. Savaş sona ermek üzereyken, Lewis Strauss\'un ortak kurduğu ABD Atom Enerjisi Ajansı\'nın danışmanı olan Robert Oppenheimer, nükleer enerjinin uluslararası kontrolüne ve nükleer silahlanma yarışına karşı olduğunu savunur ve bu nedenle ABD tarafından hedef haline gelir.'),
(4, 'THE BATMAN', 'Matt Reeves', 'Aksiyon , Suç', 4, 'https://amovieguy.com/wp-content/uploads/2022/02/batman-man.jpg', 'The Batman, suçluların kalplerine korku salan Batman\'in Riddler isimli bir seri katille mücadelesini konu ediyor. Batman olarak iki yıl sokaklarda dolaşmak ve suçlulara korku salmak Bruce Wayne\'i Gotham City\'nin karanlığının kalbine sürükledi. Gizemli bir seri katil Riddler, şehrin seçkinlerini hedef alıp bir dizi sadist ve hain saldırı gerçekleştirdiğinde Batman, Riddler\'in izini sürmeye başlar. İpuçlarının peşinden giden Batman\'in yolu bu süreçte Catwoman olarak bilinen Selina Kyle, Penguen olarak da bilinen Oswald Cobblepot ve Carmine Falcone gibi karakterlerle kesişir. Batman kurduğu yeni ilişkilerin de yardımıyla suçluların maskesini düşürmek ve Gotham Şehri’ni eski huzuruna kavuşturmak için zorlu bir mücadeleye girişir.'),
(5, 'CARS 2', 'John Lasseter', 'Animasyon , Macera', 6, 'https://upload.wikimedia.org/wikipedia/tr/7/73/Arabalar_2_afi%C5%9F.jpg', 'Dünya’nın en hızlı arabasının belirleneceği bir yarışma olan Dünya Şampiyonasını duydukları zaman Şimşek McQueen ve en yakın arkadaşı Çekici Mater hemen yola çıkarlar.Dünya Şampiyonasına en sonunda vardıklarında ikisi de büyülenir. Bu yarışlar boyunca Mater, Şimşek McQueen’e yardım etmek ve casusluk yapmak arasında kalır. Çünkü Şimşek onun yakın arkadaşıdır. Bu yüzden Şimşek’in peşindeki kötü adamlar Japonya ve Avrupa sokaklarında sürecek bir kovalamacaya başlarlar. Şimşek bu kötü adamlardan kurtulup yarışı kazanabilecek mi?'),
(6, 'FIGHT CLUB', 'David Fincher', 'Gerilim , Suç', NULL, 'https://i.ebayimg.com/images/g/0jYAAOSwef9a8PCb/s-l1200.webp', 'Dövüş kulübünün ilk kuralı, dövüş kulübü hakkında konuşmamaktır. Dövüş kulübünün ikinci kuralı da, kulüp hakkında konuşmamaktır... Filmin baş kişisi, sıradan hayatının girdaplarında bunalımlar geçiren bir sigorta müfettişi olan Jack, Kanserli olmadığı halde, uykusuzluğunu yenmek ve hayatına anlam katmak adına, kanserlilere moral destek sağlayan terapi gruplarına katılır. Orada, Marla Singer adlı bir kızla garip bir yakınlık kurar. Bir iş gezisi dönüşü ise, Tyler Durden adlı egzantrik karakterle tanışır. Durden, Jack\'in olmak isteyip de olamadığı adam gibidir. Tyler\'ın girişimleriyle bir yeraltı faaliyeti olarak başlayan dövüş kulübü, Jack\'e hayatında yepyeni kapılar açacaktır... Ve tabii, bu kapılardan ister istemez Marla geçecektir... Fakat... Tyler Durden gerçekte kimdir?'),
(7, 'WRATH OF MAN', 'Guy Ritchie', 'Aksiyon , Suç ', 5, 'https://pbs.twimg.com/media/FFGpxE3WYBEe0a6.jpg', 'İntikam Vakti, oğlunun intikamını almaya çalışan gizemli bir adamın hikayesini konu ediyor. H, varlıklı ve güçlü bir adamdır. Ancak tüm bunlara rağmen o, her hafta milyonlarca dolar taşıyan zırhlı bir araç şirketinde özel güvenlik görevlisi olarak işe başlar. H, olağanüstü yetenekleri sayesinde soygun girişimlerini engeller. Herkes onun çalışmasından memnundur ancak kimse onun neden bu şirkete girdiğini bilmemektedir. H\'nin şirkette çalışmasının tek nedeni ise oğlunun ölümüne neden olanlardan intikam almaktır.');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `salonlar`
--

CREATE TABLE `salonlar` (
  `salonID` int(11) NOT NULL,
  `salonAdi` varchar(255) DEFAULT NULL,
  `sirasayisi` int(11) NOT NULL,
  `sutunsayisi` int(11) NOT NULL,
  `ucret` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `salonlar`
--

INSERT INTO `salonlar` (`salonID`, `salonAdi`, `sirasayisi`, `sutunsayisi`, `ucret`) VALUES
(1, 'Salon1', 8, 12, 250),
(2, 'Salon2', 8, 12, 200),
(3, 'Salon3', 8, 12, 150),
(4, 'Salon4', 8, 12, 100),
(5, 'Salon5', 8, 10, 80),
(6, 'Salon6', 8, 10, 80);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seanslar`
--

CREATE TABLE `seanslar` (
  `salonID` int(11) NOT NULL,
  `seans1` time DEFAULT '09:00:00',
  `seans2` time DEFAULT '11:00:00',
  `seans3` time DEFAULT '13:00:00',
  `seans4` time DEFAULT '15:00:00',
  `seans5` time DEFAULT '17:00:00',
  `seans6` time DEFAULT '19:00:00',
  `seans7` time DEFAULT '21:00:00',
  `seans8` time DEFAULT '23:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `seanslar`
--

INSERT INTO `seanslar` (`salonID`, `seans1`, `seans2`, `seans3`, `seans4`, `seans5`, `seans6`, `seans7`, `seans8`) VALUES
(1, '09:00:00', '11:00:00', '13:00:00', '15:00:00', '17:00:00', '19:00:00', '21:00:00', '23:00:00'),
(2, '09:00:00', '11:00:00', '13:00:00', '15:00:00', '17:00:00', '19:00:00', '21:00:00', '23:00:00'),
(3, '09:00:00', '11:00:00', '13:00:00', '15:00:00', '17:00:00', '19:00:00', '21:00:00', '23:00:00'),
(4, '09:00:00', '11:00:00', '13:00:00', '15:00:00', '17:00:00', '19:00:00', '21:00:00', '23:00:00'),
(5, '09:00:00', '11:00:00', '13:00:00', '15:00:00', '17:00:00', '19:00:00', '00:00:00', '00:00:00'),
(6, '09:00:00', '11:00:00', '13:00:00', '15:00:00', '17:00:00', '19:00:00', '21:00:00', '23:00:00');

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
  `kullanici_rol` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `kullaniciadi`, `sifre`, `bakiye`, `son_giris_tarihi`, `kullanici_rol`) VALUES
(1, 'Admin', 'admin123', 6180, '2024-01-08', 1),
(19, 'Ümitcan', '123', 5050, '2024-01-04', 2),
(29, 'Murat', '123', 300, '2023-12-25', 3),
(32, 'Ahmad', '123', 200, '2023-12-26', 3);

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
-- Tablo için indeksler `seanslar`
--
ALTER TABLE `seanslar`
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
  MODIFY `biletID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- Tablo için AUTO_INCREMENT değeri `filmler`
--
ALTER TABLE `filmler`
  MODIFY `filmID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
