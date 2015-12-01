<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require_once $_GET['theme_dir'].'php/index.php'; 

$upload_dir = $_GET['upload_dir']; //specify path to your upload folder

$upload_handler = new UploadHandler(array(
           'max_file_size' => 1048576, //1MB file size
           'image_file_types' => '/\.(gif|jpe?g|png)$/i',
           'upload_dir' => $upload_dir,
           'upload_url' => '',
           'thumbnail' => array('max_width' => 100,'max_height' => 100)
           ));
exit;
?>
