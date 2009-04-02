<?
class lbsites
{
var $error;
var $hp;
var $lang;
var $info;
var $fp;

function sethp($hp)
{
$this->hp = $hp;
$this->error = $hp->geterror();
$this->lang = $hp->getlangclass();
$this->info = $hp->getinfo();
$this->fp = $hp->firephp;
}



function load($site, $vars)
{
ob_end_flush();
ob_start("ob");

$funktions = get_class_methods($this);
?>

<table width="100%" height="100%">
<tr valign="top">
<td height=100%>


<?
if (in_array("site_".$site, $funktions))
{
$site="site_".$site;
$this->$site($vars);
} else
{
$this->fp->error("ungültige LB-Site ($site)");
echo "Seite nicht gefunden!";
}
?>
</td>
</tr>
<tr>
<td>
<?

echo "<br><br><center><a href=\"#\" class=\"lbAction\" rel=\"deactivate\"><p align=\"right\"><img src=images/close.gif></p> </center>";
?>
</td>
</tr>
</table>
<?
}

function site_Test($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."user`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
echo $row->user."<br>";
}
echo " ö Ö ä Ä ü Ü ß ^ ` ' # + * , ; |";

}

function site_delnews($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['newsdel'])
{

$sql = "SELECT * FROM `$dbpräfix"."news` WHERE `ID` = '$vars';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<p id="highlight">Möchten Sie die Newsmeldung wirklich löschen?</p>
<table>
<tr valign="bottom">
<td>
<form method="POST" action="index.php?site=admin">
  <p><input type="hidden" name="newsiddel" size="3" value="<?=$vars?>"><input type="submit" value="Loeschen" name="newsdel"></form>
</td>
<td>
<form action="" methode="POST"><input  type="submit" value="Nein"> </p>
  </form>
</td>
</tr>
</table>
<b>ID:</b> <?=$row->ID?><br>
<b>Newstitel:</b> <?=$row->titel?><br>
<b>Ersteller:</b> <?=$row->ersteller?><br>
<b>
<?
} else
{
echo $lang->word('noright');
}
}



}

// Output buffering, da sonst alle Sonderzeichen nicht richtig dargestellt werden!
function ob($buffer)
{
$text = $buffer;
$text = str_replace("ü", "&uuml;", $text);
$text = str_replace("Ü", "&Uuml;", $text);
$text = str_replace("ö", "&ouml;", $text);
$text = str_replace("Ö", "&Ouml;", $text);
$text = str_replace("ä", "&auml;", $text);
$text = str_replace("Ä", "&Auml;", $text);
$text = str_replace("ß", "&szlig;", $text);

return $text;
}


?>