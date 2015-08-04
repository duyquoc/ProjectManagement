

# Dump of table fx_account_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_account_details`;

CREATE TABLE `fx_account_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(100) COLLATE utf8_unicode_ci DEFAULT 'en_US',
  `address` varchar(64) COLLATE utf8_unicode_ci DEFAULT '-',
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT '-',
  `mobile` varchar(32) COLLATE utf8_unicode_ci DEFAULT '',
  `skype` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `language` varchar(40) COLLATE utf8_unicode_ci DEFAULT 'english',
  `department` int(11) DEFAULT '0',
  `avatar` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'default_avatar.jpg',
  `use_gravatar` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT 'Y',
  `as_company` enum('false','true') COLLATE utf8_unicode_ci DEFAULT 'false',
  `allowed_modules` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;



# Dump of table fx_activities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_activities`;

CREATE TABLE `fx_activities` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `module` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_field_id` int(11) DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `icon` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'fa-coffee',
  `value1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_api_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_api_keys`;

CREATE TABLE `fx_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `api_key` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text COLLATE utf8_unicode_ci,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_api_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_api_logs`;

CREATE TABLE `fx_api_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci,
  `api_key` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_assign_projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_assign_projects`;

CREATE TABLE `fx_assign_projects` (
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assigned_user` int(11) NOT NULL,
  `project_assigned` int(11) NOT NULL,
  `assign_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_assign_tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_assign_tasks`;

