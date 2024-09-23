-- Adminer 4.8.1 MySQL 10.11.7-MariaDB-1:10.11.7+maria~ubu2004 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `category` (`id`, `name`) VALUES
(1,	'jeux de société'),
(2,	'jeu apéro'),
(3,	'jeux de cartes'),
(4,	'jeux familial/enfants'),
(5,	'jeu narratif'),
(6,	'jeu historique'),
(7,	'jeu classique'),
(8,	'jeux de société'),
(9,	'jeu apéro'),
(10,	'jeux de cartes'),
(11,	'jeux familial/enfants'),
(12,	'jeu narratif'),
(13,	'jeu historique'),
(14,	'jeu classique');

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `content_favorite`;
CREATE TABLE `content_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3A726436E48FD905` (`game_id`),
  KEY `IDX_3A726436A76ED395` (`user_id`),
  CONSTRAINT `FK_3A726436A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3A726436E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `content_favorite` (`id`, `game_id`, `user_id`) VALUES
(1,	4,	3);

DROP TABLE IF EXISTS `content_rent`;
CREATE TABLE `content_rent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rent_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF1AA009E5FD6250` (`rent_id`),
  KEY `IDX_CF1AA009E48FD905` (`game_id`),
  CONSTRAINT `FK_CF1AA009E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `FK_CF1AA009E5FD6250` FOREIGN KEY (`rent_id`) REFERENCES `rent` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `content_rent` (`id`, `rent_id`, `game_id`) VALUES
(1,	1,	4),
(2,	1,	24),
(3,	1,	7),
(4,	1,	15),
(5,	2,	12),
(6,	3,	31),
(7,	3,	18),
(8,	3,	21),
(9,	4,	29),
(10,	4,	20),
(11,	4,	25),
(12,	5,	15),
(13,	5,	2),
(14,	6,	27),
(15,	6,	13),
(16,	6,	26),
(17,	7,	21),
(18,	7,	18),
(19,	7,	1),
(20,	7,	14),
(21,	7,	20),
(22,	8,	2),
(23,	8,	11),
(24,	8,	7),
(25,	9,	1),
(26,	9,	25),
(27,	9,	9),
(28,	9,	26),
(29,	9,	13),
(30,	10,	1),
(31,	10,	31),
(32,	10,	27),
(33,	11,	2),
(34,	12,	23),
(35,	12,	8),
(36,	12,	28),
(37,	12,	18),
(38,	12,	1),
(39,	13,	3),
(40,	14,	17),
(41,	14,	7),
(42,	14,	16),
(43,	14,	12),
(44,	14,	24),
(45,	15,	1),
(46,	15,	4),
(47,	15,	21),
(48,	15,	30),
(49,	15,	6),
(50,	16,	15),
(51,	17,	11),
(52,	18,	6),
(53,	18,	19),
(54,	18,	16),
(55,	18,	1),
(56,	19,	5),
(57,	20,	25);

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240604160837',	'2024-06-04 16:08:43',	118);

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_232B318C12469DE2` (`category_id`),
  CONSTRAINT `FK_232B318C12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `game` (`id`, `category_id`, `name`, `description`, `price`, `status`, `quantity`, `image`) VALUES
