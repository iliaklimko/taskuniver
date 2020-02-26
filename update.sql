ALTER TABLE `user` ADD `pay_percent` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `user` ADD `can_paid_by_card` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `order` ADD `prepayment` FLOAT(11) NOT NULL DEFAULT '0';
ALTER TABLE `order` ADD `prepayment_percent` INT(11) NOT NULL DEFAULT '0';

CREATE TABLE `excursion_groups` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`priority` VARCHAR(255) NOT NULL DEFAULT '100',
`code` VARCHAR(255) NOT NULL ,
 PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `excursion_groups_translation` (
`excursion_groups_id` INT(11) NOT NULL,
`language_code` VARCHAR(16) COLLATE utf8_unicode_ci NOT NULL,
`name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL ,
 PRIMARY KEY (`excursion_groups_id`,`language_code`),
 UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `excursion` ADD `group_id` INT(11) NOT NULL;


ALTER TABLE `excursion` ADD COLUMN free_cancellation int(11) NOT NULL;
ALTER TABLE `user` ADD `pay_cash` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `excursion` ADD COLUMN set_to DATE NOT NULL;
ALTER TABLE `excursion` ADD COLUMN time_spending time NOT NULL;
ALTER TABLE `excursion` ADD COLUMN date_array text NOT NULL;


ALTER TABLE `excursion_groups` CHANGE `priority` `priority` INT(11) NOT NULL DEFAULT '100';
ALTER TABLE `excursion` ADD COLUMN one_time_excursion text NOT NULL;
ALTER TABLE `excursion` ADD COLUMN visitors int(11)  NOT NULL;

