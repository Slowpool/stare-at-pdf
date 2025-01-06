-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: localhost    Database: stare_at_pdf
-- ------------------------------------------------------
-- Server version	8.0.39

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
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1734255694),('m241215_093611_create_user_table',1734256169),('m241215_093846_create_pdf_file_table',1734256169),('m241215_115644_dummy_data',1734264678),('m250104_084040_create_pdf_file_category_table',1735980583),('m250104_174300_create_pdf_file_category_entry_table',1736012720);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdf_file`
--

DROP TABLE IF EXISTS `pdf_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pdf_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `bookmark` int NOT NULL DEFAULT '1',
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pdf_file` (`name`,`user_id`),
  KEY `idx-pdf_file-user_id` (`user_id`),
  CONSTRAINT `fk-pdf_file-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdf_file`
--

LOCK TABLES `pdf_file` WRITE;
/*!40000 ALTER TABLE `pdf_file` DISABLE KEYS */;
INSERT INTO `pdf_file` VALUES (1,'EFC eng',155,1),(2,'EFC eng',150,2),(3,'semaphores',150,1),(4,'semaphores',150,2),(5,'dummy pdf 1',1,1),(6,'dummy pdf 2',2,1),(7,'dummy pdf 3',3,1),(8,'dummy pdf 4',4,1),(9,'dummy pdf 5',5,1),(10,'dummy pdf 6',6,1),(11,'dummy pdf 7',7,1),(12,'dummy pdf 15',15,1),(28,'http',1,1),(29,'jquery_RU',1,1);
/*!40000 ALTER TABLE `pdf_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdf_file_category`
--

DROP TABLE IF EXISTS `pdf_file_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pdf_file_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` char(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unidx-pdf_file_category-user_id-name` (`user_id`,`name`),
  KEY `idx-pdf_file_category-user_id` (`user_id`),
  CONSTRAINT `fk-pdf_file_category-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdf_file_category`
--

LOCK TABLES `pdf_file_category` WRITE;
/*!40000 ALTER TABLE `pdf_file_category` DISABLE KEYS */;
INSERT INTO `pdf_file_category` VALUES (16,3,'C#','ff00e6'),(17,3,'English','1dd067'),(18,3,'Polish','ff0000'),(19,3,'yii','f18a2a'),(20,3,'php','fffb02'),(21,3,'multithreading','027cff');
/*!40000 ALTER TABLE `pdf_file_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdf_file_category_entry`
--

DROP TABLE IF EXISTS `pdf_file_category_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pdf_file_category_entry` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pdf_file_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unidx-pdf_file_category_entry-category_id` (`category_id`,`pdf_file_id`),
  KEY `idx-pdf_file_category_entry-pdf_file_id` (`pdf_file_id`),
  KEY `idx-pdf_file_category_entry-category_id` (`category_id`),
  CONSTRAINT `fk-pdf_file_category_entry-category_id` FOREIGN KEY (`category_id`) REFERENCES `pdf_file_category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-pdf_file_category_entry-pdf_file_id` FOREIGN KEY (`pdf_file_id`) REFERENCES `pdf_file` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdf_file_category_entry`
--

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password_hash` char(64) NOT NULL,
  `access_token` char(16) NOT NULL,
  `auth_key` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `access_token` (`access_token`),
  UNIQUE KEY `auth_key` (`auth_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','d6293a1eb09b09063261b11a84f404bc79440d7259389711114c1d902018c060','acAdmin','auAdmin'),(2,'john','03ac26e98b562753f9198b0f1a31c30e8b2b6cde8c1baea74a61e4a7db62c0e7','acJohn','auJohn'),(3,'slowpool','03ac26e98b562753f9198b0f1a31c30e8b2b6cde8c1baea74a61e4a7db62c0e7','acSlowp','auSlowp');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-06 16:15:55