(1,	14,	'Les Colons de Catane',	'Aperiam sed aliquid molestiae. Et aliquid sequi placeat saepe sapiente accusantium aut. Eveniet in ullam amet expedita. Consequatur voluptates magni iste aperiam neque.',	'54.26 €',	'Disponible',	46,	'13.jpg'),
(2,	9,	'Les Aventuriers du Rail',	'Accusantium ab ut odit vel enim aut. Omnis et fuga adipisci corporis. Atque omnis culpa quaerat rerum totam labore perspiciatis.',	'49.05 €',	'Disponible',	53,	'9209.jpg'),
(3,	9,	'Pandémie',	'Ut est id adipisci reiciendis. Nihil doloremque hic commodi non sed.',	'18.27 €',	'Disponible',	18,	'30549.jpg'),
(4,	14,	'Carcassonne',	'Soluta modi tempora nulla. Soluta at accusamus illo quaerat eveniet iste explicabo. Suscipit ipsam dolorem et eveniet ut ipsa aspernatur. Et impedit voluptas et in voluptates minus qui.',	'68.7 €',	'Disponible',	81,	'836.jpg'),
(5,	11,	'Total Domination',	'Error sint alias beatae velit deleniti. Quasi vel similique ea. Porro veritatis omnis esse voluptas id culpa. Vel atque dolorum laudantium quod.',	'16.87 €',	'Disponible',	49,	'36218.jpg'),
(6,	13,	'7 Wonders',	'Laborum quia commodi neque sequi et dolor asperiores consequuntur. Nihil cumque quam placeat blanditiis. Deserunt nihil quia rerum eligendi sunt est tempora.',	'66.67 €',	'Disponible',	85,	'68448.jpg'),
(7,	9,	'Splendor',	'Vero suscipit accusantium nesciunt velit dolor. Deserunt id non qui saepe et. Et doloribus magni at fugit. Delectus ex cumque aut est.',	'10.92 €',	'Disponible',	41,	'176896.jpg'),
(8,	13,	'Codenames',	'Non dolorum quod voluptas consequatur. Fugit et porro nisi quasi sed dolore. Vitae est quia enim ut eum. Adipisci laudantium enim omnis possimus.',	'88.8 €',	'Disponible',	22,	'198773.jpg'),
(9,	13,	'Azul',	'Velit incidunt doloremque assumenda distinctio fuga debitis odit culpa. Velit quisquam eos optio id. Tempora adipisci provident quasi enim natus et.',	'61.46 €',	'Disponible',	3,	'230802.jpg'),
(10,	9,	'Gloomhaven',	'Soluta placeat ea quibusdam rerum dolorum consequatur. Consequuntur rem ut ad ad. Voluptatem iusto veniam quia voluptas quia qui consectetur. Voluptatibus qui nostrum et ut error quam aut.',	'45.86 €',	'Disponible',	7,	'174430.jpg'),
(11,	8,	'Wingspan',	'Non dolores culpa eaque et. Repellat eligendi molestias soluta quos quo illum ipsa. Et quibusdam voluptatem exercitationem quod.',	'21.7 €',	'Disponible',	38,	'266192.jpg'),
(12,	14,	'Terraforming Mars',	'Deserunt eum enim sed maxime eligendi maxime sint est. Omnis voluptatem laboriosam quaerat accusantium ab sint. Quas quisquam ipsa tempore velit adipisci blanditiis.',	'21.26 €',	'Disponible',	16,	'167791.jpg'),
(13,	8,	'Root',	'Possimus veritatis similique sit doloribus alias quia. Quia quod eos ut aperiam delectus ea perspiciatis. Incidunt corporis in tempora explicabo quis.',	'48.95 €',	'Disponible',	99,	'237182.jpg'),
(14,	12,	'Scythe',	'Itaque et alias omnis odio occaecati vero fugit. Consectetur aut voluptas et impedit doloribus ut sunt. Quam quia numquam perspiciatis. Quis sit quas fugiat.',	'65.85 €',	'Disponible',	71,	'169786.jpg'),
(15,	13,	'The Resistance',	'Maiores esse doloremque iure expedita et rerum nihil. Ratione hic ullam fugiat quasi a. Eius est sapiente aut eum rem.',	'48.37 €',	'Disponible',	48,	'128210.jpg'),
(16,	12,	'Dixit',	'Mollitia dolorem at nostrum aut. Quo quos numquam non tenetur. Provident a fugit tenetur quidem. Quia architecto nihil et et quis.',	'72.17 €',	'Disponible',	80,	'39856.jpg'),
(17,	14,	'Sheriff de Nottingham',	'Amet facere laborum vel nihil quae quia ut necessitatibus. Nisi dolorem dolores omnis sed voluptatem aliquid. Distinctio perferendis ipsam et qui.',	'30.31 €',	'Disponible',	6,	'157969.jpg'),
(18,	13,	'Blood Rage',	'Culpa et et sunt numquam ea. Ut aliquid ex architecto facilis molestiae necessitatibus non itaque. Nesciunt porro exercitationem voluptatem et beatae. Animi numquam sed voluptatem.',	'94.35 €',	'Disponible',	73,	'170216.jpg'),
(19,	9,	'Betrayal at House on the Hill',	'Eaque commodi eos ut et ipsum cum. Ut facilis vel voluptatem consequatur ab voluptatem. Ut eius tempore saepe minus aut iure. Ullam reiciendis cumque at.',	'65.83 €',	'Disponible',	49,	'10547.jpg'),
(20,	14,	'Horreur à Arkham',	'Adipisci atque hic soluta sint occaecati velit sequi. Similique sed aperiam veritatis rerum voluptates expedita magni. Quisquam aut eveniet minima fuga. Ex nisi sit aut in vitae.',	'61.95 €',	'Disponible',	8,	'15987.jpg'),
(21,	12,	'Les Contrées de l\'Horreur',	'Odit accusantium iste suscipit impedit numquam aliquid. Eum doloremque ut distinctio et. Qui porro qui ut vero doloremque. Error ut delectus praesentium eligendi earum.',	'67.88 €',	'Disponible',	94,	'126163.jpg'),
(22,	14,	'Twilight Struggle Deluxe',	'Error eos repellat ut sunt. Quos voluptate voluptatem enim ab sed. Adipisci quas occaecati sed numquam qui quia ut. Voluptas itaque consequatur deleniti rerum corporis.',	'63.59 €',	'Disponible',	86,	'3659.jpg'),
(23,	9,	'King of Tokyo Horreur Edition',	'Quia sint vel dolores totam eum omnis soluta. Vel et molestias nulla excepturi dolores voluptas modi. Dicta tempora eum aut officiis ipsum. Rerum dolor voluptatem illum aut ut officia.',	'49.26 €',	'Disponible',	56,	'70323.jpg'),
(24,	11,	'God of War',	'Libero natus est laborum est voluptas deserunt asperiores excepturi. Minus adipisci et praesentium asperiores eos aut minima. Vel eos dolores ab earum laboriosam enim recusandae.',	'23.42 €',	'Disponible',	42,	'31260.jpg'),
(25,	10,	'Rex',	'Velit praesentium et porro vitae eius assumenda aut ducimus. Itaque quisquam error quia. Quo maiores dolor fuga distinctio dolor. Et est at dolores quis animi labore et incidunt.',	'56.08 €',	'Disponible',	47,	'3076.jpg'),
(26,	14,	'Mage Knight',	'Laudantium vel quasi possimus earum ut facere sequi. Mollitia dolores expedita sed in ut dolores ullam. At voluptates autem et dolorem numquam earum nihil.',	'18.49 €',	'Disponible',	50,	'96848.jpg'),
(27,	8,	'Power Rangers: Heroes of the Grid',	'Cumque ad et fuga consequatur sed sed quidem. Quas necessitatibus consequatur laborum sint aut sed aperiam dolorum. Necessitatibus dolorum ipsum earum accusamus animi ipsam quaerat.',	'59.73 €',	'Disponible',	21,	'2651.jpg'),
(28,	8,	'SMALL WORLD OF WARCRAFT',	'Perferendis magni accusamus autem quaerat. Mollitia magnam omnis et omnis temporibus. Tenetur quo unde iste blanditiis non. Repudiandae deleniti qui nisi totam.',	'35.03 €',	'Disponible',	97,	'40692.jpg'),
(29,	12,	'L\'Âge de Pierre',	'Fugit ipsum veniam voluptate perspiciatis aut aspernatur nesciunt placeat. Qui aut quibusdam tenetur qui. Ullam reiciendis sunt ad.',	'47.03 €',	'Disponible',	90,	'34635.jpg'),
(30,	13,	'Les Châteaux de Bourgogne Deluxe',	'Ut iusto impedit facere. Qui est et et et illum. Aut quo doloribus et. Omnis consequuntur expedita qui.',	'47.61 €',	'Disponible',	80,	'84268.jpg'),
(31,	14,	'Clank!',	'Mollitia ex excepturi ipsa. Sit iste consequuntur suscipit suscipit quidem. Sed aut molestiae earum eligendi est voluptates.',	'30.57 €',	'Disponible',	20,	'201808.jpg');

