<?php
// Das ist eine Srandartdatei, diese Kopieren um dieses System zu ;enutzen 
$Name = "Test-Script";
$Run = "autorun.php";
$description = "Das ist ein Test Script!";
$date = date('j').".".date('n').".".date('y');


// Hier ist der Part, indem ein Templatescript geschrieben wird oder nicht.





// Ab Hier nichts mehr �ndern!

$sql="INSERT INTO `".$dbpr�fix."modul` (
`Name` ,
`run` ,
`description` ,
`date` ,
`path`,
`active`
)
VALUES (
'$Name', '$Run', '$description', '$date', '$value', '1'
);";

$result=$hp->mysqlquery($sql);
if (!$result)
{
$error->error(mysql_error(), "2");
}



echo "Das Modul $Name wurde erfolgreich installiert!";
?>
