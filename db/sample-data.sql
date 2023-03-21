-- -------------------------------------------------------------
-- TablePlus 4.0.2(374)
--
-- https://tableplus.com/
--
-- Database: cs329e
-- Generation Time: 2021-08-15 14:19:56.8640
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `teamup_projects`;
CREATE TABLE `teamup_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `image` varchar(200) NOT NULL DEFAULT 'https://picsum.photos/id/0/285/190',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `teamup_user_projects`;
CREATE TABLE `teamup_user_projects` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `is_leader` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`project_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `teamup_user_projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `teamup_users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `teamup_user_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `teamup_projects` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `teamup_users`;
CREATE TABLE `teamup_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `EMAIL` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO `teamup_projects` (`id`, `title`, `content`, `image`, `created`) VALUES
(1, 'Project TeamUp', 'Looking for a teammate?\nOr a project to test your skillset? For Team Leaders ...\nHave a great project idea?\nLooking for partners who share your\npassion and complement your skills? For Project Scouts ...\nLooking to gain some real experience\nfrom project-based learning?\nMeet the next billion-dollar idea here.', 'https://picsum.photos/id/0/285/190', '2021-08-13 23:03:07'),
(2, 'Digital Art Project', 'We\'re a group of student art enthusiats, currently working on a masterpiece you will surely be interested. We need people with experience in web development!', 'https://picsum.photos/id/1000/285/190', '2021-08-13 23:03:07'),
(3, 'The Next Uber', 'Interested in refreshing the travelling experience of the future. Come join us! We need help in our backend development and server dev-ops.', 'https://picsum.photos/id/1003/285/190', '2021-08-13 23:03:07'),
(4, 'Pear Phone', 'We are making the future of smartphones. We need people with skills in anything related to smartphones: UI designers, kernel developers, app developers, computer engineers...', 'https://picsum.photos/id/3/285/190', '2021-08-13 23:04:28'),
(5, 'Project Jane Doe', 'This is a test project created by Jane Doe Foundations. We aim to do nothing at all, just to test the creating project functionality of this website. I hope you enjoy reading this nonsense.', 'https://picsum.photos/id/101/285/190', '2021-08-15 14:11:04');

INSERT INTO `teamup_user_projects` (`user_id`, `project_id`, `is_leader`) VALUES
(3, 1, 1),
(3, 2, 1),
(3, 3, 1),
(3, 4, 1),
(3, 5, 0),
(7, 1, 0),
(7, 2, 0),
(7, 3, 0),
(7, 4, 0),
(7, 5, 0),
(8, 1, 0),
(8, 2, 0),
(8, 3, 0),
(8, 5, 1);

INSERT INTO `teamup_users` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES
(3, 'Alex', 'Kuang', 'alex0@utexas.edu', '$2y$10$bmzU4z1nyWd2gX93Gh.KsOdYuaoEnZhYFWuw/4uQTJ.9bj/OmBFai'),
(7, 'John', 'Doe', 'john@doe.com', '$2y$10$GovzB28aZ2W9sxQU0l73puRd4Jy3UgQEzp6VQVw5Cq4G1RDlAGEQO'),
(8, 'Jane', 'Doe', 'jane@doe.com', '$2y$10$VQledaXcJX.kR1WpaZHY0ucHE5vuH696sT94Ls.L2zeycwlScRu0q');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;