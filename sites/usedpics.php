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
                    file_size_limit : "2MB",    // 2MB
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





    <?php
    $data = "";

    $sql = "SELECT * FROM `$dbprefix"."usedpics`";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
            
            $breite=$row->width;
            $hoehe=$row->height;
            
            $neueHoehe=100;
            $neueBreite=intval($breite*$neueHoehe/$hoehe);
            
            $img = '<img src="include/image.php?id='.$row->ID.'&source=usedpic" width="'.$neueBreite.'" height="'.$neueHoehe.'" onclick="del_a_pic('.$row->ID.')" id="pic'.$row->ID.'"> ';
            
            
            if ($data == "")
            {
            $data = "'".$img."'";
            } else
            {
            $data .= ", '".$img."'";
            }

    }
    ?>

    <script>

            function del_a_pic(id)
            {
                    $(".picturelist img").css('border', '0px');
                    $("#pic"+id).css('border', '1px solid red');
                    $("#question").fadeOut().html("Möchten Sie das eben gewähle Element löschen?<br>"+
                    "<a href=\"#\" onclick=\" del_click("+id+"); return false;\">Ja</a>  "+
                    "<a href=\"#\" onclick=\" document.getElementById('question').innerHTML=''; return false;\">Nein</a>").fadeIn();


            }
            
            function del_click(id)
            {
                    xajax_picturelist_delElement(id);
                    $("#question").fadeOut().html("");
            
            
            }

    </script>


    <div class="picturelist">
    <div class="picturelist_left" OnMouseOver="picturelist_on = true; picturelist_goleft();" OnMouseOut="picturelist_on = false;" onclick="picturelist_left()"></div>
    <div class="picturelist_holder" id="picturelist_holder"></div>
    <div class="picturelist_right"  OnMouseOver="picturelist_on = true; picturelist_goright();" OnMouseOut="picturelist_on = false;" onclick="picturelist_right()"></div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div id="question" style="float:none"></div>

    <script>
    picturelist_data = new Array (<?php echo $data; ?>);

    picturelist_print();

    </script>


<?php

} else
{
    echo $lang['Sie haben nicht die benötigte Berechtigung!'];

}

?>