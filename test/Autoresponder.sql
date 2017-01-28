-- MySQL dump 10.13  Distrib 5.6.21, for Win64 (x86_64)
--
-- Host: localhost    Database: minute
-- ------------------------------------------------------
-- Server version	5.6.21

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
-- Table structure for table `m_ar_broadcasts`
--

DROP TABLE IF EXISTS `m_ar_broadcasts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_broadcasts` (
  `ar_broadcast_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `send_at` datetime NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `ar_list_id` int(11) DEFAULT NULL,
  `mail_id` int(11) DEFAULT NULL,
  `mailing_time` int(11) DEFAULT '60',
  `status` enum('pending','processing','sent') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`ar_broadcast_id`),
  KEY `status_send_at` (`status`,`send_at`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_broadcasts`
--

LOCK TABLES `m_ar_broadcasts` WRITE;
/*!40000 ALTER TABLE `m_ar_broadcasts` DISABLE KEYS */;
INSERT INTO `m_ar_broadcasts` VALUES (1,'2016-08-15 22:39:09','2016-08-15 22:39:02','test br',1,4,1,'pending');
/*!40000 ALTER TABLE `m_ar_broadcasts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_ar_campaigns`
--

DROP TABLE IF EXISTS `m_ar_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_campaigns` (
  `ar_campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ar_list_id` int(11) DEFAULT NULL,
  `schedule_json` longtext,
  `priority` int(11) DEFAULT '0',
  `advanced` enum('false','true') DEFAULT 'false',
  `enabled` enum('false','true') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`ar_campaign_id`),
  KEY `ar_list_id` (`ar_list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_campaigns`
--

LOCK TABLES `m_ar_campaigns` WRITE;
/*!40000 ALTER TABLE `m_ar_campaigns` DISABLE KEYS */;
INSERT INTO `m_ar_campaigns` VALUES (1,'2016-08-15 22:31:35','First Ar','Ar #1',1,'[{\"days\":[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"],\"start_time\":\"09:00\",\"end_time\":\"18:00\"},{\"days\":[\"Sat\"],\"start_time\":\"06:00\",\"end_time\":\"12:00\"}]',NULL,'true','true'),(2,'2016-08-15 22:31:35','Clone Ar','Ar #2',1,'',1,'true','true');
/*!40000 ALTER TABLE `m_ar_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_ar_summary`
--

DROP TABLE IF EXISTS `m_ar_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_summary` (
  `ar_summary_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ar_campaign_id` int(11) NOT NULL,
  `last_send_date` datetime NOT NULL,
  `last_mail_id` int(11) NOT NULL,
  PRIMARY KEY (`ar_summary_id`),
  UNIQUE KEY `user_id_ar_campaign_id` (`user_id`,`ar_campaign_id`),
  KEY `ar_campaign_id_last_mail_id` (`ar_campaign_id`,`last_mail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_summary`
--

LOCK TABLES `m_ar_summary` WRITE;
/*!40000 ALTER TABLE `m_ar_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_ar_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_ar_lists`
--

DROP TABLE IF EXISTS `m_ar_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_lists` (
  `ar_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ar_list_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_lists`
--

LOCK TABLES `m_ar_lists` WRITE;
/*!40000 ALTER TABLE `m_ar_lists` DISABLE KEYS */;
INSERT INTO `m_ar_lists` VALUES (1,'2016-08-15 22:27:54','2016-08-15 22:27:54','User Ids less than 5','All user ids with user_id < 5'),(2,'2016-08-15 23:04:57','2016-08-15 23:04:57','List matching nothing','matches 0 users'),(3,'2016-08-15 22:27:54','2016-08-15 22:27:54','User Ids > 0','All user ids');
/*!40000 ALTER TABLE `m_ar_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_ar_list_sqls`
--

DROP TABLE IF EXISTS `m_ar_list_sqls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_list_sqls` (
  `ar_list_sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `ar_list_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sql` longtext NOT NULL,
  `type` enum('positive','negative') NOT NULL DEFAULT 'positive',
  PRIMARY KEY (`ar_list_sql_id`),
  UNIQUE KEY `ar_list_id_name` (`ar_list_id`,`name`),
  KEY `ar_list_id` (`ar_list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_list_sqls`
--

LOCK TABLES `m_ar_list_sqls` WRITE;
/*!40000 ALTER TABLE `m_ar_list_sqls` DISABLE KEYS */;
INSERT INTO `m_ar_list_sqls` VALUES (1,1,'all user_ids less than 10','SELECT user_id from USERS WHERE user_id < 10','positive'),(2,1,'all user ids greater than 5','SELECT user_id from users where user_id >= 5','negative'),(3,2,'select nothing','SELECT user_id from USERS WHERE user_id IN (0)','positive'),(4,3,'all user_ids','SELECT user_id from USERS WHERE 1','positive');
/*!40000 ALTER TABLE `m_ar_list_sqls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_ar_messages`
--

DROP TABLE IF EXISTS `m_ar_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_messages` (
  `ar_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `ar_campaign_id` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `mail_id` int(11) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  `wait_hrs` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ar_message_id`),
  UNIQUE KEY `autoresponder_campaign_id_mail_id` (`ar_campaign_id`,`mail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_messages`
--

LOCK TABLES `m_ar_messages` WRITE;
/*!40000 ALTER TABLE `m_ar_messages` DISABLE KEYS */;
INSERT INTO `m_ar_messages` VALUES (1,1,'2016-08-15 22:32:58',1,NULL,48),(2,1,'2016-08-15 22:32:59',2,1,6),(3,2,'2016-08-15 22:32:59',3,NULL,77),(4,2,'2016-08-15 22:32:59',4,1,24);
/*!40000 ALTER TABLE `m_ar_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_ar_queue`
--

DROP TABLE IF EXISTS `m_ar_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_ar_queue` (
  `ar_queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `send_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `mail_id` int(11) NOT NULL,
  `status` enum('pending','pass','fail') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`ar_queue_id`),
  UNIQUE KEY `user_id_mail_id` (`user_id`,`mail_id`),
  KEY `status_send_at` (`status`,`send_at`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_ar_queue`
--

LOCK TABLES `m_ar_queue` WRITE;
/*!40000 ALTER TABLE `m_ar_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_ar_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_user_groups`
--

DROP TABLE IF EXISTS `m_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_user_groups` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT '0',
  `group_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `credits` int(11) NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_group_id`),
  KEY `user_id_expires_credits` (`user_id`,`expires_at`,`credits`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_user_groups`
--

LOCK TABLES `m_user_groups` WRITE;
/*!40000 ALTER TABLE `m_user_groups` DISABLE KEYS */;
INSERT INTO `m_user_groups` VALUES (1,9,0,'admin','2016-07-09 10:51:12','2016-07-09 10:51:12','2020-01-01 00:00:00',4,'admin'),(5,1,0,'power','2016-08-05 02:51:00','2016-08-05 02:51:00','2020-01-01 00:00:00',1,'hello');
/*!40000 ALTER TABLE `m_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_mails`
--

DROP TABLE IF EXISTS `m_mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_mails` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` enum('tip','user','offer','update','billing','support') NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`mail_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_mails`
--

LOCK TABLES `m_mails` WRITE;
/*!40000 ALTER TABLE `m_mails` DISABLE KEYS */;
INSERT INTO `m_mails` VALUES (1,'2016-08-15 22:22:34','2016-08-15 22:22:34','support','Ar Email #1','Autoresponder email #1'),(2,'2016-08-15 22:23:04','2016-08-15 22:23:04','support','Ar Email #2','Autoresponder email #2'),(3,'2016-08-15 22:24:28','2016-08-15 22:24:28','support','Ar Email #3','Autoresponder email #3'),(4,'2016-08-15 22:38:37','2016-08-15 22:38:37','support','Broadcast mail','Test broadcast');
/*!40000 ALTER TABLE `m_mails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_mail_contents`
--

DROP TABLE IF EXISTS `m_mail_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_mail_contents` (
  `mail_content_id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `html` longtext NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `track_opens` enum('false','true') NOT NULL DEFAULT 'false',
  `track_clicks` enum('false','true') NOT NULL DEFAULT 'false',
  `unsubscribe_link` enum('false','true') NOT NULL DEFAULT 'true',
  `enabled` enum('false','true') NOT NULL DEFAULT 'true',
  PRIMARY KEY (`mail_content_id`),
  KEY `m_mail_id` (`mail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_mail_contents`
--

LOCK TABLES `m_mail_contents` WRITE;
/*!40000 ALTER TABLE `m_mail_contents` DISABLE KEYS */;
INSERT INTO `m_mail_contents` VALUES (1,1,'first autoresponder email','mail #1','mail #1<p></p>',NULL,'true','true','true','true'),(2,2,'second autoresponder email','mail #2','mail #2<p></p>',NULL,'true','true','true','true'),(3,3,'third autoresponder email','mail #3','mail #3<p></p>',NULL,'true','true','true','true'),(4,4,'First test broadcast','test br','test br<p></p>',NULL,'true','true','true','true');
/*!40000 ALTER TABLE `m_mail_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_mail_unsubscribes`
--

DROP TABLE IF EXISTS `m_mail_unsubscribes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_mail_unsubscribes` (
  `mail_unsubscribe_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mail_type` enum('tip','user','offer','update','billing','support') NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`mail_unsubscribe_id`),
  UNIQUE KEY `user_id_prefix` (`user_id`,`mail_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_mail_unsubscribes`
--

LOCK TABLES `m_mail_unsubscribes` WRITE;
/*!40000 ALTER TABLE `m_mail_unsubscribes` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_mail_unsubscribes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `ident` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `tz_offset` int(11) DEFAULT NULL,
  `ip_addr` varchar(15) DEFAULT NULL,
  `verified` enum('false','true') DEFAULT 'false',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `ident` (`ident`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'2016-07-06 21:32:38','2016-07-06 21:32:38','one@s',NULL,'one','two','https://s3.amazonaws.com/minutephp/users/9/image/ok-2.png',-300,NULL,'false'),(8,NULL,'2016-07-06 21:32:38','2016-07-06 21:32:38','san@toufee.com',NULL,NULL,NULL,NULL,NULL,NULL,'false'),(9,NULL,'2016-07-08 17:49:52','2016-07-08 17:49:52','sanchitbh@gmail.com','$2y$10$f0zq9VNgd6o0gmxh5xe12O0SzlCdbXgfnTmH/kaA8HPJt8bO0BqCC','Test','Account','https://s3.amazonaws.com/minutephp/users/9/image/icon19.png',NULL,'127.0.0.1','true'),(2,NULL,'2016-07-06 21:32:38','2016-07-06 21:32:38','two@s',NULL,'two','man','https://s3.amazonaws.com/minutephp/users/9/image/ok-2.png',NULL,NULL,'false');
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

-- Dump completed on 2016-08-18 23:32:20
