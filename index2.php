<?
if (isset($_GET['m']))
{
echo '<embed src="musik.swf">';
exit();
}
?>

<frameset rows="0%, 100%" frameborder="0" framespacing="0" >
<frame src="index2.php?m" scrolling="no" marginwidth="0" marginheight="15" topmargin="15" leftmargin="0" noresize>
<frame src="index.php" scrolling="yes" noresize>
