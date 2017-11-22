
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `api_key` VARCHAR(32) NOT NULL,
    `pass_sha256` VARCHAR(64) NOT NULL,
    `pass_salt` VARCHAR(15) NOT NULL,
    `ban` TINYINT(1) DEFAULT 1 NOT NULL,
    `signup_ts` INTEGER(10) DEFAULT 1483228800 NOT NULL,
    `gender` TINYINT(1) DEFAULT 0 NOT NULL,
    `reputation` INTEGER DEFAULT 0 NOT NULL,
    `setting_privacy` VARCHAR(255) DEFAULT '333' NOT NULL,
    `setting_notifications` VARCHAR(255) DEFAULT '11111' NOT NULL,
    `phone` VARCHAR(255),
    `public_message` VARCHAR(255),
    `picture_url` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_u_ce4c89` (`email`),
    INDEX `user_i_db2f7c` (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user_social
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_social`;

CREATE TABLE `user_social`
(
    `user_id` INTEGER NOT NULL,
    `firebase` VARCHAR(255),
    PRIMARY KEY (`user_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- location
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `admin_id` INTEGER NOT NULL,
    `signup_ts` INTEGER(10) DEFAULT 1483228800 NOT NULL,
    `verified` TINYINT(1) DEFAULT 0 NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `capacity` INTEGER DEFAULT 0 NOT NULL,
    `picture_url` VARCHAR(255) NOT NULL,
    `timings` VARCHAR(168) DEFAULT '' NOT NULL,
    `reputation` INTEGER DEFAULT 0 NOT NULL,
    `email` VARCHAR(255),
    `phone` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `location_u_ce4c89` (`email`),
    INDEX `location_i_db2f7c` (`id`),
    INDEX `location_fi_f2be84` (`admin_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- location_address
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `location_address`;

CREATE TABLE `location_address`
(
    `location_id` INTEGER NOT NULL,
    `country` VARCHAR(2) NOT NULL,
    `state` VARCHAR(255),
    `city` VARCHAR(255),
    `town` VARCHAR(255),
    `postcode` VARCHAR(255),
    `address` VARCHAR(255) NOT NULL,
    `lat_lng_json` VARCHAR(128),
    `google_place_id` VARCHAR(255),
    PRIMARY KEY (`location_id`),
    INDEX `location_address_i_5d8314` (`location_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- search_location
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `search_location`;

CREATE TABLE `search_location`
(
    `location_id` INTEGER NOT NULL,
    `query` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`location_id`),
    INDEX `search_location_i_5d8314` (`location_id`),
    FULLTEXT INDEX `idx_search_location_fulltext` (`query`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- search_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `search_user`;

CREATE TABLE `search_user`
(
    `user_id` INTEGER NOT NULL,
    `query` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`user_id`),
    INDEX `search_user_i_6ca017` (`user_id`),
    FULLTEXT INDEX `idx_search_user_fulltext` (`query`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user_location_favorite
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_location_favorite`;

CREATE TABLE `user_location_favorite`
(
    `user_id` INTEGER NOT NULL,
    `location_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`location_id`),
    INDEX `user_location_favorite_i_febda5` (`user_id`, `location_id`),
    INDEX `user_location_favorite_fi_5ca888` (`location_id`),
    INDEX `user_location_favorite_i_5d8314` (`location_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user_connection
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_connection`;

CREATE TABLE `user_connection`
(
    `user_id` INTEGER NOT NULL,
    `connection_id` INTEGER NOT NULL,
    `state` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`connection_id`),
    INDEX `user_connection_i_369d63` (`user_id`, `connection_id`),
    INDEX `user_connection_fi_5c780e` (`connection_id`),
    INDEX `user_connection_i_a9ceb3` (`connection_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user_location
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_location`;

CREATE TABLE `user_location`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `location_id` INTEGER NOT NULL,
    `from_ts` INTEGER(10),
    `until_ts` INTEGER(10),
    PRIMARY KEY (`id`),
    INDEX `user_location_i_febda5` (`user_id`, `location_id`),
    INDEX `user_location_fi_5ca888` (`location_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user_location_expired
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_location_expired`;

CREATE TABLE `user_location_expired`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `location_id` INTEGER NOT NULL,
    `from_ts` INTEGER(10),
    `until_ts` INTEGER(10),
    PRIMARY KEY (`id`),
    INDEX `user_location_expired_i_febda5` (`user_id`, `location_id`),
    INDEX `user_location_expired_fi_5ca888` (`location_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- location_image
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `location_image`;

CREATE TABLE `location_image`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `location_id` INTEGER NOT NULL,
    `inserter_id` INTEGER NOT NULL,
    `inserted_ts` INTEGER(10) NOT NULL,
    `approved` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `location_image_i_5d8314` (`location_id`),
    INDEX `location_image_fi_bd7f1d` (`inserter_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- system_temp_var
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `system_temp_var`;

CREATE TABLE `system_temp_var`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` TINYINT NOT NULL,
    `data` MEDIUMBLOB NOT NULL,
    `expiry_ts` INTEGER(10) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `system_temp_var_i_f36ccd` (`id`, `type`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
