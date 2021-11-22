/*
SQLyog Professional v12.09 (64 bit)
MySQL - 10.4.17-MariaDB : Database - gestor_proyectos
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gestor_proyectos` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `gestor_proyectos`;

/*Table structure for table `backlogs` */

DROP TABLE IF EXISTS `backlogs`;

CREATE TABLE `backlogs` (
  `id_backlog` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `estado` enum('Abierto','Cerrado') NOT NULL,
  PRIMARY KEY (`id_backlog`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `backlogs` */

insert  into `backlogs`(`id_backlog`,`nombre`,`estado`) values (1,'Back1','Abierto'),(4,'bakc2','Abierto'),(5,'Back3','Abierto');

/*Table structure for table `ciudades` */

DROP TABLE IF EXISTS `ciudades`;

CREATE TABLE `ciudades` (
  `id_ciudad` int(4) NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `ciudades` */

insert  into `ciudades`(`id_ciudad`,`ciudad`) values (1,'ASUNCION'),(2,'CONCEPCION'),(3,'BELEN'),(4,'AZOTEY'),(5,'HORQUETA'),(6,'LORETO'),(7,'SAN CARLOS DEL APA'),(8,'SAN LAZARO'),(9,'YBYYAU'),(10,'SAN PEDRO DEL Y.'),(11,'25 DE DICIEMBRE'),(12,'ANTEQUERA'),(13,'CAPIIVARY'),(14,'CHORE'),(15,'GRAL.ELIZARDO AQUINO'),(16,'GRAL. F. RESQUIN'),(17,'GUAJAYVI'),(18,'ITAC. DEL ROSARIO'),(19,'LIMA'),(20,'NUEVA GERMANIA'),(21,'SAN ESTANISLAO'),(22,'SAN PABLO'),(23,'STA.ROSA DEL AGUARAY'),(24,'TACUATI'),(25,'UNION'),(26,'VILLA DEL ROSARIO'),(27,'YATAITY DEL NORTE'),(28,'YRYBUCUA'),(29,'CAACUPE'),(30,'1RO. DE MARZO'),(31,'ALTOS'),(32,'ARROYOS Y ESTEROS'),(33,'ATYRA'),(34,'CARAGUATAY'),(35,'EMBOSCADA'),(36,'EUSEBIO AYALA'),(37,'ISLA PUCU'),(38,'ITAC.DE LA CORDILLERA'),(39,'JUAN DE MENA'),(40,'LOMA GRANDE'),(41,'MBOCAYATY DEL YHAGUY'),(42,'NUEVA COLOMBIA'),(43,'PIRIBEBUY'),(44,'SAN BERNARDINO'),(45,'SAN JOSE OBRERO'),(46,'SANTA ELENA'),(47,'TOBATI'),(48,'VALENZUELA'),(49,'VILLARRICA'),(50,'BORJA'),(51,'CAP. M. JOSE TROCHE'),(52,'CORONEL MARTINEZ'),(53,'DR. BOTRELL'),(54,'FELIX PEREZ CARDOZO'),(55,'GRAL. E. A. GARAY'),(56,'INDEPENDENCIA'),(57,'ITAPE'),(58,'ITURBE'),(59,'JOSE A. FASSARDI'),(60,'MBOCAYATY DEL GUAIRA'),(61,'NATALICIO TALAVERA'),(62,'ÑUMI'),(63,'PASO YOBAI'),(64,'SAN SALVADOR'),(65,'TEBICUARY'),(66,'YATAITY DEL GUAIRA'),(67,'CORONEL OVIEDO'),(68,'3 DE FEBRERO'),(69,'CAAGUAZU'),(70,'CARAYAO'),(71,'DR. CECILIO BAEZ'),(72,'DR.J. E.ESTIGARRIBIA'),(73,'DR. JUAN M. FRUTOS'),(74,'JOSE DOMINGO OCAMPOS'),(75,'LA PASTORA'),(76,'FRANCISCO S.LOPEZ'),(77,'NUEVA LONDRES'),(78,'R.I. 3 CORRALES'),(79,'RAUL ARSENIO OVIEDO'),(80,'REPATRIACION'),(81,'SAN JOAQUIN'),(82,'SAN J.DE LOS ARROYOS'),(83,'STA.ROSA DEL MBUTUY'),(84,'SIMON BOLIVAR'),(85,'TEMBIAPORA'),(86,'VAQUERIA'),(87,'YHU'),(88,'CAAZAPA'),(89,'ABAI'),(90,'BUENA VISTA'),(91,'DR. MOISES BERTONI'),(92,'FULGENCIO YEGROS'),(93,'GRAL.MORINIGO'),(94,'MACIEL'),(95,'SAN JUAN NEPOMUCENO'),(96,'TAVAI'),(97,'YUTY'),(98,'ENCARNACION'),(99,'ALTO VERA'),(100,'BELLA VISTA'),(101,'CAMBYRETA'),(102,'CAPITAN MEZA'),(103,'CAPITAN MIRANDA'),(104,'CARLOS ANTONIO LOPEZ'),(105,'CARMEN DEL PARANA'),(106,'CORONEL BOGADO'),(107,'EDELIRA'),(108,'FRAM'),(109,'GENERAL ARTIGAS'),(110,'GENERAL DELGADO'),(111,'HOHENAU'),(112,'ITAPUA POTY'),(113,'JESUS'),(114,'JOSE LEANDRO OVIEDO'),(115,'LA PAZ'),(116,'MAYOR OTAÑO'),(117,'NATALIO'),(118,'NUEVA ALBORADA'),(119,'OBLIGADO'),(120,'PIRAPO'),(121,'SAN COSME Y DAMIAN'),(122,'SAN JUAN DEL PARANA'),(123,'SAN PEDRO DEL PARANA'),(124,'SAN RAFAEL DEL PARANA'),(125,'TOMAS ROMERO PEREIRA'),(126,'TRINIDAD'),(127,'YATYTAY'),(128,'SAN JUAN BAUTISTA'),(129,'AYOLAS'),(130,'SAN IGNACIO'),(131,'SAN MIGUEL'),(132,'SAN PATRICIO'),(133,'SANTA MARIA'),(134,'SANTA ROSA'),(135,'SANTIAGO'),(136,'VILLA FLORIDA'),(137,'YAVEVYRY'),(138,'PARAGUARI'),(139,'ACAHAY'),(140,'CAAPUCU'),(141,'CARAPEGUA'),(142,'ESCOBAR'),(143,'GRAL. B. CABALLERO'),(144,'LA COLMENA'),(145,'MBUYAPEY'),(146,'PIRAYU'),(147,'QUIINDY'),(148,'QUYQUYHO'),(149,'SAN ROQUE GONZALEZ'),(150,'SAPUCAI'),(151,'TEBICUARYMI'),(152,'YAGUARON'),(153,'YBYCUI'),(154,'YBYTYMI'),(155,'CIUDAD DEL ESTE'),(156,'DR. J. L. MALLORQUIN'),(157,'DOMINGO M. DE IRALA'),(158,'HERNANDARIAS'),(159,'IRUÑA'),(160,'ITAKYRY'),(161,'JUAN E. OLEARY'),(162,'LOS CEDRALES'),(163,'MBARACAYU'),(164,'MINGA GUAZU'),(165,'MINGA PORA'),(166,'NARANJAL'),(167,'ÑACUNDAY'),(168,'PRESIDENTE FRANCO'),(169,'SAN ALBERTO'),(170,'SAN CRISTOBAL'),(171,'SANTA FE DEL PARANA'),(172,'SANTA RITA'),(173,'STA. ROSA DEL MONDAY'),(174,'YGUAZU'),(175,'AREGUA'),(176,'CAPIATA'),(177,'FERNANDO DE LA MORA'),(178,'GUARAMBARE'),(179,'ITA'),(180,'ITAUGUA'),(181,'J. AUGUSTO SALDIVAR'),(182,'LAMBARE'),(183,'LIMPIO'),(184,'LUQUE'),(185,'MARIANO R. ALONSO'),(186,'NUEVA ITALIA'),(187,'ÑEMBY'),(188,'SAN ANTONIO'),(189,'SAN LORENZO'),(190,'VILLA ELISA'),(191,'VILLETA'),(192,'YPACARAI'),(193,'YPANE'),(194,'PILAR'),(195,'ALBERDI'),(196,'CERRITO'),(197,'DESMOCHADOS'),(198,'GRAL. JOSE E. DIAZ'),(199,'GUAZU CUA'),(200,'HUMAITA'),(201,'ISLA UMBU'),(202,'LAURELES'),(203,'MAYOR J. MARTINEZ'),(204,'PASO DE PATRIA'),(205,'SAN JUAN BAUTISTA'),(206,'TACUARAS'),(207,'VILLA FRANCA'),(208,'VILLA OLIVA'),(209,'VILLALBIN'),(210,'PEDRO J. CABALLERO'),(211,'BELLA VISTA'),(212,'CAPITAN BADO'),(213,'SALTO DEL GUAIRA'),(214,'CORPUS CHRISTI'),(215,'FRANCISCO C. ALVAREZ'),(216,'ITANARA'),(217,'KATUETE'),(218,'LA PALOMA'),(219,'NUEVA ESPERANZA'),(220,'SAN ISIDRO CURUGUATY'),(221,'VILLA YGATIMI'),(222,'YASY CAÑY'),(223,'YPE JHU'),(224,'VILLA HAYES'),(225,'BENJAMIN ACEVAL'),(226,'FORTIN JOSE FALCON'),(227,'GRAL. JOSE M. BRUGUE'),(228,'NANAWA'),(229,'PTO. PINASCO'),(230,'TTE.IRALA FERNANDEZ'),(231,'TTE ESTEBAN MARTINEZ'),(232,'FUERTE OLIMPO'),(233,'PUERTO CASADO'),(234,'CARMELO PERALTA'),(235,'BAHIA NEGRA'),(236,'FILADELFIA'),(237,'LOMA PLATA'),(238,'MCAL.ESTIGARRIBIA'),(239,'(FUERA DEL PAIS)');

/*Table structure for table `comentarios` */

DROP TABLE IF EXISTS `comentarios`;

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_sprint` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_user_storie` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_comentario`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

/*Data for the table `comentarios` */

insert  into `comentarios`(`id_comentario`,`id_sprint`,`id_usuario`,`id_user_storie`,`comentario`,`fecha`) values (1,1,1,0,'hola','2021-11-21 11:59:26'),(2,1,1,0,'hoa','2021-11-21 12:03:30'),(3,1,1,0,'asdasd','2021-11-21 12:15:38'),(4,1,1,0,'nmdnmzxc','2021-11-21 12:15:58'),(5,1,1,0,'','2021-11-21 12:20:03'),(6,1,1,0,'','2021-11-21 12:20:12'),(7,1,1,0,'','2021-11-21 12:29:05'),(8,1,1,0,'','2021-11-21 12:29:12'),(9,2,1,0,'Hola que tal','2021-11-21 21:01:24'),(10,2,1,0,'hloasd','2021-11-21 21:01:35'),(11,2,1,0,'asadhas hajshdasj','2021-11-21 21:01:55'),(12,2,1,0,'sdasd','2021-11-22 17:13:48'),(13,2,1,0,'asdasda','2021-11-22 17:16:17'),(14,2,1,0,'sdasd','2021-11-22 17:17:21'),(15,2,1,0,'sdasd','2021-11-22 17:17:27'),(16,2,1,0,'sdadas','2021-11-22 17:27:30');

/*Table structure for table `empresas` */

DROP TABLE IF EXISTS `empresas`;

CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `razon_social` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `ruc` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `actividades` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `color` varchar(7) COLLATE utf8_spanish_ci DEFAULT NULL,
  `logo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `logo_nav` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 0 COMMENT '0 pendiente de aprobación, 1 activo, 2 inactivo',
  `modo` tinyint(1) NOT NULL,
  `tipo_factura` tinyint(1) NOT NULL DEFAULT 0,
  KEY `id_empresa` (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `empresas` */

insert  into `empresas`(`id_empresa`,`marca`,`razon_social`,`ruc`,`actividades`,`direccion`,`telefono`,`email`,`fecha_registro`,`color`,`logo`,`logo_nav`,`estado`,`modo`,`tipo_factura`) values (1,'Central','Central','80074213-3','Central','Tuyuti 184, Luque - Paraguay','021 - 328 3007','contacto@proinso.com.py','2021-06-13 18:11:03','#70cfd2','proinsosa.png','proinsosa-nav.png',1,1,0),(2,'Prueba','Prueba S.A','11111111','Pruebas\r\n','Avenida Dorrego 1625, Argentina','11 51984977','','2021-06-13 18:11:43','#5a423b','log25d8e5ec358691.png','log_nav25d8e62e6d6ff8.jpg',1,1,0);

/*Table structure for table `locales` */

DROP TABLE IF EXISTS `locales`;

CREATE TABLE `locales` (
  `id_local` int(11) NOT NULL AUTO_INCREMENT,
  `local` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_ciudad` int(4) NOT NULL,
  `telefonos` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario encargado de la empresa',
  `email` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1 COMMENT '1 activo, 0 inactivo',
  `id_empresa` int(4) DEFAULT 1,
  `commers` int(1) NOT NULL DEFAULT 0,
  `id_local_padre` int(11) NOT NULL,
  PRIMARY KEY (`id_local`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `locales_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `locales` */

insert  into `locales`(`id_local`,`local`,`direccion`,`id_ciudad`,`telefonos`,`id_usuario`,`email`,`estado`,`id_empresa`,`commers`,`id_local_padre`) values (1,'Central','Tuyuti 184 - Luque',184,'0981700555',1,'',1,1,0,0),(3,'Administración','Dorrego 1625',239,'5491132844007',0,NULL,1,2,0,0);

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `submenu` varchar(100) COLLATE utf8_spanish_ci DEFAULT '-',
  `titulo` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `url` varchar(50) COLLATE utf8_spanish_ci DEFAULT '-',
  `orden` int(4) DEFAULT NULL,
  `subsubmenu` int(1) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 activo 0 inactivo',
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `menus` */

insert  into `menus`(`id_menu`,`menu`,`submenu`,`titulo`,`url`,`orden`,`subsubmenu`,`estado`) values (3,'Modulo de Seguridad','Roles','Roles','./administrar-roles.php',4,0,1),(4,'Modulo de Seguridad','Accesos segun Roles','Accesos segun Roles','./menus-por-roles.php',5,0,1),(7,'Modulo de Seguridad','Usuarios','Usuarios','./administrar-usuarios.php',8,0,1),(98,'Modulo de Proyecto','Proyectos','Proyectos','./administrar-proyectos.php',9,0,1),(99,'Modulo de Desarrollo','Backlogs','Backlogs','./administrar-backlogs.php',11,0,1),(100,'Modulo de Desarrollo','User Storie','User Storie','./administrar-user-stories.php',12,0,1),(101,'Modulo de Desarrollo','Sprints','Sprints','./administrar-sprints.php',13,0,1);

/*Table structure for table `proyectos` */

DROP TABLE IF EXISTS `proyectos`;

CREATE TABLE `proyectos` (
  `id_proyecto` int(11) NOT NULL AUTO_INCREMENT,
  `id_backlog` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `estado` enum('Abierto','Cerrado') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_proyecto`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `proyectos` */

insert  into `proyectos`(`id_proyecto`,`id_backlog`,`nombre`,`estado`,`fecha_registro`) values (6,4,'Prueba','Abierto','2021-10-24 01:37:32'),(7,0,'prueba 2','Abierto','2021-11-21 20:57:36'),(8,1,'prueba 3','Abierto','2021-10-24 02:00:45'),(9,5,'prueba 4','Cerrado','2021-11-22 17:54:42');

/*Table structure for table `relaciones_us` */

DROP TABLE IF EXISTS `relaciones_us`;

CREATE TABLE `relaciones_us` (
  `id_relacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_sprint` int(11) NOT NULL,
  `id_user_storie` int(11) NOT NULL,
  PRIMARY KEY (`id_relacion`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `relaciones_us` */

insert  into `relaciones_us`(`id_relacion`,`id_sprint`,`id_user_storie`) values (6,1,3);

/*Table structure for table `relaciones_usuarios` */

DROP TABLE IF EXISTS `relaciones_usuarios`;

CREATE TABLE `relaciones_usuarios` (
  `id_relacion_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_relacion_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

/*Data for the table `relaciones_usuarios` */

insert  into `relaciones_usuarios`(`id_relacion_usuario`,`id_proyecto`,`id_usuario`) values (9,6,192),(10,7,191),(11,8,190),(12,9,193);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `acceso` tinyint(1) DEFAULT 2 COMMENT '1 empresa 2 local',
  `estado` tinyint(1) DEFAULT 1 COMMENT '1 para si y 0 para no',
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `roles` */

insert  into `roles`(`id_rol`,`rol`,`acceso`,`estado`) values (1,'Administrador',0,1),(4,'Gerente',1,1),(5,'Comision',0,1),(6,'Programador',2,1),(13,'Invitado',0,0),(25,'prueba',0,1);

/*Table structure for table `roles_menu` */

DROP TABLE IF EXISTS `roles_menu`;

CREATE TABLE `roles_menu` (
  `id_rol_menu` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  PRIMARY KEY (`id_rol_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=486 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `roles_menu` */

insert  into `roles_menu`(`id_rol_menu`,`id_rol`,`id_menu`) values (5,1,3),(6,1,7),(7,4,16),(8,4,15),(9,4,12),(10,4,17),(13,4,23),(14,4,22),(15,4,21),(16,4,20),(17,4,25),(18,4,13),(19,4,14),(20,4,11),(21,4,26),(22,4,24),(24,4,57),(25,4,56),(26,4,60),(27,4,59),(28,4,52),(29,4,53),(30,4,54),(31,4,51),(32,4,55),(33,4,75),(34,4,73),(36,4,74),(37,4,72),(40,4,31),(41,4,33),(64,2,52),(65,2,53),(66,2,54),(67,2,51),(68,2,55),(80,4,58),(81,4,32),(120,4,6),(121,4,7),(122,5,75),(123,5,73),(124,5,71),(125,5,74),(126,5,72),(127,2,12),(128,2,11),(131,4,76),(133,3,31),(134,3,32),(140,6,60),(143,6,56),(148,6,12),(149,6,11),(150,6,76),(151,6,52),(152,6,53),(153,6,54),(154,6,51),(155,6,55),(156,6,75),(157,6,72),(158,6,31),(159,6,33),(160,6,16),(161,6,15),(162,7,76),(163,7,51),(164,5,16),(165,5,15),(166,5,12),(167,5,17),(168,5,23),(169,5,11),(170,5,22),(171,5,21),(172,5,20),(173,5,25),(174,5,13),(175,5,14),(176,5,26),(177,5,54),(178,5,51),(179,5,55),(180,5,41),(181,5,42),(182,5,31),(183,5,33),(184,5,32),(186,5,5),(187,5,6),(189,5,7),(190,5,24),(191,5,58),(192,5,57),(193,5,56),(194,5,60),(195,5,59),(196,5,76),(197,5,52),(198,5,53),(199,5,0),(208,6,58),(209,6,57),(210,6,22),(211,6,21),(212,6,20),(213,6,13),(214,6,32),(217,6,59),(221,2,21),(222,4,78),(223,4,83),(225,4,85),(226,4,77),(227,4,81),(228,4,84),(230,5,80),(233,2,81),(234,2,74),(235,2,84),(236,2,72),(239,2,76),(243,2,33),(244,5,78),(245,5,83),(247,5,85),(248,5,77),(249,5,81),(250,5,84),(251,4,86),(252,5,86),(253,8,72),(258,5,88),(259,5,87),(260,4,88),(261,4,87),(262,4,71),(263,4,41),(264,4,42),(266,4,5),(268,6,83),(269,6,86),(270,5,89),(271,5,92),(272,5,90),(273,5,91),(291,9,16),(292,9,15),(293,9,12),(294,9,83),(295,9,86),(296,9,85),(297,9,17),(298,9,23),(299,9,11),(300,9,22),(301,9,21),(302,9,77),(303,9,20),(304,9,25),(305,9,13),(306,9,14),(307,9,26),(308,9,24),(309,9,58),(310,9,57),(311,9,56),(312,9,88),(313,9,60),(314,9,51),(315,9,87),(316,9,59),(317,9,76),(318,9,52),(319,9,53),(320,9,54),(321,9,55),(322,9,75),(323,9,73),(324,9,71),(325,9,81),(326,9,74),(327,9,84),(328,9,72),(329,9,41),(330,9,42),(331,9,89),(332,9,92),(333,9,90),(334,9,91),(335,9,31),(336,9,33),(337,9,32),(349,4,89),(350,4,92),(351,4,90),(352,4,91),(353,10,16),(354,10,15),(355,10,12),(356,10,78),(357,10,83),(358,10,86),(359,10,85),(360,10,17),(361,10,23),(362,10,11),(363,10,22),(364,10,21),(365,10,77),(366,10,20),(367,10,25),(368,10,13),(369,10,14),(370,10,26),(371,10,24),(372,10,58),(373,10,57),(374,10,56),(375,10,88),(376,10,60),(377,10,51),(378,10,87),(379,10,59),(380,10,76),(381,10,52),(382,10,53),(383,10,54),(384,10,55),(385,10,75),(386,10,73),(387,10,71),(388,10,81),(389,10,74),(390,10,84),(391,10,72),(392,10,41),(393,10,42),(394,10,89),(395,10,92),(396,10,90),(397,10,91),(398,10,31),(399,10,33),(400,10,32),(401,10,4),(402,10,5),(403,10,6),(404,10,3),(405,10,7),(406,10,80),(407,10,0),(456,14,89),(457,14,92),(458,14,90),(459,14,91),(462,15,73),(463,15,72),(464,2,0),(468,1,95),(469,1,96),(470,1,97),(471,1,4),(472,6,96),(473,5,96),(474,5,97),(475,4,95),(476,4,96),(477,4,97),(479,25,96),(480,1,98),(481,1,99),(482,1,100),(483,1,101),(484,6,100),(485,6,98);

/*Table structure for table `sprints` */

DROP TABLE IF EXISTS `sprints`;

CREATE TABLE `sprints` (
  `id_sprint` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL,
  `estado` enum('Iniciar','Cerrar','Pendiente','') NOT NULL DEFAULT 'Pendiente',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  PRIMARY KEY (`id_sprint`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sprints` */

insert  into `sprints`(`id_sprint`,`id_proyecto`,`estado`,`fecha_inicio`,`fecha_fin`) values (1,8,'Iniciar','2021-10-24','2021-11-08'),(2,9,'Cerrar','2021-11-21','2021-12-05'),(3,6,'Iniciar','2021-11-22','2021-12-06');

/*Table structure for table `user_stories` */

DROP TABLE IF EXISTS `user_stories`;

CREATE TABLE `user_stories` (
  `id_user_storie` int(11) NOT NULL AUTO_INCREMENT,
  `id_backlog` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_user_storie`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_stories` */

insert  into `user_stories`(`id_user_storie`,`id_backlog`,`id_usuario`,`titulo`,`descripcion`,`fecha`,`estado`) values (1,1,191,'us1','prueba user storie','2021-11-21 13:14:49',0),(3,1,190,'us2','prueba 2','2021-11-21 17:20:44',1),(4,5,192,'us3','prueba 3','2021-11-22 17:17:25',2),(5,5,191,'us4','nueva prueba','2021-11-22 17:27:29',2),(6,4,190,'prueba nueva','hola que tal','2021-11-22 17:48:38',0);

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL,
  `nombre_usuario` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 activo 0 inactivo',
  `rol` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  KEY `nombre` (`nombre`),
  KEY `apellido` (`apellido`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Usuarios del sistema';

/*Data for the table `usuarios` */

insert  into `usuarios`(`id_usuario`,`id_proyecto`,`nombre_usuario`,`nombre`,`apellido`,`password`,`fecha_registro`,`estado`,`rol`) values (1,0,'admin','admin','admin','827ccb0eea8a706c4c34a16891f84e7b','2016-08-14 23:50:02',1,1),(190,8,'prueba','Fabian','Fabian','202cb962ac59075b964b07152d234b70','2021-08-21 19:58:42',1,5),(191,7,'roger','roger','rondelli','202cb962ac59075b964b07152d234b70','2021-09-26 18:57:51',1,6),(192,6,'enrique','enrique','chavez','202cb962ac59075b964b07152d234b70','2021-09-26 18:58:13',1,6),(193,9,'prueba2','prueba 2','prueba 2','202cb962ac59075b964b07152d234b70','2021-11-21 20:27:48',1,6);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
