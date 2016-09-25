
--  MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `erepublik` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `erepublik`;

DROP TABLE IF EXISTS `article_votes`;
CREATE TABLE `article_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_uid` (`article`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `article_votes` (`id`, `article`, `uid`, `created_at`, `updated_at`) VALUES
(1,	1,	18,	'2016-08-27 11:38:35',	'2016-08-27 11:38:35'),
(2,	2,	18,	'2016-09-06 17:16:50',	'2016-09-06 17:16:50');

DROP TABLE IF EXISTS `candidate_votes`;
CREATE TABLE `candidate_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `chats`;
CREATE TABLE `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(200) NOT NULL,
  `sender` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `channel_type` int(1) NOT NULL,
  `likes` int(3) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `chats` (`id`, `message`, `sender`, `channel_id`, `channel_type`, `likes`, `created_at`, `updated_at`) VALUES
(1,	'afsdfdsfsdfsdfsdf',	18,	1,	2,	0,	'2016-08-28 15:07:28',	'2016-08-28 15:07:28'),
(2,	'judas! traicionareo!!',	18,	1,	2,	0,	'2016-08-28 15:37:16',	'2016-08-28 15:37:16'),
(3,	'movimiento sesy!!movimiento sesy!!',	18,	1,	2,	0,	'2016-08-28 15:42:43',	'2016-08-28 15:42:43');

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` int(2) NOT NULL,
  `quality` int(1) NOT NULL,
  `last_work` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `companies` (`id`, `uid`, `type`, `quality`, `last_work`, `created_at`, `updated_at`) VALUES
(1,	18,	1,	1,	'2016-08-28 14:29:47',	'2016-08-11 16:20:06',	'2016-08-28 14:29:47'),
(2,	18,	4,	1,	'2016-08-28 14:29:47',	'2016-08-11 16:21:00',	'2016-08-28 14:29:47'),
(3,	18,	1,	2,	'2016-08-28 14:29:47',	'2016-08-12 18:01:46',	'2016-08-28 14:29:47'),
(4,	18,	3,	1,	'2016-08-28 14:29:47',	'2016-08-16 17:37:33',	'2016-08-28 14:29:47');

DROP TABLE IF EXISTS `congress_candidates`;
CREATE TABLE `congress_candidates` (
  `uid` int(11) NOT NULL,
  `yes` int(3) NOT NULL DEFAULT '0',
  `no` int(3) NOT NULL DEFAULT '0',
  `country` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `congress_members`;
CREATE TABLE `congress_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `party` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `country` int(3) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `congress_members` (`id`, `party`, `uid`, `country`, `created_at`, `updated_at`) VALUES
(1,	4,	18,	1,	'2016-08-21 15:02:32',	'2016-08-21 15:02:32');

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(110) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `capital` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  KEY `id` (`id`),
  KEY `capital` (`capital`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `countries` (`id`, `name`, `currency`, `capital`, `created_at`, `updated_at`) VALUES
(1,	'Spain',	'esp',	3,	'2016-08-07 18:21:18',	'2016-08-07 18:21:18');

DROP TABLE IF EXISTS `country_funds`;
CREATE TABLE `country_funds` (
  `country` int(3) NOT NULL,
  `gold` decimal(10,2) NOT NULL DEFAULT '0.00',
  `esp` decimal(10,2) NOT NULL DEFAULT '0.00',
  UNIQUE KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `country_relations`;
CREATE TABLE `country_relations` (
  `id` int(11) NOT NULL,
  `country` int(3) NOT NULL,
  `target` int(3) NOT NULL,
  `relation` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `item_offers`;
CREATE TABLE `item_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` int(11) NOT NULL,
  `quality` int(1) NOT NULL,
  `uid` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `country` int(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `item_offers` (`id`, `item`, `quality`, `uid`, `price`, `quantity`, `country`, `created_at`, `updated_at`) VALUES
(13,	1,	0,	18,	0.30,	206,	1,	'2016-08-14 08:34:06',	'2016-08-14 08:36:26'),
(14,	4,	1,	18,	0.10,	1000,	1,	'2016-08-14 08:36:45',	'2016-08-14 08:36:45');

DROP TABLE IF EXISTS `law_proposals`;
CREATE TABLE `law_proposals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` int(2) NOT NULL,
  `country` int(11) NOT NULL,
  `reason` varchar(110) NOT NULL,
  `target_country` int(11) NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `yes` int(11) NOT NULL DEFAULT '0',
  `no` int(11) NOT NULL DEFAULT '0',
  `expected_votes` int(11) NOT NULL,
  `finished` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `law_proposals` (`id`, `uid`, `type`, `country`, `reason`, `target_country`, `amount`, `yes`, `no`, `expected_votes`, `finished`, `created_at`, `updated_at`) VALUES
(1,	18,	3,	1,	'We must increase it to steal more money',	0,	5.56,	0,	0,	1,	0,	'2016-08-21 16:00:45',	'2016-08-21 16:00:45'),
(2,	18,	3,	1,	'We must increase it to steal more money',	0,	5.56,	0,	0,	1,	0,	'2016-08-21 16:01:26',	'2016-08-21 16:01:26');

DROP TABLE IF EXISTS `law_votes`;
CREATE TABLE `law_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `law` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `in_favor` int(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `newspapers`;
CREATE TABLE `newspapers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(110) NOT NULL,
  `country` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newspapers` (`id`, `name`, `country`, `uid`, `description`, `created_at`, `updated_at`) VALUES
(2,	'El Noticiero',	1,	18,	'Para pasar el rato',	'2016-08-27 09:44:08',	'2016-08-27 09:44:08');

DROP TABLE IF EXISTS `newspaper_articles`;
CREATE TABLE `newspaper_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(110) NOT NULL,
  `text` text NOT NULL,
  `category` int(1) NOT NULL,
  `uid` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newspaper_articles` (`id`, `title`, `text`, `category`, `uid`, `country`, `views`, `votes`, `created_at`, `updated_at`) VALUES
(1,	'Debes morir',	'[center][size=6]Porque lo digo [b]yo[/b][/size][/center]\n\n[center][size=6][b][img]http://cdn.arstechnica.net/wp-content/uploads/2016/02/5718897981_10faa45ac3_b-640x624.jpg[/img][/b][/size][/center]\n',	1,	18,	1,	33,	1,	'2016-08-27 11:09:23',	'2016-09-06 17:13:06'),
(2,	'Congress declares WAR',	'[center][img]http://www.army-technology.com/projects/ariete/images/ariete5.jpg[/img][/center]\n[center][b][size=7]Congress declares WAR[/size][/b][/center]\n\n[justify][color=#000000][size=2][font=\"Open Sans\", Arial, sans-serif]orem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget augue eu ipsum molestie posuere. Ut ac ante ac odio aliquam pretium eget dictum arcu. Morbi malesuada consequat mattis. Suspendisse elementum volutpat justo, et malesuada dui posuere quis. Aliquam erat volutpat. Curabitur tristique lacinia elit, non sollicitudin orci pellentesque ut. Morbi lobortis erat quis eleifend ornare. Nunc eget fermentum neque. Interdum et malesuada fames ac ante ipsum primis in faucibus. In neque nisi, elementum scelerisque magna eget, mattis scelerisque urna. Ut congue ante at sapien dictum scelerisque. Duis sed ultricies lectus. Morbi ex nisl, facilisis vitae dolor in, facilisis tincidunt felis. Nulla in finibus dui. Integer fringilla quam vel aliquet faucibus. Ut ante eros, vehicula ut venenatis vel, gravida non quam.[/font][/size][/color][/justify]\n[justify][color=#000000][size=2][font=\"Open Sans\", Arial, sans-serif]Duis mollis accumsan risus, sit amet ornare lorem aliquet a. Fusce erat ante, elementum sit amet consequat in, imperdiet blandit risus. Maecenas hendrerit neque sit amet imperdiet tempor. Morbi sit amet pretium ante. Nulla facilisis eleifend diam a porta. Nulla condimentum quis ex vel mattis. Donec faucibus justo vitae scelerisque tempor. Duis est nisi, dignissim ut tempor id, suscipit ac lorem. In libero augue, euismod blandit nibh ut, molestie accumsan est. Donec molestie sed nibh non elementum. Aliquam pulvinar justo a tortor lobortis, id suscipit dui pulvinar. Phasellus vitae sapien sapien. Cras vulputate a tellus eget fermentum. Donec maximus massa dolor, mollis interdum neque efficitur quis. Pellentesque et ligula finibus, venenatis ligula et, rutrum nibh. Duis non maximus enim.[/font][/size][/color][/justify]\n[justify][color=#000000][size=2][font=\"Open Sans\", Arial, sans-serif]Suspendisse potenti. Pellentesque maximus sodales massa ut tempus. Curabitur at ultrices ante, vitae vestibulum nulla. Praesent ante orci, cursus non consectetur non, scelerisque euismod nisl. Donec sagittis molestie egestas. In mi nunc, auctor et aliquam vel, pharetra consequat lectus. Phasellus porttitor eget ligula id gravida. Etiam at libero efficitur, convallis eros vel, maximus lectus. Vivamus facilisis, ligula a dictum scelerisque, eros tellus pretium justo, pellentesque elementum nisi dolor non mauris. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse tempus porta imperdiet.[/font][/size][/color][/justify]\n',	1,	18,	1,	2,	1,	'2016-09-06 17:16:15',	'2016-09-06 17:16:50');

DROP TABLE IF EXISTS `party_members`;
CREATE TABLE `party_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `party` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `party_members` (`id`, `party`, `uid`, `level`, `created_at`, `updated_at`) VALUES
(4,	4,	18,	3,	'2016-08-16 15:52:26',	'2016-08-16 15:52:26');

DROP TABLE IF EXISTS `political_parties`;
CREATE TABLE `political_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `country` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `political_parties` (`id`, `uid`, `name`, `description`, `country`, `created_at`, `updated_at`) VALUES
(4,	18,	'Los traidores',	'Traicionar al país con todos los medios disponibles.',	1,	'2016-08-16 15:52:26',	'2016-08-16 15:52:26');

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(110) NOT NULL,
  `country` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`),
  CONSTRAINT `regions_ibfk_1` FOREIGN KEY (`country`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `regions` (`id`, `name`, `country`) VALUES
(3,	'Andalucia',	1),
(4,	'Aragon',	1),
(6,	'Balearic Islands',	1),
(7,	'Cantabria',	1),
(8,	'Castilla La Mancha',	1),
(9,	'Catalonia',	1),
(10,	'Extremadura',	1),
(11,	'Madrid',	1),
(12,	'Murcia',	1),
(13,	'Navarra',	1),
(14,	'Valencian Community',	1);

DROP TABLE IF EXISTS `region_connections`;
CREATE TABLE `region_connections` (
  `region_a` int(11) NOT NULL,
  `region_b` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  UNIQUE KEY `region_a_region_b` (`region_a`,`region_b`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `taxes`;
CREATE TABLE `taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(2) NOT NULL,
  `amount` float NOT NULL,
  `country` int(3) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `level` int(3) NOT NULL DEFAULT '1',
  `xp` int(11) NOT NULL DEFAULT '0',
  `strength` int(11) NOT NULL DEFAULT '1',
  `region` int(11) NOT NULL,
  `referrer` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nick` (`nick`),
  KEY `region` (`region`),
  KEY `referrer` (`referrer`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`region`) REFERENCES `regions` (`id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`referrer`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `nick`, `email`, `password`, `status`, `created_at`, `level`, `xp`, `strength`, `region`, `referrer`, `updated_at`) VALUES
(18,	'admin',	'admin@yopmail.com',	'd63be78cdd3bcfe1df97b5b3acbc752d',	1,	'2016-08-07 18:34:41',	1,	0,	31,	3,	NULL,	'2016-08-28 14:14:29');

DROP TABLE IF EXISTS `user_gyms`;
CREATE TABLE `user_gyms` (
  `uid` int(11) NOT NULL,
  `q1` datetime DEFAULT NULL,
  `q2` datetime DEFAULT NULL,
  `q3` datetime DEFAULT NULL,
  `q4` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user_gyms` (`uid`, `q1`, `q2`, `q3`, `q4`) VALUES
(18,	'2016-08-28 00:00:00',	'2016-08-18 00:00:00',	NULL,	NULL);

DROP TABLE IF EXISTS `user_items`;
CREATE TABLE `user_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `quality` int(1) NOT NULL DEFAULT '0',
  `quantity` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item_quality` (`uid`,`item`,`quality`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user_items` (`id`, `uid`, `item`, `quality`, `quantity`) VALUES
(1,	18,	1,	0,	1023),
(2,	18,	4,	1,	479),
(3,	18,	1,	2,	70),
(4,	18,	1,	1,	30),
(5,	18,	3,	1,	30),
(6,	18,	3,	0,	30);

DROP TABLE IF EXISTS `user_money`;
CREATE TABLE `user_money` (
  `uid` int(11) NOT NULL,
  `gold` decimal(11,2) NOT NULL DEFAULT '0.00',
  `esp` decimal(11,2) NOT NULL DEFAULT '0.00',
  UNIQUE KEY `uid` (`uid`),
  CONSTRAINT `user_money_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user_money` (`uid`, `gold`, `esp`) VALUES
(18,	60.00,	4800.00);

DROP TABLE IF EXISTS `work_offers`;
CREATE TABLE `work_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL DEFAULT '1000.00',
  `country` int(11) NOT NULL,
  `worker` int(11) DEFAULT NULL,
  `last_work` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `worker` (`worker`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2016-09-25 15:07:10
