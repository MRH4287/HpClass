<?php
/*
        Subpage Konfiguration:
        
        In dieser Datei stehen alle Informationen, die Später durch das System ausgelesen werden um die Daten entsprechend anzuzeigen.
        Nur die Dateien, bei denen eine Konfigurations-Datei exsistiert, werden im System angezeigt.
        
*/

  
    // Nicht ändern
    $subpageconfig = array();
    
  // Ab hier können Änderungen vorgenommen werden
    // Der Name dieses Templates:
    $subpageconfig["name"]        = "test";
    // Die Liste aller statischen Inhalte:
    $subpageconfig["template"]    = array("vorname", "nachname", "titel", "weiteres");
    // Die Liste alles dynamischen Inhalte:
    $subpageconfig["dyncontent"]  = array(  
                              // Name des Platzhalters     Typ
                                            "subjects" => "subjectList",
                                            "email" => "test"
                                            );
    


?>