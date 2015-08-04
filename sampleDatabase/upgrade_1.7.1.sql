
INSERT INTO `fx_config` (`config_key`, `value`) VALUES
('build', '0'),
('gcal_api_key',''),
('gcal_id',''),
('invoice_start_no','1'),
('last_check', '0'),
('last_seen_activities', '0'),
('postmark_api_key',''),
('postmark_from_address',''),
('pdf_engine','invoicr'),
('default_language', 'english')
ON DUPLICATE KEY UPDATE `value` = VALUES(value);


CREATE TABLE IF NOT EXISTS `fx_links` (
  `link_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `link_title` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `description` text,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `fx_updates` (
  `build` int(11) NOT NULL DEFAULT '0',
  `code` varchar(50) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `filename` varchar(255) DEFAULT NULL,
  `importance` enum('low','medium','high') DEFAULT 'low',
  `dependencies` varchar(255) DEFAULT NULL,
  `installed` int(11) DEFAULT '0',
  PRIMARY KEY (`build`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


