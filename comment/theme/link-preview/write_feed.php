<link rel="stylesheet" href="<?php echo $g['url_comment_skin']?>/css/write_style.css">
    <form name="shareForm" id="shareForm" role="form" method="post" action="<?php echo $g['s']?>/"  target="_action_frame_comment" enctype="multipart/form-data" onsubmit="return regisCheck(this);">
        <input type="hidden" name="r" value="<?php echo $r?>" />
        <input type="hidden" name="m" value="comment" />
        <input type="hidden" name="a" value="comment_regis" />        
        <input type="hidden" name="parent" value="<?php echo $d['comment']['parent']?>" />
        <input type="hidden" name="parentmbr" value="<?php echo $my['uid']?>" />
        <input type="hidden" name="uid" value="<?php echo $C['uid']?>" />
        <input type="hidden" name="upfiles" id="upfilesValue" value="<?php echo $C['upload']?>" />
         <input type="hidden" name="pcode" value="<?php echo $date['totime']?>" />
        <input type="hidden" name="theme" value="<?php echo $g['dir_comment_skin']?>" /><!-- 테마값  -->
        <input type="hidden" name="feed_table" value="<?php echo $d['comment']['feed_table']?>" /><!-- 피드백 받을 테이블명  -->
        <input type="hidden" name="c_sort" value="<?php echo $d['comment']['sort']?>" /><!-- sort  -->
        <input type="hidden" name="c_orderby" value="<?php echo $d['comment']['orderby1']?>" /><!-- sort  -->
        <input type="hidden" name="c_recnum" value="<?php echo $d['comment']['recnum']?>" /><!-- 출력수  -->
        <input type="hidden" name="c_page" value="1" /><!-- 페이지값 -->
        <input type="hidden" name="html" value="TEXT" />
    </form>     
<div class="rb-status-box shareForm">
       <input id="fileupload" type="file" class="hide" name="files[]" multiple>
       <div class="timeline-body">
         <div class="share-form">
              <ul class="post-types">
                <li class="post-type">
                  <a href="#" class="shareType-tab" id="status"><i class="fa fa-file"></i> Status</a>
                </li>
                <li class="post-type">
                  <a href="#" class="shareType-tab" id="photos"><i class="fa fa-camera"></i> Photos</a>
                </li>
                <li class="post-type">
                  <a href="#" class="shareType-tab" id="video"><i class="fa fa-film"></i> Video</a>
                </li>
                <li class="post-type">
                  <a href="#" class="shareType-tab" id="place"><i class="fa fa-map-marker"></i> Place</a>
                </li>
              </ul>
              <div class="share">
                    <div class="arrow"></div>
                    <div id="status-box" class="shareType-box">
                         <textarea placeholder="What's on your mind ?" style="height: 62px; overflow: hidden;" class="form-control message" id="status_message" rows="10" cols="40" name="content"></textarea> 
                    </div>
                    <div id="photos-box" class="shareType-box hide">
                        <div id="files" class="files"></div> <!--upload 파일 출력되는 곳 -->
                    </div>
                    <div id="video-box" class="shareType-box hide">
                       <input type="text" name="videoUrl" id="videoUrl" placeholder="Youtube or Vimeo video URL" class="form-control">
                    </div>
                    <div id="place-box" class="shareType-box hide">
                      <input type="text" placeholder="Enter a location" name="location" class="form-control" id="geocomplete" autocomplete="off">
                      <div class="map_canvas" style="position: relative; background-color: rgb(229, 227, 223); overflow: hidden;"><div style="position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%; z-index: 0;" class="gm-style"><div style="position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%; z-index: 0; cursor: url(&quot;http://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default;"><div style="position: absolute; left: 0px; top: 0px; z-index: 1; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"><div style="position: absolute; left: 0px; top: 0px; z-index: 1; visibility: inherit; display: block;" aria-hidden="true"></div></div></div><div style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: -1;"><div style="position: absolute; left: 0px; top: 0px; z-index: 1; visibility: inherit; display: block;" aria-hidden="true"></div></div></div><div style="position: absolute; z-index: 0;"><div style="overflow: hidden;"></div></div></div><div style="position: absolute; left: 0px; top: 0px; z-index: 2; width: 100%; height: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 3; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;"></div></div></div></div></div>
                      <input type="hidden" name="lat">
                      <input type="hidden" name="lng">
                    </div>
                    <input type="hidden" value="status" id="shareType" name="shareType">
              </div>
          </div>
      </div>
      <div class="timeline-footer clearfix">
        <div class="pull-right">
          <input type="submit" class="btn btn-primary" id="btn-share" value="Post" name="submit">
        </div>
      </div>
    <iframe width="0" height="0" frameborder="0" style="display:none" title="iframe" scrolling="no" name="_action_frame_comment"></iframe>
 </div>
 <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/vendor/jquery.ui.widget.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/load-image.all.min.js"></script> 
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.iframe-transport.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileupload-image.js"></script>
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileupload-validate.js"></script>

<script src="js/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="js/load-image.all.min.js"></script> 
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->


<script>
// 등록체크 
//  var submitFlag = false;
//  function regisCheck(f)
//  { 
//       if (submitFlag == true)
//       {
//         alert('포스트를 등록하고 있습니다. 잠시만 기다려 주세요.   ');
//         return false;
//       }
//      if(f.content.value=='')
//      {
//          alert('내용을 입력해주세요.');
//          f.content.focus();
//          return false;
//      }else{
//         $('').submit();
//      } 
//      return false;
// }

