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

if ($right[$level]["usedpics"])
{
	$template = new siteTemplate($hp);
	$template->load('usedpics');
	

	$pics = array();
	
	$sql = "SELECT * FROM `$dbprefix"."usedpics`";
	$erg = $hp->mysqlquery($sql);
	while ($row = mysql_fetch_object($erg))
	{
			
		$breite=$row->width;
		$hoehe=$row->height;
		
		$neueHoehe=100;
		$neueBreite=intval($breite*$neueHoehe/$hoehe);
		
		$data = array(
			'ID' => $row->ID,
			'width' => $neueBreite,
			'height' => $neueHoehe
		);
		
		$pics[] = $data;
		
	}
	
	$template->set('pics', $pics);
	
	$template->display();
	
} else
{
	$site = new siteTemplate($hp);
    $site->right("usedpics");
    $site->display();
}

?>