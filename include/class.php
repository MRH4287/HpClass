<?php

class HP
{
  // �ffentliche Variablen:
  public    $site;
  public    $lang;
  public    $hp;
  public    $langclass;
  public    $error;
  public    $info;
  public    $firephp;
  public    $fp;
  public    $lbsites;
  public    $template;
  public    $xajaxF;
  public    $widgets;
  public    $subpages;
  public    $pluginloader;
  public    $right;
  public    $config;
  
  
  //Wegen Makro Fehlern:
  public    $game = null;
  
  
  // Gesch�tzte Variablen
  protected $outputg;
  protected $outputp;
  protected $host;
  protected $pr�fix;
  protected $user;
  protected $password;
  protected $db;
  protected $connection;
  protected $sitepath;
  protected $redirectlock;
  protected $restrict;
  protected $superadminonly;
  protected $pathtomysqlversion;
  protected $superadmin;
  protected $standardsite;
  protected $navigationIgnore;
  
  
  // Config Area;
  // ----------------------------------------------------------------
  function HP()
  {
    //Main
    $this->standardsite = "news"; // Wenn nicht durch Config ver�ndert!
    
    // Superadmins:
    $this->superadmin = array("admin", "mrh");
    
    // Siteckeckup
    $this->restrict       = array("login");
    $this->superadminonly = array("rights", "config", "dragdrop", "plugins", "test");
    
    // Seiten, die von der Navigation ausgenommen sind
    $this->navigationIgnore = array("upload", "download2", "lostpw", "mailagree",
                                     "usedpics", "profil", "pm", "subpage",
                                     "userpicchange", "vote", "admin", "anwaerter");
    
    
    //Mysql
    $this->pathtomysqlversion = "version/mysql.php";
    
    $this->hp = $this;
  }
  // ------------------------------------------------------------------
  // In diesem Bereich werden alle Variablen an die Klasse �bergeben
  // Die Set-Area
  //-------------------------------------SET---------------------------------------
  function setlang($langclass2)
  {
    $this->langclass = $langclass2;
    $this->lang = $this->langclass->getlang();
  }
  
  function setdata($host, $user,$password, $pr�fix, $db)
  {
    $this->host=$host;
    $this->password=$password;
    $this->user=$user;
    $this->pr�fix=$pr�fix;
    $this->db=$db;
  }
  
  function setinfo($info)
  {
    $this->info = $info;
  }
  
  function seterror($error)
  {
    $this->error = $error;
  }
  
  function settemplate($template)
  {
    $this->template = $template;
  }
  
  function setfirephp($firephp)
  {
    $this->firephp = $firephp;
    $this->fp = $firephp;
  }
  
  function setlbsites($lbsites)
  {
    $this->lbsites=$lbsites;
  }
  
  function setxajaxF($xajax)
  {
    $this->xajaxF = $xajax;
  }
  
  
  function setwidgets($widgets)
  {
    $this->widgets = $widgets;
  }
  
  function setsubpages($subpage)
  {
    $this->subpages = $subpage;
  }
  
  function setpluginloader($pluginloader)
  {
    $this->pluginloader = $pluginloader;
  }
  
  function setright($right)
  {
  
    $this->right = $right;
    
    if (file_exists("include/rights.php"))
    {
      include "include/rights.php";
      $right->registerArray($registed);
      $right->registerLevel($levels);
    
    }
  }
  
  function setconfig($config)
  {
    $this->config = $config;
    
    if (file_exists("include/config-default.php"))
    {
      include "include/config-default.php";
      $config->registerArray($configData);
    
    }
    
  }
  
  
  
  //  Ende set-Area
  
  // Die GET Area
  // In diesem Bereich werden Funktionen bestimmt, die Variablen an andere Funktionen liefern.
  //--------------------------------------GET------------------------------------------
  function get()
  {
    return $this->outputg;
  }
  
  function post()
  {
  
    return $this->outputp;
  }
  
  function site()
  {
    return $this->site;
  }
  
  function getpr�fix()
  {
    return $this->pr�fix;
  }
  
  
  function getlangclass()
  {
    return $this->langclass;
  }
  
  function getfirephp()
  {
    return $this->firephp;
  }
  
  function getsuperadmin()
  {
    return $this->superadmin;
  }
  
  function geterror()
  {
    return $this->error;
  }
  
  function getinfo()
  {
    return $this->info;
  }
  // Ende Get Area
  //-----------------------------------------MYSQL-----------------------------------------
  // Mysql Area:
  function connect()
  {
  
    if (!isset($this->host) or !isset($this->user) or !isset($this->password)) 
    {
      $this->error->error("No Database Data set!", "3");
    }
    
    $this->connection = mysql_connect($this->host,
    $this->user,$this->password)
    or $this->error->error($this->lang['userorpasswordwrong'], "3");
    $myerror = mysql_error();
    if ($myerror <> "")
    {
      $this->error->error("$myerror", "2");
    }
     
    mysql_select_db($this->db, $this->connection)
    or print $this->lang['nodb'];
    $myerror = mysql_error();
    if ($myerror <> "")
    {
      $this->error->error("$myerror", "2");
    }
    // L�schen aller DB Variablen
    // Verhindert sp�teres auslesen!
    $this->host = "";
    $this->user = "";
    $this->password = "";
    $this->db = ""; 
    
    
  }
  
