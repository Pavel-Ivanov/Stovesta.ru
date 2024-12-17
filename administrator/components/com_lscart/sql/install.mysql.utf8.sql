DROP TABLE IF EXISTS `#__lscart_orders`;

CREATE TABLE IF NOT EXISTS `#__lscart_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `data` varchar(500) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` enum('confirm','send','cancel') DEFAULT 'confirm',
  `send_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;