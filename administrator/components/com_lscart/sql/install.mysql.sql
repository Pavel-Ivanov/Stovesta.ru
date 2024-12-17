CREATE TABLE IF NOT EXISTS `#__lscart_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `status` enum('confirm','send','cancel'),
  `send_date` datetime DEFAULT NULL,
  `data` VARCHAR(500),
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;