  function mysqlquery($query)
  {
    $query = mysql_query($query);
    
    $myerror = mysql_error();
    if ($myerror <> "")
    {
        include 'include/config.php';
        if (isset($debug) && ($debug == true))
        { 
          $this->error->error("$myerror", "2");
          echo "<b>Mysql Error: $myerror</b> ";
        } else
        {
          $this->error->error("Fehlerhafte Abfrage", "2");
        }
    }
    
    return $query;
  }
  
  function escapestring($string)
  {
    return mysql_real_escape_string($string);
  }
  
  //-------------------------------------------ALLGEMEIN--------------------------------
  // Allgemeine Funktionen:
  
  function handelinput ($get, $post)
  {
    
    foreach ($get as $key=>$value) 
    {
      if (!is_array($get[$key]))
      {
      
      	$get[$key] = $this->stripScript(mysql_real_escape_string($value));
      	
      } else
      {
      
         foreach ($value as $key2=>$value2) 
         {
          if (!is_array($value2))
          {
          	$get[$key][$key2] = $this->stripScript(mysql_real_escape_string($value2));
         	}
         }
         
      }
      
    }
    
    foreach ($post as $key=>$value)
    {
      if (!is_array($post[$key]))
      {
        
      	$post[$key] = $this->stripScript(mysql_escape_string($value));
        	
      } else
      {
        
       foreach ($value as $key2=>$value2) 
       {
        if (!is_array($value2))
        {
        	$post[$key][$key2] = $this->stripScript(mysql_escape_string($value2));
        }
       }
        
      }
    }
    
    if (!isset($_SESSION['username']) || !isset($_SESSION['level']))
    {
      $_SESSION['level'] = 0;
    }
    
    
    
    if (isset($get))
    {
      foreach ($get as $key=>$value)
      {
        $this->outputg[$key]=$value;
      }
    } 
    
    
    if (isset($post))
    {
      foreach ($post as $key=>$value) 
      {
        $this->outputp[$key]=$value; 	
      }
    } 
    
    if (isset($get['lbsite']))
    {
      $vars = $get['vars'];
      
      $this->lbsites->load($get['lbsite'], $vars);
      exit;
    }
    
    if (isset($get['site']))
    {
      
      $site = $get['site'];
      $this->site=$site;
    } 
    
    if (isset($get['lang']))
    {
      $this->langclass->setlang($get['lang']);
      $this->setlang($this->langclass);
    }
    
    if (isset($get["login"]) && ($get['login'] == "n"))
    {
      $loginfail = $this->langclass->word("loginfail");
      $this->error->error($loginfail, "2");
      }
    
    if(isset($get['lchange']) and ($_SESSION['username'] == "mrh"))
    {
      $_SESSION['level'] = $get['lchange']; 
    }
    
  
  
  }
  
  
  function lostpassword($user)
  {
    $hp = $this;
    $dbpr�fix = $hp->getpr�fix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
    
    if (($_SERVER['HTTP_HOST'] == "localhost") or ($_SERVER['HTTP_HOST'] == "127.0.0.1"))
    {
      $local = true;
    }
    
    
    
    
    $code = $this->generateToken();
     
     
     //Ermittlung des Ablaufdatums
    $verfall = time() + 7200;
     
    $sql = "REPLACE INTO `$dbpr�fix"."token` (`user`, `token`, `verfall`) VALUES ('$user', '$code', '$verfall')";
    $erg = $hp->mysqlquery($sql);
    
     
    $mail['mailcomefrom']="admin@".$_SERVER['HTTP_HOST']; // Die Emailadresse, von der angezeigt wird, dass die E-Mail kommt
    $mail['mailbetreff']="Ihr Passwort"; // Der angezeigt  Betreff in der Registrations E-mail
    
    $mail['mailtext']="Bitte folgen Sie folgenden Link um ihr Passwort zur�ckzusetzten:\r\n";
    $mail['mailtext'].="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?site=lostpw&change=$code";
    // Der Text, der nach der Aktivierungsmai stehen soll.
    $mail['mailfooter']="\n \r Vielen dank f�r ihr interesse!";
    
    
     
    
    if (!$local)
    {
      
      foreach ($mail as $key=>$value) 
      {
      	$$key = $value;
      }
      $sql = "SELECT email FROM `$dbpr�fix"."user` WHERE `user` = '$user'";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
      
      
      $info->okn("Eine Best�tigungs E-Mail wurde an Sie geschickt.");
      //print_r($mail);
      mail($row->email, $mailbetreff, $mailtext.$mailfooter ,"from:$mailcomefrom");
      $site = new siteTemplate($this);
      $site->load("info");
      $site->set("info", "Eine E-Mail mit einer Best�tigung wurde an ihre E-Mail Adresse geschickt!");
      $site->display();
       
    } else
    {
      $site->load("info");
      $site->set("info", "Bitte gehen Sie auf folgende Seite:<br><a href=\"http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?site=lostpw&change=$code\">Passwort zur�cksetzten</a>");
      $site->display();
    
    }
     
     
  
  
  }
  
  
  function generateToken()
  {
    $password_length = 15;
    $generated_password = "";
    $valid_characters = "abcdefghijklmnopqrstuvwxyz0123456789";
    $i = 0;
    
    
    $chars_length = strlen($valid_characters) - 1;
    for($i = $password_length; $i--; )
    {
      $generated_password .= $valid_characters[mt_rand(0, $chars_length)];
    }
    
    return $generated_password;   
  }
  
  
  function checksite($site)
  {
    if ($site == "")
    {
      $site = $this->standardsite;
    }
    
    $invalide = array('/','/\/',':','.','\\');
    $site = str_replace($invalide,' ',$site);
    
    $ok = true;
    
    $restrict = $this->restrict;
    $onlysupadmin = $this->superadminonly;
    
    foreach ($restrict as $key=>$value)
    {
      if ($site == $value)
      {
        $ok = false;
      }
    }
    
    if (!isset($_SESSION['username']) or !in_array($_SESSION['username'], $this->superadmin)) 
    {
      if(in_array($site, $this->superadminonly)) 
      { 
        $ok = false; 
      }
    }
    
    if ($ok)
    {
      return $site;
    } else
    {
      return "404";
    }
    
  }
  
