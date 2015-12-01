<?php 
include_once $g['dir_comment_skin'].'function.php';
include_once $g['dir_comment_skin'].'_var.php';
$_SESSION['wcode']=$date['totime'];
$R=getUidData($d['comment']['feed_table'],$_GET['uid']);

?>

<!-- 댓글 쓰기 -->
<?php include $g['dir_comment_skin'].'write.php';?>
<!-- 리스트 style 별도 호출 -->
<link rel="stylesheet" href="<?php echo $g['url_comment_skin']?>/css/list_style.css">
<script>

// 위도, 경도로 지도 세팅함수 : 리스트 출력시 place 가 있는 경우 출력  
function PlaceToMap(Lat,Lng,name,address,map_id)
{
     var myLatLng = {lat: Lat, lng: Lng};
     var map = new google.maps.Map(document.getElementById(map_id), { // jquery 쓰면 에러
         zoom: 17,
         scaleControl :true,
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
</script>
<div  id="rb-comments">
    <?php getCommentList($g['dir_comment_skin'],$d['comment']['parent'],$_where,$c_recnum,$c_sort,$orderby1,$orderby2,$cp)?>
</div>
      
<script type="text/javascript">
//<![CDATA[

// sort 버튼 클릭시 이벤트 
$('.cmt-sort').on('change',function(){
   var id=$(this).attr('id');
   var id_arr=id.split('-');
   var sort=id_arr[0];
   var orderby=id_arr[1];
   $('input[name="c_sort"]').val(sort);
   $('input[name="c_orderby"]').val(orderby);
   $('input[name="c_page"]').val(1); // 페이지 초기화
   $('#btn-prev').addClass('disabled'); // 이전 버튼 초기화
   $('#btn-next').removeClass('disabled'); // 다음 버튼 초기화

   get_CommentList('sorting');

   // 페이지 버튼 리셋 
    get_PbtnSetting();
    
    //페이지 숫자 리셋 
    var TPG=$('input[name="TPG"]').val(); 
    if(TPG) {$('#total-page').text(TPG); $('#now-page').text(1);} // 
    else {$('#total-page').text(0);$('#now-page').text(0);}

});

// 더보기 버튼 이벤트 
$('body').on('click','.btn-more',function(){
   get_CommentList('more');
});

// 다음/이전 페이징 버튼 이벤트 
$('body').on('click','.btn-page',function(){
   var npage=$('input[name="c_page"]').val();
   var id=$(this).attr('id');
   // 페이지 세팅 
   if(id=='btn-next') new_page=Number(npage)+1;
   else new_page=Number(npage)-1;
    
    // 신규 페이지 저장 input  및 보여주기 span
   $('input[name="c_page"]').val(new_page); // 현재 페이지 저장 
   $('#now-page').text(new_page); // 현재 페이지 보여주기 
   
    // 리스트 출력함수 호출 
    get_CommentList('paging');
    
    // 페이지 버튼 리셋 
    get_PbtnSetting();
});

// 페이징 버튼 세팅 
function get_PbtnSetting()
{
   var is_namegi=$('input[name="is_namegi"]').val(); // 나머지가 있는지 여부 체크값
   var npage=$('input[name="c_page"]').val();

   //다음 페이지 버튼 체크 
    if(Number(is_namegi)==0 || !is_namegi)  $('#btn-next').addClass('disabled');
    else $('#btn-next').removeClass('disabled');
   
   // 이전 페이지 버튼 체크 
   if(Number(npage)>1) $('#btn-prev').removeClass('disabled');
   else $('#btn-prev').addClass('disabled');     
}
/*
  댓글 리스트 출력 함수
   parent : 게시글 uidi , c_sort : 댓글 sort ...
   type : sorting, more(더보기)  
*/  
function get_CommentList(type)
{
     // loder 생성 
   $('.list-loader').fadeIn('fast');
   var theme=$('input[name="theme"]').val();
   var parent=$('input[name="parent"]').val();
   var sort=$('input[name="c_sort"]').val();
   var recnum=$('input[name="c_recnum"]').val();
   var page=$('input[name="c_page"]').val();
   var orderby=$('input[name="c_orderby"]').val();
   var TPG=$('input[name="TPG"]').val();

   if(type=='more')
   {
    var last_list=$('.comment-list:last'); // 댓글 마지막 리스트 
      var last_id=$(last_list).attr('id');
      var last_id_arr=last_id.split('-');
      var sort_arr={"uid":0,"score1":1,"oneline":2};
      var last_sort=last_id_arr[sort_arr[sort]]; // sort 에 따라서 last 값이 달라진다.
   }else{
      var last_sort='';
   }  
   var data=theme+'^^'+parent+'^^'+sort+'^^'+recnum+'^^'+page+'^^'+orderby+'^^'+last_sort;
    // 액션 페이지로 보내기  
   var ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&a=get_CommentList&type='+type+'&data='+data,''); 
   
   if(TPG>1) $('#btn-next').removeClass('disabled');

   //결과값 세팅  
   var result=getAjaxFilterString(ajax,'RESULT');
   if(result) $('.list-loader').fadeOut(100);
   if(type=='more'){
      $('.btn-more').remove();
      $('.comment-list:last').after(result); // 더보기일 경우 마지막 리스트 아래에 출력 
   }else{
      feedback.show('글이 삭제되었습니다.');
      $('#rb-comments').html(result); // 기존 리스트 덮어쓰우기 출력 
   }

}

function showButton(id) {
  $('#comment-btn-'+id).fadeIn('fast');
}

// 툴팁 이벤트 
$(document).ready(function() {
    $('[data-toggle=tooltip]').tooltip();
}); 

// live 툴팁 이벤트 
$('body').tooltip({
    selector :'.live-tooltip'
});

// Next button 세팅
function setNextbutton()
{
    var TPG=$('input[name="TPG"]').val();
    if(TPG>1) $('#btn-next').removeClass('disabled');
    else $('#btn-next').addClass('disabled');
}

// 댓글 등록 함수 
function comment_modify(content,uid)
{
    var f=document.cmtform; // 댓글 등록폼
    var json=new Object();
    json.content=content;
    json.uid=uid;
    json.theme=f.theme.value;
    json.parent=f.parent.value;
    json.c_recnum=f.c_recnum.value;
    json.c_sort=f.c_sort.value;
    json.c_orderby=f.c_orderby.value;
    json.c_page=f.c_page.value;
    var jsonData=JSON.stringify(json);
     
    $.ajax(
    {
      url: rooturl+'/?r='+raccount+'&m=comment&a=comment_modify&html=text&data='+jsonData,
      type: "GET",
      data : jsonData,
      success:function(data, textStatus, jqXHR){
          // 결과값 출력 
          $('#rb-comments').html(data);
          feedback.show("글이 수정되었습니다.");   
          doShorten(); // 더보기 초기화
          doPhotoGrid(); // 포토그리드 초기화     
      },
      error: function(jqXHR, textStatus, errorThrown){
      }
    });

}


// 댓글 달기 버튼을 클릭하면 한줄의견 입력 input에 포커스
$('body').on('click','.rb-comment', function(){
    var uid=$(this).attr('id');
    $('#oneline-ta-'+uid).focus();
});

// 댓글 수정 등록 이벤트 
$(document).on('click','.cmt-modify', function(){
      var uid=$(this).attr('id');
      var content=$('#comment-content-'+uid).html(); // 수정한 댓글 내용 
      comment_modify(content,uid);// 수정 등록시 uid 지정 
 });

 // 댓글 수정 취소 이벤트 
 $(document).on('click','.cmt-modify-cancel', function(){
      var uid=$(this).attr('id');
      var textarea = $('#comment-content-'+uid); // 수정한 댓글 박스
      $(textarea).css({"border":"solid 1px #fff","min-height":"2em"});
      $(textarea).attr("contenteditable","false"); // 해당 커멘터 내용 일반 div 타입으로 변경
      //$('#btn-cmt-modify-wrap-'+uid).css({"display":"none","margin":"0"});
      $('#btn-cmt-modify-wrap-'+uid).addClass('hide');      
      $('input[name="uid"]').val(''); // 해당 댓글 uid 값 삭제  
  });

// 댓글 액션 처리 이벤트 
$('body').on('click','.cmt-acts',function(e){
   e.preventDefault();
   var id=$(this).attr('id');
   var id_arr=id.split('-');
   var act=id_arr[0]; // 이벤트 종류 
   var uid=id_arr[1]; // 해당 댓글 uid
   var box=$('#cuid-'+uid); // 해당 댓글 uid media div
   var theme=$(box).find('input[name="theme"]').val(); // 댓글 테마
   var parent=$(box).find('input[name="parent"]').val(); // 부모 uid - 리스트 출력시 필요
   var old_comment=$(box).find('input[name="c_content"]').val(); // 해당 댓글 내용 
   var act_name={"good":"평가","bad":"평가","delete":"삭제","report":"신고"}; // 액션 전 확인용  
   var ajax;
   var old_cmt_total=$('#cmt_total').text(); // 기존 댓글 수 
   var old_good_total=$('#good-num-'+uid).text(); // 기존 공감 수
   var old_bad_total=$('#bad-num-'+uid).text(); // 기존 공감 수

   if(act=='edit'){
      // 수정모드일 경우  
      var textarea=$('#comment-content-'+uid);
      $(textarea).css({"border":"solid 1px #167ac6","min-height":"2em"});
      $(textarea).attr("contenteditable","true"); // 해당 커멘터 내용 editor 타입으로 변경
      //$('#btn-cmt-modify-wrap-'+uid).css({"display":"block","margin":"5px 0"}); 
      $('#btn-cmt-modify-wrap-'+uid).removeClass('hide'); 
      $('input[name="uid"]').val(uid); // 해당 댓글 uid 값 넣어준다. 
   }else{
      if(confirm('정말로 '+act_name[act]+'하시겠습니까?     ')){
        
         // 액션 파일에 전송 및 결과값 세팅 
         if(act=='good' || act=='bad')  ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&theme='+theme+'&a=comment_score&uid='+uid+'&value='+act,''); // 평가인 경우 
         else ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&theme='+theme+'&a=comment_'+act+'&uid='+uid,''); 
        var msg=getAjaxFilterString(ajax,'RESULT'); // 결과 메세지 
         
          // 액션처리 후 
          switch(act)
          {
           case 'report':
                alert(msg);
           break;
           case 'delete':
                if(msg=='ok'){
                 var new_cmt_total=Number(old_cmt_total)-1;
                  $('#cmt_total').text(new_cmt_total);
                get_CommentList('basic');// 댓글 리스트 리셋  
                }else{
                   alert(msg);
                }           
           break;
           case 'good': 
           case 'bad':
                if(msg=='ok'){
                  if(act=='good'){
                     var new_good_total=Number(old_good_total)+1; // 공감수 +1
                     $('#good-num-'+uid).text(new_good_total);
                   }else{
                      var new_bad_total=Number(old_bad_total)+1; // 비공감 수 +1 
                    $('#bad-num-'+uid).text(new_bad_total);
                   }
                   alert('반영되었습니다.   '); 
                }else{
                   alert(msg);
                }
           break;
          }
           
      }else{  // 확인한 경우 
         return false;
      }
   }// act !='edit' 인 경우 
   
   // next button 세팅 
   setNextbutton();
  
});

// 한줄의견 등록 이벤트  
$('body').on('click','.one-regis',function(){
    var box=$(this).parent().parent().parent().parent();
    var post=$(box).find('input[name="post"]').val();// 게시글 uid 
    var parent=$(box).find('input[name="parent"]').val();// 댓글 uid 
    var theme=$(box).find('input[name="theme"]').val(); // 게시판 테마
    var uid=$(box).find('input[name="ouid"]').val(); // 한줄의견 uid    
    var content=$(box).find('textarea[name="oneline"]').val(); // 한줄의견 내용 
    var old_one_num=$('#one-num-'+parent).text(); // 기존 한줄의견 갯수  
    if(content==''){
        alert('내용을 입력해주세요.    ');
        $(box).find('textarea[name="oneline"]').focus();
        return false;
    }else{
        content=encodeURI(content); // encode
        $(box).find('textarea[name="oneline"]').val(''); // 입력내용 초기화
      var ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&a=oneline_regis&post='+post+'&parent='+parent+'&content='+content+'&uid='+uid,''); // 액션 페이지로 보내기  
      var msg=getAjaxFilterString(ajax,'RESULT');
      if(msg=='ok')
      {
          var new_one_num=Number(old_one_num)+1;
           if(!uid) $('#one-num-'+parent).text(new_one_num);// 수정이 아닌경우 신규 한줄의견 수량 갱신
           get_OnelineList(theme,parent) // 한줄의견 리스트 출력 함수 호출 
      }else{
          alert(msg);
      }
    }
   
});

// 한줄의견 액션 처리 이벤트 
$('body').on('click','a.one-tools',function(e){
   e.preventDefault();
   var box=$(this).parent().parent();
   var theme=$(box).find('input[name="theme"]').val(); // 게시판 테마
   var parent=$(box).find('input[name="parent"]').val(); // 댓글 uid
   var old_oneline=$(box).find('input[name="o_content"]').val(); // 해당 한줄의견 내용 
    var id=$(this).attr('id');
   var id_arr=id.split('-');
   var act=id_arr[0]; // 이벤트 종류 
   var uid=id_arr[1]; // 해당 한줄의견 uid
   var act_name={"delete":"삭제","report":"신고"};
   var ajax;
   var old_one_num=$('#one-num-'+parent).text(); // 기존 한줄의견 갯수 

   if(act=='edit'){
    scrollToAnchor('one-write2-'+parent); // 글쓰기 창으로 이동 아래 함수 참조  
    $('textarea[name="oneline"]').val(old_oneline);
    $('input[name="ouid"]').val(uid);
      $('textarea[name="oneline"]').focus();    
      $('#one-regis-'+parent).text('수정');
   }else{
      if(confirm('정말로 '+act_name[act]+'하시겠습니까?     ')){
        ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&a=oneline_'+act+'&uid='+uid,''); 
         var msg=getAjaxFilterString(ajax,'RESULT'); // 결과 메세지 
         // 액션처리 후 
         if(act=='report'){
            alert(msg); 
         }else if(act=='delete'){
            if(msg=='ok'){
               var new_one_num=Number(old_one_num)-1;
              $('#one-num-'+parent).text(new_one_num);
            get_OnelineList(theme,parent);
            }else{
               alert(msg);
            }            
         } 
      }else{
         return false;
      }
   }
  
});

// 수정시 이동 스크립트
function scrollToAnchor(aid){
    var aTag = $("a[name='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top},'fast');
}

// 한줄의견 리스트 출력 호출 함수
function get_OnelineList(theme,parent)
{
   var ajax=getHttprequest(rooturl+'/?r='+raccount+'&m=comment&a=get_OnelineList&theme='+theme+'&parent='+parent,''); 
   var result=getAjaxFilterString(ajax,'RESULT');
   $('#oneline-box-'+parent).html(result); // 한줄의견 리스트 출력 
   $('input[name="ouid"]').val('');// 입력된 한줄의견 uid 값을 지운다
   $('textarea[name="oneline"]').val('');// 입력된 내용 삭제 
   $('#one-regis-'+parent).text('등록'); // 등록버튼 초기화
}

// 에디터 입력내용 소스창에 적용
function InserHTMLtoEditor(sHTML)
{
  var nHTML = $('#summernote').code();
  $('#summernote').code(nHTML+sHTML);
}


/* 파일 업로드 함수
     type : 파일 타입(이미지, 워드,엑셀 등) 
*/ 
 function Upload_file(type,file,editor,welEditable) 
 {
   data = new FormData();
   data.append("file",file); // 가상의 "file" 이라는 오브젝트를 만들어서 전송한다.
   data.append("mbruid","<?php echo $my['uid']?>");
   data.append("s","<?php echo $s?>");
   $.ajax({
       type: "POST",
       url : rooturl+'/?r=<?php echo $r?>&m=comment&a=ajax_upload',
       data:data,
       cache: false,
       contentType: false,
       processData: false,
       success: function(result) {
         var val = $.parseJSON(result);
         var code=val[0];
         if(code=='100') // code 값이 100 일때만 실행 
         {
            var source=val[1];// path + tempname
           var upuid=val[2]; // upload 테이블 저장 uid
           var up_val=$('input[name="upfiles"]').val(); // 현재 upfiles 값 

           if(up_val=='') $('input[name="upfiles"]').val('['+upuid+']'); // 처음이면 uid 값만...
           else $('input[name="upfiles"]').val(up_val+'['+upuid+']'); // 처음이 아니면 콤마 추가 
           
           // 파일 타입이 이미지인 경우에만 에디터에 이미지 삽입
           if(type=='img') {
               editor.insertImage(welEditable, source); 
          }
         }else{
            var msg=val[1];
            alert(msg);
            return false;
         }  

       } // success
    }); // ajax
 } // function
//]]>
</script>

 

