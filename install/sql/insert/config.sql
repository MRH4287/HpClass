INSERT INTO `#!-PRÄFIX-!#config` (`ID`, `name`, `ok`, `description`, `typ`) VALUES
(1, 'checkversion', 'false', 'Überprüft ob die aktuelle Version die aktuellste ist.', 'bool'),
(2, 'design', 'default', 'Aktuelles Design (Leer lassen für Standard)', 'string'),
(3, 'titel', 'HPClass Demo', 'Der Webseiten Titel  (Leer lassen für Standard)', 'string'),
(4, 'redirectlock', 'admin, config, rights', 'Seiten die von der Weiterleitung (Modul) ausgenommen sind (Mit , trennen)', 'string'),
(5, 'superadmin', '', 'Definiert Superadmins, neben admin (Mit , trennen)', 'string'),
(6, 'standardsite', 'news', 'Definiert die Standard Seite (Wenn Leer dann news)', 'string'),
(7, 'mainheadline', '&lt;font size=''2'' face=''Comic Sans MS''>Beispieltext&lt;/font>', 'HTML - Hauptüberschrifft (nicht in allen Designs verwendet)', 'string');