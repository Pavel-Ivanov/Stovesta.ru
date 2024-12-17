DROP TABLE IF EXISTS `#__contactus`;
CREATE TABLE IF NOT EXISTS `#__contactus_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(20000) NOT NULL,
  `page` varchar(200) NOT NULL,
  `module_id` int(16) NOT NULL,
  `read` tinyint(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `replied` tinyint(11) NOT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;