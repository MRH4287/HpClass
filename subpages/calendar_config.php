<?php
/*
        Subpage Konfiguration:

        In dieser Datei stehen alle Informationen, die Später durch das System ausgelesen werden um die Daten entsprechend anzuzeigen.
        Nur die Dateien, bei denen eine Konfigurations-Datei existiert, werden im System angezeigt.

*/


    // Nicht ändern
    $subpageconfig = array();

  // Ab hier können Änderungen vorgenommen werden
    // Der Name dieses Templates:
    $subpageconfig["name"]        = "Kalender";
    // Die Liste aller statischen Inhalte:
    $subpageconfig["template"]    = array(
      "headline" => "textbox",
      "main"     => "textarea",
      "MinLevel" => "level"
    );
    // Die Liste alles dynamischen Inhalte:
    $subpageconfig["dyncontent"]  = array("calendar" => "calendar");



?>