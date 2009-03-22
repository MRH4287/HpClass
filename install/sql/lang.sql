CREATE TABLE IF NOT EXISTS `#!-PRÄFIX-!#lang` (
  `lang` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `word` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `wort` text COLLATE latin1_general_ci NOT NULL,
  KEY `lang` (`lang`,`word`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
