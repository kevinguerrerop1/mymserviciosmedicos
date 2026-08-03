/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.13-MariaDB : Database - mymserviciosmedicos
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mymserviciosmedicos` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `mymserviciosmedicos`;

/*Table structure for table `comentario_examenes` */

DROP TABLE IF EXISTS `comentario_examenes`;

CREATE TABLE `comentario_examenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `examen_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comentario_examenes_examen_id_foreign` (`examen_id`),
  KEY `comentario_examenes_user_id_foreign` (`user_id`),
  CONSTRAINT `comentario_examenes_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentario_examenes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `comentario_examenes` */

insert  into `comentario_examenes`(`id`,`examen_id`,`user_id`,`comentario`,`created_at`,`updated_at`) values (1,1,1,'? Estado actualizado de \'PENDIENTE\' a \'EN ESPERA INFORME COMPLEMENTARIO\'.','2026-08-03 18:43:43','2026-08-03 18:43:43'),(2,1,1,'? Estado actualizado de \'EN ESPERA INFORME COMPLEMENTARIO\' a \'INFORMADO RESULTADO CRÍTICO\'.','2026-08-03 18:43:49','2026-08-03 18:43:49'),(3,1,1,'? Estado actualizado de \'INFORMADO RESULTADO CRÍTICO\' a \'INFORMADO\'.','2026-08-03 18:43:55','2026-08-03 18:43:55'),(4,1,1,'? Estado actualizado de \'INFORMADO\' a \'PENDIENTE\'.','2026-08-03 18:43:58','2026-08-03 18:43:58'),(5,1,4,'Solicito urgencia','2026-08-03 18:59:43','2026-08-03 18:59:43'),(6,1,1,'Estamos trabajando para ud','2026-08-03 19:00:05','2026-08-03 19:00:05'),(7,1,1,'? Se adjuntó el Informe Diagnóstico Oficial (PDF).','2026-08-03 19:01:25','2026-08-03 19:01:25'),(8,1,1,'? Se agregaron 1 nueva(s) imagen(es) al expediente.','2026-08-03 19:05:49','2026-08-03 19:05:49'),(9,1,2,'falta muestra','2026-08-03 19:07:12','2026-08-03 19:07:12'),(10,1,4,'se tom muestra pendiente','2026-08-03 19:09:08','2026-08-03 19:09:08'),(11,1,1,'? Estado actualizado de \'PENDIENTE\' a \'INFORMADO RESULTADO CRÍTICO\'.','2026-08-03 19:11:28','2026-08-03 19:11:28'),(12,1,1,'? Estado actualizado de \'INFORMADO RESULTADO CRÍTICO\' a \'INFORMADO\'.','2026-08-03 19:11:48','2026-08-03 19:11:48'),(13,5,1,'? Registro de examen creado e ingresado al sistema de trazabilidad (Correlativo #3).','2026-08-03 20:02:05','2026-08-03 20:02:05'),(14,6,1,'? Registro de examen creado e ingresado al sistema de trazabilidad (Correlativo #6).','2026-08-03 20:02:24','2026-08-03 20:02:24'),(15,7,1,'? Registro de examen creado e ingresado al sistema de trazabilidad (Correlativo #7).','2026-08-03 20:02:46','2026-08-03 20:02:46'),(16,7,1,'aaa','2026-08-03 20:03:02','2026-08-03 20:03:02'),(17,7,1,'? Estado actualizado de \'PENDIENTE\' a \'EN ESPERA INFORME COMPLEMENTARIO\'.','2026-08-03 20:03:12','2026-08-03 20:03:12'),(18,7,1,'? Se adjuntó el Informe Diagnóstico Oficial (PDF).','2026-08-03 20:03:13','2026-08-03 20:03:13');

/*Table structure for table `examenes` */

DROP TABLE IF EXISTS `examenes`;

CREATE TABLE `examenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero_correlativo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_toma` date NOT NULL,
  `fecha_recepcion` date NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `paciente_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paciente_rut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `medico_solicitante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad_muestras` int(11) NOT NULL DEFAULT 1,
  `numero_fragmentos` int(11) DEFAULT NULL,
  `tincion_rutina` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tecnicas_especiales` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_examen_id` bigint(20) unsigned NOT NULL,
  `laboratorio_id` bigint(20) unsigned NOT NULL,
  `patologo_id` bigint(20) unsigned DEFAULT NULL,
  `estado` enum('PENDIENTE','EN ESPERA INFORME COMPLEMENTARIO','INFORMADO RESULTADO CRÍTICO','INFORMADO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDIENTE',
  `archivo_informe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `galeria_imagenes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`galeria_imagenes`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `examenes_numero_correlativo_unique` (`numero_correlativo`),
  KEY `examenes_tipo_examen_id_foreign` (`tipo_examen_id`),
  KEY `examenes_laboratorio_id_foreign` (`laboratorio_id`),
  KEY `examenes_patologo_id_foreign` (`patologo_id`),
  CONSTRAINT `examenes_laboratorio_id_foreign` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorios` (`id`),
  CONSTRAINT `examenes_patologo_id_foreign` FOREIGN KEY (`patologo_id`) REFERENCES `users` (`id`),
  CONSTRAINT `examenes_tipo_examen_id_foreign` FOREIGN KEY (`tipo_examen_id`) REFERENCES `tipo_examenes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `examenes` */

insert  into `examenes`(`id`,`numero_correlativo`,`fecha_toma`,`fecha_recepcion`,`fecha_entrega`,`paciente_nombre`,`paciente_rut`,`medico_solicitante`,`cantidad_muestras`,`numero_fragmentos`,`tincion_rutina`,`tecnicas_especiales`,`tipo_examen_id`,`laboratorio_id`,`patologo_id`,`estado`,`archivo_informe`,`galeria_imagenes`,`created_at`,`updated_at`) values (1,'EXAMEN 1','2026-08-03','2026-08-03',NULL,'Juan Perez','12123123-4','Dr. Rojas',1,NULL,NULL,NULL,1,1,2,'INFORMADO','informes/O1SMTIWiMmhKtvjn8p6Q8yYCBIaazuhUpqwrvmqt.pdf','[\"galeria_examenes\\/6eaW0iX28WdxPxeI8cpMKnAhhvGqqWLLUby69guv.png\"]','2026-08-03 18:28:25','2026-08-03 19:11:48'),(2,'0','2026-08-03','2026-08-03',NULL,'Juan Perez','12123123-4','Dr. Rojas',1,NULL,NULL,NULL,1,1,2,'PENDIENTE',NULL,NULL,'2026-08-03 19:52:22','2026-08-03 19:52:22'),(5,'3','2026-08-03','2026-08-03',NULL,'Juan Perez','12123123-4','Dr. Rojas',1,NULL,NULL,NULL,1,1,2,'PENDIENTE',NULL,NULL,'2026-08-03 20:02:05','2026-08-03 20:02:05'),(6,'6','2026-08-04','2026-08-04',NULL,'Juan Perez','12123123-4','Dr. Rojas',1,NULL,NULL,NULL,1,1,2,'PENDIENTE',NULL,NULL,'2026-08-03 20:02:24','2026-08-03 20:02:24'),(7,'7','2026-08-03','2026-08-03',NULL,'Juan Perez','12123123-4','Dr. Rojas',1,NULL,NULL,NULL,1,1,2,'EN ESPERA INFORME COMPLEMENTARIO','informes/PMfbWKXfVzs10Jq9A9oKPunB0xgEZDcVNaxRnZWV.pdf',NULL,'2026-08-03 20:02:46','2026-08-03 20:03:12');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `laboratorios` */

DROP TABLE IF EXISTS `laboratorios`;

CREATE TABLE `laboratorios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laboratorios_rut_unique` (`rut`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `laboratorios` */

insert  into `laboratorios`(`id`,`nombre`,`rut`,`direccion`,`email`,`created_at`,`updated_at`) values (1,'Clinica Vesalio','121231231','Av. España','aaaa@aaa.cl','2026-08-03 18:24:52','2026-08-03 18:24:52');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2026_08_03_175209_create_tipo_examens_table',1),(5,'2026_08_03_180246_create_laboratorios_table',1),(6,'2026_08_03_180254_create_examens_table',1),(7,'2026_08_03_180301_create_comentario_examens_table',1),(8,'2026_08_03_180322_create_permission_tables',1),(9,'2026_08_03_180500_add_laboratorio_id_to_users_table',1);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(2,'App\\Models\\User',3),(3,'App\\Models\\User',4);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values (1,'admin','web','2026-08-03 18:11:48','2026-08-03 18:11:48'),(2,'patologo','web','2026-08-03 18:11:48','2026-08-03 18:11:48'),(3,'laboratorio','web','2026-08-03 18:11:48','2026-08-03 18:11:48');

/*Table structure for table `tipo_examenes` */

DROP TABLE IF EXISTS `tipo_examenes`;

CREATE TABLE `tipo_examenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tipo_examenes` */

insert  into `tipo_examenes`(`id`,`nombre`,`created_at`,`updated_at`) values (1,'Biopsia','2026-08-03 18:28:00','2026-08-03 18:28:00'),(2,'Pap','2026-08-03 18:37:00','2026-08-03 18:37:00');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `laboratorio_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_laboratorio_id_foreign` (`laboratorio_id`),
  CONSTRAINT `users_laboratorio_id_foreign` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`,`laboratorio_id`) values (1,'Kevin Guerrero','kevinguerrerop1@gmail.com',NULL,'$2y$10$CZpya2ESNcayIHInsmDOj.7S9JV7TpQCQnl8XRg6afkuMQO1.tkXm',NULL,'2026-08-03 18:16:10','2026-08-03 18:16:10',NULL),(2,'David Retamal','dretamal@gmail.com',NULL,'$2y$10$3LAR11aqmF4AtQ6zC0CqduMz1pNbqLa1vEmuhkau9aJVKOAHUY5wm',NULL,'2026-08-03 18:24:19','2026-08-03 18:24:19',NULL),(3,'Luis Rojas','lrojasgo@hospitalcurico.cl',NULL,'$2y$10$GwP/gzoQpKno2tNthg.ajueYSPQGW0EFFfRM40f5WVO4QhABF8NqK',NULL,'2026-08-03 18:32:19','2026-08-03 18:32:19',NULL),(4,'admin vesalio','vesalio@vesalio.cl',NULL,'$2y$10$wsTvYd.u4Yz4uGxDGWq9Qee/.P/GwtRrLUyKohwTxca.R2rfWE7mC',NULL,'2026-08-03 18:33:13','2026-08-03 18:33:13',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