CREATE TABLE `fx_assign_tasks` (
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assigned_user` int(11) NOT NULL,
  `project_assigned` int(11) NOT NULL,
  `task_assigned` int(11) NOT NULL,
  `assign_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_bug_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_bug_comments`;

CREATE TABLE `fx_bug_comments` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `bug_id` int(11) NOT NULL,
  `comment_by` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `date_commented` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_bug_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_bug_files`;

CREATE TABLE `fx_bug_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `bug` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_ext` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(5) DEFAULT NULL,
  `is_image` int(2) DEFAULT NULL,
  `image_width` int(5) DEFAULT NULL,
  `image_height` int(5) DEFAULT NULL,
  `original_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_name` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `uploaded_by` int(11) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_bugs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_bugs`;

CREATE TABLE `fx_bugs` (
  `bug_id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_ref` int(11) DEFAULT NULL,
  `project` int(11) DEFAULT NULL,
  `reporter` int(11) DEFAULT NULL,
  `assigned_to` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bug_status` enum('Unconfirmed','Confirmed','In Progress','Resolved','Verified') COLLATE utf8_unicode_ci DEFAULT 'Unconfirmed',
  `issue_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reproducibility` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `severity` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bug_description` text COLLATE utf8_unicode_ci,
  `reported_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`bug_id`),
  UNIQUE KEY `issue_ref` (`issue_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_captcha
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_captcha`;

CREATE TABLE `fx_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT '0',
  `word` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_comment_replies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_comment_replies`;

CREATE TABLE `fx_comment_replies` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_comment` int(11) DEFAULT NULL,
  `reply_msg` text COLLATE utf8_unicode_ci,
  `replied_by` int(11) DEFAULT NULL,
  `del` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_comments`;

CREATE TABLE `fx_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `project` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_companies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_companies`;

CREATE TABLE `fx_companies` (
  `co_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_ref` int(32) DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primary_contact` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_phone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'USD',
  `language` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `VAT` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hosting_company` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hostname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`co_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




# Dump of table fx_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_config`;

CREATE TABLE `fx_config` (
  `config_key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `fx_config` (`config_key`, `value`)
VALUES
  ('2checkout_private_key',''),
  ('2checkout_publishable_key',''),
  ('2checkout_seller_id',''),
  ('allowed_files','gif|png|jpeg|jpg|pdf|doc|txt|docx|xls|zip|rar|xls|mp4'),
  ('allow_client_registration','TRUE'),
  ('automatic_email_on_recur','TRUE'),
  ('build','0'),
  ('button_color','primary'),
  ('captcha_registration','FALSE'),
  ('client_create_project','TRUE'),
  ('company_address','4146 Golden Hickory Woods'),
  ('company_city','Glass Hill, Sydney'),
  ('company_country','Australia'),
  ('company_domain','http://example.com'),
  ('company_email','wm@gitbench.com'),
  ('company_legal_name','Gitbench'),
  ('company_logo','logo.png'),
  ('company_name','Gitbench Inc'),
  ('company_phone','+123 456 789'),
  ('company_phone_2',''),
  ('company_vat',''),
  ('company_zip_code',''),
  ('contact_person','John Doe'),
  ('cron_key','34WI2L12L87I1A65M90M9A42N41D08A26I'),
  ('date_format','%d-%m-%Y'),
  ('date_php_format','d-m-Y'),
  ('date_picker_format','dd-mm-yyyy'),
  ('decimal_separator','.'),
  ('default_currency','USD'),
  ('default_currency_symbol','$'),
  ('default_language','english'),
  ('default_tax','0.00'),
  ('default_terms','Thank you for <span style=\"font-weight: bold;\">your</span> business. Please process this invoice within the due date.'),
  ('demo_mode','FALSE'),
  ('developer','ig63Yd/+yuA8127gEyTz9TY4pnoeKq8dtocVP44+BJvtlRp8Vqcetwjk51dhSB6Rx8aVIKOPfUmNyKGWK7C/gg=='),
  ('display_estimate_badge','TRUE'),
  ('display_invoice_badge','TRUE'),
  ('email_account_details','TRUE'),
  ('email_estimate_message','Hi {CLIENT}<br>Thanks for your business inquiry. <br>The estimate EST {REF} is attached with this email. <br>Estimate Overview:<br>Estimate # : EST {REF}<br>Amount: {CURRENCY} {AMOUNT}<br> You can view the estimate online at:<br>{LINK}<br>Best Regards,<br>{COMPANY}'),
  ('email_invoice_message','Hello {CLIENT}<br>Here is the invoice of {CURRENCY} {AMOUNT}<br>You can view the invoice online at:<br>{LINK}<br>Best Regards,<br>{COMPANY}'),
  ('email_staff_tickets','TRUE'),
  ('enable_languages','TRUE'),
  ('estimate_color','#FB6B5B'),
  ('estimate_language','en'),
  ('estimate_prefix','EST'),
  ('estimate_terms','Looking forward to doing business with you.'),
  ('file_max_size','80000'),
  ('gcal_api_key',''),
  ('gcal_id',''),
  ('increment_invoice_number','TRUE'),
  ('installed','TRUE'),
  ('invoices_due_after','30'),
  ('invoice_color','#53B567'),
  ('invoice_language','en'),
  ('invoice_logo','invoice_logo.png'),
  ('invoice_prefix','INV'),
  ('invoice_start_no','1'),
  ('language','english'),
  ('languages','spanish'),
  ('last_check','0'),
  ('last_seen_activities','0'),
  ('locale','en_US'),
  ('login_bg','bg-login.jpg'),
  ('logo_or_icon','icon_title'),
  ('notify_bug_assignment','TRUE'),
  ('notify_bug_comments','TRUE'),
  ('notify_bug_status','TRUE'),
  ('notify_message_received','TRUE'),
  ('notify_project_assignments','TRUE'),
  ('notify_project_comments','TRUE'),
  ('notify_project_files','TRUE'),
  ('notify_task_assignments','TRUE'),
  ('paypal_cancel_url','paypal/cancel'),
  ('paypal_email','billing@gitbench.com'),
  ('paypal_ipn_url','paypal/t_ipn/ipn'),
  ('paypal_live','TRUE'),
  ('paypal_success_url','paypal/success'),
  ('postmark_api_key',''),
  ('postmark_from_address',''),
  ('pdf_engine','invoicr'),
  ('project_prefix','PRO'),
  ('protocol','mail'),
  ('purchase_code',''),
  ('reminder_message','Hello {CLIENT}<br>This is a friendly reminder to pay your invoice of {CURRENCY} {AMOUNT}<br>You can view the invoice online at:<br>{LINK}<br>Best Regards,<br>{COMPANY}'),
  ('reset_key','34WI2L12L87I1A65M90M9A42N41D08A26I'),
  ('rows_per_table','25'),
  ('settings','invoice'),
  ('show_estimate_tax','TRUE'),
  ('show_invoice_tax','TRUE'),
  ('show_login_image','TRUE'),
  ('show_only_logo','FALSE'),
  ('sidebar_theme','dark'),
  ('site_appleicon','logo.png'),
  ('site_author','William M.'),
  ('site_desc','Freelancer Office is a Web based PHP application for Freelancers - buy it on Codecanyon'),
  ('site_favicon','logo.png'),
  ('site_icon','fa-flask'),
  ('smtp_host','smtp.mandrillapp.com'),
  ('smtp_pass','n3Y53YfQVbnu03JCnSM5fLQF1BIpesi9vCDx28/nYfKdEkMlSceNzU6s6VfuDUpzrsTh+PB47GGdKNNBwG0Xaw=='),
  ('smtp_port','587'),
  ('smtp_user','wm@gitbench.com'),
  ('stripe_private_key',''),
  ('stripe_public_key',''),
  ('system_font','roboto_condensed'),
  ('thousand_separator',','),
  ('timezone','Europe/London'),
  ('use_gravatar','TRUE'),
  ('use_postmark','FALSE'),
  ('valid_license','TRUE'),
  ('webmaster_email','support@example.com'),
  ('website_name','Sample Company');


# Dump of table fx_updates
# ------------------------------------------------------------
DROP TABLE IF EXISTS `fx_updates`;

CREATE TABLE `fx_updates` (
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




# Dump of table fx_countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_countries`;

CREATE TABLE `fx_countries` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `value` varchar(250) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



INSERT INTO `fx_countries` (`id`, `value`)
VALUES
  (1,'Afghanistan'),
  (2,'Aringland Islands'),
  (3,'Albania'),
  (4,'Algeria'),
  (5,'American Samoa'),
  (6,'Andorra'),
  (7,'Angola'),
  (8,'Anguilla'),
  (9,'Antarctica'),
  (10,'Antigua and Barbuda'),
  (11,'Argentina'),
  (12,'Armenia'),
  (13,'Aruba'),
  (14,'Australia'),
  (15,'Austria'),
  (16,'Azerbaijan'),
  (17,'Bahamas'),
  (18,'Bahrain'),
  (19,'Bangladesh'),
  (20,'Barbados'),
  (21,'Belarus'),
  (22,'Belgium'),
  (23,'Belize'),
  (24,'Benin'),
  (25,'Bermuda'),
  (26,'Bhutan'),
  (27,'Bolivia'),
  (28,'Bosnia and Herzegovina'),
  (29,'Botswana'),
  (30,'Bouvet Island'),
  (31,'Brazil'),
  (32,'British Indian Ocean territory'),
  (33,'Brunei Darussalam'),
  (34,'Bulgaria'),
  (35,'Burkina Faso'),
  (36,'Burundi'),
  (37,'Cambodia'),
  (38,'Cameroon'),
  (39,'Canada'),
  (40,'Cape Verde'),
  (41,'Cayman Islands'),
  (42,'Central African Republic'),
  (43,'Chad'),
  (44,'Chile'),
  (45,'China'),
  (46,'Christmas Island'),
  (47,'Cocos (Keeling) Islands'),
  (48,'Colombia'),
  (49,'Comoros'),
  (50,'Congo'),
  (51,'Congo'),
  (52,' Democratic Republic'),
  (53,'Cook Islands'),
  (54,'Costa Rica'),
  (55,'Ivory Coast (Ivory Coast)'),
  (56,'Croatia (Hrvatska)'),
  (57,'Cuba'),
  (58,'Cyprus'),
  (59,'Czech Republic'),
  (60,'Denmark'),
  (61,'Djibouti'),
  (62,'Dominica'),
  (63,'Dominican Republic'),
  (64,'East Timor'),
  (65,'Ecuador'),
  (66,'Egypt'),
  (67,'El Salvador'),
  (68,'Equatorial Guinea'),
  (69,'Eritrea'),
  (70,'Estonia'),
  (71,'Ethiopia'),
  (72,'Falkland Islands'),
  (73,'Faroe Islands'),
  (74,'Fiji'),
  (75,'Finland'),
  (76,'France'),
  (77,'French Guiana'),
  (78,'French Polynesia'),
  (79,'French Southern Territories'),
  (80,'Gabon'),
  (81,'Gambia'),
  (82,'Georgia'),
  (83,'Germany'),
  (84,'Ghana'),
  (85,'Gibraltar'),
  (86,'Greece'),
  (87,'Greenland'),
  (88,'Grenada'),
  (89,'Guadeloupe'),
  (90,'Guam'),
  (91,'Guatemala'),
  (92,'Guinea'),
  (93,'Guinea-Bissau'),
  (94,'Guyana'),
  (95,'Haiti'),
  (96,'Heard and McDonald Islands'),
  (97,'Honduras'),
  (98,'Hong Kong'),
  (99,'Hungary'),
  (100,'Iceland'),
  (101,'India'),
  (102,'Indonesia'),
  (103,'Iran'),
  (104,'Iraq'),
  (105,'Ireland'),
  (106,'Israel'),
  (107,'Italy'),
  (108,'Jamaica'),
  (109,'Japan'),
  (110,'Jordan'),
  (111,'Kazakhstan'),
  (112,'Kenya'),
  (113,'Kiribati'),
  (114,'Korea (north)'),
  (115,'Korea (south)'),
  (116,'Kuwait'),
  (117,'Kyrgyzstan'),
  (118,'Lao People\'s Democratic Republic'),
  (119,'Latvia'),
  (120,'Lebanon'),
  (121,'Lesotho'),
  (122,'Liberia'),
  (123,'Libyan Arab Jamahiriya'),
  (124,'Liechtenstein'),
  (125,'Lithuania'),
  (126,'Luxembourg'),
  (127,'Macao'),
  (128,'Macedonia'),
  (129,'Madagascar'),
  (130,'Malawi'),
  (131,'Malaysia'),
  (132,'Maldives'),
  (133,'Mali'),
  (134,'Malta'),
  (135,'Marshall Islands'),
  (136,'Martinique'),
  (137,'Mauritania'),
  (138,'Mauritius'),
  (139,'Mayotte'),
  (140,'Mexico'),
  (141,'Micronesia'),
  (142,'Moldova'),
  (143,'Monaco'),
  (144,'Mongolia'),
  (145,'Montserrat'),
  (146,'Morocco'),
  (147,'Mozambique'),
  (148,'Myanmar'),
  (149,'Namibia'),
  (150,'Nauru'),
  (151,'Nepal'),
  (152,'Netherlands'),
  (153,'Netherlands Antilles'),
  (154,'New Caledonia'),
  (155,'New Zealand'),
  (156,'Nicaragua'),
  (157,'Niger'),
  (158,'Nigeria'),
  (159,'Niue'),
  (160,'Norfolk Island'),
  (161,'Northern Mariana Islands'),
  (162,'Norway'),
  (163,'Oman'),
  (164,'Pakistan'),
  (165,'Palau'),
  (166,'Palestinian Territories'),
  (167,'Panama'),
  (168,'Papua New Guinea'),
  (169,'Paraguay'),
  (170,'Peru'),
  (171,'Philippines'),
  (172,'Pitcairn'),
  (173,'Poland'),
  (174,'Portugal'),
  (175,'Puerto Rico'),
  (176,'Qatar'),
  (177,'Runion'),
  (178,'Romania'),
  (179,'Russian Federation'),
  (180,'Rwanda'),
  (181,'Saint Helena'),
  (182,'Saint Kitts and Nevis'),
  (183,'Saint Lucia'),
  (184,'Saint Pierre and Miquelon'),
  (185,'Saint Vincent and the Grenadines'),
  (186,'Samoa'),
  (187,'San Marino'),
  (188,'Sao Tome and Principe'),
  (189,'Saudi Arabia'),
  (190,'Senegal'),
  (191,'Serbia and Montenegro'),
  (192,'Seychelles'),
  (193,'Sierra Leone'),
  (194,'Singapore'),
  (195,'Slovakia'),
  (196,'Slovenia'),
  (197,'Solomon Islands'),
  (198,'Somalia'),
  (199,'South Africa'),
  (200,'South Georgia and the South Sandwich Islands'),
  (201,'Spain'),
  (202,'Sri Lanka'),
  (203,'Sudan'),
  (204,'Suriname'),
  (205,'Svalbard and Jan Mayen Islands'),
  (206,'Swaziland'),
  (207,'Sweden'),
  (208,'Switzerland'),
  (209,'Syria'),
  (210,'Taiwan'),
  (211,'Tajikistan'),
  (212,'Tanzania'),
  (213,'Thailand'),
  (214,'Togo'),
  (215,'Tokelau'),
  (216,'Tonga'),
  (217,'Trinidad and Tobago'),
  (218,'Tunisia'),
  (219,'Turkey'),
  (220,'Turkmenistan'),
  (221,'Turks and Caicos Islands'),
  (222,'Tuvalu'),
  (223,'Uganda'),
  (224,'Ukraine'),
  (225,'United Arab Emirates'),
  (226,'United Kingdom'),
  (227,'United States of America'),
  (228,'Uruguay'),
  (229,'Uzbekistan'),
  (230,'Vanuatu'),
  (231,'Vatican City'),
  (232,'Venezuela'),
  (233,'Vietnam'),
  (234,'Virgin Islands (British)'),
  (235,'Virgin Islands (US)'),
  (236,'Wallis and Futuna Islands'),
  (237,'Western Sahara'),
  (238,'Yemen'),
  (239,'Zaire'),
  (240,'Zambia'),
  (241,'Zimbabwe');




# Dump of table fx_currencies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_currencies`;

CREATE TABLE `fx_currencies` (
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `xrate` decimal(12,5) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `fx_currencies` (`code`, `name`, `symbol`, `xrate`)
VALUES
  ('AUD','Australian Dollar','$',NULL),
  ('BRL','Brazilian Real','R$',NULL),
  ('CAD','Canadian Dollar','$',NULL),
  ('CHF','Swiss Franc','Fr',NULL),
  ('CLP','Chilean Peso','$',NULL),
  ('CNY','Chinese Yuan','¥',NULL),
  ('CZK','Czech Koruna','Kč',NULL),
  ('DKK','Danish Krone','kr',NULL),
  ('EUR','Euro','€',NULL),
  ('GBP','British Pound','£',NULL),
  ('HKD','Hong Kong Dollar','$',NULL),
  ('HUF','Hungarian Forint','Ft',NULL),
  ('IDR','Indonesian Rupiah','Rp',NULL),
  ('ILS','Israeli New Shekel','₪',NULL),
  ('INR','Indian Rupee','INR',NULL),
  ('JPY','Japanese Yen','¥',NULL),
  ('KRW','Korean Won','₩',NULL),
  ('MXN','Mexican Peso','$',NULL),
  ('MYR','Malaysian Ringgit','RM',NULL),
  ('NOK','Norwegian Krone','kr',NULL),
  ('NZD','New Zealand Dollar','$',NULL),
  ('PHP','Philippine Peso','₱',NULL),
  ('PKR','Pakistan Rupee','₨',NULL),
  ('PLN','Polish Zloty','zł',NULL),
  ('RUB','Russian Ruble','₽',NULL),
  ('SEK','Swedish Krona','kr',NULL),
  ('SGD','Singapore Dollar','$',NULL),
  ('THB','Thai Baht','฿',NULL),
  ('TRY','Turkish Lira','TRY',NULL),
  ('TWD','Taiwan Dollar','$',NULL),
  ('USD','US Dollar','$',1.000),
  ('VEF','Bolívar Fuerte','Bs.',NULL),
  ('ZAR','South African Rand','R',NULL);


# Dump of table fx_departments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_departments`;

CREATE TABLE `fx_departments` (
  `deptid` int(10) NOT NULL AUTO_INCREMENT,
  `deptname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `depthidden` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`deptid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_email_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_email_templates`;

CREATE TABLE `fx_email_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_group` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_body` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `fx_email_templates` (`template_id`, `email_group`, `subject`, `template_body`)
VALUES
  (1,'registration','Registration successful','<div style=\"height: 7px; background-color: #535353;\"></div><div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Welcome to {SITE_NAME}</div><div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\">Thanks for joining {SITE_NAME}. We listed your sign in details below, make sure you keep them safe.<br>To open your {SITE_NAME} homepage, please follow this link:<br><big><b><a href=\"{SITE_URL}\">{SITE_NAME} Account!</a></b></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{SITE_URL}\">{SITE_URL}</a><br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your password: {PASSWORD}<br>Have fun!<br>The {SITE_NAME} Team.<br><br></div></div>'),
  (2,'forgot_password','Forgot Password','        <div style=\"height: 7px; background-color: #535353;\"></div><div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New Password</div><div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\">Forgot your password, huh? No big deal.<br>To create a new password, just follow this link:<br><br><big><b><a href=\"{PASS_KEY_URL}\">Create a new password</a></b></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{PASS_KEY_URL}\">{PASS_KEY_URL}</a><br><br><br>You received this email, because it was requested by a <a href=\"{SITE_URL}\">{SITE_NAME}</a> user. <p></p><p>This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.</p><br>Thank you,<br>The {SITE_NAME} Team</div></div>'),
  (3,'change_email','Change Email','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New email address on {SITE_NAME}</div>\r\n\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\">You have changed your email address for {SITE_NAME}.<br>Follow this link to confirm your new email address:<br><big><b><a href=\"{NEW_EMAIL_KEY_URL}\">Confirm your new email</a></b></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{NEW_EMAIL_KEY_URL}\">{NEW_EMAIL_KEY_URL}</a><br><br>Your email address: {NEW_EMAIL}<br><br>You received this email, because it was requested by a <a href=\"{SITE_URL}\">{SITE_NAME}</a> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br>Thank you,<br>The {SITE_NAME} Team</div>\r\n\r\n</div>'),
  (4,'activate_account','Activate Account','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Welcome to {SITE_NAME}!</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Thanks for joining {SITE_NAME}. We listed your sign in details below, make sure you keep them safe.</p>\r\nTo verify your email address, please follow this link:<br><big><b><a href=\"{ACTIVATE_URL}\">Finish your registration...</a></b></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{ACTIVATE_URL}\">{ACTIVATE_URL}</a><br><br>Please verify your email within {ACTIVATION_PERIOD} hours, otherwise your registration will become invalid and you will have to register again.<br><br><br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your password: {PASSWORD}<br><br>Have fun!<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (5,'reset_password','Reset Password','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New password on {SITE_NAME}</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>You have changed your password.<br>Please, keep it in your records so you don\'t forget it.<br></p>\r\nYour username: {USERNAME}<br>Your email address: {EMAIL}<br>Your new password: {NEW_PASSWORD}<br><br>Thank you,<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (6,'bug_assigned','New Bug Assigned','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New Bug Assigned</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>A new bug ( {ISSUE_TITLE} ) has been assigned to you by {ASSIGNED_BY} in project {PROJECT_TITLE}. </p>\r\n<p>You can view this bug by logging in to the portal using the link below.</p>\r\n--------------------------<br><big><b><a href=\"{SITE_URL}\">My Account</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (7,'bug_status','Bug status changed','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Bug status changed</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>Bug {ISSUE_TITLE} has been marked as {STATUS} by {MARKED_BY}. </p>\r\n<p>You can view this bug by logging in to the portal using the link below.</p>\r\n--------------------------<br><big><b><a href=\"{BUG_URL}\">My Account</a></b></big><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (8,'bug_comment','New Bug Comment Received','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New Comment Received</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>A new comment has been posted by {POSTED_BY} to bug {ISSUE_TITLE}. </p>\r\n<p>You can view the comment using the link below.</p>\r\n----------------------------------------------------------<br><big><b><a href=\"{COMMENT_URL}\">View Comment</a></b></big><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (9,'bug_file','New bug file','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New bug file</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there, </p>\r\n<p>A new file has been uploaded by {UPLOADED_BY} to issue {ISSUE_TITLE}. </p>\r\n<p>You can view the bug using the link below.</p>\r\n--------------------------<br><big><b><a href=\"{BUG_URL}\">View Bug</a></b></big><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (10,'bug_reported','New bug Reported','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New bug Reported</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p></p>\r\n<p>A new bug ({ISSUE_TITLE}) has been reported by {ADDED_BY}. </p>\r\n<p>You can view the Bug using the Dashboard Page.</p>\r\n--------------------------<br><big><b><a href=\"{BUG_URL}\">View Bug</a></b></big><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (11,'project_file','New Project File','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New Project File</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>A new file has been uploaded by {UPLOADED_BY} to project {PROJECT_TITLE}. </p>\r\n<p>You can view the Project using the link below.</p>\r\n--------------------------<br><big><b><a href=\"{PROJECT_URL}\">View Project</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (12,'project_complete','Project Completed','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Project Completed</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi {CLIENT_NAME}</p>\r\n<p>Project : {PROJECT_TITLE} - {PROJECT_CODE} has been completed. </p>\r\n<p>You can view the project by logging into your portal Account.</p>\r\n<big><b><a href=\"{PROJECT_URL}\">View Project</a></b></big><br><br>--------------------------<br>Project Overview:<br>Hours Logged # :  {PROJECT_HOURS} hours<br>Project Cost : {PROJECT_COST}<br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (13,'project_comment','New Project Comment Received','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New Comment Received</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>A new comment has been posted by {POSTED_BY} to project {PROJECT_TITLE}. </p>\r\n<p>You can view the comment using the link below.</p>\r\n-----------------------------------------------------------------------<br><big><b><a href=\"{COMMENT_URL}\">View Comment</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (14,'task_assigned','Task assigned','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Task assigned</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>A new task ( {TASK_NAME} ) has been assigned to you by {ASSIGNED_BY} in project {PROJECT_TITLE}. </p>\r\n<p>You can view this task by logging in to the portal using the link below.</p>\r\n-----------------------------------<br><big><b><a href=\"{PROJECT_URL}\">View Task</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (15,'project_assigned','Project assigned','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Project assigned</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>A new project ( {PROJECT_TITLE} ) has been assigned to you by {ASSIGNED_BY}.</p>\r\n<p>You can view this project by logging in to the portal using the link below.</p>\r\n-----------------------------------<br><big><b><a href=\"{PROJECT_URL}\">View Project</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (16,'payment_email','Payment Received','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Payment Received</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Dear Customer</p>\r\n<p>We have received your payment of {INVOICE_CURRENCY} {PAID_AMOUNT}. </p>\r\n<p>Thank you for your Payment and business. We look forward to working with you again.</p>\r\n--------------------------<br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (17,'invoice_message','New Invoice','<div style=\"height: 7px; background-color: #535353;\"></div><div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">INVOICE {REF}</div><div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><span class=\"style1\"><span style=\"font-weight:bold;\">Hello {CLIENT}</span></span><br><br>Here is the invoice of {CURRENCY} {AMOUNT}.<br><br>You can view the invoice online at:<br><big><b><a href=\"{INVOICE_LINK}\">View Invoice</a></b></big><br><br>Best Regards<br><br>The {SITE_NAME} Team</div></div>'),
  (18,'invoice_reminder','Invoice Reminder','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Invoice Reminder</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hello {CLIENT}</p>\r\n<br><p>This is a friendly reminder to pay your invoice of {CURRENCY} {AMOUNT}<br>You can view the invoice online at:<br><big><b><a href=\"{INVOICE_LINK}\">View Invoice</a></b></big><br><br>Best Regards,<br>The {SITE_NAME} Team</p>\r\n</div>\r\n</div>'),
  (19,'message_received','Message Received','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Message Received</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi {RECIPIENT},</p>\r\n<p>You have received a message from {SENDER}. </p>\r\n------------------------------------------------------------------<br><blockquote>\r\n{MESSAGE}</blockquote>\r\n<big><b><a href=\"{SITE_URL}\">Go to Account</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (20,'estimate_email','New Estimate','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Estimate {ESTIMATE_REF}</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi {CLIENT}</p>\r\n<p>Thanks for your business inquiry. </p>\r\nThe estimate {ESTIMATE_REF} is attached with this email. <br> Estimate Overview:<br> Estimate # : {ESTIMATE_REF}<br> Amount: {CURRENCY} {AMOUNT}<br> <br>You can view the estimate online at:<br> <big><b><a href=\"{ESTIMATE_LINK}\">View Estimate</a></b></big><br><br>  Best Regards,<br> The {SITE_NAME} Team</div>\r\n</div>'),
  (21,'ticket_staff_email','New Ticket [TICKET_CODE]','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">New Ticket</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Ticket #{TICKET_CODE} has been created by the client.</p>\r\n<p>You may view the ticket by clicking on the following link <br><br>  Client Email : {REPORTER_EMAIL}<br><br> <big><b><a href=\"{TICKET_LINK}\">View Ticket</a></b></big> <br><br>Regards<br><br>{SITE_NAME}</p>\r\n</div>\r\n</div>'),
  (22,'ticket_client_email','Ticket [TICKET_CODE] Opened','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Ticket Opened</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hello {CLIENT_EMAIL},<br><br></p>\r\n<p>Your ticket has been opened with us.<br><br>Ticket #{TICKET_CODE}<br>Status : Open<br><br>Click on the below link to see the ticket details and post additional comments.<br><br><big><b><a href=\"{TICKET_LINK}\">View Ticket</a></b></big><br><br>Regards<br><br>The {SITE_NAME} Team<br></p>\r\n</div>\r\n</div>'),
  (23,'ticket_reply_email','Ticket [TICKET_CODE] Response','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Ticket Response</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>A new response has been added to Ticket #{TICKET_CODE}<br><br> Ticket : #{TICKET_CODE} <br>Status : {TICKET_STATUS} <br><br></p>\r\nTo see the response and post additional comments, click on the link below.<br><br>         <big><b><a href=\"{TICKET_LINK}\">View Reply</a> </b></big><br><br>          Note: Do not reply to this email as this email is not monitored.<br><br>     Regards<br>The {SITE_NAME} Team<br></div>\r\n</div>'),
  (24,'ticket_closed_email','Ticket [TICKET_CODE] Closed','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Ticket Closed</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\">Hi {REPORTER_EMAIL}<br><br>Ticket #{TICKET_CODE} has been closed by {STAFF_USERNAME} <br><br>          Ticket : #{TICKET_CODE} <br>     Status : {TICKET_STATUS}<br><br>Replies : {NO_OF_REPLIES}<br><br>          To see the responses or open the ticket, click on the link below.<br><br>          <big><b><a href=\"{TICKET_LINK}\">View Ticket</a></b></big> <br><br>          Note: Do not reply to this email as this email is not monitored.<br><br>    Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (25,'project_updated','Project updated','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Project updated</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>{PROJECT_TITLE} ) has been updated by {ASSIGNED_BY}.</p>\r\n<p>You can view this project by logging in to the portal using the link below.</p>\r\n-----------------------------------<br><big><b><a href=\"{PROJECT_URL}\">View Project</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>'),
  (26,'task_updated','Task updated','<div style=\"height: 7px; background-color: #535353;\"></div>\r\n<div style=\"background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;\"><div style=\"text-align:center; font-size:24px; font-weight:bold; color:#535353;\">Task updated</div>\r\n<div style=\"border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;\"><p>Hi there,</p>\r\n<p>{TASK_NAME} in {PROJECT_TITLE} has been updated by {ASSIGNED_BY}.</p>\r\n<p>You can view this project by logging in to the portal using the link below.</p>\r\n-----------------------------------<br><big><b><a href=\"{PROJECT_URL}\">View Project</a></b></big><br><br>Regards<br>The {SITE_NAME} Team</div>\r\n</div>');



# Dump of table fx_estimate_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_estimate_items`;

CREATE TABLE `fx_estimate_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_tax_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT 'Item Name',
  `item_desc` longtext COLLATE utf8_unicode_ci,
  `unit_cost` decimal(10,2) DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `item_tax_total` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `estimate_id` int(11) NOT NULL,
  `date_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `item_order` int(11) DEFAULT '0',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_estimates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_estimates`;

CREATE TABLE `fx_estimates` (
  `est_id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'USD',
  `discount` float NOT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `tax` int(11) NOT NULL DEFAULT '0',
  `status` enum('Accepted','Declined','Pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pending',
  `date_sent` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `est_deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `date_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `emailed` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No',
  `show_client` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No',
  `invoiced` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No',
  PRIMARY KEY (`est_id`),
  UNIQUE KEY `reference_no` (`reference_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_fields`;

CREATE TABLE `fx_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `deptid` int(10) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uniqid` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_files`;

CREATE TABLE `fx_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `project` int(11) NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ext` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(10) DEFAULT NULL,
  `is_image` int(2) DEFAULT NULL,
  `image_width` int(5) DEFAULT NULL,
  `image_height` int(5) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `uploaded_by` int(11) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_invoices`;

CREATE TABLE `fx_invoices` (
  `inv_id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `due_date` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `allow_paypal` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `allow_stripe` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `allow_2checkout` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'Yes',
  `allow_bitcoin` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `recurring` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `r_freq` int(11) NOT NULL DEFAULT '31',
  `recur_start_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recur_end_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recur_frequency` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recur_next_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `status` enum('Unpaid','Paid') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `archived` int(11) DEFAULT '0',
  `date_sent` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `date_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `emailed` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `show_client` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `viewed` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  PRIMARY KEY (`inv_id`),
  UNIQUE KEY `reference_no` (`reference_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_items`;

CREATE TABLE `fx_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_tax_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_tax_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `invoice_id` int(11) NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Item Name',
  `item_desc` longtext COLLATE utf8_unicode_ci,
  `unit_cost` decimal(10,2) DEFAULT '0.00',
  `item_order` int(11) DEFAULT '0',
  `date_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_items_saved
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_items_saved`;

CREATE TABLE `fx_items_saved` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'Item Name',
  `item_desc` longtext COLLATE utf8_unicode_ci,
  `unit_cost` decimal(10,2) DEFAULT '0.00',
  `item_tax_rate` decimal(10,2) DEFAULT '0.00',
  `item_tax_total` decimal(10,2) DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `deleted` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_languages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_languages`;

CREATE TABLE `fx_languages` (
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(2) DEFAULT '0',
  `bundled` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `fx_languages` (`code`, `name`, `icon`, `active`) VALUES
('cs', 'czech', 'cs', 1),
('de', 'german', 'de', 1),
('el', 'greek', 'gr', 1),
('en', 'english', 'us', 1),
('es', 'spanish', 'es', 1),
('fr', 'french', 'fr', 1),
('it', 'italian', 'it', 0),
('nl', 'dutch', 'nl', 1),
('no', 'norwegian', 'no', 1),
('pl', 'polish', 'pl', 0),
('pt', 'portuguese', 'pt', 1),
('ro', 'romanian', 'ro', 1),
('ru', 'russian', 'ru', 1),
('sr', 'serbian', 'sr', 0),
('tr', 'turkish', 'tr', 0);



# Dump of table fx_links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_links`;

CREATE TABLE `fx_links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `link_title` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `description` text,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `client` int(11) DEFAULT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table fx_locales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_locales`;

CREATE TABLE `fx_locales` (
  `locale` varchar(10) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `name` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `fx_locales` (`locale`, `code`, `language`, `name`)
VALUES
  ('aa_DJ','aa','afar','Afar (Djibouti)'),
  ('aa_ER','aa','afar','Afar (Eritrea)'),
  ('aa_ET','aa','afar','Afar (Ethiopia)'),
  ('af_ZA','af','afrikaans','Afrikaans (South Africa)'),
  ('am_ET','am','amharic','Amharic (Ethiopia)'),
  ('an_ES','an','aragonese','Aragonese (Spain)'),
  ('ar_AE','ar','arabic','Arabic (United Arab Emirates)'),
  ('ar_BH','ar','arabic','Arabic (Bahrain)'),
  ('ar_DZ','ar','arabic','Arabic (Algeria)'),
  ('ar_EG','ar','arabic','Arabic (Egypt)'),
  ('ar_IN','ar','arabic','Arabic (India)'),
  ('ar_IQ','ar','arabic','Arabic (Iraq)'),
  ('ar_JO','ar','arabic','Arabic (Jordan)'),
  ('ar_KW','ar','arabic','Arabic (Kuwait)'),
  ('ar_LB','ar','arabic','Arabic (Lebanon)'),
  ('ar_LY','ar','arabic','Arabic (Libya)'),
  ('ar_MA','ar','arabic','Arabic (Morocco)'),
  ('ar_OM','ar','arabic','Arabic (Oman)'),
  ('ar_QA','ar','arabic','Arabic (Qatar)'),
  ('ar_SA','ar','arabic','Arabic (Saudi Arabia)'),
  ('ar_SD','ar','arabic','Arabic (Sudan)'),
  ('ar_SY','ar','arabic','Arabic (Syria)'),
  ('ar_TN','ar','arabic','Arabic (Tunisia)'),
  ('ar_YE','ar','arabic','Arabic (Yemen)'),
  ('ast_ES','ast','asturian','Asturian (Spain)'),
  ('as_IN','as','assamese','Assamese (India)'),
  ('az_AZ','az','azerbaijani','Azerbaijani (Azerbaijan)'),
  ('az_TR','az','azerbaijani','Azerbaijani (Turkey)'),
  ('bem_ZM','bem','bemba','Bemba (Zambia)'),
  ('ber_DZ','ber','berber','Berber (Algeria)'),
  ('ber_MA','ber','berber','Berber (Morocco)'),
  ('be_BY','be','belarusian','Belarusian (Belarus)'),
  ('bg_BG','bg','bulgarian','Bulgarian (Bulgaria)'),
  ('bn_BD','bn','bengali','Bengali (Bangladesh)'),
  ('bn_IN','bn','bengali','Bengali (India)'),
  ('bo_CN','bo','tibetan','Tibetan (China)'),
  ('bo_IN','bo','tibetan','Tibetan (India)'),
  ('br_FR','br','breton','Breton (France)'),
  ('bs_BA','bs','bosnian','Bosnian (Bosnia and Herzegovina)'),
  ('byn_ER','byn','blin','Blin (Eritrea)'),
  ('ca_AD','ca','catalan','Catalan (Andorra)'),
  ('ca_ES','ca','catalan','Catalan (Spain)'),
  ('ca_FR','ca','catalan','Catalan (France)'),
  ('ca_IT','ca','catalan','Catalan (Italy)'),
  ('crh_UA','crh','crimean turkish','Crimean Turkish (Ukraine)'),
  ('csb_PL','csb','kashubian','Kashubian (Poland)'),
  ('cs_CZ','cs','czech','Czech (Czech Republic)'),
  ('cv_RU','cv','chuvash','Chuvash (Russia)'),
  ('cy_GB','cy','welsh','Welsh (United Kingdom)'),
  ('da_DK','da','danish','Danish (Denmark)'),
  ('de_AT','de','german','German (Austria)'),
  ('de_BE','de','german','German (Belgium)'),
  ('de_CH','de','german','German (Switzerland)'),
  ('de_DE','de','german','German (Germany)'),
  ('de_LI','de','german','German (Liechtenstein)'),
  ('de_LU','de','german','German (Luxembourg)'),
  ('dv_MV','dv','divehi','Divehi (Maldives)'),
  ('dz_BT','dz','dzongkha','Dzongkha (Bhutan)'),
  ('ee_GH','ee','ewe','Ewe (Ghana)'),
  ('el_CY','el','greek','Greek (Cyprus)'),
  ('el_GR','el','greek','Greek (Greece)'),
  ('en_AG','en','english','English (Antigua and Barbuda)'),
  ('en_AS','en','english','English (American Samoa)'),
  ('en_AU','en','english','English (Australia)'),
  ('en_BW','en','english','English (Botswana)'),
  ('en_CA','en','english','English (Canada)'),
  ('en_DK','en','english','English (Denmark)'),
  ('en_GB','en','english','English (United Kingdom)'),
  ('en_GU','en','english','English (Guam)'),
  ('en_HK','en','english','English (Hong Kong SAR China)'),
  ('en_IE','en','english','English (Ireland)'),
  ('en_IN','en','english','English (India)'),
  ('en_JM','en','english','English (Jamaica)'),
  ('en_MH','en','english','English (Marshall Islands)'),
  ('en_MP','en','english','English (Northern Mariana Islands)'),
  ('en_MU','en','english','English (Mauritius)'),
  ('en_NG','en','english','English (Nigeria)'),
  ('en_NZ','en','english','English (New Zealand)'),
  ('en_PH','en','english','English (Philippines)'),
  ('en_SG','en','english','English (Singapore)'),
  ('en_TT','en','english','English (Trinidad and Tobago)'),
  ('en_US','en','english','English (United States)'),
  ('en_VI','en','english','English (Virgin Islands)'),
  ('en_ZA','en','english','English (South Africa)'),
  ('en_ZM','en','english','English (Zambia)'),
  ('en_ZW','en','english','English (Zimbabwe)'),
  ('eo','eo','esperanto','Esperanto'),
  ('es_AR','es','spanish','Spanish (Argentina)'),
  ('es_BO','es','spanish','Spanish (Bolivia)'),
  ('es_CL','es','spanish','Spanish (Chile)'),
  ('es_CO','es','spanish','Spanish (Colombia)'),
  ('es_CR','es','spanish','Spanish (Costa Rica)'),
  ('es_DO','es','spanish','Spanish (Dominican Republic)'),
  ('es_EC','es','spanish','Spanish (Ecuador)'),
  ('es_ES','es','spanish','Spanish (Spain)'),
  ('es_GT','es','spanish','Spanish (Guatemala)'),
  ('es_HN','es','spanish','Spanish (Honduras)'),
  ('es_MX','es','spanish','Spanish (Mexico)'),
  ('es_NI','es','spanish','Spanish (Nicaragua)'),
  ('es_PA','es','spanish','Spanish (Panama)'),
  ('es_PE','es','spanish','Spanish (Peru)'),
  ('es_PR','es','spanish','Spanish (Puerto Rico)'),
  ('es_PY','es','spanish','Spanish (Paraguay)'),
  ('es_SV','es','spanish','Spanish (El Salvador)'),
  ('es_US','es','spanish','Spanish (United States)'),
  ('es_UY','es','spanish','Spanish (Uruguay)'),
  ('es_VE','es','spanish','Spanish (Venezuela)'),
  ('et_EE','et','estonian','Estonian (Estonia)'),
  ('eu_ES','eu','basque','Basque (Spain)'),
  ('eu_FR','eu','basque','Basque (France)'),
  ('fa_AF','fa','persian','Persian (Afghanistan)'),
  ('fa_IR','fa','persian','Persian (Iran)'),
  ('ff_SN','ff','fulah','Fulah (Senegal)'),
  ('fil_PH','fil','filipino','Filipino (Philippines)'),
  ('fi_FI','fi','finnish','Finnish (Finland)'),
  ('fo_FO','fo','faroese','Faroese (Faroe Islands)'),
  ('fr_BE','fr','french','French (Belgium)'),
  ('fr_BF','fr','french','French (Burkina Faso)'),
  ('fr_BI','fr','french','French (Burundi)'),
  ('fr_BJ','fr','french','French (Benin)'),
  ('fr_CA','fr','french','French (Canada)'),
  ('fr_CF','fr','french','French (Central African Republic)'),
  ('fr_CG','fr','french','French (Congo)'),
  ('fr_CH','fr','french','French (Switzerland)'),
  ('fr_CM','fr','french','French (Cameroon)'),
  ('fr_FR','fr','french','French (France)'),
  ('fr_GA','fr','french','French (Gabon)'),
  ('fr_GN','fr','french','French (Guinea)'),
  ('fr_GP','fr','french','French (Guadeloupe)'),
  ('fr_GQ','fr','french','French (Equatorial Guinea)'),
  ('fr_KM','fr','french','French (Comoros)'),
  ('fr_LU','fr','french','French (Luxembourg)'),
  ('fr_MC','fr','french','French (Monaco)'),
  ('fr_MG','fr','french','French (Madagascar)'),
  ('fr_ML','fr','french','French (Mali)'),
  ('fr_MQ','fr','french','French (Martinique)'),
  ('fr_NE','fr','french','French (Niger)'),
  ('fr_SN','fr','french','French (Senegal)'),
  ('fr_TD','fr','french','French (Chad)'),
  ('fr_TG','fr','french','French (Togo)'),
  ('fur_IT','fur','friulian','Friulian (Italy)'),
  ('fy_DE','fy','western frisian','Western Frisian (Germany)'),
  ('fy_NL','fy','western frisian','Western Frisian (Netherlands)'),
  ('ga_IE','ga','irish','Irish (Ireland)'),
  ('gd_GB','gd','scottish gaelic','Scottish Gaelic (United Kingdom)'),
  ('gez_ER','gez','geez','Geez (Eritrea)'),
  ('gez_ET','gez','geez','Geez (Ethiopia)'),
  ('gl_ES','gl','galician','Galician (Spain)'),
  ('gu_IN','gu','gujarati','Gujarati (India)'),
  ('gv_GB','gv','manx','Manx (United Kingdom)'),
  ('ha_NG','ha','hausa','Hausa (Nigeria)'),
  ('he_IL','he','hebrew','Hebrew (Israel)'),
  ('hi_IN','hi','hindi','Hindi (India)'),
  ('hr_HR','hr','croatian','Croatian (Croatia)'),
  ('hsb_DE','hsb','upper sorbian','Upper Sorbian (Germany)'),
  ('ht_HT','ht','haitian','Haitian (Haiti)'),
  ('hu_HU','hu','hungarian','Hungarian (Hungary)'),
  ('hy_AM','hy','armenian','Armenian (Armenia)'),
  ('ia','ia','interlingua','Interlingua'),
  ('id_ID','id','indonesian','Indonesian (Indonesia)'),
  ('ig_NG','ig','igbo','Igbo (Nigeria)'),
  ('ik_CA','ik','inupiaq','Inupiaq (Canada)'),
  ('is_IS','is','icelandic','Icelandic (Iceland)'),
  ('it_CH','it','italian','Italian (Switzerland)'),
  ('it_IT','it','italian','Italian (Italy)'),
  ('iu_CA','iu','inuktitut','Inuktitut (Canada)'),
  ('ja_JP','ja','japanese','Japanese (Japan)'),
  ('ka_GE','ka','georgian','Georgian (Georgia)'),
  ('kk_KZ','kk','kazakh','Kazakh (Kazakhstan)'),
  ('kl_GL','kl','kalaallisut','Kalaallisut (Greenland)'),
  ('km_KH','km','khmer','Khmer (Cambodia)'),
  ('kn_IN','kn','kannada','Kannada (India)'),
  ('kok_IN','kok','konkani','Konkani (India)'),
  ('ko_KR','ko','korean','Korean (South Korea)'),
  ('ks_IN','ks','kashmiri','Kashmiri (India)'),
  ('ku_TR','ku','kurdish','Kurdish (Turkey)'),
  ('kw_GB','kw','cornish','Cornish (United Kingdom)'),
  ('ky_KG','ky','kirghiz','Kirghiz (Kyrgyzstan)'),
  ('lg_UG','lg','ganda','Ganda (Uganda)'),
  ('li_BE','li','limburgish','Limburgish (Belgium)'),
  ('li_NL','li','limburgish','Limburgish (Netherlands)'),
  ('lo_LA','lo','lao','Lao (Laos)'),
  ('lt_LT','lt','lithuanian','Lithuanian (Lithuania)'),
  ('lv_LV','lv','latvian','Latvian (Latvia)'),
  ('mai_IN','mai','maithili','Maithili (India)'),
  ('mg_MG','mg','malagasy','Malagasy (Madagascar)'),
  ('mi_NZ','mi','maori','Maori (New Zealand)'),
  ('mk_MK','mk','macedonian','Macedonian (Macedonia)'),
  ('ml_IN','ml','malayalam','Malayalam (India)'),
  ('mn_MN','mn','mongolian','Mongolian (Mongolia)'),
  ('mr_IN','mr','marathi','Marathi (India)'),
  ('ms_BN','ms','malay','Malay (Brunei)'),
  ('ms_MY','ms','malay','Malay (Malaysia)'),
  ('mt_MT','mt','maltese','Maltese (Malta)'),
  ('my_MM','my','burmese','Burmese (Myanmar)'),
  ('naq_NA','naq','namibia','Namibia'),
  ('nb_NO','nb','norwegian bokmål','Norwegian Bokmål (Norway)'),
  ('nds_DE','nds','low german','Low German (Germany)'),
  ('nds_NL','nds','low german','Low German (Netherlands)'),
  ('ne_NP','ne','nepali','Nepali (Nepal)'),
  ('nl_AW','nl','dutch','Dutch (Aruba)'),
  ('nl_BE','nl','dutch','Dutch (Belgium)'),
  ('nl_NL','nl','dutch','Dutch (Netherlands)'),
  ('nn_NO','nn','norwegian nynorsk','Norwegian Nynorsk (Norway)'),
  ('no_NO','no','norwegian','Norwegian (Norway)'),
  ('nr_ZA','nr','south ndebele','South Ndebele (South Africa)'),
  ('nso_ZA','nso','northern sotho','Northern Sotho (South Africa)'),
  ('oc_FR','oc','occitan','Occitan (France)'),
  ('om_ET','om','oromo','Oromo (Ethiopia)'),
  ('om_KE','om','oromo','Oromo (Kenya)'),
  ('or_IN','or','oriya','Oriya (India)'),
  ('os_RU','os','ossetic','Ossetic (Russia)'),
  ('pap_AN','pap','papiamento','Papiamento (Netherlands Antilles)'),
  ('pa_IN','pa','punjabi','Punjabi (India)'),
  ('pa_PK','pa','punjabi','Punjabi (Pakistan)'),
  ('pl_PL','pl','polish','Polish (Poland)'),
  ('ps_AF','ps','pashto','Pashto (Afghanistan)'),
  ('pt_BR','pt','portuguese','Portuguese (Brazil)'),
  ('pt_GW','pt','portuguese','Portuguese (Guinea-Bissau)'),
  ('pt_PT','pt','portuguese','Portuguese (Portugal)'),
  ('ro_MD','ro','romanian','Romanian (Moldova)'),
  ('ro_RO','ro','romanian','Romanian (Romania)'),
  ('ru_RU','ru','russian','Russian (Russia)'),
  ('ru_UA','ru','russian','Russian (Ukraine)'),
  ('rw_RW','rw','kinyarwanda','Kinyarwanda (Rwanda)'),
  ('sa_IN','sa','sanskrit','Sanskrit (India)'),
  ('sc_IT','sc','sardinian','Sardinian (Italy)'),
  ('sd_IN','sd','sindhi','Sindhi (India)'),
  ('seh_MZ','seh','sena','Sena (Mozambique)'),
  ('se_NO','se','northern sami','Northern Sami (Norway)'),
  ('sid_ET','sid','sidamo','Sidamo (Ethiopia)'),
  ('si_LK','si','sinhala','Sinhala (Sri Lanka)'),
  ('sk_SK','sk','slovak','Slovak (Slovakia)'),
  ('sl_SI','sl','slovenian','Slovenian (Slovenia)'),
  ('sn_ZW','sn','shona','Shona (Zimbabwe)'),
  ('so_DJ','so','somali','Somali (Djibouti)'),
  ('so_ET','so','somali','Somali (Ethiopia)'),
  ('so_KE','so','somali','Somali (Kenya)'),
  ('so_SO','so','somali','Somali (Somalia)'),
  ('sq_AL','sq','albanian','Albanian (Albania)'),
  ('sq_MK','sq','albanian','Albanian (Macedonia)'),
  ('sr_BA','sr','serbian','Serbian (Bosnia and Herzegovina)'),
  ('sr_ME','sr','serbian','Serbian (Montenegro)'),
  ('sr_RS','sr','serbian','Serbian (Serbia)'),
  ('ss_ZA','ss','swati','Swati (South Africa)'),
  ('st_ZA','st','southern sotho','Southern Sotho (South Africa)'),
  ('sv_FI','sv','swedish','Swedish (Finland)'),
  ('sv_SE','sv','swedish','Swedish (Sweden)'),
  ('sw_KE','sw','swahili','Swahili (Kenya)'),
  ('sw_TZ','sw','swahili','Swahili (Tanzania)'),
  ('ta_IN','ta','tamil','Tamil (India)'),
  ('teo_UG','teo','teso','Teso (Uganda)'),
  ('te_IN','te','telugu','Telugu (India)'),
  ('tg_TJ','tg','tajik','Tajik (Tajikistan)'),
  ('th_TH','th','thai','Thai (Thailand)'),
  ('tig_ER','tig','tigre','Tigre (Eritrea)'),
  ('ti_ER','ti','tigrinya','Tigrinya (Eritrea)'),
  ('ti_ET','ti','tigrinya','Tigrinya (Ethiopia)'),
  ('tk_TM','tk','turkmen','Turkmen (Turkmenistan)'),
  ('tl_PH','tl','tagalog','Tagalog (Philippines)'),
  ('tn_ZA','tn','tswana','Tswana (South Africa)'),
  ('to_TO','to','tongan','Tongan (Tonga)'),
  ('tr_CY','tr','turkish','Turkish (Cyprus)'),
  ('tr_TR','tr','turkish','Turkish (Turkey)'),
  ('ts_ZA','ts','tsonga','Tsonga (South Africa)'),
  ('tt_RU','tt','tatar','Tatar (Russia)'),
  ('ug_CN','ug','uighur','Uighur (China)'),
  ('uk_UA','uk','ukrainian','Ukrainian (Ukraine)'),
  ('ur_PK','ur','urdu','Urdu (Pakistan)'),
  ('uz_UZ','uz','uzbek','Uzbek (Uzbekistan)'),
  ('ve_ZA','ve','venda','Venda (South Africa)'),
  ('vi_VN','vi','vietnamese','Vietnamese (Vietnam)'),
  ('wa_BE','wa','walloon','Walloon (Belgium)'),
  ('wo_SN','wo','wolof','Wolof (Senegal)'),
  ('xh_ZA','xh','xhosa','Xhosa (South Africa)'),
  ('yi_US','yi','yiddish','Yiddish (United States)'),
  ('yo_NG','yo','yoruba','Yoruba (Nigeria)'),
  ('zh_CN','zh','chinese','Chinese (China)'),
  ('zh_HK','zh','chinese','Chinese (Hong Kong SAR China)'),
  ('zh_SG','zh','chinese','Chinese (Singapore)'),
  ('zh_TW','zh','chinese','Chinese (Taiwan)'),
  ('zu_ZA','zu','zulu','Zulu (South Africa)');




# Dump of table fx_login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_login_attempts`;

CREATE TABLE `fx_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table fx_messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_messages`;

CREATE TABLE `fx_messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_to` int(11) DEFAULT NULL,
  `user_from` int(11) DEFAULT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci,
  `status` enum('Read','Unread') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unread',
  `attached_file` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `favourite` int(11) DEFAULT '0',
  `deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_milestones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_milestones`;

CREATE TABLE `fx_milestones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `milestone_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `project` int(11) DEFAULT NULL,
  `start_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_payment_methods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_payment_methods`;

CREATE TABLE `fx_payment_methods` (
  `method_id` int(11) NOT NULL AUTO_INCREMENT,
  `method_name` varchar(64) NOT NULL DEFAULT 'Paypal',
  PRIMARY KEY (`method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `fx_payment_methods` (`method_id`, `method_name`)
VALUES
  (1,'Online'),
  (2,'Cash'),
  (3,'Bank Deposit'),
  (5,'Cheque');



# Dump of table fx_payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_payments`;

CREATE TABLE `fx_payments` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` int(11) NOT NULL,
  `paid_by` int(11) NOT NULL,
  `payer_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` longtext COLLATE utf8_unicode_ci,
  `currency` varchar(64) COLLATE utf8_unicode_ci DEFAULT 'USD',
  `trans_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `month_paid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year_paid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_permissions`;

CREATE TABLE `fx_permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `fx_permissions` (`permission_id`, `name`, `description`, `status`)
VALUES
  (1,'view_all_invoices','Allow user access to view all invoices','active'),
  (2,'edit_all_invoices','Allow user access to edit all invoices','active'),
  (3,'add_invoices','Allow user access to add invoices','active'),
  (4,'delete_invoices','Allow user access to delete invoice','active'),
  (5,'pay_invoice_offline','Allow user access to make offline Invoice Payments','active'),
  (6,'view_payments','Allow user access to view own payments','active'),
  (7,'email_invoices','Allow user access to email invoices','active'),
  (8,'send_email_reminders','Allow user access to send invoice reminders','active'),
  (9,'add_estimates','Allow user access to add estimates','active'),
  (10,'edit_estimates','Allow user access to edit all estimates','active'),
  (11,'view_all_estimates','Allow user access to view all estimates','active'),
  (12,'delete_estimates','Allow user access to delete estimates','active'),
  (17,'view_all_projects','Allow user access to view all projects','active'),
  (18,'view_project_cost','Allow user access to view project cost','active'),
  (19,'add_projects','Allow user access to add projects','active'),
  (20,'edit_all_projects','Allow user access to edit projects','active'),
  (21,'view_all_projects','Allow user access to view all projects','active'),
  (22,'delete_projects','Allow user access to delete projects','active'),
  (23,'edit_settings','Allow user access to edit all settings','active'),
  (25,'view_project_clients','Allow staff to view project\'s clients','active'),
  (26,'view_project_notes','Allow staff to view project notes','active');



# Dump of table fx_priorities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_priorities`;

CREATE TABLE `fx_priorities` (
  `priority` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `fx_priorities` (`priority`)
VALUES
  ('Low'),
  ('Medium'),
  ('High');




# Dump of table fx_project_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_project_settings`;

CREATE TABLE `fx_project_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `fx_project_settings` (`id`, `setting`, `description`)
VALUES
  (1,'show_team_members','Allow client to view team members'),
  (2,'show_milestones','Allow client to view project milestones'),
  (5,'show_project_tasks','Allow client to view project tasks'),
  (6,'show_project_files','Allow client to view project files'),
  (7,'show_timesheets','Allow clients to view project timesheets'),
  (8,'show_project_bugs','Allow client to view project bugs'),
  (9,'show_project_history','Allow client to view project history'),
  (10,'show_project_calendar','Allow clients to view project calendars'),
  (11,'show_project_comments','Allow clients to view project comments'),
  (12,'show_project_links','Allow client to view project links');


# Dump of table fx_project_timer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_project_timer`;

CREATE TABLE `fx_project_timer` (
  `timer_id` int(11) NOT NULL AUTO_INCREMENT,
  `project` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `start_time` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_time` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_timed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_projects`;

CREATE TABLE `fx_projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Project Title',
  `description` longtext COLLATE utf8_unicode_ci,
  `client` int(11) NOT NULL,
  `currency` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixed_rate` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No',
  `hourly_rate` decimal(10,2) DEFAULT '0.00',
  `fixed_price` decimal(10,2) DEFAULT '0.00',
  `progress` int(11) DEFAULT '0',
  `notes` longtext COLLATE utf8_unicode_ci,
  `assign_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('On Hold','Active','Done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  `timer` enum('On','Off') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Off',
  `timer_started_by` int(11) DEFAULT NULL,
  `timer_start` int(11) DEFAULT NULL,
  `time_logged` int(11) DEFAULT NULL,
  `proj_deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `auto_progress` enum('TRUE','FALSE') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FALSE',
  `estimate_hours` decimal(10,2) NOT NULL DEFAULT '0.00',
  `settings` text COLLATE utf8_unicode_ci,
  `language` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `archived` int(11) DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_roles`;

CREATE TABLE `fx_roles` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(64) NOT NULL,
  `default` int(11) NOT NULL,
  `permissions` varchar(255) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `fx_roles` (`r_id`, `role`, `default`, `permissions`)
VALUES
  (1,'admin',1,'{\"settings\":\"permissions\",\"role\":\"admin\",\"view_all_invoices\":\"on\",\"edit_invoices\":\"on\",\"pay_invoice_offline\":\"on\",\"view_all_payments\":\"on\",\"email_invoices\":\"on\",\"send_email_reminders\":\"on\"}'),
  (2,'client',2,'{\"settings\":\"permissions\",\"role\":\"client\"}'),
  (3,'staff',3,'{\"settings\":\"permissions\",\"role\":\"staff\",\"view_all_invoices\":\"on\",\"edit_invoices\":\"on\",\"add_invoices\":\"on\",\"pay_invoice_offline\":\"on\",\"send_email_reminders\":\"on\"}');




# Dump of table fx_saved_tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_saved_tasks`;

CREATE TABLE `fx_saved_tasks` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT 'Task Name',
  `task_desc` text COLLATE utf8_unicode_ci,
  `visible` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `estimate_hours` decimal(10,2) DEFAULT '0.00',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `saved_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_status`;

CREATE TABLE `fx_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



INSERT INTO `fx_status` (`id`, `status`)
VALUES
  (1,'answered'),
  (2,'closed'),
  (3,'open'),
  (5,'in progress');


# Dump of table fx_task_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_task_files`;

CREATE TABLE `fx_task_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `task` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_name` mediumtext COLLATE utf8_unicode_ci,
  `path` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `file_ext` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `is_image` int(2) DEFAULT NULL,
  `image_width` int(10) DEFAULT NULL,
  `image_height` int(10) DEFAULT NULL,
  `original_name` mediumtext COLLATE utf8_unicode_ci,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `file_status` enum('unconfirmed','confirmed','in_progress','done','verified') COLLATE utf8_unicode_ci DEFAULT 'unconfirmed',
  `uploaded_by` int(11) DEFAULT NULL,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_tasks`;

CREATE TABLE `fx_tasks` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Task Name',
  `project` int(11) NOT NULL,
  `milestone` int(11) DEFAULT NULL,
  `assigned_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `visible` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `task_progress` int(11) DEFAULT '0',
  `timer_status` enum('On','Off') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Off',
  `timer_started_by` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `estimated_hours` decimal(10,2) DEFAULT NULL,
  `logged_time` int(11) NOT NULL DEFAULT '0',
  `auto_progress` enum('TRUE','FALSE') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FALSE',
  `due_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_tasks_timer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_tasks_timer`;

CREATE TABLE `fx_tasks_timer` (
  `timer_id` int(11) NOT NULL AUTO_INCREMENT,
  `task` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `start_time` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_time` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `date_timed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_tax_rates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_tax_rates`;

CREATE TABLE `fx_tax_rates` (
  `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_rate_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `tax_rate_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  KEY `Index 1` (`tax_rate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_ticketreplies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_ticketreplies`;

CREATE TABLE `fx_ticketreplies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticketid` int(10) DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `replier` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replierid` int(10) DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_tickets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_tickets`;

CREATE TABLE `fx_tickets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `status` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `reporter` int(10) DEFAULT '0',
  `priority` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional` text COLLATE utf8_unicode_ci,
  `attachment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `archived_t` int(2) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fx_un_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_un_sessions`;

CREATE TABLE `fx_un_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table fx_user_autologin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_user_autologin`;

CREATE TABLE `fx_user_autologin` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table fx_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fx_users`;

CREATE TABLE `fx_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '2',
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

