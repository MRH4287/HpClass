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



echo "<b>Dies ist eine Testseite!</b><br>Solltet ihr sie finden, ignoriert sie ^^<br><br><hr>";

print_r($_SESSION);

$error->error("TEST", "2");
$info->info("123");
$info->okn("123");

?>
<button rel="lightbox" ><object width="425" height="344" ><param name="movie" value="http://www.youtube.com/v/X3dPJeVcOi4&hl=en&fs=1"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.youtube.com/v/X3dPJeVcOi4&hl=en&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" width="425" height="344"></embed></object></button>
