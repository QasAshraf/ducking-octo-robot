ALTER TABLE `tagchatapi`.`user`
ADD COLUMN `firstname` VARCHAR(128) NULL AFTER `password`,
ADD COLUMN `lastname` VARCHAR(128) NULL AFTER `firstname`;