// function regisCheck(form){
//     $(form)
//         .on('success.form.fv', function(e) {
//             // Prevent form submission
//             e.preventDefault();

//             var $form    = $(e.target),
//                 formData = new FormData(),
//                 params   = $form.serializeArray(),
//                 files    = $form.find('[name="files"]')[0].files;

//             $.each(files, function(i, file) {
//                 // Prefix the name of uploaded files with "files-"
//                 // Of course, you can change it to any string
//                 formData.append('files-' + i, file);
//             });

//             $.each(params, function(i, val) {
//                 formData.append(val.name, val.value);
//             });

//             $.ajax({
//                 url: $form.attr('action'),
//                 data: formData,
//                 cache: false,
//                 contentType: false,
//                 processData: false,
//                 type: 'POST',
//                 success: function(result) {
//                     // Process the result ...
//                 }
//             });
//         });
// }
 // shareType 변경 스크립트 
  function setSharetypebox(shareType)
  {
     $('.shareType-box').addClass('hide');
     $('#status-box').removeClass('hide');
     $('#'+shareType+'-box').removeClass('hide');      
  }
  $('.rb-status-box').on('click','.shareType-tab', function(e) { 
       e.preventDefault();
       var shareType=$(this).attr('id');
        var positionArray = {};
        positionArray['status'] = 0;
        positionArray['photos'] = 80;
        positionArray['video'] = 160;
        positionArray['place'] = 231;        
        $('input[name="shareType"]').val(shareType); // share 타입 저장
        $('.arrow').css("left", positionArray[shareType]); // 화살표 이동
         setSharetypebox($(this).attr('id'));
       return false;
  });
 /*************************  파일업로드 스크립트 ******/
 
 // sharType-photos 버튼 클릭시 파일선택 이벤트
$('#photos').on('click',function(e){
   e.preventDefault();
   $('#fileupload').click();
});

// 미리보기 이미지 마우스 오버시 삭제버튼 노출
$("#files").on({
  mouseover: function(){
    $(this).find('.btn-file-del').removeClass('hide');
  },
  mouseout: function(){
    $(this).find('.btn-file-del').addClass('hide');
  }
}, ".file-row");

$(function(){
    'use strict';
    var fi = $('#fileupload'); //file input 
    var process_url = rooturl+'/?m=comment&a=theme/<?php echo $d['comment']['theme']?>/_action/a.multi_file_upload'; //PHP script
    var progressBar = $('<div/>').addClass('progress').append($('<div/>').addClass('progress-bar')); //progress bar
    var uploadButton = $('<button/>').addClass('button btn-blue upload').text('Upload');    //upload button
    
    uploadButton.on('click', function () {
        var $this = $(this), data = $this.data();
        data.submit().always(function () {
                $this.parent().find('.progress').show();
                $this.parent().find('.remove').remove();
                $this.remove();
        });
    });

    //initialize blueimp fileupload plugin
    fi.fileupload({
        url: process_url,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|mp4|mp3)$/i,
        maxFileSize: 1048576, //1MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/ 
        .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true,
        dropZone: $('#dropzone')
    });
    // 파일선택시 이벤트
    fi.on('fileuploadadd', function (e, data) {
            data.context = $('<div/>').addClass('file-wrapper').appendTo('#files');
            $.each(data.files, function (index, file){  
            var node = $('<div/>').addClass('file-row');
            var removeBtn  = $('<img src="<?php echo $g['url_comment_skin']?>/img/btn-file-del.png" class="btn-file-del hide"/>');
            removeBtn.on('click', function(e, data){
                $(this).parent().parent().remove();
            });
            node.append(removeBtn);
            var file_txt = $('<div/>').addClass('file-row-text').append('<span>'+file.name + ' (' +format_size(file.size) + ')' + '</span>');
            
            file_txt.append(removeBtn);
            file_txt.prependTo(node).append(uploadButton.clone(true).data(data));
            progressBar.clone().appendTo();
            if (!index){
                node.prepend(file.preview);
            }
            
            node.appendTo(data.context);
        });
    });

    fi.on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
            if (file.preview) {
                node .prepend(file.preview);
            }
            if (file.error) {
                node.append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button.upload').prop('disabled', !!data.files.error);
            }
    });
    
    fi.on('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        if (data.context) {
            data.context.each(function () {
                $(this).find('.progress').attr('aria-valuenow', progress).children().first().css('width',progress + '%').text(progress + '%');
            });
        }
    });

    fi.on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
                var link = $('<a>') .attr('target', '_blank') .prop('href', file.url);
                $(data.context.children()[index]).addClass('file-uploaded');
                $(data.context.children()[index]).find('canvas').wrap(link);
                $(data.context.children()[index]).find('.file-remove').hide(); 
                var done = $('<span class="text-success"/>').text('Uploaded!');
                $(data.context.children()[index]).append(done);
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index]).append(error);
            }
        });
    });
    
    fi.on('fileuploadfail', function (e, data) {
     $('#error_output').html(data.jqXHR.responseText);
    });
    
    function format_size(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }
            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }
            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }
            return (bytes / 1000).toFixed(2) + ' KB';
        }
});
 </script>