DROP TABLE IF EXISTS `rent`;
CREATE TABLE `rent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2784DCCA76ED395` (`user_id`),
  CONSTRAINT `FK_2784DCCA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rent` (`id`, `user_id`, `date_debut`, `date_fin`, `status`) VALUES
(1,	3,	'2024-05-14 06:13:55',	'2024-06-08 01:43:40',	'Returned'),
(2,	3,	'2024-05-24 06:59:01',	'2024-06-12 20:25:24',	'Approved'),
(3,	1,	'2024-05-20 21:53:46',	'2024-06-08 10:01:11',	'Returned'),
(4,	3,	'2024-05-28 10:27:14',	'2024-06-09 14:51:45',	'Returned'),
(5,	1,	'2024-05-20 02:12:23',	'2024-06-24 20:45:15',	'Approved'),
(6,	1,	'2024-05-30 05:35:01',	'2024-06-20 05:02:05',	'Approved'),
(7,	3,	'2024-05-16 00:18:54',	'2024-07-03 01:59:54',	'Returned'),
(8,	1,	'2024-05-28 02:18:45',	'2024-06-24 04:02:46',	'Approved'),
(9,	3,	'2024-05-09 21:44:44',	'2024-07-04 15:54:59',	'Pending'),
(10,	2,	'2024-05-05 04:48:53',	'2024-07-04 03:22:50',	'Returned'),
(11,	1,	'2024-05-24 17:32:15',	'2024-07-04 07:12:30',	'Returned'),
(12,	2,	'2024-05-23 21:54:21',	'2024-06-25 09:28:34',	'Pending'),
(13,	2,	'2024-06-01 09:19:27',	'2024-06-05 08:21:30',	'Approved'),
(14,	2,	'2024-05-12 08:04:57',	'2024-06-25 10:20:31',	'Returned'),
(15,	1,	'2024-05-29 00:54:26',	'2024-06-20 01:57:12',	'Returned'),
(16,	1,	'2024-05-18 05:29:07',	'2024-07-01 08:56:26',	'Approved'),
(17,	1,	'2024-05-07 07:10:16',	'2024-06-28 02:05:57',	'Returned'),
(18,	2,	'2024-05-16 18:55:20',	'2024-06-26 06:10:41',	'Pending'),
(19,	2,	'2024-05-15 06:46:15',	'2024-06-15 08:57:05',	'Returned'),
(20,	1,	'2024-05-29 13:14:27',	'2024-06-12 05:54:46',	'Returned');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '["ROLE_USER"]' COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `icon`) VALUES
(1,	'admin@gmail.com',	'[\"ROLE_ADMIN\"]',	'$2y$13$N7K17UUd2zIaq3YpjKeZDuy4LJaR23VIO3R5/L9pBneo3cuKR54G.',	'Augustin',	'Rossi',	NULL),
(2,	'manager@gmail.com',	'[\"ROLE_MANAGER\"]',	'$2y$13$AgbmGh5XshNzyNMYEwR3sO571B1iPLn7UQ3dkQmCpbo0Bt4755b2m',	'Bernadette',	'Letellier',	NULL),
(3,	'user@gmail.com',	'[\"ROLE_USER\"]',	'$2y$13$/H6Ls7Sni9sqwFf5gPtonuYPoFFQYDNDSkiyKZ7KZejTqwVemVlB6',	'Charles',	'Hebert',	NULL);

-- 2024-06-04 16:10:09