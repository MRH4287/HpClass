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

$pluginloader = $hp->pluginloader;


// Diese Seite sollte nicht ohne SuperAdmin Rechte zu öffnen sein,
// Jedoch prüfe ich das lieber schnell nach

  if (isset($_SESSION['username']) and in_array($_SESSION['username'], $hp->getsuperadmin()))
  {
  
    $plugins = $pluginloader->plugins;
  
  
  
     ?>
  
  
    <div class="pluginContainer">

    <table border="0" width="100%">

	<tr class="pluginHeadline">
		<td>Name</td>
		<td>Version</td>
		<td width="10%" align="center">Enabled</td>
	</tr>

<?php

foreach ($plugins as $name=>$data)
{

 ?>

	<tr class="pluginLine">
		<td>
      <div class="pluginName"><?php echo $data["o"]->name; ?></div>
      <div class="pluginInfo">
 
      </div>
    </td>
		<td>
      <div class="pluginVersion"><?php echo $data["o"]->version; ?></div>
    </td>
				<td>
      <div class="pluginData">
      <?php 
          if ($data["o"]->lock)
          {
       ?><img src="./images/lock.gif" alt="Gesperrt"></div><?php   
          } elseif ($data["enabled"]) {
      
       ?><img src="./images/on.gif" alt="ON"></div><?php
                                
           } else { 
           
       ?><img src="./images/off.gif" alt="OFF"></div><?php
           } 
           
           ?>
    </td>
	</tr>
	
<?php

 }

 ?>


</table>
<br />
<hr />
<font size="2px"><b>Info:</b> Plugins, die mit einem <img src="./images/lock.gif" alt="lock" width="12" height="12"> makiert sind,
können nicht geändert werden.</font>



</div>

  
  
  
       <?php
  
  
  
  } else
  {
  // Hier scheint wohl jemand die class.php geändert zu haben ...
  // Da diese Seite jedoch Kritisch ist, kann das nicht erlaubt werden!
  echo "<b>Fehler:<b /> Um diese Seite zu betreten, müssen Sie SuperAdmin sein!";
  }




?>