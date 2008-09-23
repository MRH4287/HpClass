<?
$sql = 'DROP TABLE `'.$dbpräfix.'download_kat`';
$result=$hp->mysqlquery($sql);
if (!$result)
{
$error->error(mysql_error(), "2");
}

$sql = 'ALTER TABLE `'.$dbpräfix.'download` DROP `kat`';
$result=$hp->mysqlquery($sql);
if (!$result)
{
$error->error(mysql_error(), "2");
}
echo "Download-Extention erfolgreich deinstalliert!";

?>