  function inc()
  {
    $site = $this->site;
  
    $site= $this->checksite($site);
     
    
    if (isset($this->sitepath[$site]) and ($this->sitepath[$site] != "") and (!in_array($site, $this->redirectlock)))
    {
      $sitesp = $this->sitepath[$site];
    } else
    {
      $sitesp = "sites";
    }
    
    if (file_exists("$sitesp/$site.php") and (is_file("$sitesp/$site.php")))
     {
        include "$sitesp/$site.php";
        
     } elseif (($content = $this->subpages->loadSite($site)) != false)
     {
        echo $content; 
        
     } else
      {
      
        if (isset($this->sitepath['404']) and ($this->sitepath['404'] != ""))
        {
          $sitesp = $this->sitepath['404'];
          include "$sitesp/404.php";
        } else
        {  
          if (file_exists("sites/404.php"))
          {
              include "sites/404.php";
              
          } else
          {
            echo $this->langclass->word('notfound');
          }
        }
      } 
  }
  
  
  function getright()
  {
  
    return $this->right->getright();
  
  }
  
  function PM($zu, $von, $Betreff, $text)
  { 
    $time = time();
    $datum = date('j.n.y');
    
    
    	$eintragintodb = "INSERT INTO `".$this->pr�fix."pm`
    (
    von, 
    datum, 
    zu, 
    text, 
    Betreff, 
    gelesen, 
    timestamp
    )
    VALUES
    ('$von', '$datum', '$zu', '$text', '$Betreff', '0', '$time')";
    $this->mysqlquery($eintragintodb);
  }
  
  
  // ---------------------------------------- Security --------------------------------
  
  function stripScript($text)
  {
    $reg = "(<[^>]*script[^>]*>)";
    $reg2 = "/onload=\"[^\"]*\"/";
    
    $new = preg_replace($reg, "$2", $text, -1);
    $new = preg_replace($reg2, "", $new, -1);
    
    return $new;
    
  
  }
  
  
  public function isSiteRestricted($site)
  {
      return (in_array($site, $this->restrict) 
            or in_array($site, $this->superadminonly)
            or in_array($site, $this->navigationIgnore));
   
  }
  
  
  
  // ------------------------------------------CONFIG----------------------------------
  // Config Area:
  // 4.1
  function getconfig()
  {
  
    return $this->config->getconfig();
  
  }
  
  
  
  
  
  //Handel Config Funkion
  //Als erleichterung f�r Sp�tere �nderungen.
  //4.2
  function handelconfig()
  {
    $config=$this->getconfig();
    
    // Superadmins
    // 4.2b
    
    
    $admins2 = explode(", ", $config['superadmin']);
    if ($admins2[0] != "")
    {
      $this->superadmin = @array_merge($this->superadmin, $admins2);
    }
    
    
    // StandardSeite
    //4.2b
    if ($config['standardsite'] != "")
    {
      $this->standardsite=$config['standardsite'];
    }
    
    
    // Redirectlock Config
    //4.2
    $redirectlock = explode(", ", $config['redirectlock']);
    
    if (!is_array($this->redirectlock))
    {
      $this->redirectlock = array();
    }
    $this->redirectlock = array_merge($this->redirectlock, $redirectlock);
  
  }
  
  //---------------------------------------------MODULE----------------------------
  
  // Module
  function addredirect($site, $path)
  {
    $this->sitepath[$site]=$path;
  }
  
  function addredirectlock($site)
  {
    $this->redirectlock[]=$site;
  }
  
  
  
} // Class Ende!
?>