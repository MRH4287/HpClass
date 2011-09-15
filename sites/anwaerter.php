<?php

// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();

$local = false;
if (($_SERVER['HTTP_HOST'] == "localhost") or ($_SERVER['HTTP_HOST'] == "127.0.0.1"))
{
  $local = true;
}

  if (!isset ($_SESSION['username'])) 
  {
    $error->error($lang->word('noright2'),"3");
  } else
  {

    if ((!$right[$level]['useragree']) and ((!$right[$level]['userdisagree'])))
    {
      $error->error($lang->word('noright2'),"2");
    } else
    {
      if (!$right[$level]['useragree'] and isset($get['register']))
      {
        $error->error($lang->word('kberechacanwae'),"2");

      } elseif (isset($get['register']))
      {
        $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter WHERE `user`= '".$get['register']."'";
        $ergebnis = $hp->mysqlquery($abfrage);
    
        $row = mysql_fetch_object($ergebnis);
        
        $user=$row->user;
        $passwort123=$row->pass;
        $name=$row->name;
        $nachname=$row->nachname;
        $datum=$row->datum;
        $email = $row->email;
        $tel = $row->tel;
        $wohnort = $row->wohnort;
        $geschlecht = $row->geschlecht;
        
   
  
        $eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['register']."'";
        $eintragen1 = $hp->mysqlquery($eintrag);
        

        $eintrag = "INSERT INTO `".$dbpräfix."user`
        (`user`, `pass`, `name`, `nachname`, `datum`, `level`, `email`, `tel`, `wohnort`, `geschlecht`)
        VALUES
        ('$user', '$passwort123', '$name', '$nachname', '$datum', '1', '$email', '$tel', '$wohnort', '$geschlecht')";
        $eintragen2 = $hp->mysqlquery($eintrag);

        if (!$local)
        {
          //Registrierung:
          $mail['mailcomefrom']="admin@".$_SERVER['HTTP_HOST']; // Die Emailadresse, von der angezeigt wird, dass die E-Mail kommt
          $mail['mailbetreff']="Die Registrierung auf ".$_SERVER['HTTP_HOST']; // Der angezeigt  Betreff in der Registrations E-mail
          $mail['pageadress']= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; // Der Pfad zu ihrer Homepage
          $mail['mailtext']= "Ihre Registrierung auf unserer Seite wurde abgeschlossen.\n \r";
          // Der Text, der nach der Aktivierungsmai stehen soll.
          $mail['mailfooter']="\n \r Vielen Dank für ihr Interesse!";
          
          mail($email, $mail['mailbetreff'], $mail['mailtext'].$mail['mailfooter'] ,"from:".$mail['mailcomefrom']);
     
        }

        if ($eintragen1 == true and $eintragen2 == true)
        {
          $info->okn($lang->word('postok'));
        } else { $error->error("Fehler: ".mysql_error(),"2"); }
      }


      if (!$right[$level]['userdisagree'] and isset($get['delet']))
      {
        $error->error($lang->word('kberechdeanwae'),"2");

      } elseif (isset($get['delet']))
      {
        $eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['delet']."'";
        $eintragen = $hp->mysqlquery($eintrag);
        if ($eintragen == true)
        {
          $info->okn($lang->word('delok'));
        } else 
        { 
          $error->error("Fehler: ".mysql_error(),"2");
        }
      }


      $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
      $ergebnis = $hp->mysqlquery($abfrage);
    
      $site = new siteTemplate($hp);
      $site->load("anwaerter");
    
      $content = "";
      while($row = mysql_fetch_object($ergebnis))
      {
        $data = array(
          "user" => $row->user,
          "name" => $row->name,
          "nachname" => $row->nachname,
          "datum" => $row->datum,
          "text" => $row->text
        );

        $content .= $site->getNode("Line", $data);

      } 
      $site->set("Content", $content);
      
      $site->display();

    }
  }
?>
