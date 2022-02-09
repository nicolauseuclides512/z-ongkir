-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: zuragan_ongkir_api_dev
-- ------------------------------------------------------
-- Server version	5.7.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table `asset_carriers`
--

DROP TABLE IF EXISTS `asset_carriers`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_carriers` (
  `id`         BIGINT(20) UNSIGNED NOT NULL            AUTO_INCREMENT,
  `name`       VARCHAR(50) COLLATE utf8mb4_unicode_ci  DEFAULT NULL,
  `code`       VARCHAR(50) COLLATE utf8mb4_unicode_ci  DEFAULT NULL,
  `logo`       VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status`     TINYINT(1)          NOT NULL            DEFAULT '0',
  `created_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` TIMESTAMP           NULL                DEFAULT NULL,
  `updated_at` TIMESTAMP           NULL                DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_carriers`
--

LOCK TABLES `asset_carriers` WRITE;
/*!40000 ALTER TABLE `asset_carriers`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_carriers`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_countries`
--

DROP TABLE IF EXISTS `asset_countries`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_countries` (
  `id`         BIGINT(20) UNSIGNED                     NOT NULL          AUTO_INCREMENT,
  `code`       VARCHAR(3) COLLATE utf8mb4_unicode_ci                     DEFAULT NULL,
  `name`       VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status`     TINYINT(1)                              NOT NULL          DEFAULT '1',
  `created_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci                   DEFAULT NULL,
  `updated_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci                   DEFAULT NULL,
  `deleted_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci                   DEFAULT NULL,
  `created_at` TIMESTAMP                               NULL              DEFAULT NULL,
  `updated_at` TIMESTAMP                               NULL              DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_countries`
--

LOCK TABLES `asset_countries` WRITE;
/*!40000 ALTER TABLE `asset_countries`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_countries`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_districts`
--

DROP TABLE IF EXISTS `asset_districts`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_districts` (
  `id`             BIGINT(20) UNSIGNED                     NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type`           VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip`            VARCHAR(10) COLLATE utf8mb4_unicode_ci           DEFAULT NULL,
  `status`         TINYINT(1)                              NOT NULL DEFAULT '1',
  `province_id`    BIGINT(20) UNSIGNED                     NOT NULL,
  `created_by`     VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `updated_by`     VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `deleted_by`     VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `created_at`     TIMESTAMP                               NULL     DEFAULT NULL,
  `updated_at`     TIMESTAMP                               NULL     DEFAULT NULL,
  `lion_parcel_id` BIGINT(20) UNSIGNED                              DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_districts`
--

LOCK TABLES `asset_districts` WRITE;
/*!40000 ALTER TABLE `asset_districts`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_districts`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_lion_parcels`
--

DROP TABLE IF EXISTS `asset_lion_parcels`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_lion_parcels` (
  `id`            BIGINT(20) UNSIGNED                     NOT NULL     AUTO_INCREMENT,
  `area_code`     VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city`          VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_route` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_route`    VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_city`       TINYINT(1)                              NOT NULL,
  `status`        TINYINT(1)                              NOT NULL     DEFAULT '1',
  `created_at`    TIMESTAMP                               NULL         DEFAULT NULL,
  `updated_at`    TIMESTAMP                               NULL         DEFAULT NULL,
  `created_by`    VARCHAR(255) COLLATE utf8mb4_unicode_ci              DEFAULT NULL,
  `updated_by`    VARCHAR(255) COLLATE utf8mb4_unicode_ci              DEFAULT NULL,
  `deleted_by`    VARCHAR(255) COLLATE utf8mb4_unicode_ci              DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_lion_parcels`
--

LOCK TABLES `asset_lion_parcels` WRITE;
/*!40000 ALTER TABLE `asset_lion_parcels`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_lion_parcels`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_provinces`
--

DROP TABLE IF EXISTS `asset_provinces`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_provinces` (
  `id`         BIGINT(20) UNSIGNED                     NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status`     TINYINT(1)                              NOT NULL DEFAULT '1',
  `country_id` BIGINT(20) UNSIGNED                     NOT NULL,
  `created_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `updated_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `deleted_by` VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `created_at` TIMESTAMP                               NULL     DEFAULT NULL,
  `updated_at` TIMESTAMP                               NULL     DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_provinces`
--

LOCK TABLES `asset_provinces` WRITE;
/*!40000 ALTER TABLE `asset_provinces`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_provinces`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_regions`
--

DROP TABLE IF EXISTS `asset_regions`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_regions` (
  `id`             BIGINT(20) UNSIGNED                     NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type`           VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip`            VARCHAR(10) COLLATE utf8mb4_unicode_ci           DEFAULT NULL,
  `status`         TINYINT(1)                              NOT NULL DEFAULT '1',
  `district_id`    BIGINT(20) UNSIGNED                     NOT NULL,
  `is_priority`    TINYINT(1)                              NOT NULL DEFAULT '0',
  `created_by`     VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `updated_by`     VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `deleted_by`     VARCHAR(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `created_at`     TIMESTAMP                               NULL     DEFAULT NULL,
  `updated_at`     TIMESTAMP                               NULL     DEFAULT NULL,
  `lion_parcel_id` BIGINT(20) UNSIGNED                              DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_regions`
--

LOCK TABLES `asset_regions` WRITE;
/*!40000 ALTER TABLE `asset_regions`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_regions`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id`        INT(10) UNSIGNED                        NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch`     INT(11)                                 NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 11
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations`
  DISABLE KEYS */;
INSERT INTO `migrations`
VALUES (1, '2014_10_12_000000_create_users_table', 1), (2, '2014_10_12_100000_create_password_resets_table', 1),
  (3, '2018_04_11_091359_create_asset_countries', 1), (4, '2018_04_11_235202_create_asset_provinces', 1),
  (5, '2018_04_11_235222_create_asset_districts', 1), (6, '2018_04_11_235236_create_asset_regions', 1),
  (7, '2018_04_12_014411_create_asset_carriers', 1), (8, '2018_06_25_072538_update_carrier_table', 1),
  (9, '2018_07_31_042015_create_asset_lion_parcels_table', 1),
  (10, '2018_08_01_070859_alter_add_lion_parcel_id_regions_and_districts_tables', 1);
/*!40000 ALTER TABLE `migrations`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email`      VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token`      VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` TIMESTAMP                               NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id`             INT(10) UNSIGNED                        NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email`          VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password`       VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` VARCHAR(100) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `created_at`     TIMESTAMP                               NULL     DEFAULT NULL,
  `updated_at`     TIMESTAMP                               NULL     DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users`
  DISABLE KEYS */;
/*!40000 ALTER TABLE `users`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'zuragan_ongkir_api_dev'
--
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2018-08-01  9:19:12
