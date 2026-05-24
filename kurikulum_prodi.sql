SET FOREIGN_KEY_CHECKS=0;
-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: kurikulum_prodi
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('kurikulum-prodi-cache-admin@feb.unm.ac.id|127.0.0.1','i:2;',1779419248),('kurikulum-prodi-cache-admin@feb.unm.ac.id|127.0.0.1:timer','i:1779419248;',1779419248),('kurikulum-prodi-cache-admin@kurikulum.dev|127.0.0.1','i:2;',1779359247),('kurikulum-prodi-cache-admin@kurikulum.dev|127.0.0.1:timer','i:1779359247;',1779359247);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clo_taxonomis`
--

DROP TABLE IF EXISTS `clo_taxonomis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clo_taxonomis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `domain` enum('Afektif','Kognitif','Psikomotor') NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clo_taxonomis_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clo_taxonomis`
--

LOCK TABLES `clo_taxonomis` WRITE;
/*!40000 ALTER TABLE `clo_taxonomis` DISABLE KEYS */;
INSERT INTO `clo_taxonomis` VALUES (1,'C1','Kognitif',1,'Mengingat (Remember)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(2,'C2','Kognitif',2,'Memahami (Understand)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(3,'C3','Kognitif',3,'Menerapkan (Apply)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(4,'C4','Kognitif',4,'Menganalisis (Analyze)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(5,'C5','Kognitif',5,'Mengevaluasi (Evaluate)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(6,'C6','Kognitif',6,'Mencipta (Create)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(7,'A1','Afektif',1,'Menerima (Receiving)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(8,'A2','Afektif',2,'Merespons (Responding)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(9,'A3','Afektif',3,'Menghargai (Valuing)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(10,'A4','Afektif',4,'Mengorganisasi (Organization)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(11,'A5','Afektif',5,'Karakterisasi (Characterization)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(12,'A6','Afektif',6,'Internalisasi Nilai','2026-05-21 02:01:26','2026-05-21 02:01:26'),(13,'P1','Psikomotor',1,'Menirukan (Imitation)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(14,'P2','Psikomotor',2,'Manipulasi (Manipulation)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(15,'P3','Psikomotor',3,'Presisi (Precision)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(16,'P4','Psikomotor',4,'Artikulasi (Articulation)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(17,'P5','Psikomotor',5,'Naturalisasi (Naturalization)','2026-05-21 02:01:26','2026-05-21 02:01:26'),(18,'P6','Psikomotor',6,'Kreasi Gerak (Creation)','2026-05-21 02:01:26','2026-05-21 02:01:26');
/*!40000 ALTER TABLE `clo_taxonomis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpls`
--

DROP TABLE IF EXISTS `cpls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_studi_id` bigint(20) unsigned NOT NULL,
  `kode` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `urutan` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpls_program_studi_id_kode_unique` (`program_studi_id`,`kode`),
  CONSTRAINT `cpls_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpls`
--

