<?php
if (!$_SESSION['upsescode']) $_SESSION['upsescode'] = str_replace('.','',$g['time_start']);
$sescode = $_SESSION['upsescode'];
$sess_Code =$sescode.'_'.$my['uid'].'_'.str_replace('-', '', $d['comment']['parent']); // 코드- 회원 uid - 부모 구분자 
?>
<link rel="stylesheet" href="<?php echo $g['url_comment_skin']?>/css/write_style.css">
<script src="<?php echo $g['url_comment_skin']?>/js/file_upload/jquery.fileuploadmulti.min.js"></script> 
<script src="<?php echo $g['url_comment_skin']?>/js/jquery.form.js"></script> 
<!-- link-preview script -->

<script src="<?php echo $g['url_comment_skin']?>/js/link_preview/linkPreview.js"></script> 
<script src="<?php echo $g['url_comment_skin']?>/js/link_preview/linkPreviewRetrieve.js"></script> 
<form name="cmtform" id="shareForm" role="form" method="post" action="<?php echo $g['s']?>/"  target="_action_frame_comment" enctype="multipart/form-data" onsubmit="return false;">
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
    <input type="hidden" name="place_name" id="place_name">
    <input type="hidden" name="address" id="address">
    <input type="hidden" name="lat" id="Lat">
    <input type="hidden" name="lng" id="Lng">
    <input type="hidden" name="links" id="status-links"><!-- 링크정보를 저장한다. -->
 <div class="rb-status-box shareForm" id="rb-status-box">
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
                    <div id="status-message"><!-- 입력창 출력 부분 : link-preview.js 참조 : input id="text_status-message" 로 생성된다. --></div>
                </div>
                <div id="photos-box" class="shareType-box hide">
                    <div id="files" class="files"><!-- 파일폼 출력 --></div> 
                    <div id="file-row"><!-- 업로드 이미지 출력 --></div>
               </div>
                <div id="video-box" class="shareType-box hide">
                    <input type="text" name="videoUrl" id="videoUrl" placeholder="Youtube or Vimeo video URL" class="form-control">
                </div>
                <div id="place-box" class="shareType-box hide">
                    <div><input id="rb-status-box-autocomplete" placeholder="장소나 주소를 입력해주세요" type="text" name="location" class="form-control"></div>
                    <div id="rb-status-box-map" style="height:0px;" class="map"><!-- 지도출력 부분 : 처음에 지도를 안보여주기 위해서 height : 0 으로 하고 주소선택시 조정 --></div>
                 </div>                 
            </div>
         </div>
      </div>
      <div class="timeline-footer clearfix">
        <div class="pull-right">
          <input type="button" class="btn btn-primary" id="btn-share" value="Post" name="submit">
        </div>
      </div>
    <iframe width="0" height="0" frameborder="0" style="display:none" title="iframe" scrolling="no" name="_action_frame_comment"></iframe>
 </div>
</form> 
<script>
var object1 = {
  apple: 0,
  banana: {weight: 52, price: 100},
  cherry: 97
};
var object2 = {
  banana: {price: 200},
  durian: 100
};

/* merge object2 into object1 */
$.extend(object1, object2);

var printObj = typeof JSON != "undefined" ? JSON.stringify : function(obj) {
  var arr = [];
  $.each(obj, function(key, val) {
    var next = key + ": ";
    next += $.isPlainObject(val) ? printObj(val) : val;
    arr.push( next );
  });
  return "{ " +  arr.join(", ") + " }";
};
console.log(printObj);

/*************************  Start of  링크 프리뷰  *****************************************/
$(document).ready(function() {
   $('#status-message').linkPreview({
       themeUrl :'<?php echo $g['url_comment_skin']?>',
       themeName : '<?php echo $d['comment']['theme']?>'
  });

});
// [END of Link-preview]
/*************************End of 링크 프리뷰  *******************************************/

