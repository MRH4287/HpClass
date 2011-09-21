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
$info = $hp->getinfo();

  $site = new siteTemplate($hp);
  $site->load("info");
  $site->set("info", "Diese Seite wird aufgerufen, wenn sich ein neuer Benutzer anmeldet.<br>Normalerweiße enthält diese Seite keine Informationen.");

  if (isset($get['user']) and isset($get['code']))
  {
    $codeg = $get['code'];

    $abfrage = "SELECT * FROM ".$dbprefix."anwaerter WHERE `user`= '".$get['user']."'";
    $ergebnis = $hp->mysqlquery($abfrage);
    
    $row = mysql_fetch_object($ergebnis);

    $user="$row->user";
    $passwort123="$row->pass";
    $name="$row->name";
    $nachname="$row->nachname";
    $email="$row->email";
    $datum="$row->datum"; 
    $wohnort = "$row->wohnort";
    $geschlecht = "$row->geschlecht";
    $tel = "$row->tel";
   
    $code = $row->code;

    if ($codeg != $code)
    {
      $site->set("info", "<br>Der angegebene Sicherheitscode stimmt nicht überein!<br>Falls dies ein Systemfehler ist, wenden Sie sich bitte an einen der Administratoren!");
    } else
    {
      if (isset($user) and ($user != ""))
      {
        $eintrag = "DELETE FROM `".$dbprefix."anwaerter` WHERE `user` = '".$get['user']."'";
        $eintragen1 = $hp->mysqlquery($eintrag);

        $eintrag = "INSERT INTO `".$dbprefix."user`
        (user, pass, name, nachname, datum, level, email, wohnort, tel, geschlecht)
        VALUES
        ('$user', '$passwort123', '$name', '$nachname', '$datum', '1', '$email', '$wohnort',  '$tel', '$geschlecht')";
        $eintragen2 = $hp->mysqlquery($eintrag);

        if (($eintragen1 == true) and ($eintragen2 == true))
        {
           $site->set("info", "<br>Vielen Dank,<br>Ihre Registration wurde erfolgreich abgeschlossen!");

          foreach ($hp->superadmin as $key=>$superadmin) 
          {
	           $hp->PM($superadmin, "System", "Neuer User", "Ein neuer User:<br>$user", $datum, $dbprefix);
          }

        } else 
        { 
        $site->set("info", "Fehler: <br>Melden Sie sich bitte umgehen bei dem zuständigem Administrator! <br>".mysql_error());
        }

      } else
      {
       $site->set("info", "Ihre E-Mailadresse wurde bereits verifiziert!");
      }

    }
    
  }
  $site->display();
?>