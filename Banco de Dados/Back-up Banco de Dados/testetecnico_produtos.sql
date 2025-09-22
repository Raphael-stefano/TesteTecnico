-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: testetecnico
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `quantidade` int NOT NULL DEFAULT '0',
  `preco` int NOT NULL,
  `id_categoria` int DEFAULT NULL,
  `sku` varchar(7) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_produtos_categorias` (`id_categoria`),
  CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (5,'Câmera DSLR',16,829990,2,'ABC-128','Lorem\r\n                \r\n                \r\n                \r\n                \r\n                \r\n                \r\n                '),(7,'Teclado Mecânico 75%',34,59900,1,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(8,'Monitor 27\" 4K IPS',7,199900,2,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(9,'Headset Gamer Surround',22,42900,2,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(10,'SSD NVMe 1TB Gen4',45,69900,2,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(11,'Notebook Ultrafino 14\"',9,549900,2,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(12,'Cadeira Ergonômica Pro',15,129900,2,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(13,'Impressora Laser Wi-Fi',18,89900,3,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(14,'Hub USB C 7-em-1',73,18900,3,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(15,'Smartphone 128GB',26,239900,3,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(17,'Cafetreira Automática',31,74900,3,'ABC-123','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae tellus id lorem congue molestie eu nec purus. Suspendisse vel tincidunt velit.'),(18,'Câmera DSLR Mark IV',12,892000,2,'ABC-123','Descriçao do produto'),(19,'Mouse Sem Fio PRO',58,24999,2,'ABC-128','Descriçao do produto');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-22 18:12:17
