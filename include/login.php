<?php
$dbpräfix = $hp->getpräfix();


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
