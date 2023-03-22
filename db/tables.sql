DROP TABLE IF EXISTS users; 

CREATE TABLE users 
  ( 
	id       SMALLINT UNSIGNED NOT NULL auto_increment, 
	username VARCHAR(255) NOT NULL,
	password TEXT NOT NULL, 
	role     VARCHAR(255) NOT NULL,
	join_date DATE NULL DEFAULT NULL,
	birth_date DATE NULL DEFAULT NULL,
	gender varchar(15) NULL DEFAULT NULL,
	data text NULL DEFAULT NULL,
	email varchar(40) NULL DEFAULT NULL,
	bio varchar(180) NULL DEFAULT NULL,
	xp varchar(180) NULL DEFAULT 0,
	avatar varchar(180) NULL DEFAULT 0,
	PRIMARY KEY (id) 
  );

DROP TABLE IF EXISTS loginlogs; 

CREATE TABLE loginlogs (
	id SMALLINT UNSIGNED NOT NULL auto_increment,
	IpAddress varbinary(16) NOT NULL,
	TryTime bigint(20) NOT NULL,
	PRIMARY KEY (id) 
);

DROP TABLE IF EXISTS login_history; 

CREATE TABLE login_history (
	id SMALLINT UNSIGNED NOT NULL auto_increment,
	ip varbinary(16) NOT NULL,
	data MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY (id) 
);

DROP TABLE IF EXISTS categories; 

CREATE TABLE categories 
  ( 
	 id          SMALLINT UNSIGNED NOT NULL auto_increment, 
	 name        VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 slug     VARCHAR(30) NOT NULL,
	 description  MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 meta_description  MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 PRIMARY KEY (id) 
  );

DROP TABLE IF EXISTS cat_links; 

CREATE TABLE cat_links 
  ( 
	 id         SMALLINT UNSIGNED NOT NULL auto_increment, 
	 gameid     SMALLINT UNSIGNED NOT NULL,
	 categoryid SMALLINT UNSIGNED NOT NULL,
	 PRIMARY KEY (id) 
  ); 

DROP TABLE IF EXISTS pages; 

CREATE TABLE pages 
  ( 
	 id          SMALLINT UNSIGNED NOT NULL auto_increment, 
	 createddate DATE NOT NULL,
	 title       VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 slug        VARCHAR(255) NOT NULL,
	 content     MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 PRIMARY KEY (id) 
  );

DROP TABLE IF EXISTS games; 

CREATE TABLE games 
( 
	 id           SMALLINT UNSIGNED NOT NULL auto_increment, 
	 createddate  DATE NOT NULL,
	 title        VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 description  MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	 instructions MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	 category     TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	 source       TEXT NOT NULL, 
	 thumb_1      VARCHAR(255) NOT NULL,
	 thumb_2      VARCHAR(255) NOT NULL,
	 thumb_small  VARCHAR(255) NOT NULL,
	 url          TEXT NOT NULL, 
	 width        TEXT NOT NULL, 
	 height       TEXT NOT NULL, 
	 tags         TEXT NOT NULL, 
	 views        INT NOT NULL, 
	 upvote       INT NOT NULL, 
	 downvote     INT NOT NULL,
	 slug     VARCHAR(255) NOT NULL,
	 data MEDIUMTEXT NOT NULL, 
	 PRIMARY KEY (id) 
);

DROP TABLE IF EXISTS votelogs; 

CREATE TABLE votelogs 
( 
	id           SMALLINT UNSIGNED NOT NULL auto_increment, 
	game_id     SMALLINT UNSIGNED NOT NULL,
	ip 		varbinary(16) NOT NULL,
	action          TEXT NOT NULL, 
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS options
( 
	id           SMALLINT UNSIGNED NOT NULL auto_increment, 
	name 			VARCHAR(255) NOT NULL,
	value          TEXT NOT NULL, 
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS collections; 

CREATE TABLE collections 
  ( 
	 id          SMALLINT UNSIGNED NOT NULL auto_increment, 
	 name        VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 data  MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	 PRIMARY KEY (id) 
  );

DROP TABLE IF EXISTS comments;

CREATE TABLE comments (
 id int(10) unsigned NOT NULL AUTO_INCREMENT,
 game_id int(10) NOT NULL,
 parent_id int(10) unsigned DEFAULT NULL,
 comment varchar(200) NOT NULL,
 sender_id int(40) NOT NULL,
 sender_username varchar(20) NOT NULL,
 created_date DATE NULL DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (parent_id) REFERENCES comments (id) 
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `statistics` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `created_date` date DEFAULT NULL,
 `page_views` varchar(255) DEFAULT NULL,
 `unique_visitor` varchar(255) DEFAULT NULL,
 `data` mediumtext DEFAULT NULL,
 PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS stats_ip_address; 

CREATE TABLE stats_ip_address (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 ip_address varchar(255) DEFAULT NULL,
 created_date date DEFAULT NULL,
 PRIMARY KEY (id)
);

INSERT IGNORE INTO options (id, name, value) VALUES('1', 'site_title', 'Cloud Arcade');
INSERT IGNORE INTO options (id, name, value) VALUES('2', 'site_description', 'Play HTML5 Games');
INSERT IGNORE INTO options (id, name, value) VALUES('3', 'meta_description', 'Play HTML5 Games for Free');
INSERT IGNORE INTO options (id, name, value) VALUES('4', 'site_logo', 'images/cloudarcade-logo.png');
INSERT IGNORE INTO options (id, name, value) VALUES('5', 'theme_name', 'default');
INSERT IGNORE INTO options (id, name, value) VALUES('6', 'import_thumb', 'false');
INSERT IGNORE INTO options (id, name, value) VALUES('7', 'small_thumb', 'false');
INSERT IGNORE INTO options (id, name, value) VALUES('8', 'custom_slug', 'false');
INSERT IGNORE INTO options (id, name, value) VALUES('9', 'pretty_url', 'true');
INSERT IGNORE INTO options (id, name, value) VALUES('10', 'url_protocol', 'http://');
INSERT IGNORE INTO options (id, name, value) VALUES('11', 'purchase_code', '');
INSERT IGNORE INTO options (id, name, value) VALUES('12', 'language', 'en');
INSERT IGNORE INTO options (id, name, value) VALUES('13', 'comments', 'true');
INSERT IGNORE INTO options (id, name, value) VALUES('14', 'upload_avatar', 'true');
INSERT IGNORE INTO options (id, name, value) VALUES('15', 'user_register', 'true');