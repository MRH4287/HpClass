<?php
$dbpr�fix = $hp->getpr�fix();


 if (!isset($_SESSION['username'])) 
 { 
    $site = new siteTemplate($hp);
    $site->load("login");
    $template['login'] = $site->get("Login");
    
 } else { 

    $site = new siteTemplate($hp);
    $site->load("login");
    
 
   $template['login'] = $site->get("Links");


}

?>
