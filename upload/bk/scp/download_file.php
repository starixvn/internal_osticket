<?php
    // get directory for upload folder
    $uploadPath = 'http';
   /*if ($_SERVER["HTTPS"] == "on") {
        $uploadPath .= "s";        
   }*/
    $uploadPath .= "://";
    $projectName = dirname($_SERVER["SCRIPT_NAME"]);
    if ($_SERVER["SERVER_PORT"] != "80") {
     $uploadPath .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$projectName;
    } else {
     $uploadPath .= $_SERVER["SERVER_NAME"].$projectName;
    }
   
   // The file path where the file exists
   $filepath = $uploadPath ."/uploads/".$_GET['filename']."";
   header("Pragma: public");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   //setting content type of page
   header("Content-Type: application/force-download");
   header("Content-Disposition: attachment; filename=".basename($filepath ));
   header("Content-Description: File Transfer");
   //Read File it will start downloading
   @readfile($filepath);
  
  ?>