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
    
    Argumente:
      0 - Die Informationen über welche Seite
      1
      ... Werden an die Unterfunktionen übergeben
      n
    
    Ausgabe:
      Seiten Bedingt
  
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
  
  
  /*
  
    Die Logik für die Generierung der Seitennavigation
    Argumente:
      0 - Aktuelle Seite
      1 - Maximale Anzahl an Seiten
      2 - Block der pro Link ausgegeben werden soll
      3 - Wieviele Links sollen zusätzlich links und rechts angezeigt werden? (Wenn nicht definiert dann 1)
      4 - Abtrennungs Block       (Wenn nicht definiert dann " ... ")
      5 - Block für Erste Seite   (Wenn nicht definiert dann 2)
      6 - Block für Letzte Seite  (Wenn nicht definiert dann 2)
    
    Ausgabe:
      result - String - Die Seitennavigation
      ForumNavigationPageID - String - Wird für intene Zwecke genutzt
  
  */
  public function temp_ForumDisplayPages($args, $context)
  {
    if (count($args) < 3)
    {
      return "[Args?]";
    } else
    {      
      $page =  intval($args[0]);
      $pageCount = intval($args[1]);
      $block = $args[2];
      
      $additionalLinks = 1;
      if (isset($args[3]))
      {
        $additionalLinks = intval($args[3]);
      }
      
      $spacer = " ... ";
      if (isset($args[4]))
      {
       $spacer = $args[4]; 
      }
      
      $fistPageBlock = $block;
      if (isset($args[5]))
      {
        $fistPageBlock = $args[5];
      }
      
      $lastPageBlock = $block;
      if (isset($args[6]))
      {
        $lastPageBlock = $args[6];
      }
      
      $content = "";
      
      // Always display the first Page
      $context->set('ForumNavigationPageID', 1);
      $content .= $context->replace($fistPageBlock);
      
      $diffToFirst = ($page - 2);
      if ($diffToFirst > ($additionalLinks))
      {
        $content .= $spacer;
      }
      
      // Display the Links
      for ($i = ($page - $additionalLinks); $i <= ($page + $additionalLinks); $i++)
      {
        // Check if this Value is the first or last one
        if (($i <= 1) || ($i >= $pageCount))
        {
          continue;
        }
        
        $context->set('ForumNavigationPageID', $i);
        $content .= $context->replace($block); 
        
      }
      
      $diffToLast = $pageCount - $page -1;
      if ($diffToLast > ($additionalLinks))
      {
        $content .= $context->replace($spacer);
      }
      
      // Display the last Page if it it does not equals the first
      if ($pageCount != 1)
      {
        $context->set('ForumNavigationPageID', $pageCount);
        $content .= $context->replace($lastPageBlock);
      }
      
      $context->set('result', $content);
      
    }
    
    
    
  }
  
  
  // --------------------   Context für Unterseiten  ---------------------------
  
  
  /*
   
    Setzt die Informationen für die Foren-Seite
    
    Argumente:
      0 - /
      
    Ausgabe:
      content - Array - Liste der Foren
   
  */
  public function site_forums($args, $context)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $right = $hp->right;
    
    
    $sql = 'SELECT f.ID, f.titel, f.timestamp AS time, f.level, f.passwort,
            f.visible, f.description, f.type, u.user, f.userid,
            (
              SELECT count(*) FROM `'.$dbprefix.'threads` WHERE `forumid` = f.ID GROUP BY `forumid`          
            ) AS threads,
            IFNULL((
              SELECT count(*) FROM `'.$dbprefix.'posts` WHERE `threadid` IN (
                 SELECT `ID` FROM `'.$dbprefix.'threads` WHERE `forumid` = f.ID  
              ) GROUP BY `threadid`                       
            ), 0) AS posts,
            IFNULL((
              SELECT threadid
              FROM `'.$dbprefix.'posts` WHERE `threadid` IN (
                 SELECT `ID` FROM `'.$dbprefix.'threads` WHERE `forumid` = f.ID  
              ) ORDER BY `ID` DESC LIMIT 1                             
            ), -1) AS lastpostThreadID,
            IFNULL((
              SELECT titel
              FROM `'.$dbprefix.'threads` WHERE `ID` = lastpostThreadID                            
            ), \'\') AS lastpostThreadTitel,
            IFNULL((
              SELECT ID
              FROM `'.$dbprefix.'posts` WHERE `threadid` = lastpostThreadID ORDER BY `ID` DESC LIMIT 1                             
            ), -1) AS lastpostID,
            IFNULL((
              SELECT timestamp
              FROM `'.$dbprefix.'posts` WHERE `ID` = lastpostID                             
            ), \'Keiner\') AS lastpost,
            IFNULL((
              SELECT userid
              FROM `'.$dbprefix.'posts` WHERE `ID` = lastpostID                            
            ), -1) AS lastpostUserID,
            IFNULL((
              SELECT user
              FROM `'.$dbprefix.'user` WHERE `ID` = lastpostUserID                            
            ), \'\') AS lastpostUser

            FROM `'.$dbprefix.'forums` f LEFT JOIN
            `'.$dbprefix.'user` u ON f.userid = u.ID ORDER BY `titel` ASC;';
    $erg = $hp->mysqlquery($sql);
    
    
    
    $content = array();
    
    if (mysql_num_rows($erg) >0)
    {    
      while ($row = mysql_fetch_object($erg))
      {
        $allowed = $right->isAllowed($row->level);
        if (($row->visible == 1) || $allowed)
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
            'lastpost' => $row->lastpost,
            'lastpostThreadID' => $row->lastpostThreadID,
            'lastpostTitel' => $row->lastpostThreadTitel,
            'laspostID' => $row->lastpostID,
            'lastpostUserID' => $row->lastpostUserID,
            'lastpostUser' => $row->lastpostUser,
            'locked' => !$allowed
            );
          
            $content[] = $data;
          
        }
      }
    }
    
    $context->set('content', $content);
  
  }
  
  
  /*
   
    Setzt die Informationen für die Threads-Seite
    
    Variablen:
      entrysPerPage - Wievele Einträge pro Seite (Default=15)
    
    Parameter (GET):
      page - Regelt, welche Seite dargestellt werden soll
    
    Argumente:
      0 - /
      1 - ForenID
      
    Ausgabe:
      error - String - Ist ein Fehler aufgetreten
      page - String - Welche Seite ist offen
      pageCount - String - Wieviele Seiten gibt es
      content - Array - Liste der Threads im Forum
      announcement - Array - Liste der Announcements im Forum
      forumtitel - Titel des Forums
      forumid - ID des Forums
   
  */
  public function site_threads($args, $context)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $right = $hp->right;
    $get = $hp->get();
        
    if (!isset($args[1]) || !is_numeric($args[1]))
    {
      return "[No or Invalid Argument for forumID!]";
    } else
    {
      $forumid =  intval($args[1]);
      $page = 1;
      $entrysPerPage = 15;
      
      if( isset($get['page']) )
      {
        $page = intval($get['page']);
        if ($page < 1)
        {
          $page = 1;
        }
      }
      
      $vars = $context->getVars();
      
      if (isset($vars['entrysPerPage']))
      {
        $entrysPerPage = intval($vars['entrysPerPage']);
      }
      
      
      
      
      // Check for the Level
      // We can't do that over a Query because we manage our rights over config files
      $sql = 'SELECT ID, level, visible FROM `'.$dbprefix.'forums` WHERE ID = '.$forumid.';';
      $erg = $hp->mysqlquery($sql);
      
      $context->set('error', '');
      
      $row = mysql_fetch_object($erg);
      
      if ((mysql_num_rows($erg) == 0))
      {
        $context->set('error', 'NotFound');
      } else
      {
   
        $allowed = $right->isAllowed($row->level);      
        
        if (!$allowed && ($row->visible != "1"))
        {
          $context->set('error', 'NotRight');
          
        } else
        {
          // Load Content of the Forum
          
          $sql = 'SELECT t.ID, t.titel, t.userid, t.level, t.visible, t.closed, t.type, t.timestamp,
          IFNULL(t.lastedit, \'never\') AS lastedit, IFNULL(t.lastpost, \'never\') AS lastpost, u.user,
          t.passwort,
          IFNULL(t.lastpost, t.timestamp) AS lastaction,
          IFNULL((
            SELECT COUNT(*) FROM `'.$dbprefix.'posts` WHERE `threadid` = t.ID GROUP BY `threadid`
          ), 0) AS posts,
          IFNULL((
            SELECT userid FROM `'.$dbprefix.'posts` WHERE threadid IN
              (
               SELECT ID FROM `'.$dbprefix.'threads` WHERE `forumid` = t.forumid
              ) ORDER BY timestamp DESC LIMIT 1
          ), -1) AS lastpostUserID,
          IFNULL((
            SELECT user
            FROM `'.$dbprefix.'user` WHERE `ID` = lastpostUserID                            
          ), \'\') AS lastpostUser,
          (
            SELECT titel FROM `'.$dbprefix.'forums` WHERE ID = t.forumid
          ) AS forumtitel
          
          
          FROM `'.$dbprefix.'threads` t
          LEFT JOIN `'.$dbprefix.'user` u ON t.userid = u.ID WHERE `forumid` = '.$forumid.' ORDER BY lastaction DESC;';
         
          $erg = $hp->mysqlquery($sql);

          $threads = array();
          
          if (mysql_num_rows($erg) >0)
          {
            
            // Forumtitel
            // Save this here for later use
            $forumtitel = "";
            
            while ($row = mysql_fetch_object($erg))
            {
              $forumtitel = $row->forumtitel;
              
              $allowed = $right->isAllowed($row->level);
              if (($row->visible == 1) || $allowed)
              {
                $threads[$row->type][] = $row;
              }
            }
            
            
            
            $count = mysql_num_rows($erg);
            if (isset($threads[2]))
            {
              $count -= count($threads[2]);
            }
            
            $pages = ceil($count / $entrysPerPage);
            
            if ($pages < 1)
            {
              $pages = 1;
            }
            
            if ($page > $pages)
            {
              $page = $pages;
            }
            
       
            
            $data = array();
            
            // Sticky Threads
            if (isset($threads[1]))
            {
              foreach ($threads[1] as $k => $value)
              {
                $data[] = $value;  
              }
            }
            
            // Normal Threads
            if (isset($threads[0]))
            {
              foreach ($threads[0] as $k => $value)
              {
                $data[] = $value;  
              }
            }
            
            $content = array();
            
            $firstEntry = $entrysPerPage * ($page -1);
            $lastEntry = $firstEntry + $entrysPerPage;
            
            
            for($i = $firstEntry; $i < $lastEntry; $i++)
            {
              if (!isset($data[$i]))
              {
                continue;
              }
              
              
              $row = $data[$i];
              
              $value = array(
                'ID' => $row->ID,
                'titel' => $row->titel,
                'level' => $row->level,
                'passwort' => $row->passwort,
                'visible' => ($row->visible == '1'),
                'type' => $row->type,
                'user' => $row->user,
                'userid' => $row->userid,
                'posts' => intval($row->posts),
                'lastpost' => $row->lastpost,
                'lastedit' => $row->lastedit,
                'lastaction' => $row->lastaction,
                'lastpostUserID' => $row->lastpostUserID,
                'lastpostUser' => $row->lastpostUser,
                'timestamp' => $row->timestamp,
                'locked' => !$allowed || ($row->closed == 1),
                'sticky' => ($row->type == 1),
                'normal' => ($row->type == 0),
                'announcement' => false
              );
              
              $content[] = $value;
            }
            
           
            
            
            $announcement = array();
            
            if (isset($threads[2]))
            {
              foreach( $threads[2] as $k => $row )
              {
                $value = array(
                  'ID' => $row->ID,
                  'titel' => $row->titel,
                  'level' => $row->level,
                  'passwort' => $row->passwort,
                  'visible' => ($row->visible == '1'),
                  'type' => $row->type,
                  'user' => $row->user,
                  'userid' => $row->userid,
                  'posts' => intval($row->posts),
                  'lastpost' => $row->lastpost,
                  'lastedit' => $row->lastedit,
                  'lastaction' => $row->lastaction,
                  'lastpostUserID' => $row->lastpostUserID,
                  'lastpostUser' => $row->lastpostUser,
                  'timestamp' => $row->timestamp,
                  'locked' => !$allowed || ($row->closed == 1),
                  'sticky' => false,
                  'normal' => false,
                  'announcement' => true 
                );
                
                $announcement[] = $value;
              }
            }
            
            $context->set('announcement', $announcement);
            $context->set('content', $content);
            $context->set('page', $page);
            $context->set('pageCount', $pages);
            $context->set('forumtitel', $forumtitel);
            $context->set('forumid', $forumid);
            
          } else
          {
            $context->set('announcement', array());
            $context->set('content', array());
          }
        }
      }
    }
  }
  
  
  
}



?>