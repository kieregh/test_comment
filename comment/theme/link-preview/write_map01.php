<?php
if (!$_SESSION['upsescode']) $_SESSION['upsescode'] = str_replace('.','',$g['time_start']);
$sescode = $_SESSION['upsescode'];
$sess_Code =$sescode.'-'.$my['uid'].'-'.str_replace('-', '', $d['comment']['parent']); // 코드- 회원 uid - 부모 구분자 
?>
 <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#pac-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 300px;
}

#pac-input:focus {
  border-color: #4d90fe;
}

.pac-container {
  font-family: Roboto;
}

#type-selector {
  color: #fff;
  background-color: #4d90fe;
  padding: 5px 11px 0px 11px;
}

#type-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}

    </style>

 
<link rel="stylesheet" href="<?php echo $g['url_comment_skin']?>/css/write_style.css">
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileuploadmulti.min.js"></script> 
<script src="<?php echo $g['url_comment_skin']?>/js/jquery.form.js"></script> 
<form name="shareForm" id="shareForm" role="form" method="post" action="<?php echo $g['s']?>/"  target="_action_frame_comment" enctype="multipart/form-data">
    <input type="hidden" name="r" value="<?php echo $r?>" />
    <input type="hidden" name="m" value="comment" />
    <input type="hidden" name="a" value="comment_regis" />        
    <input type="hidden" name="parent" value="<?php echo $d['comment']['parent']?>" />
    <input type="hidden" name="parentmbr" value="<?php echo $my['uid']?>" />
    <input type="hidden" name="uid" value="<?php echo $C['uid']?>" />
    <input type="hidden" name="pcode" value="<?php echo $date['totime']?>" />
    <input type="hidden" name="wcode" value="<?php echo $_SESSION['wcode']?>" />
    <input type="hidden" name="theme" value="<?php echo $g['dir_comment_skin']?>" /><!-- 테마값  -->
    <input type="hidden" name="feed_table" value="<?php echo $d['comment']['feed_table']?>" /><!-- 피드백 받을 테이블명  -->
    <input type="hidden" name="c_sort" value="<?php echo $d['comment']['sort']?>" /><!-- sort  -->
    <input type="hidden" name="c_orderby" value="<?php echo $d['comment']['orderby1']?>" /><!-- sort  -->
    <input type="hidden" name="c_recnum" value="<?php echo $d['comment']['recnum']?>" /><!-- 출력수  -->
    <input type="hidden" name="c_page" value="1" /><!-- 페이지값 -->
    <input type="hidden" name="html" value="TEXT" />
 <div class="rb-status-box shareForm">
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
                    <div id="files" class="files"><!-- 파일폼 출력 --></div> 
                    <div id="file-row"><!-- 업로드 이미지 출력 --></div>
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
</form>  
<input id="pac-input" class="controls" type="text" placeholder="Enter a location">
    <div id="type-selector" class="controls">
      <input type="radio" name="type" id="changetype-all" checked="checked">
      <label for="changetype-all">All</label>

      <input type="radio" name="type" id="changetype-establishment">
      <label for="changetype-establishment">Establishments</label>

      <input type="radio" name="type" id="changetype-address">
      <label for="changetype-address">Addresses</label>

      <input type="radio" name="type" id="changetype-geocode">
      <label for="changetype-geocode">Geocodes</label>
    </div>
    <div id="map"></div>

<script>

/*************************  Start of 파일 업로드 스크립트 *****************************************/
$(document).ready(function()
{
    var themeUrl='<?php echo $g['url_comment_skin']?>';
    var saveDir='<?php echo $g['dir_comment_module']?>files/';
    var path_core='<?php echo $g['path_core']?>';
    var path_module='<?php echo $g['path_module']?>';
    var sess_Code='<?php echo $sess_Code?>';
    var params=themeUrl+'($)'+saveDir+'($)'+path_core+'($)'+path_module+'($)'+sess_Code;
    var settings = {
        url: rooturl+'/?r=<?php echo $r?>&m=comment&a=multi_upload&params='+encodeURI(params),
        method: "POST",
        allowedTypes:"jpg,png,gif,doc,pdf,zip",
        fileName: "files",
        multiple: true,
        // 개별 파일 업로드 완료 후
        onSuccess:function(files,response,xhr)
        {
            //var result=$.parseJSON(response);
            //var img=printThumb(result.src);
            var img=response;
           $(img).appendTo('#file-row');
        }
    }
    $("#files").uploadFile(settings); // 아작스 폼+input=file 엘리먼트 세팅
});

// 썸네일 삭제 이벤트시 DB/서버도 삭제하기 
$('#file-row').on('click','.btn-file-del',function(){
   var uid=$(this).attr('id'); // img uid 
   DoDelimg(uid); // 이미지 삭제함수 실행
   $(this).parent().remove(); // 해당 file-wrapper 삭제 
});

// 이미지 삭제 함수 
function DoDelimg(uid)
{
   var ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&a=comment_img_delete&uid='+uid,'');
}

 // sharType-photos 버튼 클릭시 파일선택 이벤트
$('#photos').on('click',function(e){
   e.preventDefault();
   if(memberid) $('#open-file').click();
   else alert('로그인을 먼저 해주세요.');
});

// 썸네일 이미지 마우스 오버시 이벤트
$("#file-row").on({
    mouseover: function(){
      $(this).find('.btn-file-del').removeClass('hide');
    },
    mouseout: function(){
      $(this).find('.btn-file-del').addClass('hide');
    }
}, ".file-wrapper");

/*************************End of 파일 업로드 스크립트 *****************************************/

// 피드폼 전송 
$("#shareForm").submit(function(e)
{
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    for(var i=0;i<postData.length;i++){
       if(postData[i].name=='content'&& postData[i].value==''){
          feedback.show("피드 내용을 입력해주세요.");
          $('#status_message').focus();
          return false;
       }      
    }
    $.ajax(
    {
      url : formURL,
      type: "POST",
      data : postData,
      success:function(data, textStatus, jqXHR){
          // 결과값 출력 
          $('#rb-comments').html(data);
          feedback.show("글이 등록되었습니다.");
          $('.file-wrapper').remove(); // 이미지 div 지우고 
          $('#shareForm').find('#status_message').val(''); // 입력창 내용 지우고  
      },
      error: function(jqXHR, textStatus, errorThrown){
      }
    });
    e.preventDefault(); //STOP default action
});

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

/************************************ Start of 구글주소 자동완성 *****************************************/
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -33.8688, lng: 151.2195},
    zoom: 13
  });
  var input = /** @type {!HTMLInputElement} */(
      document.getElementById('pac-input'));

  var types = document.getElementById('type-selector');
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

  autocomplete.addListener('place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      window.alert("Autocomplete's returned place contains no geometry");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    }));
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  });

  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  function setupClickListener(id, types) {
    var radioButton = document.getElementById(id);
    radioButton.addEventListener('click', function() {
      autocomplete.setTypes(types);
    });
  }

  setupClickListener('changetype-all', []);
  setupClickListener('changetype-address', ['address']);
  setupClickListener('changetype-establishment', ['establishment']);
  setupClickListener('changetype-geocode', ['geocode']);
}
/************************************ End of 구글주소 자동완성 *****************************************/
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZ8EeFrfenX5wvMemxyUshWRbPcJRt6vQ&signed_in=true&libraries=places&callback=initMap"
        async defer></script>
