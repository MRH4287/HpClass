ALTER TABLE `#!-PREFIX-!#user` ADD `token` VARCHAR( 100 ) NOT NULL DEFAULT '' COMMENT 'ScriptAccsess-Security-Token',ADD `counter` INT( 100 ) NOT NULL DEFAULT '0' COMMENT 'ScriptAcsess-Security-Counter',ADD `lastaction` INT( 100 ) NOT NULL DEFAULT '0' COMMENT 'ScriptAcsess-lastAction',ADD INDEX ( `token` );