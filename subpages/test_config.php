<?php
/*
        Subpage Konfiguration:
        
        In dieser Datei stehen alle Informationen, die Sp�ter durch das System ausgelesen werden um die Daten entsprechend anzuzeigen.
        Nur die Dateien, bei denen eine Konfigurations-Datei exsistiert, werden im System angezeigt.
        
*/

  
    // Nicht �ndern
    $subpageconfig = array();
    
  // Ab hier k�nnen �nderungen vorgenommen werden
    // Der Name dieses Templates:
    $subpageconfig["name"]        = "Test-Template";
    // Die Liste aller statischen Inhalte:
    // M�gliche Daten:
    //  textbox, textarea, combobox, checkbox
    $subpageconfig["template"]    = array(
    "vorname" => "textbox",
    "nachname" => "textbox",
    "titel" => "textbox",
    "weiteres" => "textarea",
    "cbb" => "combobox" // Muss zu einem Array Verlinken
    );
    
    $subpageconfig["data"] = array(
      "cbb" => array(  // Eintr�ge f�r die ComboBox "cbb"
         "A", "B", "C"
      )
    
    );
    
    // Die Liste alles dynamischen Inhalte:
    $subpageconfig["dyncontent"]  = array(  
                              // Name des Platzhalters     Typ
                                    "subjects" => "subjectList",
                                    "email" => "test"
                                    );
    


?>