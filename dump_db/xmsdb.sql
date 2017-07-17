-- MySQL dump 10.16  Distrib 10.1.25-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: xmsdb
-- ------------------------------------------------------
-- Server version	10.1.25-MariaDB-1~xenial

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fat_migrations`
--

DROP TABLE IF EXISTS `fat_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_migrations`
--

LOCK TABLES `fat_migrations` WRITE;
/*!40000 ALTER TABLE `fat_migrations` DISABLE KEYS */;
INSERT INTO `fat_migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2017_07_10_045211_create_user_groups_table',1),(4,'2017_07_10_120332_create_user_menus_table',1),(5,'2017_07_10_120806_create_table_user_menu_group',1),(6,'2017_07_10_120850_create_sites_table',1),(7,'2017_07_10_120914_create_site_settings_table',1),(10,'2017_07_16_225244_create_user_logs_table',2),(11,'2017_07_17_153810_create_pages_table',3);
/*!40000 ALTER TABLE `fat_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_pages`
--

DROP TABLE IF EXISTS `fat_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teaser` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `type` enum('static_page','module','external_link') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'static_page',
  `slug_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` smallint(6) NOT NULL DEFAULT '1',
  `is_published` tinyint(4) NOT NULL DEFAULT '0',
  `is_featured` tinyint(4) NOT NULL DEFAULT '0',
  `is_header` tinyint(4) NOT NULL DEFAULT '0',
  `is_footer` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_pages`
--

LOCK TABLES `fat_pages` WRITE;
/*!40000 ALTER TABLE `fat_pages` DISABLE KEYS */;
INSERT INTO `fat_pages` VALUES (1,0,'Home',NULL,NULL,'module',NULL,'home',NULL,'home_image201707171720.png',NULL,NULL,NULL,1,1,0,1,0,'2017-07-17 17:18:09','2017-07-17 17:20:17');
/*!40000 ALTER TABLE `fat_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_password_resets`
--

DROP TABLE IF EXISTS `fat_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_password_resets`
--

LOCK TABLES `fat_password_resets` WRITE;
/*!40000 ALTER TABLE `fat_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `fat_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_site_settings`
--

DROP TABLE IF EXISTS `fat_site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_site_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_site_settings`
--

LOCK TABLES `fat_site_settings` WRITE;
/*!40000 ALTER TABLE `fat_site_settings` DISABLE KEYS */;
INSERT INTO `fat_site_settings` VALUES (199,1,'app_header','FAT XMS APP',NULL,NULL),(200,1,'app_footer','FAT XMS - All Right Reserved',NULL,NULL),(201,1,'default_email','ivan.z.lubis@gmail.com',NULL,NULL),(202,1,'default_name','FAT Admin',NULL,NULL),(203,1,'whitelist_ip','::1;127.0.0.1',NULL,NULL),(204,1,'mail_host','mail.test.com',NULL,NULL),(205,1,'mail_pass','mail27',NULL,NULL),(206,1,'mail_port','25',NULL,NULL),(207,1,'mail_protocol','smtp',NULL,NULL),(208,1,'mail_user','smtp@test.com',NULL,NULL),(209,1,'maintenance_message','<p>This site currently on maintenance, please check again later.</p>',NULL,NULL),(210,1,'maintenance_mode','0',NULL,NULL),(211,1,'meta_description','',NULL,NULL),(212,1,'meta_keywords','',NULL,NULL);
/*!40000 ALTER TABLE `fat_site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_sites`
--

DROP TABLE IF EXISTS `fat_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_sites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_image_header` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_sites`
--

LOCK TABLES `fat_sites` WRITE;
/*!40000 ALTER TABLE `fat_sites` DISABLE KEYS */;
INSERT INTO `fat_sites` VALUES (1,'FAT XMS','/','/','','1',1,'2017-05-28 22:38:11','2017-06-09 23:28:53');
/*!40000 ALTER TABLE `fat_sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_user_groups`
--

DROP TABLE IF EXISTS `fat_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_user_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_superadmin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_user_groups`
--

LOCK TABLES `fat_user_groups` WRITE;
/*!40000 ALTER TABLE `fat_user_groups` DISABLE KEYS */;
INSERT INTO `fat_user_groups` VALUES (1,'Super Administrator',1,'2017-05-28 22:34:23','2017-05-28 22:34:23'),(2,'Administrator',1,'2017-05-28 22:34:23','2017-05-28 22:34:23'),(3,'Admin',0,'2017-05-28 22:34:23','2017-05-28 22:34:23');
/*!40000 ALTER TABLE `fat_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_user_logs`
--

DROP TABLE IF EXISTS `fat_user_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_user_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_data` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_user_logs`
--

LOCK TABLES `fat_user_logs` WRITE;
/*!40000 ALTER TABLE `fat_user_logs` DISABLE KEYS */;
INSERT INTO `fat_user_logs` VALUES (1,0,0,'login','FAILED User login','192.168.10.1','http://fat-xms.app/xms/login','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 06:38:21 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"username\":\"admin\",\"password\":\"afaskjabgs\",\"user_status\":1}','2017-07-17 13:38:21','2017-07-17 13:38:21'),(2,0,0,'login','FAILED User login','192.168.10.1','http://fat-xms.app/xms/login','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 06:40:13 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"username\":\"admin\",\"password\":\"afaskjabgs\"}','2017-07-17 13:40:13','2017-07-17 13:40:13'),(3,1,1,'login','SUCCESS User login','192.168.10.1','http://fat-xms.app/xms/login','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 06:44:00 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"username\":\"admin\",\"password\":\"anukan123\"}','2017-07-17 13:44:00','2017-07-17 13:44:00'),(4,1,1,'login','SUCCESS User login','192.168.10.1','http://fat-xms.app/xms/login','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 07:31:30 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"username\":\"admin\"}','2017-07-17 14:31:30','2017-07-17 14:31:30'),(5,1,1,'user_update','SUCCESS Update User ID: 2','192.168.10.1','http://fat-xms.app/xms/users/update/2','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 08:18:33 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"username\":\"cuman_admin\",\"user_group_id\":\"3\",\"password_confirmation\":null,\"email\":\"ihate.haters@yahoo.com\",\"name\":\"Zulkarnain\",\"user_status\":\"1\"}','2017-07-17 15:18:33','2017-07-17 15:18:33'),(6,1,1,'page_update','SUCCESS Update Page ID: 1','192.168.10.1','http://fat-xms.app/xms/pages/update/1','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 10:18:37 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"parent_id\":\"0\",\"title\":\"Home\",\"type\":\"module\",\"slug_url\":null,\"teaser\":null,\"description\":null,\"module\":\"home\",\"ext_link\":null,\"position\":\"1\",\"is_published\":\"1\",\"is_header\":\"1\"}','2017-07-17 17:18:37','2017-07-17 17:18:37'),(7,1,1,'page_update','SUCCESS Update Page ID: 1','192.168.10.1','http://fat-xms.app/xms/pages/update/1','HTTP/1.0 200 OK\r\nCache-Control: no-cache, private\r\nContent-Type:  application/json\r\nDate:          Mon, 17 Jul 2017 10:20:17 GMT\r\n\r\n{\"_token\":\"Ah79CWlIcSmRWvYbTDOaWUZbJerBsFBZM2zg7D4D\",\"parent_id\":\"0\",\"title\":\"Home\",\"type\":\"module\",\"slug_url\":null,\"teaser\":null,\"description\":null,\"module\":\"home\",\"ext_link\":null,\"position\":\"1\",\"is_published\":\"1\",\"is_header\":\"1\"}','2017-07-17 17:20:17','2017-07-17 17:20:17');
/*!40000 ALTER TABLE `fat_user_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_user_menu_group`
--

DROP TABLE IF EXISTS `fat_user_menu_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_user_menu_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `user_menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_user_menu_group`
--

LOCK TABLES `fat_user_menu_group` WRITE;
/*!40000 ALTER TABLE `fat_user_menu_group` DISABLE KEYS */;
INSERT INTO `fat_user_menu_group` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,1,6),(7,1,7),(8,1,8),(34,3,1),(35,3,3),(36,3,2),(37,3,6),(38,3,7);
/*!40000 ALTER TABLE `fat_user_menu_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_user_menus`
--

DROP TABLE IF EXISTS `fat_user_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_user_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `menu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '1',
  `is_superadmin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_user_menus`
--

LOCK TABLES `fat_user_menus` WRITE;
/*!40000 ALTER TABLE `fat_user_menus` DISABLE KEYS */;
INSERT INTO `fat_user_menus` VALUES (1,0,'Settings','#','fa fa-gears',2,0,'2017-05-28 22:34:34','2017-05-28 22:34:34'),(2,1,'Admin User','users','fa fa-user',22,0,'2017-05-28 22:34:34','2017-07-17 01:34:49'),(3,1,'Admin User Group & Authorization','groups','fa fa-users',21,0,'2017-05-28 22:34:34','2017-06-04 04:35:40'),(4,1,'Site Management','sites','fa fa-ban',23,0,'2017-05-28 22:34:34','2017-06-07 00:10:13'),(5,1,'Logs Record (Backend)','logs','fa fa-archive',24,0,'2017-05-28 22:34:34','2017-05-28 22:34:34'),(6,0,'Menus','#','fa fa-bars',3,0,'2017-06-01 11:16:22','2017-06-01 11:16:22'),(7,6,'Front End Menu (Static Page)','pages','fa fa-align-left',31,0,'2017-06-01 11:16:22','2017-06-01 11:16:22'),(8,6,'Back End Menu (Module)','menus','fa fa-align-left',32,0,'2017-06-01 11:16:22','2017-06-01 11:16:22');
/*!40000 ALTER TABLE `fat_user_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fat_users`
--

DROP TABLE IF EXISTS `fat_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fat_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_status` tinyint(4) NOT NULL,
  `themes` enum('adminlte2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'adminlte2',
  `is_superadmin` tinyint(4) NOT NULL DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fat_users`
--

LOCK TABLES `fat_users` WRITE;
/*!40000 ALTER TABLE `fat_users` DISABLE KEYS */;
INSERT INTO `fat_users` VALUES (1,1,'admin','ivan.z.lubis@gmail.com','$2y$10$a16BTYs5lVT.uWVbn/PQuuRJw00TBZUTPZAlYdSIsDNdEL6l6pN2y','Ivan Lubis','user_avatar_1_201707151433.jpg',1,'adminlte2',1,'2017-07-17 14:31:30','3sTwO2KnM8WL7GBOhMUyNeSi8dkwToowf5xv96OaljTXkffzFAvYDZL1ZQEF','2017-05-28 22:37:54','2017-07-17 14:31:30'),(2,3,'cuman_admin','ihate.haters@yahoo.com','$2y$10$dRsVte2uryBOIPxrympaeOB9/0ampz.4LZvGzFEaAJ8wmNbmsIzOq','Zulkarnain','user_avatar_1_201707151433.jpg',1,'adminlte2',0,'2017-06-12 16:25:39',NULL,'2017-06-10 23:28:36','2017-07-17 15:18:33');
/*!40000 ALTER TABLE `fat_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-17 12:00:30