/*************************  Start of 파일 업로드 스크립트 *****************************************/
$(document).ready(function()
{
    var themeUrl='<?php echo $g['url_comment_skin']?>';
    var saveDir='<?php echo $g['dir_comment_module']?>files/';
    var path_core='<?php echo $g['path_core']?>';
    var path_module='<?php echo $g['path_module']?>';
    var sess_Code='<?php echo $sess_Code?>';
    var params=themeUrl+'@'+saveDir+'@'+path_core+'@'+path_module+'@'+sess_Code;
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
   else feedback.show("로그인을 먼저 해주세요.");;
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

// [END of 파일 업로드]
/*************************End of 파일 업로드 스크립트 *****************************************/

// 피드폼 전송 
$("#btn-share").on('click',function(e)
{
    var form =$('#shareForm');
    if(!memberid){
      feedback.show("로드인을 먼저 해주세요."); return false;
    } 

    // 링크정보 저장 
    var link_title=$('.previewSpanTitle').text();
    var link_url=$('.previewUrl').text();
    var link_desc=$('.previewSpanDescription').text();
    var link_thumb=$('.previewImage').find('img').attr('src');

    if(link_title && link_title!=''){
       $(form).find('#status-links').val(link_title+'^^'+encodeURI(link_url)+'^^'+link_desc+'^^'+link_thumb);  
    }
    var postData = $(form).serializeArray();
    var formURL = $(form).attr("action");
    for(var i=0;i<postData.length;i++){
       if(postData[i].name=='content'&& postData[i].value==''){
          feedback.show("피드 내용을 입력해주세요.");
          $('#text_status-message').focus();
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
          $('#shareForm').find('#text_status-message').val(''); // 입력창 내용 지우고  
          $('#link-preview-wrapper').css("display","none");// 링크 출력부 숨기고
          $('#place-box').hide();// 장소 입력부 숨기고
      },
      error: function(jqXHR, textStatus, errorThrown){
      }
    });
    e.preventDefault(); //STOP default action
});

// shareType 변경 스크립트 
function setSharetypebox(shareType)
{
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

/****************************** Start of 구글주소 자동완성 ****************************************
  ** 링크 스크립트로 함수 호출하려면 파라미터 값에 '&callback=함수명' 을 추가하면 된다. 단, 함수보다 아래에 위치해야 한다.
*/
function initAutocomplete() {
    // 사용자 입력 input id 지정 
    var input = document.getElementById('rb-status-box-autocomplete');// jquery 쓰면 에러

    /* map 을 지정하지 않으면 주소가 제대로 검색되지 않는다. 
        단, 처음부터 지도를 보여줄 것이 아니기 때문에 해당 엘리먼트 height 를 0 으로 해주고 주소 선택시 지도생성 함수(initMap) 호출시 height 를 조정해준다.  
    */
    var map = new google.maps.Map(document.getElementById('rb-status-box-map'), { // jquery 쓰면 에러 
      center: {lat: -33.8688, lng: 151.2195},
      zoom: 13
    });    
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map); // 이 부분이 없으면 주소검색이 제대로 되지 않는다.

    // 자동완성 세팅
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace(); // 자동완성으로 얻어진 장소 정보배열 
        var Lat=place.geometry.location.lat(); // 위도값
        var Lng=place.geometry.location.lng(); // 경도값
        var pname=place.name; // 장소명 (cf : 이 장소명이 address 마지막 부분과 중복되는 현상이 있어 조정했다. ) 
        var address = '';
        if (place.address_components) {
          address = [
            // 순서를 반대로 해야 어순이 맞다
            (place.address_components[2] && place.address_components[2].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[0] && place.address_components[0].short_name || '')
          ].join(' ');
        }
        // 위도,경도,주소값을 form input 에 넣는다.
        var addr_last;
        var address_arr=address.split(' ');
        addr_last=address_arr[0]+' '+address_arr[1]+' '+address_arr[2]; 
        document.getElementById('place_name').value = pname;  
        document.getElementById('address').value = addr_last;
        document.getElementById('Lat').value = Lat;
        document.getElementById('Lng').value =Lng;

        // 지도세팅 함수 호출 : 위도,경도,장소명,주소값
        FeedSetMap(Lat,Lng,pname,address);
    });
}

// 검색된 주소로 지도 세팅함수 
function FeedSetMap(Lat,Lng,name,address)
{
    $('#rb-status-box-map').css("height","200px");
     var myLatLng = {lat: Lat, lng: Lng};
     var map = new google.maps.Map(document.getElementById('rb-status-box-map'), { // jquery 쓰면 에러
         zoom: 17,
         center: myLatLng
      });

      var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: name
      });
      var infowindow = new google.maps.InfoWindow({
          content: '<div><strong>' + name + '</strong><br>' + address
      });

       infowindow.open(map, marker);  
}

// [END of 구글주소 자동완성]
/*****************************************************************************/
</script>


