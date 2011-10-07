<?php
class Forum extends Plugin
{

  
  function __construct($hp, $loader)
  {
    // Laden der Daten
    // Nicht Editieren!
    parent::__construct($hp, $loader);
    
    // Plugin Config:
    // -----------------------------------------------
    
    // Der Name des Plugins:
    $this->name = "Foren System";
    
    // Die Version des Plugins:
    $this->version = "Beta 1";
    
    // Der Autor des Plugins:
    $this->autor = "MRH";
    
    //Die Homepage des Autors:
    $this->homepage = "http://mrh-development.de";
    
    //Notizen zu dem Plugin:
    $this->notes = "Foren System";
  }
  
  
  /*
  
  Lade alle für das System relevanten Daten.
  
  z.B. Datenbank Aufrufe, Datei Aufrufe, etc.
  
  */
  function onEnable()
  {
    

    $this->hp->addredirect("forum", "plugins/Forum");
  }
  
  
  /*
  
  Hier werden die eigentlichen Aufgaben des Plugins erledigt.
  Wie zum Beispiel das hinzufügen von Weiterleitungen.
  
  */
  function onLoad()
  {
    
    pluginTemplate::extend($this);
    
  }
  
  
  
  /*
  
    Diese Funktion läd alle für eine unterseite relevanten Daten
  
  */
  public function temp_ForumLoadContent($args, $context)
  {
    
     $functions = get_class_methods($this);
     
     $site = 'site_'.$args[0];
     
     if (in_array($site, $functions))
     {
        $this->$site($args, $context);
     } else
     {
        return '[site?]';
     }
     
    // Always return an empty string
    return '';
  
  
  }
  
  // --------------------   Context für Unterseiten  ---------------------------
  
  
  public function site_forums($args, $context)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $right = $hp->right;
    $level = $_SESSION['level'];
    
    
    $sql = 'SELECT f.ID, f.titel, f.timestamp AS time, f.level, f.passwort,
            f.visible, f.description, f.type, u.user, f.userid,
            (
              SELECT count(*) FROM `'.$dbprefix.'threads` WHERE `forumid` = f.ID GROUP BY `forumid`          
              
            ) AS threads,
            (
              SELECT count(*) FROM `'.$dbprefix.'posts` WHERE `threadid` IN (
                 SELECT `ID` FROM `'.$dbprefix.'threads` WHERE `forumid` = f.ID  
              ) GROUP BY `threadid`                       
            ) AS posts,
            IFNULL(
            (
              SELECT timestamp FROM `'.$dbprefix.'posts` WHERE `threadid` IN (
                 SELECT `ID` FROM `'.$dbprefix.'threads` WHERE `forumid` = f.ID  
              ) ORDER BY `ID` DESC LIMIT 1                       
            ), \'Keiner\') AS lastpost

            FROM `'.$dbprefix.'forums` f LEFT JOIN
            `'.$dbprefix.'user` u ON f.userid = u.ID;';
    $erg = $hp->mysqlquery($sql);
    
    
    
    $content = array();
    
    while ($row = mysql_fetch_object($erg))
    {
      if (($row->visible == 1) || $right->isAllowed($row->level))
      {
        $data = array(
          
          'ID' => $row->ID,
          'titel' => $row->titel,
          'level' => $row->level,
          'passwort' => $row->passwort,
          'visible' => ($row->visible == '1'),
          'description' => $row->description,
          'type' => $row->type,
          'user' => $row->user,
          'userid' => $row->userid,
          'threads' => intval($row->threads),
          'posts' => intval($row->posts),
          'lastpost' => $row->lastpost
          );
        
          $content[] = $data;
        
      }
    }
    
    $context->set('content', $content);
    
  
  
  }

  
  
  
  

}



?>