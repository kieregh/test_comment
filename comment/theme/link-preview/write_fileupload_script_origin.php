<style type="text/css">
      img {border-width: 0}
      * {font-family:'Lucida Grande', sans-serif;}
 </style>
 <link href="<?php echo $g['url_comment_skin']?>/css/uploadfilemulti.css" rel="stylesheet">
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileuploadmulti.min.js"></script>

 <div id="mulitplefileuploader">Upload</div>

<div id="status"></div>
<script>

$(document).ready(function()
{

var settings = {
    url: rooturl+"/?m=comment&a=multi_upload&save_dir=<?php echo $g['dir_comment_module']?>/files/",
    method: "POST",
    allowedTypes:"jpg,png,gif,doc,pdf,zip",
    fileName: "myfile",
    multiple: true,
    onSuccess:function(files,data,xhr)
    {
        $("#status").html("<font color='green'>Upload is success</font>");
        
    },
    afterUploadAll:function()
    {
        alert("all images uploaded!!");
    },
    onError: function(files,status,errMsg)
    {       
        $("#status").html("<font color='red'>Upload is Failed</font>");
    }
}
$("#mulitplefileuploader").uploadFile(settings);

});
</script>
</body>
</html>
