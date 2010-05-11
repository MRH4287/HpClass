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

if (!isset($_SESSION["file_info"]))
{
$_SESSION["file_info"] = array();
}

?>
<link href="include/usedpics/swfupload/swfupload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="include/usedpics/swfupload/swfupload.js"></script>
<script type="text/javascript" src="include/usedpics/swfupload/handlers.js"></script>
<script type="text/javascript">
		var swfu;
			
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "include/usedpics/pics_upload.php",
				post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},

				// File Upload Settings
				file_size_limit : "2 MB",	// 2MB
				file_types : "*.jpg; *.png; *.gif",
				file_types_description : "Gültiges Bild-Format",
				file_upload_limit : "0",

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "include/usedpics/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
				button_placeholder_id : "upload_button",
				button_width: 180,
				button_height: 18,
				button_text : '<span class="button">Bilder auswählen <span class="buttonSmall">(2 MB Max)</span></span>',
				button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
				button_text_top_padding: 0,
				button_text_left_padding: 18,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "include/usedpics/swfupload/swfupload.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer"
				},
				
				// Debug Settings
				debug: false
			});
		};
	</script>

     <center><b>Bilder Manager:</b></center>
     
     Upload:
     <div style="border: thin rgb(0,0,0) inset;">
     
        <div id="upload_fenster">
         	<form>
	         	<div style="display: inline; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
			       <span id="upload_button"></span>
	         	</div>
	        </form>
	
		    <div id="divFileProgressContainer" style="height: 75px;"></div>
      	<div id="thumbnails"></div>
      </div>
          
    </div><br>
    
 
 <link rel="stylesheet" href="include/usedpics/css/usedpics.css" type="text/css" />


<?php
$data = "";

$sql = "SELECT * FROM `$dbpräfix"."usedpics`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

         $breite=$row->width; 
         $hoehe=$row->height; 

         $neueHoehe=100;
         $neueBreite=intval($breite*$neueHoehe/$hoehe); 
         
        // $neueBreite=100; 
        // $neueHoehe=intval($hoehe*$neueHoehe/$breite); 

        $img = "<img src=\"include/usedpics/pic.php?id=$row->ID\" width=\"$neueBreite\" height=\"$neueHoehe\"\> ";
       
        
        if ($data == "")
        {
        $data = "'".$img."'";
        } else
        {
          $data .= ", '".$img."'";
        }
      


}
?> 
    


<div class="picturelist">
<div class="picturelist_left" OnMouseOver="picturelist_on = true; picturelist_goleft();" OnMouseOut="picturelist_on = false;" onclick="picturelist_left()"></div>
<div class="picturelist_holder" id="picturelist_holder"></div>
<div class="picturelist_right"  OnMouseOver="picturelist_on = true; picturelist_goright();" OnMouseOut="picturelist_on = false;" onclick="picturelist_right()"></div>
</div>


<script>
picturelist_data = new Array (<?php echo $data; ?>);

picturelist_print();

</script>