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
$funktions = get_class_methods($this);
?>



<div style="width:100%; height=90px">
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
</div>
<div style="width:100%; height=10%">
<?

echo "<br><br><center><a href=\"#\" class=\"lbAction\" rel=\"deactivate\">Close</center>";
?>
</div>
<?
}

function site_Test($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."user`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
echo $row->user."<br>";
}


}



}
?>