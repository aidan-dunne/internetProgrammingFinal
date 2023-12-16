SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE IF EXISTS shoppingcart;
CREATE DATABASE IF NOT EXISTS shoppingcart;
USE shoppingcart;

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `desc` text NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `rrp` decimal(7,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

INSERT INTO `products` (`id`, `name`, `desc`, `price`, `rrp`, `quantity`, `date_added`) VALUES
(1, 'Pokeball', 'A home for your Pokemon', 10000.00, 10000.99, 100, '2023-12-07 14:09:45'),
(2, 'Rayquaza', '23 feet tall, consumes meteors for sustenance, requires constant care. Good with kids.', 4.00, 99999.25, 1, '2023-12-07 13:42:05'),
(3, 'Charmander', '2 feet tall, dies if flame burns out, do not take swimming', 0.76, 25000.00, 19, '2023-12-07 13:54:30'),
(4, 'Zekrom', '9.5 feet tall; armored. Tail produces massive amounts of energy, so watch items on your coffee table. Likes to snuggle.', 2.65, 85250.75, 4, '2023-12-07 13:54:30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
