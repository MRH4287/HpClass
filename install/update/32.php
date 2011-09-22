ALTER TABLE `#!-PREFIX-!#token` ADD `verfall_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#token` CHANGE `verfall_new` `verfall_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#token` SET `verfall_new` = FROM_UNIXTIME(`verfall`);
ALTER TABLE `#!-PREFIX-!#token` DROP `verfall`;
ALTER TABLE `#!-PREFIX-!#token` CHANGE `verfall_new` `verfall` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#pm` ADD `timestamp_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#pm` CHANGE `timestamp_new` `timestamp_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#pm` SET `timestamp_new` = FROM_UNIXTIME(`timestamp`);
ALTER TABLE `#!-PREFIX-!#pm` DROP `timestamp`;
ALTER TABLE `#!-PREFIX-!#pm` CHANGE `timestamp_new` `timestamp` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#user` ADD `lastaction_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#user` CHANGE `lastaction_new` `lastaction_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#user` SET `lastaction_new` = FROM_UNIXTIME(`lastaction`);
ALTER TABLE `#!-PREFIX-!#user` DROP `lastaction`;
ALTER TABLE `#!-PREFIX-!#user` CHANGE `lastaction_new` `lastaction` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#user` ADD `lastlogin_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#user` CHANGE `lastlogin_new` `lastlogin_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#user` SET `lastlogin_new` = FROM_UNIXTIME(`lastlogin`);
ALTER TABLE `#!-PREFIX-!#user` DROP `lastlogin`;
ALTER TABLE `#!-PREFIX-!#user` CHANGE `lastlogin_new` `lastlogin` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#vote` ADD `timestamp_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#vote` CHANGE `timestamp_new` `timestamp_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#vote` SET `timestamp_new` = FROM_UNIXTIME(`timestamp`);
ALTER TABLE `#!-PREFIX-!#vote` DROP `timestamp`;
ALTER TABLE `#!-PREFIX-!#vote` CHANGE `timestamp_new` `timestamp` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#vote` ADD `upto_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#vote` CHANGE `upto_new` `upto_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#vote` SET `upto_new` = FROM_UNIXTIME(`upto`);
ALTER TABLE `#!-PREFIX-!#vote` DROP `upto`;
ALTER TABLE `#!-PREFIX-!#vote` CHANGE `upto_new` `upto` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#usedpics` ADD `time_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#usedpics` CHANGE `time_new` `time_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#usedpics` SET `time_new` = FROM_UNIXTIME(`time`);
ALTER TABLE `#!-PREFIX-!#usedpics` DROP `time`;
ALTER TABLE `#!-PREFIX-!#usedpics` CHANGE `time_new` `time` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#events` ADD `time_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#events` CHANGE `time_new` `time_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#events` SET `time_new` = FROM_UNIXTIME(`time`);
ALTER TABLE `#!-PREFIX-!#events` DROP `time`;
ALTER TABLE `#!-PREFIX-!#events` CHANGE `time_new` `time` DATETIME NOT NULL;

ALTER TABLE `#!-PREFIX-!#subpages` ADD `created_new` DATETIME NOT NULL;
ALTER TABLE `#!-PREFIX-!#subpages` CHANGE `created_new` `created_new` DATETIME NOT NULL;
UPDATE `#!-PREFIX-!#subpages` SET `created_new` = FROM_UNIXTIME(`created`);
ALTER TABLE `#!-PREFIX-!#subpages` DROP `created`;
ALTER TABLE `#!-PREFIX-!#subpages` CHANGE `created_new` `created` DATETIME NOT NULL;
