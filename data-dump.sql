-- MySQL dump 10.13  Distrib 8.0.35, for Linux (aarch64)
--
-- Host: localhost    Database: service
-- ------------------------------------------------------
-- Server version	8.0.35

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_880E0D76E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$9Hr1t6Cki6xXZdpK.1PnhuNfADBfd.hur/oRK16TDhP0xDGISTDiK','2023-11-26 19:25:31','2023-11-26 19:25:31'),(2,'admin2@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$BmggnOzfU8R1.Y2Xd3FJdekTNKtT4nA/a6pNLZC6ZiV1FnC1olVn6','2023-11-26 19:25:40','2023-11-26 19:25:40');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (2,'Ремонт смартфонів',NULL,1,'2023-11-26 19:11:46','2023-11-26 19:11:49'),(3,'Ремонт ноутбуків та персональних комп\'ютерів',NULL,1,'2023-11-26 19:11:58','2023-11-26 19:12:01'),(4,'Ремонт планшетів',NULL,1,'2023-11-26 19:12:11','2023-11-26 19:12:12'),(5,'Ремонт телевізорів',NULL,1,'2023-11-26 19:12:25','2023-11-26 19:12:27'),(6,'Ремонт ігрових приставок',NULL,0,'2023-11-26 19:12:37','2023-11-26 19:12:37');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  KEY `IDX_F52993984584665A` (`product_id`),
  CONSTRAINT `FK_F52993984584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (2,2,10,'2023-11-26 19:28:48','2023-11-26 19:28:48'),(3,3,3,'2023-11-26 19:28:53','2023-11-26 19:28:53'),(4,4,8,'2023-11-26 19:28:58','2023-11-26 19:28:58'),(5,2,12,'2023-11-26 19:29:02','2023-11-26 19:29:02'),(6,5,9,'2023-11-26 19:29:11','2023-11-26 19:29:11');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(20,2) NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (2,2,'Заміна розбитого екрану',NULL,500.00,NULL,NULL,1,'2023-11-26 19:14:20','2023-11-26 19:14:20'),(3,2,'Ремонт пошкоджених кнопок',NULL,550.00,NULL,NULL,1,'2023-11-26 19:14:39','2023-11-26 19:14:39'),(5,5,'Діагностика та усунення проблем з зображенням або звуком',NULL,1250.00,NULL,NULL,1,'2023-11-26 19:26:25','2023-11-26 19:26:25'),(6,5,'Ремонт або заміна плати живлення',NULL,570.00,NULL,NULL,1,'2023-11-26 19:27:00','2023-11-26 19:27:00'),(7,3,'Діагностика та усунення проблем з апаратною частиною',NULL,2000.00,NULL,NULL,1,'2023-11-26 19:27:17','2023-11-26 19:27:17'),(8,3,'Відновлення даних',NULL,1500.00,NULL,NULL,1,'2023-11-26 19:27:29','2023-11-26 19:27:29'),(9,4,'Заміна тріснутого дисплея',NULL,4000.00,NULL,NULL,1,'2023-11-26 19:27:48','2023-11-26 19:27:48'),(10,4,'Діагностика та виправлення проблем з Wi-Fi або Bluetooth',NULL,900.00,NULL,NULL,1,'2023-11-26 19:28:03','2023-11-26 19:28:03'),(11,6,'Усунення проблем з читанням дисків',NULL,3500.00,NULL,NULL,1,'2023-11-26 19:28:20','2023-11-26 19:28:20'),(12,6,'Ремонт або заміна джойстиків та кнопок',NULL,2400.00,NULL,NULL,1,'2023-11-26 19:28:40','2023-11-26 19:28:40');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `grade` smallint NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_794381C6A76ED395` (`user_id`),
  CONSTRAINT `FK_794381C6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (2,'Все супер','Дуже швидко та якісно',5,'2023-11-26 19:29:34','2023-11-26 19:29:34',2),(3,'Не швидко але якісно','Довго чекав але якісно',3,'2023-11-26 19:29:51','2023-11-26 19:29:59',3),(4,'Швидко але не якісно','Погано зробили',2,'2023-11-26 19:30:32','2023-11-26 19:30:32',4),(5,'Не змогли зробити','Не зробили',1,'2023-11-26 19:30:48','2023-11-26 19:30:48',5);
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fio` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'petrov.oleksandr@gmail.com','Олександр Володимирович Петров','+380987654321','[\"ROLE_USER\"]','$2y$13$jgnzC9WDZt0R4VnKkHwxY.FAhb0H6a62VE2TUU8Yz/OWh/C4DzCL6','2023-11-26 19:17:38','2023-11-26 19:17:38'),(3,'irina.kovalova@gmail.com','Ірина Олегівна Ковальова','+380951234567','[\"ROLE_USER\"]','$2y$13$YZ4to/vEXeXxnxaxbK8hdOh28Vl4rOtqIqy1l7dD/xJIQpgsBj.ke','2023-11-26 19:18:23','2023-11-26 19:18:23'),(4,'anna.shevchenko@example.com','Анна Михайлівна Шевченко','+380936363636','[\"ROLE_USER\"]','$2y$13$RJGtdOJaENSLdBM.hJaPLOCeFHrftAqHA31k.1Mf1gNA501d88zNO','2023-11-26 19:24:50','2023-11-26 19:24:50'),(5,'morozov.vitaliy@hotmail.com','Катерина Василівна Проценко','+380977777777','[\"ROLE_USER\"]','$2y$13$VuLLMoPTmucuwA4qzqhA6O0sNGme3x0MXBwjIrtdZJVGj3pWxlpt2','2023-11-26 19:25:20','2023-11-26 19:25:20');
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

-- Dump completed on 2023-11-26 19:33:32
