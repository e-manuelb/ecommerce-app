### SQL to Generate Products and important data

````mysql
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `discount_type` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `min_subtotal_to_apply` decimal(8,2) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_uuid_unique` (`uuid`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `coupons` WRITE;
INSERT INTO `coupons` VALUES
(2,'bc200842-4d87-48f4-aa40-a3e9e95432e4','DESCONTO50',50.00,'PERCENTAGE',1,1000.00,'2025-05-24 00:00:00','2025-05-26 06:48:31','2025-05-26 06:48:31'),
(3,'59acb39e-51dd-4b01-926e-a35baedae5e1','DESCONTO60',10.00,'PERCENTAGE',1,1500.00,'2028-07-26 00:00:00','2025-05-26 07:07:31','2025-05-26 07:07:31');
UNLOCK TABLES;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
INSERT INTO `migrations` VALUES
(1,'2025_05_21_235708_create_products_table',1),
(2,'2025_05_21_235713_create_product_variations_table',1),
(3,'2025_05_21_235718_create_orders_table',1),
(4,'2025_05_21_235911_create_coupons_table',1),
(5,'2025_05_21_235954_create_stocks_table',1),
(6,'2025_05_22_000346_create_order_items_table',1),
(7,'2025_05_23_160914_create_order_addresses_table',1),
(8,'2025_05_26_010342_add_additional_information_to_orders_table',2),
(10,'2025_05_26_011740_add_subtotal_to_orders_table',3),
(12,'2025_05_26_025427_add_status_to_orders_table',4);
UNLOCK TABLES;

DROP TABLE IF EXISTS `order_addresses`;
CREATE TABLE `order_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_addresses_uuid_unique` (`uuid`),
  KEY `order_addresses_order_id_foreign` (`order_id`),
  CONSTRAINT `order_addresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_addresses` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `product_type` enum('NORMAL','VARIATION') NOT NULL,
  `product_reference_uuid` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_items_uuid_unique` (`uuid`),
  KEY `order_items_order_id_foreign` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_items` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` double NOT NULL,
  `status` enum('PENDING','PROCESSING','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `additional_information` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_information`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `orders` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `product_variations`;
CREATE TABLE `product_variations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variations_uuid_unique` (`uuid`),
  UNIQUE KEY `product_variations_sku_unique` (`sku`),
  KEY `product_variations_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_variations` WRITE;
INSERT INTO `product_variations` VALUES
(1,'6e2e170c-d350-4eea-ab15-a2a69baa52bc',1,'iPhone 14 - Versão 1','SKU-IPHONE-14-1-V1','Smartphone Apple com câmera dupla e chip A15 Bionic - Versão 1',1709.55,NULL,'2023-04-09 17:07:13','2023-04-10 17:07:13'),
(3,'5674f35a-d96e-4a87-a8d0-48e84821b935',1,'iPhone 14 - Versão 3','SKU-IPHONE-14-1-V3','Smartphone Apple com câmera dupla e chip A15 Bionic - Versão 3',1707.42,NULL,'2023-04-09 17:07:13','2023-04-11 17:07:13'),
(4,'90d99458-7886-4c58-bda4-225399b65efd',2,'Samsung Galaxy S23 - Versão 1','SKU-SAMSUNG-GALAXY-S23-2-V1','Smartphone Samsung com câmera tripla e tela AMOLED - Versão 1',1633.13,NULL,'2024-09-29 09:41:17','2024-10-04 09:41:17'),
(5,'76848266-1733-49bb-81cb-7da2ddcc1fe7',2,'Samsung Galaxy S23 - Versão 2','SKU-SAMSUNG-GALAXY-S23-2-V2','Smartphone Samsung com câmera tripla e tela AMOLED - Versão 2',1556.63,NULL,'2024-09-28 09:41:17','2024-09-29 09:41:17'),
(6,'9638c362-90ca-40c2-9ce1-423b31614640',2,'Samsung Galaxy S23 - Versão 3','SKU-SAMSUNG-GALAXY-S23-2-V3','Smartphone Samsung com câmera tripla e tela AMOLED - Versão 3',1495.11,NULL,'2024-09-30 09:41:17','2024-10-02 09:41:17'),
(7,'58194438-25d5-4d50-9ccd-e1c4e1e65e35',3,'PlayStation 5 - Versão 1','SKU-PLAYSTATION-5-3-V1','Console de videogame Sony com suporte a jogos em 4K - Versão 1',4986.75,NULL,'2024-12-19 23:44:16','2024-12-24 23:44:16'),
(8,'acb80d28-d729-4817-8e7c-fc0e89b0b9da',3,'PlayStation 5 - Versão 2','SKU-PLAYSTATION-5-3-V2','Console de videogame Sony com suporte a jogos em 4K - Versão 2',5096.80,NULL,'2024-12-20 23:44:16','2024-12-25 23:44:16'),
(9,'6c8cd9ee-168d-4abb-ad3d-7aee69279d33',3,'PlayStation 5 - Versão 3','SKU-PLAYSTATION-5-3-V3','Console de videogame Sony com suporte a jogos em 4K - Versão 3',4927.64,NULL,'2024-12-16 23:44:16','2024-12-19 23:44:16'),
(10,'feac3bde-fc73-4429-9afa-c5aac089aac3',4,'Xbox Series X - Versão 1','SKU-XBOX-SERIES-X-4-V1','Console de videogame Microsoft com SSD ultrarrápido - Versão 1',3891.34,NULL,'2024-05-01 00:09:25','2024-05-02 00:09:25'),
(11,'064f4378-1cbe-464e-be60-6bbd0c4e8633',4,'Xbox Series X - Versão 2','SKU-XBOX-SERIES-X-4-V2','Console de videogame Microsoft com SSD ultrarrápido - Versão 2',3680.62,NULL,'2024-05-04 00:09:25','2024-05-08 00:09:25'),
(12,'66ac3bff-f880-4e4b-93bf-ca61f01aba11',4,'Xbox Series X - Versão 3','SKU-XBOX-SERIES-X-4-V3','Console de videogame Microsoft com SSD ultrarrápido - Versão 3',3815.90,NULL,'2024-05-01 00:09:25','2024-05-05 00:09:25'),
(13,'042814fd-4b5f-4c8e-9042-0470be0cb5f1',5,'MacBook Air M2 - Versão 1','SKU-MACBOOK-AIR-M2-5-V1','Notebook Apple com chip M2 e tela Retina - Versão 1',3059.67,NULL,'2024-09-23 15:05:21','2024-09-25 15:05:21'),
(14,'fc12108e-4b91-49fb-b808-f843ce9cb97f',5,'MacBook Air M2 - Versão 2','SKU-MACBOOK-AIR-M2-5-V2','Notebook Apple com chip M2 e tela Retina - Versão 2',2888.15,NULL,'2024-09-23 15:05:21','2024-09-24 15:05:21'),
(15,'f1185bdf-50c6-4e1b-baa6-512507f27c7e',5,'MacBook Air M2 - Versão 3','SKU-MACBOOK-AIR-M2-5-V3','Notebook Apple com chip M2 e tela Retina - Versão 3',2950.19,NULL,'2024-09-24 15:05:21','2024-09-29 15:05:21');
UNLOCK TABLES;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_uuid_unique` (`uuid`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `products` WRITE;
INSERT INTO `products` VALUES
(1,'ce81aa52-6253-4a1a-b7de-e0ec70eb2b8c','iPhone 14','SKU-IPHONE-14-1','Smartphone Apple com câmera dupla e chip A15 Bionic',1584.98,NULL,'2023-04-06 17:07:13','2025-05-26 07:17:10'),
(2,'9163cc35-5926-4cd4-a15f-09c1d1628dc0','Samsung Galaxy S23','SKU-SAMSUNG-GALAXY-S23-2','Smartphone Samsung com câmera tripla e tela AMOLED',1235.28,NULL,'2024-09-25 09:41:17','2024-09-29 09:41:17'),
(3,'2a079b1f-f7b9-404a-a08d-a6a9d13cbcf4','PlayStation 5','SKU-PLAYSTATION-5-3','Console de videogame Sony com suporte a jogos em 4K',4851.07,NULL,'2024-12-15 23:44:16','2025-01-12 23:44:16'),
(4,'071fb7ba-9020-4810-9712-b7f3c40bdbe0','Xbox Series X','SKU-XBOX-SERIES-X-4','Console de videogame Microsoft com SSD ultrarrápido',3601.52,NULL,'2024-04-30 00:09:25','2024-05-18 00:09:25'),
(5,'23c88aa3-7bba-43c7-bc57-2b5dece9a0f2','MacBook Air M2','SKU-MACBOOK-AIR-M2-5','Notebook Apple com chip M2 e tela Retina',2716.41,NULL,'2024-09-20 15:05:21','2024-10-05 15:05:21');
UNLOCK TABLES;

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_type` enum('NORMAL','VARIATION') NOT NULL,
  `product_reference_uuid` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `stocks` WRITE;
INSERT INTO `stocks` VALUES
(1,'b79619e0-0b82-4952-ac3e-b22e71f3f441',10,'NORMAL','ce81aa52-6253-4a1a-b7de-e0ec70eb2b8c','2023-04-06 17:07:13','2025-05-26 04:19:33'),
(2,'2d395957-4749-472e-a2a8-031ebe332daa',32,'VARIATION','6e2e170c-d350-4eea-ab15-a2a69baa52bc','2023-04-09 17:07:13','2025-05-26 04:19:33'),
(3,'51b44d6e-ff26-4fe6-9f8f-df742260d4e7',11,'VARIATION','42f7a97f-c39e-436d-943c-6642ee5e2216','2023-04-09 17:07:13','2025-05-26 04:19:33'),
(4,'8ebe6c06-7e2e-4765-8bf9-a1ee7e4f2ce4',42,'VARIATION','5674f35a-d96e-4a87-a8d0-48e84821b935','2023-04-09 17:07:13','2025-05-23 20:28:29'),
(5,'cca17473-3fa7-42ed-b0f8-e4f87f4df55f',75,'NORMAL','9163cc35-5926-4cd4-a15f-09c1d1628dc0','2024-09-25 09:41:17','2025-05-26 07:08:16'),
(6,'42d86cea-46b1-4e16-ae24-889ad7c5a354',19,'VARIATION','90d99458-7886-4c58-bda4-225399b65efd','2024-09-29 09:41:17','2025-05-26 06:48:42'),
(7,'d3bc1b27-fdab-44ce-8f87-84b7998dc427',27,'VARIATION','76848266-1733-49bb-81cb-7da2ddcc1fe7','2024-09-28 09:41:17','2025-05-26 06:48:42'),
(8,'120cca50-0565-4d9b-8b4d-d71413215447',26,'VARIATION','9638c362-90ca-40c2-9ce1-423b31614640','2024-09-30 09:41:17','2025-05-26 06:48:42'),
(9,'7ab364d2-53f9-4a4b-a2a2-75b9b8953e0a',0,'NORMAL','2a079b1f-f7b9-404a-a08d-a6a9d13cbcf4','2024-12-15 23:44:16','2025-05-23 20:15:23'),
(10,'153bad29-091b-4b50-a6d5-3a49b6d5f27c',17,'VARIATION','58194438-25d5-4d50-9ccd-e1c4e1e65e35','2024-12-19 23:44:16','2024-12-24 23:44:16'),
(11,'4b174321-3513-44bd-bab2-14c916440788',15,'VARIATION','acb80d28-d729-4817-8e7c-fc0e89b0b9da','2024-12-20 23:44:16','2024-12-25 23:44:16'),
(12,'7d490682-32e7-41e2-b788-1a3beee0d267',46,'VARIATION','6c8cd9ee-168d-4abb-ad3d-7aee69279d33','2024-12-16 23:44:16','2024-12-19 23:44:16'),
(13,'fdb769a8-35ae-4bb5-a501-8ddfd8d9a082',100,'NORMAL','071fb7ba-9020-4810-9712-b7f3c40bdbe0','2024-04-30 00:09:25','2024-05-18 00:09:25'),
(14,'71d7eb43-2d1f-4d39-859c-0b2e7b7d30f3',19,'VARIATION','feac3bde-fc73-4429-9afa-c5aac089aac3','2024-05-01 00:09:25','2024-05-02 00:09:25'),
(15,'4c947495-b981-4106-b1e8-171849f4ce5a',49,'VARIATION','064f4378-1cbe-464e-be60-6bbd0c4e8633','2024-05-04 00:09:25','2024-05-08 00:09:25'),
(16,'9be9ca6b-6e62-427c-8232-bf2d9f9e589a',38,'VARIATION','66ac3bff-f880-4e4b-93bf-ca61f01aba11','2024-05-01 00:09:25','2024-05-05 00:09:25'),
(17,'1abe5ba4-f844-41ac-932d-90656e4a1747',62,'NORMAL','23c88aa3-7bba-43c7-bc57-2b5dece9a0f2','2024-09-20 15:05:21','2025-05-24 00:50:33'),
(18,'52b6f0de-0f66-4bee-83ab-7b7a78039610',22,'VARIATION','042814fd-4b5f-4c8e-9042-0470be0cb5f1','2024-09-23 15:05:21','2024-09-25 15:05:21'),
(19,'1f519b08-93d3-4797-8fb7-d3626a0c4c9a',12,'VARIATION','fc12108e-4b91-49fb-b808-f843ce9cb97f','2024-09-23 15:05:21','2024-09-24 15:05:21'),
(20,'0cc2fa4c-f80f-4569-b4fe-130a51711f6b',33,'VARIATION','f1185bdf-50c6-4e1b-baa6-512507f27c7e','2024-09-24 15:05:21','2024-09-29 15:05:21');
UNLOCK TABLES;
