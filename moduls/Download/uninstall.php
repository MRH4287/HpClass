<?
$sql = 'DROP TABLE `'.$dbpr�fix.'download_kat`';
$result=$hp->mysqlquery($sql);
if (!$result)
{
$error->error(mysql_error(), "2");
}

$sql = 'ALTER TABLE `'.$dbpr�fix.'download` DROP `kat`';
$result=$hp->mysqlquery($sql);
if (!$result)
{
$error->error(mysql_error(), "2");
}
echo "Download-Extention erfolgreich deinstalliert!";

?>
