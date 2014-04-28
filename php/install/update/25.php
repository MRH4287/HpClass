DROP TABLE IF EXISTS `#!-PREFIX-!#subpages`;
CREATE TABLE `#!-PREFIX-!#subpages` (`ID` int(100) NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL,`content` longtext NOT NULL COMMENT 'Elemente werden mit <!--!> getrennt innerhalb werden Keys und Values mit <!=!> getrennt', `template` varchar(100) NOT NULL, `created` int(100) NOT NULL,  PRIMARY KEY (`ID`),  UNIQUE KEY `name` (`name`),  FULLTEXT KEY `content` (`content`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- INSERT INTO `#!-PREFIX-!#right` ( `level` , `right` , `ok` , `description` ) VALUES ( '0', 'manage_subpage', 'false', 'Das Recht die Unterseiten zu bearbeiten' );
-- INSERT INTO `#!-PREFIX-!#right` ( `level` , `right` , `ok` , `description` ) VALUES ( '1', 'manage_subpage', 'false', 'Das Recht die Unterseiten zu bearbeiten' );
-- INSERT INTO `#!-PREFIX-!#right` ( `level` , `right` , `ok` , `description` ) VALUES ( '2', 'manage_subpage', 'false', 'Das Recht die Unterseiten zu bearbeiten' );
-- INSERT INTO `#!-PREFIX-!#right` ( `level` , `right` , `ok` , `description` ) VALUES ( '3', 'manage_subpage', 'false', 'Das Recht die Unterseiten zu bearbeiten' );
-- INSERT INTO `#!-PREFIX-!#right` ( `level` , `right` , `ok` , `description` ) VALUES ( '4', 'manage_subpage', 'true', 'Das Recht die Unterseiten zu bearbeiten' );
-- System no longer in use (use config files)