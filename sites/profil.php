<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();


  if (!isset($_SESSION['username'])) {
    $error->error($lang->word("error-login-profil") ,"2");
  } else
  {
    if (!isset ($post['pwändern']) and !isset($post['go']) and !isset($post['pwneu']))
    {
      $abfrage = "SELECT * FROM ".$dbprefix."user WHERE `user` = '".$_SESSION['username']."'";
      $ergebnis = $hp->mysqlquery($abfrage);
    
      
      $row = mysql_fetch_object($ergebnis);
      
      $site = new siteTemplate($hp);
      $site->load("profil");
      
      $data = array(
        "username" => $_SESSION['username'],
        "ID" => $row->ID,
        "name" => $row->name,
        "nachname" => $row->nachname,
        "alter" => $row->alter,
        "geburtstag" => $row->geburtstag,
        "wohnort" => $row->wohnort,
        "tel" => $row->tel,
        "email" => $row->email,
        "cpu" => $row->cpu,
        "ram" => $row->ram,
        "graka" => $row->graka,
        "hdd" => $row->hdd,
        "clan" => $row->clan,
        "clantag" => $row->clantag,
        "clanhomepage" => $row->clanhomepage,
        "clanhistory" => $row->clanhistory
                 
      );
      
      $site->setArray($data);
      $site->display();
      
     
    } elseif (isset ($post['pwändern'])) 
    {
      
      $site = new siteTemplate($hp);
      $site->load("profil_changepw");
      $site->display();
      
    } elseif (isset ($post['pwneu'])) 
    {

      

      $site = new siteTemplate($hp);
      $site->load("info");

      $sql = "SELECT * FROM `".$dbprefix."user` WHERE `user` = '".$_SESSION['username']."';";
      $erg = $hp->mysqlquery($sql);
      
      if (mysql_num_rows($erg) > 0)
      {
        
        $row = mysql_fetch_object($erg);
        
        $passwort=$post['passwortalt'];
        $passwortneu=md5("pw_".$post['passwort']);
        $passwortneu2=md5("pw_".$post['passwort2']);
        $passwortalt = md5("pw_".$passwort);
        $passwortalt2 = md5("pw_".$passwort.$row->ID);
          
        if ((($passwortalt == $row->pass) or ($passwortalt2 == $row->pass)) and ($passwortneu == $passwortneu2))
        {
          $eingabe = "UPDATE `".$dbprefix."user` SET `pass` = '$passwortneu' WHERE `user` = '".$_SESSION['username']."';";
          $ergebnis = $hp->mysqlquery($eingabe);
          $site->set("info", $lang->word('ok_profil')."!<br><a href=index.php?site=profil>".$lang->word('back')."</a>");
        } else 
        { 
          $site->set("info", $lang->word('pwwrong_profil')."!<br><a href=index.php?site=profil>".$lang->word('back')."</a>"); 
        }
     } else
     {
      $site->set("info", "Fehler");
     }
      $site->display();
      
    } elseif (isset($post['go'])) 
    {
      $site = new siteTemplate($hp);
      $site->load("info");

      $ignore = array ("hp", "info", "error", "hp", "config", "dbprefix", "right", "level");
      foreach ($post as $key=>$value) 
      {
        $value = str_replace('<',"&lt;" ,$value);
        $value = str_replace('\'',"\"" ,$value);// , 

        // Leicht Unsicher, da aber alle Variablen, die hier ankommen eh gestrippt werden, ist das irrelevant
        if (!in_array($key, $ignore))
        {
	         $$key = "'".$value."'";
	      }
      }
      
      if ((isset($name)) and (isset($nachname)) and (isset($wohnort))) 
      {

        $eingabe = "UPDATE `".$dbprefix."user` SET `name` = $name, `nachname` = $nachname, `alter` = $alter, `geburtstag` = $geburtstag, `wohnort` = $wohnort, `cpu` = $cpu,".
        " `ram` = $ram, `graka` = $graka, `hdd` = $hdd, `clan` = $clan, `clantag` = $clantag  WHERE `user` = '".$_SESSION['username']."';";

        $ergebnis2 = $hp->mysqlquery($eingabe);

        $eingabe = "UPDATE `".$dbprefix."user` SET `clanhomepage` = $clanhomepage, `clanhistory` = $clanhistory, `tel` = $tel  WHERE `user` = '".$_SESSION['username']."';";

        $ergebnis = $hp->mysqlquery($eingabe);
        
        $site->set("info", $lang->word('ok_profil')."!<br><a href=index.php>".$lang->word('back')."</a>");  
      } else
      {
        $site->set("info", $lang->word('notallfields'));
      }
      $site->display();
    }


  }

 ?> 