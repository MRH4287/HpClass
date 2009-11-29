CREATE TABLE IF NOT EXISTS `#!-PRÄFIX-!#threads` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `forumid` int(100) NOT NULL,
  `userid` int(100) NOT NULL,
  `timestamp` int(255) NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `level` int(100) NOT NULL,
  `passwort` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `visible` int(5) NOT NULL,
  `closed` int(5) NOT NULL,
  `type` int(5) NOT NULL COMMENT '0 = Normal, 1 = Sticky, 2 = Anounce',
  `lastpost` int(100) NOT NULL,
  `lastedit` int(100) NOT NULL,
  `ergebnisse` text COLLATE latin1_general_ci NOT NULL,
  `voted` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ;
