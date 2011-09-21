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
$config = $hp->getconfig();

$useMail = $config["user_mailagree"];


$local = (($_SERVER['HTTP_HOST'] == "localhost") or ($_SERVER['HTTP_HOST'] == "127.0.0.1"));

//require_once( './include/captcha.class.php' );
if (!isset($post['register'])) 
{
  $site = new siteTemplate($hp);
  $site->load("register");
  $site->display();


} else
{
  if ($post['passwort12'] == $post['passwort212'])
  {

    $ok=true;
    if (!isset($post['passwort12']) or !isset($post['username']) or !isset($post['name']) or (strlen($post['name']) < 1) or
      !isset($post['nachname']) or (strlen($post['nachname']) < 1) or !isset($post['wohnort'])
       or !isset($post['geschlecht']) or !isset($post['tel'])) 
       { 
          $error->error("Geben die alle Werte an!<br><a href=index.php?site=register>zurück</a><br>", "1");
          $ok=false;
       } 

    $passwort12=$post['passwort12'];
    $user=$post['username'];
    $name=$post['name'];
    $nachname=$post['nachname'];
    $email = $post['email'];


    $passwort12 = str_replace('<',"&lt;" ,$passwort12);
    $user = str_replace('<',"&lt;" ,$user);
    $user = str_replace(' ',"_" ,$user);
    $name = str_replace('<',"&lt;" ,$name);
    $name = str_replace(' ',"_" ,$name);
    $nachname = str_replace('<',"&lt;" ,$nachname);
    $email = str_replace('<',"&lt;" ,$email);
    $datum = date('j').".".date('n').".".date('y');
    // [ADD] 21.04.08 ändern der Registrieren page! 
    $tel = $post['tel'];
    $wohnort = $post['wohnort'];
    $geschlecht = $post['geschlecht'];
    $tel = str_replace('<',"&lt;" ,$nachname);
    $wohnort = str_replace('<',"&lt;" ,$wohnort);
    $geschlecht = str_replace('<',"&lt;" ,$geschlecht);



    if (strlen($user) <= 3)
    {
      $error->error("Der Benutzername muss mindestens 4 Zeichen haben!<br>", "1");
      $ok = false;
    } elseif (strlen($passwort12) <= 3)
    {
      $error->error("Das Passwort muss mindestens 4 Zeichen haben!<br>", "1");
      $ok = false;
    }



/*if ($captcha) {

	$c	= new Captcha( $post[ 'id' ] );
	$c->setPrivateKey( 'spambotresistant' );
	
	if( !$c->isCaptcha( $post[ 'captcha' ] ) ) {

		echo "Das Captcha wurde falsch angegeben!<br><a href=index.php?site=register>zurück</a>";
		$ok = false;

	} 
} */

    $abfrage = "SELECT * FROM ".$dbprefix."user";
    $ergebnis = $hp->mysqlquery($abfrage);
        
    while($row = mysql_fetch_object($ergebnis))
       {
        if ((strtolower($user) == strtolower("$row->user")) or (strtolower($email) == strtolower("$row->email")))
        {  
          $error->error("Benutzername oder Email bereits vorhanden!<br>","1");
          $ok=false;
        }
       }
    
    $abfrage = "SELECT * FROM ".$dbprefix."anwaerter";
    $ergebnis = $hp->mysqlquery($abfrage);
        
    while($row = mysql_fetch_object($ergebnis))
       {
        if (strtolower($user) == strtolower("$row->user")  or (strtolower($email) == strtolower("$row->email")))
        {
          $error->error("Benutzername bereits vorhanden!<br>","1");
          $ok=false;
        }
       }
  
    if ($ok == true) 
    {  
      $text = "Feature Disabled";
  
      $passwort12 = md5("pw_".$passwort12);
      
      $password_length = 5;
      $generated_password = "";
      $valid_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $i = 0;
  
      for ($a = 0; $a < 5; $a++) 
      {
       $chars_length = strlen($valid_characters) - 1;
       for($i = $password_length; $i--; ) 
       {
        $generated_password .= $valid_characters[mt_rand(0, $chars_length)];
       }
      	if ($a != 4)
      	{
          $generated_password .= "-";
        }
      	
      }
      $code = $generated_password;
  
  
  
      $eintrag = "INSERT INTO `".$dbprefix."anwaerter`
      (user, pass, name, nachname, datum, text, email, geschlecht, wohnort, tel, code)
      VALUES
      ('$user', '$passwort12', '$name', '$nachname', '$datum', '$text', '$email', '$geschlecht', '$wohnort', '$tel', '$code')";
      $eintragen = $hp->mysqlquery($eintrag);

      $site = new siteTemplate($hp);
      $site->load("info");
      
      if ($useMail)
      {
        $msg = "Antrag erfolgreich gestellt!<br>Eine Bestätigungs E-Mail ist zu Ihnen Unterwegs!<br><a href=index.php>zurück</a>";
      } else
      {
        $msg = "Antrag erfolgreich gestellt!<br>Bitte warten Sie, bis Sie freigeschaltet werden.<br><a href=index.php>zurück</a>"; 
      }
  
      if ($useMail)
      {
        if (!$local)
        {
          // Laden der Template Daten:
          $site = new siteTemplate($hp);
          $site->load("email");
          
          $site->set("HTTP_HOST", $_SERVER['HTTP_HOST']);
          $site->set("PHP_SELF", $_SERVER['PHP_SELF']);
          
          $site->get();
          $mail = $site->getVars();
          
          $site->get("Register-Mailagree");
          $mail = array_merge($mail, $site->getVars());

        
           mail($email, $mail['mailbetreff'], $mail['mailtext'].$mail['pageadress']."?site=mailagree&user=$user&code=$code ".$mail['mailfooter'] ,"from:".$mail['mailcomefrom']);
        } else
        {
          $msg .= "<br>Diese Funktion ist auf Localhost deaktiviert!<br>gehen Sie bitte auf folgende Seite:<br>";
          $msg .= '<a href='.$mail['pageadress']."?site=mailagree&user=$user&code=$code>".$mail['pageadress']."?site=mailagree&user=$user&code=$code</a>";
         
        }
      }
      
      
      $site->set("info",$msg);
      $site->display();

    }
  } else 
  { 
    $error->error("Die Passwörter sind nicht identisch!<br><a href=index.php?site=register>zurück</a>","1"); 
  }
}

?>
