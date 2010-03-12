<?php
session_start();
class login
{
####################### Variablen #################################
private $user = array();
private $session_var = array( "user" => "login_username", "password" => "login_password" );
private $error_message = "";
private $adress;

####################### Methoden ##################################

function __construct($adress)
{

 if (file_exists($adress))
 {
 
 $this->adress = $adress;
 
 } else
 {
 $this->error("Datei wurde nicht gefunden: $adress", __LINE__);
 }



}


//------------------------------------------------------------------

function check()
{

 if (isset($_POST['login']))
 {

 $username = $_POST['user'];
 $password = $_POST['pass'];
  
 $this->user_check($username, $password);
 exit();


 } elseif (!isset($_SESSION[$this->session_var["user"]]))
 {
 $this->print_formular($this->adress);
 exit();
 }

 // Freie fahrt :)

}

//------------------------------------------------------------------

function print_formular($adress)
{

?>
<table width="100%" border="1">
  <tr>
    <td><p align="center"><b>Login</b>:</p>
    &nbsp;
    <form method="post" action="<?php echo $adress; ?>">
      <table width="100%" border="0">
        <tr>
          <td width="16%"><p>
            Benutzername:
          </p></td>
          <td width="84%"><input type="text" name="user" id="user" /></td>
        </tr>
        <tr>
          <td>Passwort: </td>
          <td><input type="password" name="pass" id="pass" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><p>&nbsp;</p>
            <button type="submit" name="login"> <img src="../images/ok.gif"> </button> <button type="reset"> <img src="../images/abort.gif"> </button></td>
        </tr>
      </table>
      <p>&nbsp;</p>
</form></td>
  </tr>
</table>

<?php

}

//------------------------------------------------------------------


function user_add($username, $password)
{

 if (!array_key_exists($username, $this->user))
 {
 $this->user[$username] = $password;
 } else
  {
   $this->error("Benutzername bereits vergeben", __LINE__);
  }

}

//------------------------------------------------------------------

function user_check($username, $password)
{

 if (array_key_exists($username, $this->user) and ($this->user[$username] == $password))
 {
  // User OK
  // Setzte Session
  
  $sesname_user     = $this->session_var["user"];
  $sesname_password = $this->session_var["password"];
  
  $_SESSION[$sesname_user]     = $username;
  $_SESSION[$sesname_password] = md5($password);
  
  echo '
  <script>
   window.location="'.$this->adress.'"
  </script>';
  
  
 } else
  {
  
  // Benutzer abgelehnt:
  
  $this->error("Benutzername oder Passwort falsch!<br>".$this->error_message."<br>Seite wird in 3 Sekunden neu geladen.".
  '<script>
  function reload()
  {
  window.location="'.$this->adress.'";
  }
   
   window.setTimeout("reload()",3000);
  </script>', false);
  
  
  
  }

}

//------------------------------------------------------------------

function set_var($array)
{

 if ((array_key_exists("user", $array)) and (array_key_exists("password", $array)))
 {
  $this->session_var = $array;
 } else
 {
 $this->error("Das Array hat nicht alle benötigten Werte, um als Config-Array verwendet werden zu können!", __LINE__);
 }

}

//------------------------------------------------------------------

function set_message($message)
{

$this->error_message = $message;

}


//------------------------------------------------------------------

function error($message, $line)
{

echo "<br><b>Systemfehler: $message";

if ($line != false)
{
echo " in Zeile: $line";
}

echo "<br>";

exit();

}

}
?>