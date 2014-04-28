ALTER TABLE `#!-PREFIX-!#plugins` DROP `ID`;
TRUNCATE TABLE `#!-PREFIX-!#plugins`;
ALTER TABLE `#!-PREFIX-!#plugins` ADD PRIMARY KEY(`name`);