<?php
if (!$_SESSION['upsescode']) $_SESSION['upsescode'] = str_replace('.','',$g['time_start']);
$sescode = $_SESSION['upsescode'];
$sess_Code =$sescode.'_'.$my['uid'].'_'.str_replace('-', '', $d['comment']['parent']); // 코드- 회원 uid - 부모 구분자 
$_SESSION['wcode']=$date['totime'];
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
  <!-- status box-->
    <div class="rb-status panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $comment_box_title?></h3> <!-- 위젯에서 설정된 변수이다 -->
            <div class="rb-spinner"><i class="fa fa-spinner fa-spin fa-lg"></i></div>
        </div>
        <div class="panel-body">
            <div class="media">
              <?php if($comment_show_avatar):?>
              <?php 
                   if($my['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$my['photo'];
                   else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif'; 
              ?>
                <div class="media-left">
                    <a href="<?php echo $g['s'].'/profile/'.$my['id']?>">
                         <img alt="<?php echo $my['id']?> -아바타" src="<?php echo $avatar_img?>" class="media-object" width="40px" height="40px">
                    </a>
                </div>
               <?php endif?>  
                <div class="media-body">
                    <div id="status-message"><!-- 입력창 출력 부분 : link-preview.js 참조 : input id="text_status-message" 로 생성된다. --></div>
                </div>
            </div>
            
        </div>
        <div class="panel-body tab-content">
            <div class="tab-pane" id="attach-photo">               
               <div id="files" class="files"><!-- 파일폼 출력 --></div> 
               <div id="file-row"><!-- 업로드 이미지 출력 --></div>
            </div>
            <div class="tab-pane" id="attach-file">
                <input type="text" name="" class="form-control" placeholder="파일을 추가해 주세요." value="1개의 파일이 추가되었습니다.">
            </div>
            <div class="tab-pane" id="attach-tag">
                <input type="text" name="" class="form-control" placeholder="키워드를 입력해 주세요. 콤마(,)로 구분하여 입력하세요." value="" data-role="tagsinput" >
            </div>
            <div class="tab-pane" id="attach-friend">
                <input type="text" name="" class="form-control" placeholder="누구와 계신가요? 콤마(,)로 구분하여 입력하세요." value="" data-role="tagsinput">
            </div>
            <div class="tab-pane" id="attach-place">
                <div class="input-group input-group-sm">
                    <input type="text" name="location" class="form-control" id="rb-status-box-autocomplete" placeholder="어디인가요 ? 장소명이나 주소를 입력해 주세요">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" id="place-reset"><i class="fa fa-times"></i></button>
                     </span>
                </div>                 
                <div id="rb-status-box-map" style="height:0px;" class="map"><!-- 지도출력 부분 : 처음에 지도를 안보여주기 위해서 height : 0 으로 하고 주소선택시 조정 --></div>
            </div>
        </div>

        <div class="panel-footer hide">
            <div class="rb-attach">
                <div class="btn-group btn-group-sm" data-toggle="buttons">

                    <label class="btn btn-default rb-tooltip" id="photos" data-placement="bottom" title="사진을 추가해 보세요" data-toggle="tab" href="#attach-photo">
                        <input type="radio" name="options" id="option1" autocomplete="off"> <i class="fa fa-camera fa-lg"></i>
                    </label>
                <!--     <label class="btn btn-default rb-tooltip" data-placement="bottom" title="파일을 첨부해 보세요" data-toggle="tab" href="#attach-file">
                        <input type="radio" name="options" id="option2" autocomplete="off"> <i class="fa fa-paperclip fa-lg"></i>
                    </label>
                  -->   <label class="btn btn-default rb-tooltip" data-placement="bottom" title="이슈를 태그해 보세요" data-toggle="tab" href="#attach-tag">
                        <input type="radio" name="options" id="option3" autocomplete="off"> <i class="fa fa-tags fa-lg"></i>
                    </label>
                    <label class="btn btn-default rb-tooltip" data-placement="bottom" title="친구를 태그해 보세요" data-toggle="tab" href="#attach-friend">
                        <input type="radio" name="options" id="option4" autocomplete="off"> <i class="fa fa-user-plus fa-lg"></i>
                    </label>
                    <label class="btn btn-default rb-tooltip" data-placement="bottom" title="장소" data-toggle="tab" href="#attach-place" id="attach-place-btn">
                        <input type="radio" name="options" autocomplete="off"> <i class="fa fa-map-marker fa-lg"></i>
                    </label>
                </div>
            </div>
            <div class="rb-action">
               <select class="rb-send selectpicker show-tick" data-style="btn-default btn-sm"  multiple data-header="게시물 공유하기" data-width="150px" data-selected-text-format="count>2">
                    <option title='페이스북' data-icon="fa fa-facebook fa-lg fa-fw">페이스북으로 보내기</option>
                    <option title='트위터' data-icon="fa fa-twitter fa-lg fa-fw">트위터로 보내기</option>
                    <option title='구글+' data-icon="fa fa-google fa-lg fa-fw">구글+로 보내기</option>
                </select>

                <select class="rb-range selectpicker show-tick" data-style="btn-default btn-sm" data-header="게시물 공개대상" data-width="110px">
                    <option data-icon="fa fa-globe fa-lg fa-fw" data-subtext="withconsumer 내외의  모든사람">전체공개</option>
                    <option data-icon="fa fa-users fa-lg fa-fw" data-subtext="withconsumer의 친구">친구만</option>
                    <option data-icon="fa fa-lock fa-lg fa-fw">나만보기</option>
                </select>
                <button class="btn btn-primary btn-sm" id="btn-share" type="submit">게시</button>
            </div>
        </div>
    </div>
    <!-- //status -->
</form> 
<script>

/*************************  Start of  링크 프리뷰  *****************************************/
$(document).ready(function() {
   $('#status-message').linkPreview({
       themeUrl :'<?php echo $g['url_comment_skin']?>',
       themeName : '<?php echo $d['comment']['theme']?>',
       placeholder : '<?php echo $comment_input_placeholder?>'
  });

});
// [END of Link-preview]
/*************************End of 링크 프리뷰  *******************************************/


/*************************  Start of 파일 업로드 스크립트 *****************************************/
var add_img='<div class="file-wrapper" id="add-img"><img src="<?php echo $g['url_comment_skin']?>/img/add-img.png" alt="이미지 추가" id="add-photos"/></div>';

$(document).ready(function()
{
    var themeUrl='<?php echo $g['url_comment_skin']?>';
    var saveDir='<?php echo $g['path_file']?>comment/';
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
           $('#add-img').remove();
            //var result=$.parseJSON(response);
            //var img=printThumb(result.src);
            var img=response;
           $(img).appendTo('#file-row');
           $('#attach-photo #file-row').append(add_img); // + 이미지(포토 추가) 보이기  
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
   $('#add-img').remove();
   if(memberid){
      $('#open-file').click();
      $('#attach-photo #file-row').append(add_img); // + 이미지(포토 추가) 보이기
   }else{
      feedback.show("로그인을 먼저 해주세요.");
   } 
});

// 추가하기 이미지 클릭 이벤트
$('#file-row').on('click','#add-photos',function(){
   if(memberid) $('#open-file').click();
   else feedback.show("로그인을 먼저 해주세요.");
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

// 피드폼 전송 이벤트 
$("#btn-share").on('click',function(e){
    e.preventDefault(); //STOP default action
    RegisFeed(); // 피드폼 전송 함수 실행 
});

// 피드폼 전송 함수 
var RegisFeed=function(){
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
    var error=0;
    for(var i=0;i<postData.length;i++){
       if(postData[i].name=='content'&& postData[i].value==''){
          feedback.show("피드 내용을 입력해주세요.");
          $('#text_status-message').focus();
          error++;
          return false;
       } 
    }
    if(error==0){
       // 스피너 출력
      $('.rb-status .rb-spinner').show(200); 
      
      $.ajax({
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR){
              // 스피너 출력
              $('.rb-status .rb-spinner').hide(200); 
              
              // 결과값 출력 
              $('#rb-comments').html(data);
              feedback.show("글이 등록되었습니다.");
              $('.file-wrapper').remove(); // 이미지 div 지우고 
              $('#shareForm').find('#text_status-message').val(''); // 입력창 내용 지우고  
              $('#link-preview-wrapper').css("display","none");// 링크 출력부 숨기고
              $('#place-box').hide();// 장소 입력부 숨기고
              doShorten(); // 더보기 초기화
              doPhotoGrid(); // 포토그리드 초기화
            },
             error: function(jqXHR, textStatus, errorThrown){
           }
      });
    }
}


// 구글지도 엔터로 선택시 폼 전송 방지 
$('#rb-status-box-autocomplete').keydown(function (e) {
   if (e.which == 13 && $('#rb-status-box-map').length){
       e.preventDefault();
   } 
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
// 검색된 지도 리셋
$('#place-reset').on('click',function(){
    // 입력된 값 삭제 
   $('#place_name').val('');
   $('#address').val('');
   $('#Lat').val('');
   $('#Lng').val('');
   
   // 해당 input 값 리셋
   $('#rb-status-box-autocomplete').val('');
    
   // 지도 숨기기 
   $('#rb-status-box-map').css("height","0px");

});

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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZ8EeFrfenX5wvMemxyUshWRbPcJRt6vQ&signed_in=true&libraries=places&callback=initAutocomplete" async defer></script>


