<?php
//If directory doesnot exists create it.
//$save_dir = $g['dir_comment_dir'].'files/';

if(isset($_FILES["myfile"]))
{
 $ret = array();

 $error =$_FILES["myfile"]["error"];
   {
    
     if(!is_array($_FILES["myfile"]['name'])) //single file
     {
            $RandomNum   = time();
            
            $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
            $ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.
         
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt       = str_replace('.','',$ImageExt);
            $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

          move_uploaded_file($_FILES["myfile"]["tmp_name"],$save_dir. $NewImageName);
           //echo "<br> Error: ".$_FILES["myfile"]["error"];
           
            $ret[$fileName]= $save_dir.$NewImageName;
     }
     else
     {
            $fileCount = count($_FILES["myfile"]['name']);
      for($i=0; $i < $fileCount; $i++)
      {
                $RandomNum   = time();
            
                $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name'][$i]));
                $ImageType      = $_FILES['myfile']['type'][$i]; //"image/png", image/jpeg etc.
             
                $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
                $ImageExt       = str_replace('.','',$ImageExt);
                $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
                
                $ret[$NewImageName]= $save_dir.$NewImageName;
          move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$save_dir.$NewImageName );
      }
     }
    }
    echo json_encode($ret);
 
}

?>