LOCK TABLES `cpls` WRITE;
/*!40000 ALTER TABLE `cpls` DISABLE KEYS */;
INSERT INTO `cpls` VALUES (1,1,'CPL1','Lulusan memiliki kecakapan literasi sains, teknologi, dan seni dalam upaya menumbuhkan inovasi nasional.','CPL UNM',1,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(2,1,'CPL2','Lulusan memiliki mindset wirausaha yang tercermin dalam kemampuan berpikir kreatif dan inovatif, memiliki jiwa kepemimpinan, dan mampu menciptakan peluang usaha.','CPL UNM',2,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(3,1,'CPL3','Lulusan mampu menunjukkan karakter yang baik dan Humanis.','CPL APSMBI',3,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(4,1,'CPL4','Lulusan mampu mengaplikasikan teori manajemen secara efektif.','CPL APSMBI',4,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(5,1,'CPL5','Lulusan mampu berkomunikasi secara efektif.','CPL APSMBI',5,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(6,1,'CPL6','Lulusan mampu menggunakan teknologi untuk mendukung pengambilan keputusan manajemen secara tepat.','CPL APSMBI',6,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(7,1,'CPL7','Lulusan mampu bekerja dengan baik untuk pengembangan diri.','CPL APSMBI',7,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(8,1,'CPL8','Lulusan mampu memecahkan masalah manajemen secara tepat.','CPL APSMBI',8,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(9,1,'CPL9','Lulusan mampu membuat rencana bisnis dengan baik.','CPL APSMBI',9,'2026-05-21 02:01:26','2026-05-21 02:01:26'),(10,1,'CPL10','Lulusan mampu melakukan penelitian manajemen dengan baik dan beretika.','CPL APSMBI',10,'2026-05-21 02:01:26','2026-05-21 02:01:26');
/*!40000 ALTER TABLE `cpls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpmk_cpls`
--

DROP TABLE IF EXISTS `cpmk_cpls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpmk_cpls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cpmk_id` bigint(20) unsigned NOT NULL,
  `cpl_id` bigint(20) unsigned NOT NULL,
  `has_relation` tinyint(1) NOT NULL DEFAULT 0,
  `bobot_persen` decimal(6,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpmk_cpls_cpmk_id_cpl_id_unique` (`cpmk_id`,`cpl_id`),
  KEY `cpmk_cpls_cpl_id_foreign` (`cpl_id`),
  CONSTRAINT `cpmk_cpls_cpl_id_foreign` FOREIGN KEY (`cpl_id`) REFERENCES `cpls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cpmk_cpls_cpmk_id_foreign` FOREIGN KEY (`cpmk_id`) REFERENCES `cpmks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpmk_cpls`
--

LOCK TABLES `cpmk_cpls` WRITE;
/*!40000 ALTER TABLE `cpmk_cpls` DISABLE KEYS */;
INSERT INTO `cpmk_cpls` VALUES (3,7,1,1,40.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(4,8,2,1,37.50,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(5,9,1,1,22.50,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(6,10,4,1,15.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(7,10,6,1,10.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(8,11,4,1,10.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(9,11,6,1,17.67,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(10,11,8,1,8.33,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(11,12,1,1,25.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(12,12,6,1,14.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(13,13,5,1,15.00,'2026-05-21 19:01:04','2026-05-21 22:07:46'),(14,14,1,1,8.33,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(15,14,6,1,17.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(16,15,6,1,8.00,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(17,16,8,1,25.00,'2026-05-21 19:01:04','2026-05-21 19:01:04');
/*!40000 ALTER TABLE `cpmk_cpls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpmks`
--

DROP TABLE IF EXISTS `cpmks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpmks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint(20) unsigned NOT NULL,
  `kode` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `urutan` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cpmks_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `cpmks_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpmks`
--

LOCK TABLES `cpmks` WRITE;
/*!40000 ALTER TABLE `cpmks` DISABLE KEYS */;
INSERT INTO `cpmks` VALUES (7,11,'CPMK 1','Menerapkan konsep literasi sains, teknologi, dan seni dalam konteks inovasi di bidang manajemen',1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(8,11,'CPMK 2','Mengembangkan ide kreatif dan inovatif berbasis sains, teknologi, dan seni sebagai wujud mindset kewirausahaan.',2,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(9,11,'CPMK 3','Menunjukkan sikap humanis, etis, dan bertanggung jawab dalam pemanfaatan sains, teknologi, dan seni.',3,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(10,36,'CPMK 1','Mampu menjelaskan konsep, komponen, dan peranan sistem informasi manajemen dalam mendukung fungsi dan strategi organisasi.',1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(11,36,'CPMK 2','Mampu menganalisis kebutuhan informasi serta merancang sistem informasi untuk mendukung efektivitas pengambilan keputusan manajerial.',2,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(12,36,'CPMK 3','Mampu mengevaluasi pemanfaatan teknologi informasi dalam proses bisnis organisasi untuk menciptakan inovasi dan peningkatan kinerja manajerial.',3,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(13,66,'CPMK 1','Memahami konsep dasar manajemen konten serta peranannya dalam strategi komunikasi dan pemasaran digital.',1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(14,66,'CPMK 2','Merancang strategi konten kreatif yang relevan dengan perilaku audiens dan perkembangan teknologi digital.',2,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(15,66,'CPMK 3','Menilai efektivitas kinerja konten melalui analisis data, metrik digital, dan indikator keberhasilan komunikasi.',3,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(16,66,'CPMK 4','Menginternalisasi nilai etika, tanggung jawab, dan profesionalisme dalam pengelolaan dan komunikasi konten digital.',4,'2026-05-21 19:01:04','2026-05-21 19:01:04');
/*!40000 ALTER TABLE `cpmks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_kuliahs`
--

DROP TABLE IF EXISTS `mata_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_studi_id` bigint(20) unsigned NOT NULL,
  `kode_rps` varchar(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `sks` tinyint(3) unsigned NOT NULL DEFAULT 3,
  `semester` tinyint(3) unsigned DEFAULT NULL,
  `konsentrasi` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `urutan` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliahs_kode_rps_unique` (`kode_rps`),
  KEY `mata_kuliahs_program_studi_id_foreign` (`program_studi_id`),
  CONSTRAINT `mata_kuliahs_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_kuliahs`
--

LOCK TABLES `mata_kuliahs` WRITE;
/*!40000 ALTER TABLE `mata_kuliahs` DISABLE KEYS */;
INSERT INTO `mata_kuliahs` VALUES (1,1,'RPS001','Pendidikan Agama Islam',2,1,NULL,1,1,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(2,1,'RPS002','Pendidikan Agama Kristen',2,1,NULL,1,2,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(3,1,'RPS003','Pendidikan Agama Katolik',2,1,NULL,1,3,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(4,1,'RPS004','Pendidikan Agama Hindu',2,1,NULL,1,4,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(5,1,'RPS005','Pendidikan Agama Budha',2,1,NULL,1,5,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(6,1,'RPS006','Pendidikan Agama Konghucu',2,1,NULL,1,6,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(7,1,'RPS007','Pendidikan Agama Lainnya',2,1,NULL,1,7,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(8,1,'RPS008','Pendidikan Pancasila',2,1,NULL,1,8,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(9,1,'RPS009','Pendidikan Kewarganegaraan',2,1,NULL,1,9,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(10,1,'RPS010','Bahasa Indonesia',2,1,NULL,1,10,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(11,1,'RPS011','Literasi Sains, Teknologi dan Seni',2,1,NULL,1,11,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(12,1,'RPS012','Kewirausahaan',3,2,NULL,1,12,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(13,1,'RPS013','Bahasa Inggris',2,1,NULL,1,13,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(14,1,'RPS014','Pengantar Ilmu Ekonomi',3,1,NULL,1,14,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(15,1,'RPS015','Pengantar Manajemen',3,1,NULL,1,15,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(16,1,'RPS016','Pengantar Akuntansi',3,1,NULL,1,16,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(17,1,'RPS017','Pengantar Bisnis',3,1,NULL,1,17,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(18,1,'RPS018','Bahasa Inggris Bisnis',2,2,NULL,1,18,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(19,1,'RPS019','Statistik Deskriptif',3,2,NULL,1,19,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(20,1,'RPS020','Manajemen Keuangan',3,3,NULL,1,20,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(21,1,'RPS021','Manajemen Pemasaran',3,3,NULL,1,21,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(22,1,'RPS022','Manajemen Sumber Daya Manusia',3,3,NULL,1,22,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(23,1,'RPS023','Teori Ekonomi Mikro',3,2,NULL,1,23,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(24,1,'RPS024','Teori Ekonomi Makro',3,2,NULL,1,24,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(25,1,'RPS025','Matematika Ekonomi',3,2,NULL,1,25,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(26,1,'RPS026','Filsafat Ilmu Manajemen',2,3,NULL,1,26,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(27,1,'RPS027','Pengantar Metodologi Penelitian',3,3,NULL,1,27,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(28,1,'RPS028','Etika dan Komunikasi Bisnis',3,3,NULL,1,28,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(29,1,'RPS029','Manajemen Koperasi',3,4,NULL,1,29,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(30,1,'RPS030','Manajemen Perbankan',3,4,NULL,1,30,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(31,1,'RPS031','Statistik Inferensial',3,3,NULL,1,31,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(32,1,'RPS032','Manajemen Operasional',3,4,NULL,1,32,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(33,1,'RPS033','Manajemen Strategik',3,5,NULL,1,33,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(34,1,'RPS034','Manajemen Risiko',3,5,NULL,1,34,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(35,1,'RPS035','Perilaku Keorganisasian',3,4,NULL,1,35,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(36,1,'RPS036','Sistem Informasi Manajemen',3,4,NULL,1,36,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(37,1,'RPS037','Teori Pengambilan Keputusan',3,5,NULL,1,37,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(38,1,'RPS038','Akuntansi Biaya',3,4,NULL,1,38,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(39,1,'RPS039','Ekonomi Internasional',3,4,NULL,1,39,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(40,1,'RPS040','Akuntansi Manajemen',3,5,NULL,1,40,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(41,1,'RPS041','Ekonometrika',3,5,NULL,1,41,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(42,1,'RPS042','Penelitian Bisnis',3,6,NULL,1,42,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(43,1,'RPS043','Perpajakan',3,5,NULL,1,43,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(44,1,'RPS044','Ekonomi Manajerial',3,5,NULL,1,44,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(45,1,'RPS045','Kebanksentralan',3,5,NULL,1,45,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(46,1,'RPS046','Manajemen Perbankan Syariah',3,6,NULL,1,46,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(47,1,'RPS047','Analisis Laporan Keuangan',3,6,NULL,1,47,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(48,1,'RPS048','Anggaran Perusahaan',3,6,NULL,1,48,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(49,1,'RPS049','Lembaga Keuangan Non Bank',3,6,NULL,1,49,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(50,1,'RPS050','Manajemen Keuangan Koperasi & UMKM',3,6,NULL,1,50,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(51,1,'RPS051','Studi Kelayakan Keuangan Bisnis',3,7,NULL,1,51,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(52,1,'RPS052','Manajemen Keuangan Strategik',3,7,NULL,1,52,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(53,1,'RPS053','Perilaku Keuangan',3,7,NULL,1,53,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(54,1,'RPS054','Financial Technology',3,7,NULL,1,54,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(55,1,'RPS055','Manajemen dan Kebijakan Keuangan Daerah',3,7,NULL,1,55,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(56,1,'RPS056','Manajemen Investasi dan Portofolio',3,7,NULL,1,56,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(57,1,'RPS057','Keuangan Internasional',3,7,NULL,1,57,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(58,1,'RPS058','Seminar Manajemen Keuangan',3,8,NULL,1,58,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(59,1,'RPS059','Pemasaran Digital',3,5,NULL,1,59,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(60,1,'RPS060','Perilaku Konsumen',3,5,NULL,1,60,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(61,1,'RPS061','Manajemen Produk dan Merek',3,6,NULL,1,61,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(62,1,'RPS062','Pemasaran Internasional',3,6,NULL,1,62,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(63,1,'RPS063','Manajemen Agribisnis',3,6,NULL,1,63,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(64,1,'RPS064','Komunikasi Pemasaran',3,6,NULL,1,64,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(65,1,'RPS065','Pemasaran Jasa',3,6,NULL,1,65,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(66,1,'RPS066','Manajemen Konten',3,7,NULL,1,66,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(67,1,'RPS067','Manajemen Penjualan',3,7,NULL,1,67,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(68,1,'RPS068','Riset Analisis Pasar',3,7,NULL,1,68,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(69,1,'RPS069','MICE Management',3,7,NULL,1,69,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(70,1,'RPS070','Strategi Pemasaran',3,7,NULL,1,70,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(71,1,'RPS071','Manajemen Distribusi',3,7,NULL,1,71,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(72,1,'RPS072','Seminar Manajemen Pemasaran',3,8,NULL,1,72,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(73,1,'RPS073','Manajemen SDM Strategik',3,5,NULL,1,73,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(74,1,'RPS074','Kepemimpinan',3,5,NULL,1,74,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(75,1,'RPS075','Sistem Informasi SDM',3,6,NULL,1,75,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(76,1,'RPS076','Manajemen Konflik dan Hubungan Industrial',3,6,NULL,1,76,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(77,1,'RPS077','Manajemen Kinerja',3,6,NULL,1,77,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(78,1,'RPS078','Manajemen Perubahan',3,6,NULL,1,78,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(79,1,'RPS079','Pelatihan dan Pengembangan SDM',3,7,NULL,1,79,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(80,1,'RPS080','Kesehatan dan Keselamatan Kerja',3,7,NULL,1,80,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(81,1,'RPS081','Organizational Development',3,7,NULL,1,81,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(82,1,'RPS082','Manajemen Kompensasi',3,7,NULL,1,82,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(83,1,'RPS083','Psikologi Industri',3,7,NULL,1,83,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(84,1,'RPS084','Manajemen Talenta',3,7,NULL,1,84,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(85,1,'RPS085','Manajemen SDM Internasional',3,7,NULL,1,85,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(86,1,'RPS086','Seminar Manajemen SDM',3,8,NULL,1,86,'2026-05-21 02:01:26','2026-05-21 03:44:52'),(87,1,'RPS087','KKP',3,7,NULL,1,87,'2026-05-21 02:01:26','2026-05-23 05:34:43'),(88,1,'RPS088','Tugas Akhir',6,8,NULL,1,88,'2026-05-21 02:01:26','2026-05-21 03:44:52');
/*!40000 ALTER TABLE `mata_kuliahs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_21_095200_create_program_studis_table',1),(5,'2026_05_21_095201_create_cpls_table',1),(6,'2026_05_21_095202_create_mata_kuliahs_table',1),(7,'2026_05_21_095203_create_mk_cpls_table',1),(8,'2026_05_21_095204_create_cpmks_table',1),(9,'2026_05_21_095205_create_cpmk_cpls_table',1),(10,'2026_05_21_095206_create_sub_cpmks_table',1),(11,'2026_05_21_095207_create_clo_taxonomis_table',1),(12,'2026_05_21_095208_create_sub_cpmk_clos_table',1),(13,'2026_05_21_095209_create_sub_cpmk_asesmens_table',1),(14,'2026_05_21_095210_create_rps_table',1),(15,'2026_05_21_095211_add_program_studi_id_to_users_table',1),(16,'2026_05_21_095611_create_permission_tables',1),(17,'2026_05_21_110327_create_mk_dosens_table',2),(18,'2026_05_23_000001_add_visi_misi_tujuan_to_program_studis_table',3),(19,'2026_05_23_000002_create_profil_lulusans_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mk_cpls`
--

DROP TABLE IF EXISTS `mk_cpls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mk_cpls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint(20) unsigned NOT NULL,
  `cpl_id` bigint(20) unsigned NOT NULL,
  `level` enum('Tinggi','Sedang','Rendah') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mk_cpls_mata_kuliah_id_cpl_id_unique` (`mata_kuliah_id`,`cpl_id`),
  KEY `mk_cpls_cpl_id_foreign` (`cpl_id`),
  CONSTRAINT `mk_cpls_cpl_id_foreign` FOREIGN KEY (`cpl_id`) REFERENCES `cpls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mk_cpls_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mk_cpls`
--

LOCK TABLES `mk_cpls` WRITE;
/*!40000 ALTER TABLE `mk_cpls` DISABLE KEYS */;
INSERT INTO `mk_cpls` VALUES (1,1,1,'Tinggi','2026-05-21 02:17:07','2026-05-21 03:44:52'),(2,1,2,'Tinggi','2026-05-21 02:17:08','2026-05-21 02:17:08'),(3,1,3,'Rendah','2026-05-21 02:17:10','2026-05-21 02:17:10'),(4,1,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(5,1,7,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(6,2,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(7,2,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(8,2,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(9,3,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(10,3,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(11,3,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(12,4,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(13,4,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(14,4,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(15,5,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(16,5,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(17,5,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(18,6,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(19,6,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(20,6,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(21,8,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(22,8,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(23,8,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(24,8,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(25,9,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(26,9,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(27,9,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(28,9,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(29,10,3,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(30,10,5,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(31,10,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(32,11,1,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(33,11,2,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(34,12,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(35,12,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(36,12,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(37,13,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(38,13,5,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(39,13,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(40,14,1,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(41,14,2,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(42,14,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(43,14,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(44,14,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(45,15,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(46,15,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(47,15,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(48,15,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(49,16,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(50,16,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(51,16,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(52,17,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(54,17,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(55,17,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(56,17,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(57,18,1,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(58,18,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(59,18,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(60,19,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(61,19,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(62,19,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(63,19,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(64,19,9,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(65,20,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(66,20,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(67,20,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(68,20,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(69,21,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(70,21,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(71,21,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(72,21,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(73,21,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(74,21,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(75,22,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(76,22,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(77,22,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(78,22,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(79,23,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(80,23,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(81,23,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(82,23,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(83,23,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(84,24,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(85,24,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(86,24,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(87,24,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(88,24,10,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(89,25,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(90,25,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(91,25,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(92,25,10,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(93,26,1,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(94,26,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(95,26,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(96,26,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(97,27,4,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(98,27,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(99,27,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(100,27,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(101,28,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(102,28,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(103,28,5,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(104,28,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(105,29,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(106,29,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(107,29,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(108,29,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(109,30,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(110,30,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(111,30,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(112,30,9,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(113,31,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(114,31,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(115,31,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(116,31,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(117,32,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(118,32,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(119,32,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(120,32,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(121,33,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(122,33,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(123,33,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(124,33,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(125,34,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(126,34,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(127,34,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(128,34,10,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(129,35,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(130,35,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(131,35,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(132,35,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(133,36,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(134,36,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(135,36,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(136,36,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(137,37,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(138,37,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(139,37,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(140,38,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(141,38,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(142,38,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(143,38,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(144,39,3,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(145,39,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(146,39,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(147,40,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(148,40,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(149,40,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(150,40,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(151,41,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(152,41,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(153,41,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(154,41,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(155,41,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(156,41,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(157,42,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(158,42,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(159,42,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(160,42,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(161,42,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(162,43,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(163,43,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(164,43,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(165,44,1,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(166,44,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(167,44,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(168,44,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(169,45,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(170,45,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(171,45,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(172,45,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(173,46,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(174,46,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(175,46,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(176,46,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(177,47,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(178,47,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(179,47,7,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(180,47,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(181,48,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(182,48,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(183,48,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(184,48,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(185,49,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(186,49,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(187,49,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(188,49,10,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(189,50,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(190,50,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(191,50,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(192,50,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(193,51,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(194,51,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(195,51,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(196,51,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(197,52,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(198,52,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(199,52,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(200,52,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(201,53,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(202,53,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(203,53,7,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(204,53,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(205,54,1,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(206,54,2,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(207,54,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(208,54,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(209,55,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(210,55,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(211,55,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(212,55,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(213,56,1,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(214,56,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(215,56,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(216,56,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(217,56,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(218,57,1,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(219,57,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(220,57,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(221,57,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(222,58,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(223,58,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(224,58,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(225,58,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(226,59,1,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(227,59,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(228,59,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(229,60,2,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(230,60,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(231,60,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(232,60,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(233,61,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(234,61,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(235,61,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(236,61,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(237,62,1,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(238,62,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(239,62,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(240,62,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(241,63,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(242,63,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(243,63,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(244,63,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(245,64,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(246,64,5,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(247,64,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(248,64,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(249,64,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(250,65,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(251,65,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(252,65,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(253,65,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(254,66,1,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(255,66,5,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(256,66,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(257,66,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(258,67,3,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(259,67,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(260,67,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(261,67,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(262,67,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(263,68,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(264,68,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(265,68,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(266,68,9,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(267,69,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(268,69,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(269,69,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(270,69,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(271,69,9,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(272,70,2,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(273,70,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(274,70,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(275,70,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(276,71,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(277,71,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(278,71,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(279,71,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(280,72,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(281,72,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(282,72,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(283,72,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(284,73,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(285,73,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(286,73,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(287,73,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(288,74,3,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(289,74,4,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(290,74,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(291,74,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(292,75,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(293,75,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(294,75,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(295,75,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(296,76,3,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(297,76,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(298,76,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(299,76,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(300,77,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(301,77,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(302,77,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(303,77,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(304,78,2,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(305,78,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(306,78,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(307,78,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(308,78,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(309,79,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(310,79,4,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(311,79,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(312,79,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(313,80,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(314,80,6,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(315,80,7,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(316,80,8,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(317,81,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(318,81,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(319,81,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(320,81,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(321,81,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(322,82,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(323,82,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(324,82,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(325,82,10,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(326,83,3,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(327,83,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(328,83,6,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(329,83,7,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(330,83,8,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(331,84,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(332,84,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(333,84,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(334,84,7,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(335,84,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(336,85,2,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(337,85,4,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(338,85,7,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(339,85,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(340,86,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(341,86,5,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(342,86,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(343,86,10,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(344,87,1,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(345,87,3,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(346,87,4,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(347,87,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(348,87,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(349,88,4,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(350,88,5,'Sedang','2026-05-21 03:44:52','2026-05-21 03:44:52'),(351,88,6,'Rendah','2026-05-21 03:44:52','2026-05-21 03:44:52'),(352,88,8,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52'),(353,88,10,'Tinggi','2026-05-21 03:44:52','2026-05-21 03:44:52');
/*!40000 ALTER TABLE `mk_cpls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mk_dosens`
--

DROP TABLE IF EXISTS `mk_dosens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mk_dosens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mk_dosens_mata_kuliah_id_user_id_unique` (`mata_kuliah_id`,`user_id`),
  KEY `mk_dosens_user_id_foreign` (`user_id`),
  CONSTRAINT `mk_dosens_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mk_dosens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mk_dosens`
--

LOCK TABLES `mk_dosens` WRITE;
/*!40000 ALTER TABLE `mk_dosens` DISABLE KEYS */;
INSERT INTO `mk_dosens` VALUES (1,1,46,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(2,2,26,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(3,3,45,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(4,8,27,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(5,9,27,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(6,10,41,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(7,11,12,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(8,12,23,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(9,13,43,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(10,14,37,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(11,15,13,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(12,16,33,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(13,17,20,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(14,18,43,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(15,19,37,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(16,20,18,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(17,21,15,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(18,22,40,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(19,23,31,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(20,24,11,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(21,25,22,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(22,26,19,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(23,27,30,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(24,28,35,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(25,29,36,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(26,30,42,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(27,31,37,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(28,32,35,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(29,33,32,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(30,34,25,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(31,35,14,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(32,36,12,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(33,37,19,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(34,38,9,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(35,39,48,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(36,40,9,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(37,41,37,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(38,42,17,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(39,43,38,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(40,44,34,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(41,45,25,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(42,46,18,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(43,47,22,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(44,48,16,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(45,49,36,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(46,50,36,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(47,51,16,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(48,52,23,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(49,53,16,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(50,54,32,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(51,55,33,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(52,56,24,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(53,57,24,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(54,58,32,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(55,59,28,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(56,60,17,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(57,61,28,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(58,62,48,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(59,63,29,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(60,64,48,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(61,65,48,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(62,66,12,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(63,67,19,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(64,68,21,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(65,69,29,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(66,70,35,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(67,71,15,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(68,72,48,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(69,84,39,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(70,74,26,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(71,75,14,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(72,73,39,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(73,77,30,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(74,78,40,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(75,79,10,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(76,80,47,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(77,81,40,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(78,82,44,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(79,83,40,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(80,76,8,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(81,85,26,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(82,86,44,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(83,87,39,'2026-05-21 04:18:25','2026-05-21 04:18:25'),(84,88,39,'2026-05-21 04:18:25','2026-05-21 04:18:25');
/*!40000 ALTER TABLE `mk_dosens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(3,'App\\Models\\User',4),(3,'App\\Models\\User',5),(3,'App\\Models\\User',6),(3,'App\\Models\\User',7),(3,'App\\Models\\User',8),(3,'App\\Models\\User',9),(3,'App\\Models\\User',10),(3,'App\\Models\\User',11),(3,'App\\Models\\User',12),(3,'App\\Models\\User',13),(3,'App\\Models\\User',14),(3,'App\\Models\\User',15),(3,'App\\Models\\User',16),(3,'App\\Models\\User',17),(3,'App\\Models\\User',18),(3,'App\\Models\\User',19),(3,'App\\Models\\User',20),(3,'App\\Models\\User',21),(3,'App\\Models\\User',22),(3,'App\\Models\\User',23),(3,'App\\Models\\User',24),(3,'App\\Models\\User',25),(3,'App\\Models\\User',26),(3,'App\\Models\\User',27),(3,'App\\Models\\User',28),(3,'App\\Models\\User',29),(3,'App\\Models\\User',30),(3,'App\\Models\\User',31),(3,'App\\Models\\User',32),(3,'App\\Models\\User',33),(3,'App\\Models\\User',34),(3,'App\\Models\\User',35),(3,'App\\Models\\User',36),(3,'App\\Models\\User',37),(3,'App\\Models\\User',38),(3,'App\\Models\\User',39),(3,'App\\Models\\User',40),(3,'App\\Models\\User',41),(3,'App\\Models\\User',42),(3,'App\\Models\\User',43),(3,'App\\Models\\User',44),(3,'App\\Models\\User',45),(3,'App\\Models\\User',46),(3,'App\\Models\\User',47),(3,'App\\Models\\User',48);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profil_lulusans`
--

DROP TABLE IF EXISTS `profil_lulusans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil_lulusans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_studi_id` bigint(20) unsigned NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profil_lulusans_program_studi_id_kode_unique` (`program_studi_id`,`kode`),
  CONSTRAINT `profil_lulusans_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profil_lulusans`
--

LOCK TABLES `profil_lulusans` WRITE;
/*!40000 ALTER TABLE `profil_lulusans` DISABLE KEYS */;
INSERT INTO `profil_lulusans` VALUES (1,1,'PL1','Wirausahawan Pemula','Lulusan yang memiliki jiwa wirausahawan yang tangguh, inovatif, adaptif, mampu mengidentifikasi peluang bisnis, serta dapat mendirikan dan mengembangkan usaha secara mandiri berdasarkan prinsip manajemen yang baik.',1,'2026-05-23 05:34:45','2026-05-23 05:34:45'),(2,1,'PL2','Analis Manajemen','Lulusan yang memiliki kemampuan berpikir kritis dan analitis berbasis data dalam mengidentifikasi permasalahan manajerial, merancang solusi, serta mendukung pengambilan keputusan strategis di organisasi bisnis maupun publik.',2,'2026-05-23 05:34:45','2026-05-23 05:34:45'),(3,1,'PL3','Profesional Bidang Manajemen Keuangan','Lulusan yang kompeten dalam pengelolaan dan pengambilan keputusan keuangan, analisis investasi, manajemen risiko keuangan, serta perencanaan keuangan di berbagai jenis organisasi.',3,'2026-05-23 05:34:45','2026-05-23 05:34:45'),(4,1,'PL4','Profesional Bidang Manajemen Pemasaran','Lulusan yang kompeten dalam mengelola kegiatan pemasaran, mengembangkan strategi pemasaran digital dan konvensional, serta mampu menganalisis perilaku konsumen dan tren pasar untuk mencapai tujuan organisasi.',4,'2026-05-23 05:34:45','2026-05-23 05:34:45'),(5,1,'PL5','Profesional Bidang Manajemen SDM','Lulusan yang kompeten dalam pengelolaan sumber daya manusia, meliputi rekrutmen, pengembangan, evaluasi kinerja, kompensasi, serta pengembangan budaya organisasi yang kondusif dan beretika.',5,'2026-05-23 05:34:45','2026-05-23 05:34:45');
/*!40000 ALTER TABLE `profil_lulusans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program_studis`
--

DROP TABLE IF EXISTS `program_studis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_studis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenjang` enum('D3','D4','S1','S2','S3') NOT NULL DEFAULT 'S1',
  `fakultas` varchar(255) DEFAULT NULL,
  `universitas` varchar(255) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `tujuan` text DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_studis_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_studis`
--

LOCK TABLES `program_studis` WRITE;
/*!40000 ALTER TABLE `program_studis` DISABLE KEYS */;
INSERT INTO `program_studis` VALUES (1,'MNJ-S1','Manajemen','S1','Fakultas Ekonomi dan Bisnis','Universitas Negeri Makassar','Program studi unggul dalam penerapan Ilmu Manajemen berbasis literasi sains teknologi dan seni berwawasan kewirausahaan di Asia Tenggara','Menyelenggarakan pendidikan ilmu manajemen berbasis literasi sains teknologi dan seni berwawasan kewirausahaan\nMenyelenggarakan penelitian bidang manajemen berbasis literasi sains teknologi dan seni berwawasan kewirausahaan\nMenyelenggarakan pengabdian kepada masyarakat bidang manajemen berbasis literasi sain teknologi dan seni berwawasan kewirausahaan\nMenyelenggarakan tata kelola program studi berdasarkan prinsip good university governance\nMenjalin kerjasama dengan stakeholders/mitra di dalam maupun luar negeri secara berkelanjutan','Menghasilkan lulusan yang memiliki kompetensi di bidang ilmu manajemen berbasis literasi sains teknologi dan seni berwawasan kewirausahaan\nMenghasilkan karya penelitian di bidang manajemen berbasis literasi sains teknologi dan seni berwawasan kewirausahaan\nMenghasilkan karya pengabdian kepada masyarakat di bidang manajemen berbasis literasi sains teknologi dan seni berwawasan kewirausahaan\nMewujudkan tata kelola program studi yang baik berdasarkan prinsip good university governance\nMenghasilkan kerjasama yang produktif dengan stakeholders/mitra di dalam maupun luar negeri',1,'2026-05-21 02:01:26','2026-05-23 05:34:42');
/*!40000 ALTER TABLE `program_studis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2026-05-21 02:01:26','2026-05-21 02:01:26'),(2,'kaprodi','web','2026-05-21 02:01:26','2026-05-21 02:01:26'),(3,'dosen','web','2026-05-21 02:01:26','2026-05-21 02:01:26');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rps`
--

DROP TABLE IF EXISTS `rps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint(20) unsigned NOT NULL,
  `dosen_id` bigint(20) unsigned NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status` enum('Draft','Review','Final') NOT NULL DEFAULT 'Draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rps_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `rps_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `rps_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rps`
--

LOCK TABLES `rps` WRITE;
/*!40000 ALTER TABLE `rps` DISABLE KEYS */;
/*!40000 ALTER TABLE `rps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2syvtVnrzOUvtUix150yHFlfLNxuNMdYjgeTTGlF',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJNbU5KY2VVRmdSMUdrWjl0MGdXR3IxcDNrSEticWNLdlJzUXBjT1dCIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2t1cmlrdWx1bSIsInJvdXRlIjoicHVibGljLmt1cmlrdWx1bSJ9fQ==',1779552920),('HNBwEHRwvPK7IDmvPVza05WXUj3HZ6WB8QjWYBRG',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJOdzB2aXNwWkdSNkkwbGpiaXBUTWxkU2puenNLSURWVlZuREVFWUQ4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9rdXJpa3VsdW1cL2NwbWtcL3RhYmVsMTEiLCJyb3V0ZSI6Imt1cmlrdWx1bS5jcG1rLnRhYmVsMTEifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1779552932);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_cpmk_asesmens`
--

DROP TABLE IF EXISTS `sub_cpmk_asesmens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_cpmk_asesmens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_cpmk_id` bigint(20) unsigned NOT NULL,
  `tugas` decimal(5,2) NOT NULL DEFAULT 0.00,
  `quis` decimal(5,2) NOT NULL DEFAULT 0.00,
  `kasus_proyek` decimal(5,2) NOT NULL DEFAULT 0.00,
  `uts` decimal(5,2) NOT NULL DEFAULT 0.00,
  `uas` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_cpmk_asesmens_sub_cpmk_id_foreign` (`sub_cpmk_id`),
  CONSTRAINT `sub_cpmk_asesmens_sub_cpmk_id_foreign` FOREIGN KEY (`sub_cpmk_id`) REFERENCES `sub_cpmks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_cpmk_asesmens`
--

LOCK TABLES `sub_cpmk_asesmens` WRITE;
/*!40000 ALTER TABLE `sub_cpmk_asesmens` DISABLE KEYS */;
INSERT INTO `sub_cpmk_asesmens` VALUES (1,1,5.00,5.00,0.00,5.00,5.00,'2026-05-23 08:03:30','2026-05-23 08:14:39'),(2,2,5.00,5.00,0.00,5.00,5.00,'2026-05-23 08:13:17','2026-05-23 08:14:39'),(3,3,0.00,0.00,5.00,2.50,5.00,'2026-05-23 08:14:01','2026-05-23 08:14:47'),(4,4,0.00,0.00,5.00,2.50,5.00,'2026-05-23 08:14:02','2026-05-23 08:14:50'),(5,5,2.50,0.00,5.00,2.50,2.50,'2026-05-23 08:14:08','2026-05-23 08:14:58'),(6,6,10.00,5.00,0.00,2.50,5.00,'2026-05-23 08:15:02','2026-05-23 08:15:18');
/*!40000 ALTER TABLE `sub_cpmk_asesmens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_cpmk_clos`
--

DROP TABLE IF EXISTS `sub_cpmk_clos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_cpmk_clos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_cpmk_id` bigint(20) unsigned NOT NULL,
  `clo_id` bigint(20) unsigned NOT NULL,
  `is_tertinggi` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sub_cpmk_clos_sub_cpmk_id_clo_id_unique` (`sub_cpmk_id`,`clo_id`),
  KEY `sub_cpmk_clos_clo_id_foreign` (`clo_id`),
  CONSTRAINT `sub_cpmk_clos_clo_id_foreign` FOREIGN KEY (`clo_id`) REFERENCES `clo_taxonomis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sub_cpmk_clos_sub_cpmk_id_foreign` FOREIGN KEY (`sub_cpmk_id`) REFERENCES `sub_cpmks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_cpmk_clos`
--

LOCK TABLES `sub_cpmk_clos` WRITE;
/*!40000 ALTER TABLE `sub_cpmk_clos` DISABLE KEYS */;
INSERT INTO `sub_cpmk_clos` VALUES (1,1,9,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(2,1,2,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(3,2,10,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(4,2,4,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(5,3,10,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(6,3,4,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(7,3,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(8,4,6,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(9,4,16,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(10,5,11,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(11,5,5,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(12,5,17,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(13,6,11,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(14,6,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(15,7,8,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(16,7,2,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(17,8,8,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(18,8,2,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(19,9,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(20,9,3,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(21,10,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(22,10,4,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(23,10,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(24,11,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(25,11,4,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(26,11,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(27,12,10,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(28,12,5,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(29,13,10,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(30,13,5,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(31,13,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(32,14,10,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(33,14,6,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(34,14,16,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(35,15,8,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(36,15,2,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(37,16,8,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(38,16,3,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(39,17,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(40,17,4,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(41,18,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(42,18,6,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(43,18,16,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(44,19,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(45,19,4,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(46,19,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(47,20,9,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(48,20,5,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(49,20,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(50,21,11,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(51,21,14,0,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(52,22,11,1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(53,22,15,0,'2026-05-21 19:01:04','2026-05-21 19:01:04');
/*!40000 ALTER TABLE `sub_cpmk_clos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_cpmks`
--

DROP TABLE IF EXISTS `sub_cpmks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_cpmks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cpmk_id` bigint(20) unsigned NOT NULL,
  `mata_kuliah_id` bigint(20) unsigned NOT NULL,
  `kode` varchar(30) NOT NULL,
  `deskripsi` text NOT NULL,
  `kode_clo_tertinggi` varchar(255) DEFAULT NULL,
  `urutan` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_cpmks_cpmk_id_foreign` (`cpmk_id`),
  KEY `sub_cpmks_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `sub_cpmks_cpmk_id_foreign` FOREIGN KEY (`cpmk_id`) REFERENCES `cpmks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sub_cpmks_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_cpmks`
--

LOCK TABLES `sub_cpmks` WRITE;
/*!40000 ALTER TABLE `sub_cpmks` DISABLE KEYS */;
INSERT INTO `sub_cpmks` VALUES (1,7,11,'Sub-CPMK 1.1','Menganalisis penerapan literasi sains, teknologi, dan seni dalam pengambilan keputusan dan inovasi manajemen.','A3',1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(2,7,11,'Sub-CPMK 1.2','Mengintegrasikan literasi sains, teknologi, dan seni dalam penyelesaian masalah manajemen secara kreatif.','C4',2,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(3,8,11,'Sub-CPMK 2.1','Mengidentifikasi peluang kewirausahaan berbasis sains, teknologi, dan seni.','C4',3,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(4,8,11,'Sub-CPMK 2.2','Mengembangkan rancangan produk, layanan, atau strategi bisnis inovatif berbasis literasi sains, teknologi, dan seni.','C6',4,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(5,8,11,'Sub-CPMK 2.3','Mengimplementasikan ide kreatif dan inovatif dalam konteks kewirausahaan.','C5',5,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(6,9,11,'Sub-CPMK 3.1','Menunjukkan sikap humanis, etis, kolaboratif, dan bertanggung jawab dalam pemanfaatan sains, teknologi, dan seni.','A5',6,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(7,10,36,'Sub-CPMK 1.1','Menjelaskan hubungan antara data, informasi, dan sistem informasi dalam organisasi','C2',1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(8,10,36,'Sub-CPMK 1.2','Menguraikan komponen utama sistem informasi manajemen','C2',2,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(9,10,36,'Sub-CPMK 1.3','Menjelaskan fungsi dan peranan sistem informasi manajemen dalam mendukung proses dan strategi organisasi','C3',3,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(10,11,36,'Sub-CPMK 2.1','Menganalisis kebutuhan informasi pada fungsi-fungsi manajerial','C4',4,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(11,11,36,'Sub-CPMK 2.2','Memetakan proses bisnis organisasi untuk identifikasi kebutuhan sistem informasi','C4',5,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(12,12,36,'Sub-CPMK 3.1','Mengevaluasi efektivitas pemanfaatan teknologi informasi dalam proses bisnis organisasi','C5',6,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(13,12,36,'Sub-CPMK 3.2','Memberikan rekomendasi inovasi berbasis teknologi informasi untuk meningkatkan kinerja organisasi','C5',7,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(14,11,36,'Sub-CPMK 2.3','Merancang model sistem informasi yang relevan dengan kebutuhan dan permasalahan manajerial organisasi','C6',8,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(15,13,66,'Sub-CPMK 1.1','Menjelaskan prinsip dasar manajemen konten digital dan fungsinya dalam komunikasi pemasaran.','C2',1,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(16,13,66,'Sub-CPMK 1.2','Mengidentifikasi elemen konten yang membentuk daya tarik serta efektivitas pesan konten digital.','C3',2,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(17,14,66,'Sub-CPMK 2.1','Menganalisis kebutuhan audiens dan tren digital dalam perencanaan konten.','C4',3,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(18,14,66,'Sub-CPMK 2.2','Menghasilkan rancangan konten kreatif yang menarik dan relevan dengan identitas merek.','C6',4,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(19,15,66,'Sub-CPMK 3.1','Menganalisis data performa konten dari berbagai platform digital','C4',5,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(20,15,66,'Sub-CPMK 3.2','Mengevaluasi kesesuaian desain visual dan pesan komunikasi dengan tujuan strategis.','C5',6,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(21,16,66,'Sub-CPMK 4.1','Menunjukkan sikap etis dan tanggung jawab dalam produksi serta publikasi konten.','A5',7,'2026-05-21 19:01:04','2026-05-21 19:01:04'),(22,16,66,'Sub-CPMK 4.2','Menunjukkan sikap kolaboratif dan profesional dalam proyek pembuatan konten digital.','A5',8,'2026-05-21 19:01:04','2026-05-21 19:01:04');
/*!40000 ALTER TABLE `sub_cpmks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `program_studi_id` bigint(20) unsigned DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_program_studi_id_foreign` (`program_studi_id`),
  CONSTRAINT `users_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studis` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@kurikulum.id','2026-05-21 02:10:41','$2y$12$hHD1.uebP9ob6SsXCcvvNeNPi6f3gyjwoTjq5B90cpoozL8iKJZrG',NULL,'2026-05-21 02:01:27','2026-05-21 02:10:41',1,NULL,NULL),(2,'Ketua Program Studi','kaprodi@kurikulum.id','2026-05-21 02:10:41','$2y$12$uP/MYBuQ0zENwtc.zb.LQuo6Ayyq9GFULqoKVJZ2U289vkby9J5D2',NULL,'2026-05-21 02:01:28','2026-05-21 02:10:41',1,NULL,'Ketua Prodi'),(3,'Dosen Demo','dosen@kurikulum.id','2026-05-21 02:10:41','$2y$12$YGbW7gB0BKPikIPb/.RVROYiRN3Ua12zsVHopdG.YpTT1vWw2HUhi',NULL,'2026-05-21 02:01:29','2026-05-21 02:10:41',1,'198001012010121001',NULL),(4,'Dr. Andi Mappa, S.E., M.M.','andi.mappa@unm.ac.id',NULL,'$2y$12$hDsx7vm2X.Qk6oiNLcjE/.rrhdqtGR1Yjs/NWWs4UODiHGWpbjtOO',NULL,'2026-05-21 03:16:38','2026-05-21 03:16:38',1,'198501012010011001',NULL),(5,'Prof. Sitti Rahma, S.E., M.Si.','sitti.rahma@unm.ac.id',NULL,'$2y$12$0op2KrU332jJ5RYsMRugcONcMpcpq7K3HINZko3fqLVFUGnQulE5K',NULL,'2026-05-21 03:16:39','2026-05-21 03:16:39',1,'197203152005012002',NULL),(6,'Drs. Hasanuddin, M.M.','hasanuddin@unm.ac.id',NULL,'$2y$12$14rHBNSzrz25K3uFkbkoP.OfxucNNvKLs4qH42n1O4Cnp2TNs47JW',NULL,'2026-05-21 03:16:39','2026-05-21 03:16:39',1,'196811221999031003',NULL),(7,'Nurhayati, S.E., M.Si.','nurhayati@unm.ac.id',NULL,'$2y$12$B2A2xvQKE27k/Nf39zxCNeSUyO5MbG6suoAWQheHkfOVV9oA7xfRe',NULL,'2026-05-21 03:16:39','2026-05-21 03:16:39',1,'199001052018012001',NULL),(8,'A. Nurfiana Haz, S.E., M.M.','a.nurfiana@kurikulum.id','2026-05-21 04:18:05','$2y$12$t/OlONWNBXCb3fAAQFmWE.LKpUZf85ESJ8GRv6K4nNeja0.8Q7YuO',NULL,'2026-05-21 04:18:05','2026-05-21 04:18:05',1,NULL,NULL),(9,'A. Reski Almaida Dg Macenning, S.Pd., M.M.','a.reski@kurikulum.id','2026-05-21 04:18:05','$2y$12$9OC7qljwIOYek8RXW4m.Ced1Hrx11VVhwV8bJvteY1TQS.9ankxn.',NULL,'2026-05-21 04:18:05','2026-05-21 04:18:05',1,NULL,NULL),(10,'Abdul Muis, S.E., M.M.','abdul.muis@kurikulum.id','2026-05-21 04:18:06','$2y$12$0Wv7/oJwMXdAhr5OUuGgUOQ/BykGLUlmyEX7xwnSzAOc09.M4qlbm',NULL,'2026-05-21 04:18:06','2026-05-21 04:18:06',1,NULL,NULL),(11,'Abdul Rahman','abdul.rahman@kurikulum.id','2026-05-21 04:18:06','$2y$12$JszebChYFUiCqCVCAmIOJ.NkBdNuLPsZ4jPiByQZdV6A.qTyB.oUW',NULL,'2026-05-21 04:18:06','2026-05-21 04:18:06',1,NULL,NULL),(12,'Achmad Ridha, S.M., M.M.','achmad.ridha@kurikulum.id','2026-05-21 04:18:07','$2y$12$45R/.AdpBBnAH/Sa2HFJS.V/w2r6iVKqmY/d3ozKRCrNAWB1D.FFe',NULL,'2026-05-21 04:18:07','2026-05-21 04:28:34',1,NULL,NULL),(13,'Ade Vidya Eryanti K., S.M., M.M.','ade.vidya@kurikulum.id','2026-05-21 04:18:08','$2y$12$sSkmO8twCBNZD7jUXwm98upRtm8quhjlQGIA0Ol16P4RdIhzIZpRm',NULL,'2026-05-21 04:18:08','2026-05-21 04:18:08',1,NULL,NULL),(14,'Anang Setiawan, S.M., M.M.','anang.setiawan@kurikulum.id','2026-05-21 04:18:09','$2y$12$thtdHYQOi24uKNw1eWteAO2g1EtaS/hgr26D0QL9cAP7J4z4Zdve.',NULL,'2026-05-21 04:18:09','2026-05-21 04:18:09',1,NULL,NULL),(15,'Andi Aryani Hardiyanti, S.Si., M.M.','andi.aryani@kurikulum.id','2026-05-21 04:18:10','$2y$12$NTPeGYbxf6vz9gR2FGA.Xu541DuhChd.Udu6Pad/QBnaxySWC4sV6',NULL,'2026-05-21 04:18:10','2026-05-21 04:18:10',1,NULL,NULL),(16,'Andi Dewi Angreyani','andi.dewi@kurikulum.id','2026-05-21 04:18:10','$2y$12$b8DwSmfd3q9Ay0BeQF75LOk1LRV2jcb6.o7LpBx7vjiEBFWmIaKaO',NULL,'2026-05-21 04:18:10','2026-05-21 04:18:10',1,NULL,NULL),(17,'Chairunnisaa M Z Huzaen, S.E., M.S.M.','chairunnisaa.m@kurikulum.id','2026-05-21 04:18:11','$2y$12$fHTy.JreqoSVPoT3MvVWD.9YG747PU1/9vnAs9flqPrJxY3p7Q1Hu',NULL,'2026-05-21 04:18:11','2026-05-21 04:18:11',1,NULL,NULL),(18,'Deddy Ibrahim Rauf, S.E., M.M.','deddy.ibrahim@kurikulum.id','2026-05-21 04:18:12','$2y$12$hdTCH5lmOc.s/ccOhgzjdeRcR.1x3BuKUwcuARhHUQ.H3Qu52eiJy',NULL,'2026-05-21 04:18:12','2026-05-21 04:18:12',1,NULL,NULL),(19,'Dr. Azlan Azhari, S.E., M.M.','azlan.azhari@kurikulum.id','2026-05-21 04:18:12','$2y$12$4IvELr84NH2Weob/plCwbuBDD1kT9/GCqweMy5o3KvQWSBbmtBc9q',NULL,'2026-05-21 04:18:12','2026-05-21 04:18:12',1,NULL,NULL),(20,'Dr. Hasnidar, S.E., M.M.','hasnidar@kurikulum.id','2026-05-21 04:18:13','$2y$12$6DUJh6Q4JmABvXi23HcaJ.CDLkvInPvwYa3pZr5MuTFkS9Ld7sDwe',NULL,'2026-05-21 04:18:13','2026-05-21 04:18:13',1,NULL,NULL),(21,'Dr. Nurul Fadilah Aswar, S.E., M.M.','nurul.fadilah@kurikulum.id','2026-05-21 04:18:13','$2y$12$oy9kWuEJihY7c.KHJwmJhuVPtchKsQGuV9EY5D8WMcJVjWO3LCany',NULL,'2026-05-21 04:18:13','2026-05-21 04:18:13',1,NULL,NULL),(22,'Fakhirah Husain, S.E., M.M.','fakhirah.husain@kurikulum.id','2026-05-21 04:18:14','$2y$12$Sr/1SOha30Su7wqttZ2QvOcQZD4.qup5Uqp9vICVUYgZBU3iBM2Pi',NULL,'2026-05-21 04:18:14','2026-05-21 04:18:14',1,NULL,NULL),(23,'Fitriani Rahim, S.E., M.M.','fitriani.rahim@kurikulum.id','2026-05-21 04:18:14','$2y$12$Py2qmNFDwi6LJSO5/BYiD.swYj6fFMILaE6yKp1lDGs9nouOWN3yq',NULL,'2026-05-21 04:18:14','2026-05-21 04:18:14',1,NULL,NULL),(24,'Indah Lestari Anwar, S.E., M.S.M.','indah.lestari@kurikulum.id','2026-05-21 04:18:15','$2y$12$gJa0D7I1nCVnEoAnc1xVYO66ysx9uoTWD3XZRVSVBfKJhQmSnROiq',NULL,'2026-05-21 04:18:15','2026-05-21 04:18:15',1,NULL,NULL),(25,'Indri Iswardhani, S.E., M.M.','indri.iswardhani@kurikulum.id','2026-05-21 04:18:15','$2y$12$Z8iKlP0iO0sB1F5mmJGem.kRZc5ZFQ4FuSqSpY8nS5Xyx77kiFsrC',NULL,'2026-05-21 04:18:15','2026-05-21 04:18:15',1,NULL,NULL),(26,'Josafat Gracia Ginting, S.M., M.M.','josafat.gracia@kurikulum.id','2026-05-21 04:18:15','$2y$12$x8PrEbNvVOyihY9ImwUP/e2A1FSX8Xo/HtrSiYeoL93FxAzLbt2W6',NULL,'2026-05-21 04:18:15','2026-05-21 04:18:15',1,NULL,NULL),(27,'M. Yunasri Ridhoh, S.Pd., M.Pd.','m.yunasri@kurikulum.id','2026-05-21 04:18:16','$2y$12$SpPcEfO/Ot0lCxl9Y53VbOiJM1h4dqMKnl67XnPFoKxOONcx7x7li',NULL,'2026-05-21 04:18:16','2026-05-21 04:18:16',1,NULL,NULL),(28,'Mufidatul Azmi, S.M., M.M.','mufidatul.azmi@kurikulum.id','2026-05-21 04:18:16','$2y$12$5CdGoqDuOv.mpWAX24gDle0nfLfBtiJ25aIVQE1sos/1QARQ/7gTu',NULL,'2026-05-21 04:18:16','2026-05-21 04:18:16',1,NULL,NULL),(29,'Muh Al Fatah Arief Putra, S.E., M.M.','muh.al@kurikulum.id','2026-05-21 04:18:17','$2y$12$O1X.5v4QMLzWynYI.o8zbeq6sz/z/xte3gIVYRzB/gwKG6l4YvnAC',NULL,'2026-05-21 04:18:17','2026-05-21 04:18:17',1,NULL,NULL),(30,'Muh. Nurfadel Hamzah, S.Tr.Pel., M.M.','muh.nurfadel@kurikulum.id','2026-05-21 04:18:17','$2y$12$kjRyQ12FSS4T70Hy2bnxcudT4DJ7uraydsWo67UA9ohOPSKZ3tgvW',NULL,'2026-05-21 04:18:17','2026-05-21 04:18:17',1,NULL,NULL),(31,'Muhaidir Ikram M., S.M., M.M.','muhaidir.ikram@kurikulum.id','2026-05-21 04:18:18','$2y$12$QebcB2eDzujJ8VRc.bmw7ueRNl3JIh55tBA9G9W3FpjRKGjBplSYm',NULL,'2026-05-21 04:18:18','2026-05-21 04:18:18',1,NULL,NULL),(32,'Muhammad Rijal Alim Rahmat','muhammad.rijal@kurikulum.id','2026-05-21 04:18:18','$2y$12$APZ69VIVemvEkCAyE80vbOV3zTy4m68AaQzXnfIH8cirWh.iCtU8O',NULL,'2026-05-21 04:18:18','2026-05-21 04:18:18',1,NULL,NULL),(33,'Nidrah, S.E., M.M.','nidrah@kurikulum.id','2026-05-21 04:18:19','$2y$12$MZRYfnTBIBybCZtq.cOVEOgwH/1HTHUSjbT2bPUiWXdZOquW9Z2gK',NULL,'2026-05-21 04:18:19','2026-05-21 04:18:19',1,NULL,NULL),(34,'Nulthazam Sarah, S.E., M.M.','nulthazam.sarah@kurikulum.id','2026-05-21 04:18:19','$2y$12$SLIBoGHgiBudAkw1BRdKE.a1viXu5S2LK5JmDoWY5ro.IPT6NS4Pu',NULL,'2026-05-21 04:18:19','2026-05-21 04:18:19',1,NULL,NULL),(35,'Nur Fadilah Ayu Sandira, S.Pd., M.M.','nur.fadilah@kurikulum.id','2026-05-21 04:18:20','$2y$12$07Ein5ruyyA21E0q0XbrnOMedNuIOJSUlWuaI.tJgqNIElsRClD0.',NULL,'2026-05-21 04:18:20','2026-05-21 04:18:20',1,NULL,NULL),(36,'Nurhaedah, S.E., M.Si.','nurhaedah@kurikulum.id','2026-05-21 04:18:20','$2y$12$vUhJPTsKmw1.9GTlknlBkubEUbAETUQSilmHu9ZAVdtYQ/Umx8Mj6',NULL,'2026-05-21 04:18:20','2026-05-21 04:18:20',1,NULL,NULL),(37,'Prof. Dr. Romansyah Sahabuddin,S.E.,M.Si','dr.romansyah@kurikulum.id','2026-05-21 04:18:21','$2y$12$DGao3SQrBw7.RsT/sf4M8.PbRzigtqQptAR5iJkTh4UXqXoNeVPtm',NULL,'2026-05-21 04:18:20','2026-05-21 04:18:21',1,NULL,NULL),(38,'Rahmat Burhamzah, S.Pd., M.M., Ak.','rahmat.burhamzah@kurikulum.id','2026-05-21 04:18:21','$2y$12$bSgjyPNFGefgU74rFsucfeAGuH/3KWdhwySfkk5Pm9e1ZMmFr8iju',NULL,'2026-05-21 04:18:21','2026-05-21 04:18:21',1,NULL,NULL),(39,'Rezky Amalia Hamka, S.E., M.M.','rezky.amalia@kurikulum.id','2026-05-21 04:18:22','$2y$12$TSn3k40mRMVGe25QO.Oh.utRdmQgJd/Jt.bivcLWKfqEIPqPwcGoS',NULL,'2026-05-21 04:18:22','2026-05-21 04:18:22',1,NULL,NULL),(40,'Ridfan Abadi, S.E., M.M.','ridfan.abadi@kurikulum.id','2026-05-21 04:18:22','$2y$12$eNcCNoJdipVX9TU4b6KPkOZnWMK6Q6qZUzSpndat3R92YJEHLcpcq',NULL,'2026-05-21 04:18:22','2026-05-21 04:18:22',1,NULL,NULL),(41,'Ridwan Andi Mattoliang, S.Pd., M.Pd.','ridwan.andi@kurikulum.id','2026-05-21 04:18:22','$2y$12$QdSGquTLf5ZaSRiZzNpjaOcY9nwfio2HLq9G5hgS32vOR6fQsAT82',NULL,'2026-05-21 04:18:22','2026-05-21 04:18:22',1,NULL,NULL),(42,'Rika Kurniawati, S.E., M.M.','rika.kurniawati@kurikulum.id','2026-05-21 04:18:23','$2y$12$0gctLFVWN4vXGDJczieMluaOfdKkUWHnE7Esvm2xE0aBZ0IkPQb5q',NULL,'2026-05-21 04:18:23','2026-05-21 04:18:23',1,NULL,NULL),(43,'Rostina Alimuddin, S.Pd., M.Pd.','rostina.alimuddin@kurikulum.id','2026-05-21 04:18:23','$2y$12$w0Ef5PJ7I2JgXF1GulM6huCVUF5Arr8DOiyEJo7PIp3Pl6tJmQTTK',NULL,'2026-05-21 04:18:23','2026-05-21 04:18:23',1,NULL,NULL),(44,'Sri Astuti Nasir, S.Pd.,M.M','sri.astuti@kurikulum.id','2026-05-21 04:18:23','$2y$12$6jD.uXbYqLn7cPTEKvH0Pe6umBNK.ofVhel5s/tRrCBS0YOXAniOa',NULL,'2026-05-21 04:18:23','2026-05-21 04:18:23',1,NULL,NULL),(45,'Stefanie Inggried','stefanie.inggried@kurikulum.id','2026-05-21 04:18:24','$2y$12$lwAZfr.4vf/e2iNtSDqALuVpWS1MIqgweugGMKkGs0P592BIpYW7G',NULL,'2026-05-21 04:18:24','2026-05-21 04:18:24',1,NULL,NULL),(46,'Ushwa Dwi Masrurah Arifin Bando, S.Pd.I., M.Pd.','ushwa.dwi@kurikulum.id','2026-05-21 04:18:24','$2y$12$H2mCAe7fD.oQDI2vzBOgF.PC4F/GvtsfQXdUJvVEo4aVbcsV11ZDi',NULL,'2026-05-21 04:18:24','2026-05-21 04:18:24',1,NULL,NULL),(47,'Widhi Nugraha SD','widhi.nugraha@kurikulum.id','2026-05-21 04:18:24','$2y$12$gq1LnQ6P/fuMFlPMNVDrXO8vqsmGFrq8wsUgwLO8ukRgruDlLZTPW',NULL,'2026-05-21 04:18:24','2026-05-21 04:18:24',1,NULL,NULL),(48,'Wiwin Riski Windarsari, S.E., M.M.','wiwin.riski@kurikulum.id','2026-05-21 04:18:25','$2y$12$hQ7iL0KXKxGeLf9gkohzruqqWM4mTVWlik9.B4v5hITEewx0L3IsO',NULL,'2026-05-21 04:18:25','2026-05-21 04:18:25',1,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-24  0:20:25

SET FOREIGN_KEY_CHECKS=1;