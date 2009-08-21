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




if (($_SERVER['HTTP_HOST'] == "localhost") or ($_SERVER['HTTP_HOST'] == "127.0.0.1"))
{
$local = true;
}


//Registrierung:
$mail['mailcomefrom']="admin@".$_SERVER['HTTP_HOST']; // Die Emailadresse, von der angezeigt wird, dass die E-Mail kommt
$mail['mailbetreff']="Die Registrierung auf ".$_SERVER['HTTP_HOST']; // Der angezeigt  Betreff in der Registrations E-mail
$mail['pageadress']= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; // Der Pfad zu ihrer Homepage
$mail['mailregistertext']="Aktivieren"; // Der Text, den der Link zur Registrationsseite haben soll
// Der Text, der in der Aktivierungsemail stehen soll. Der Aktivierungslink, wird unten angefügt.
$mail['mailtext']="Herzich willkommen bei uns! \n \r Drücken sie unten stehenden Link, um die Registrierung abzuschließen:\n \r";
// Der Text, der nach der Aktivierungsmai stehen soll.
$mail['mailfooter']="\n \r Vielen dank für ihr interesse!";


//require_once( './include/captcha.class.php' );
if (!isset($post['register'])) {
?>
<form method="POST" action="index.php?site=register">
  <table border="0" width="522">
    <tr>
      <td colspan="2" width="512">
        <p align="center"><font size="5">Anmeldung:</font></td>
    </tr>
    <tr>
      <td width="150"></td>
      <td width="365"></td>
    </tr>
    <tr>
      <td width="150">Username: *+</td>
      <td width="365"><input type="text" name="username" size="20" maxlength="20"></td>
    </tr>
    <tr>
      <td width="150">Passwort: *+</td>
      <td width="365"><input type="password" name="passwort12" size="20" maxlength="20"></td>
    </tr>
    <tr>
      <td width="150">Passwort wiederholen:</td>
      <td width="365"><input type="password" name="passwort212" size="20" maxlength="20"></td>
    </tr>
    <tr>
      <td width="150">Vorname: *+</td>
      <td width="365"><input type="text" name="name" size="20" maxlength="20"></td>
    </tr>
    <tr>
      <td width="150">Nachname: *+</td>
      <td width="365"><input type="text" name="nachname" size="20" maxlength="20"></td>
    </tr>
    <tr>
      <td width="150">E-Mail: *</td>
      <td width="365"><input type="text" name="email" size="20"></td>
    </tr>
    
    <?php  // [ADD] 21.04.08 ändern der Registrieren page!  ?>
      <tr>
      <td width="150">Wohnort: *+</td>
      <td width="365"><input type="text" name="wohnort" size="20"  maxlength="20"></td>
    </tr>
       <tr>
      <td width="150">Tel. oder Handy: +</td>
      <td width="365"><input type="text" name="tel" size="20"  maxlength="20"></td>
    </tr>  
      <tr>
      <td width="150">Geschlecht: *</td>
      <td width="365"><input type="radio" value="M" checked name="geschlecht">Mänlich / <input type="radio" name="geschlecht" value="W"> Weiblich</td>
    </tr> 
    
        <?php /* ?>
    <tr>
      <td width="150">Bewerbungstext: *</td>
      <td width="365" rowspan="2"><textarea rows="2" name="btext" cols="44"></textarea></td>
    </tr>
    <? */ ?>
    <tr>
    </tr>
    <?php

    if ($captcha) {  ?>
    <tr>
    <td width="150"></td>
    <td width="365" rowspan="2"><?php

$c	= new Captcha();
echo '<img src="./include/captcha.php?id=' . $c->getId() . '" alt="captcha" />';
echo '<input type="hidden" name="id" value="' . $c->getId() . '" />';

?>


<input type="text" name="captcha" /><br /></td>

    </tr>
    <?php } ?>
    <tr>
      <td width="136"></td>
    </tr>
    <tr>
      <td width="506" colspan="2"></td>
    </tr>
    <tr>
      <td width="506" colspan="2">
        <p align="center"><input type="submit" value="Jetzt registrieren!" name="register"><br><br>
        <font size="2">* Pflichtfeld<br>+  Maximal 20 Zeichen</font></td>
    </tr>
  </table>
</form>
<?php } else
{
if ($post['passwort12'] == $post['passwort212'])
{
$passwort12=$post['passwort12'];
$user=$post['username'];
$name=$post['name'];
$nachname=$post['nachname'];
$text=$post['btext'];
$email = $post['email'];
$passwort12 = str_replace('<',"&lt;" ,$passwort12);
$user = str_replace('<',"&lt;" ,$user);
$name = str_replace('<',"&lt;" ,$name);
$name = str_replace(' ',"_" ,$name);
$nachname = str_replace('<',"&lt;" ,$nachname);
$text = str_replace('<',"&lt;" ,$text);
$email = str_replace('<',"&lt;" ,$email);
$datum = date('j').".".date('n').".".date('y');
// [ADD] 21.04.08 ändern der Registrieren page! 
$tel = $post['tel'];
$wohnort = $post['wohnort'];
$geschlecht = $post['geschlecht'];
$tel = str_replace('<',"&lt;" ,$nachname);
$wohnort = str_replace('<',"&lt;" ,$wohnort);
$geschlecht = str_replace('<',"&lt;" ,$geschlecht);


$ok=true;
if (!isset($post['passwort12']) or !isset($post['username']) or !isset($post['name']) or !isset($post['nachname'])  or !isset($datum) or !isset($wohnort) or !isset($geschlecht)) { // Entfernt: or !isset($post['btext'])
echo "Geben die alle Werte an!<br><a href=index.php?site=register>zurück</a><br>";
$ok=false;
}



if ($captcha) {

	$c	= new Captcha( $post[ 'id' ] );
	$c->setPrivateKey( 'spambotresistant' );
	
	if( !$c->isCaptcha( $post[ 'captcha' ] ) ) {

		echo "Das Captcha wurde falsch angegeben!<br><a href=index.php?site=register>zurück</a>";
		$ok = false;

	} 
}
$abfrage = "SELECT * FROM ".$dbpräfix."user";
$ergebnis = $hp->mysqlquery($abfrage);
    
while($row = mysql_fetch_object($ergebnis))
   {
   if (strtolower($user) == strtolower("$row->user"))
   {
   echo "Benutzername bereits vorhanden!<br>";
   $ok=false;
   }
   }

$abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
$ergebnis = $hp->mysqlquery($abfrage);
    
while($row = mysql_fetch_object($ergebnis))
   {
   if (strtolower($user) == strtolower("$row->user"))
   {
   echo "Benutzername bereits vorhanden!<br>";
   $ok=false;
   }
   }

if ($ok == true) {  
$text = "Feature Disabled";

$passwort12 = md5($passwort12);

$password_length = 5;
$generated_password = "";
$valid_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$i = 0;

for ($a = 0; $a < 5; $a++) {
 $chars_length = strlen($valid_characters) - 1;
 for($i = $password_length; $i--; ) {
  $generated_password .= $valid_characters[mt_rand(0, $chars_length)];
 }
	if ($a != 4)
	{
  $generated_password .= "-";
  }
	
}
 $code = $generated_password;



$eintrag = "INSERT INTO `".$dbpräfix."anwaerter`
(user, pass, name, nachname, datum, text, email, geschlecht, wohnort, tel, code)
VALUES
('$user', '$passwort12', '$name', '$nachname', '$datum', '$text', '$email', '$geschlecht', '$wohnort', '$tel', '$code')";
$eintragen = $hp->mysqlquery($eintrag);
if ($eintragen== true) { 
echo "Antrag erfolgreich gestellt!<br>Eine Bestätigungs E-Mail ist zu ihnen Unterwegs!<br><a href=index.php>zurück</a>";


//mail("$email", "Bewerbung zur ...", "Herzich willkommen bei uns! \nDrücken sie unten stehenden Link,".
//" um die Registrierung abzuschließen: \n<a href=\"".$pageadress."index.php?site=mailagree&user=$user>".
//"Aktivieren</a>");

if (!$local)
{

foreach ($mail as $key=>$value) {
	$$key = $value;
}



 mail($email, $mailbetreff, $htmlmailtext.$mailtext.$pageadress."?site=mailagree&user=$user&code=$code ".$mailfooter ,"from:$mailcomefrom");
} else
{
echo "<br>Diese Funktion ist auf Localhost deaktiviert!<br>gehen Sie bitte auf folgende Seite:<br>";
echo '<a href='.$pageadress."?site=mailagree&user=$user&code=$code>$pageadress?site=mailagree&user=$user&code=$code</a>";

}

?>



<?php
}
else{echo "Fehler: ".mysql_error(); }
}
} else { echo "Die Passwörter sind nicht identisch!<br><a href=index.php?site=register>zurück</a>"; }
}

?>
