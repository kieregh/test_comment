<?php
if(!defined('__KIMS__')) exit;

function getCMNTUpfiles($R)
{
	if (!$R['upload']) return array();
	else
	{
		global $table,$m;
		$d['upload'] = array();
		$d['upload']['tmp'] = $R['upload'];
		$d['_pload'] = getArrayString($R['upload']);
		foreach($d['_pload']['data'] as $_val)
		{
			$U = getUidData($table['s_upload'],$_val);
			if (!$U['uid'])
			{
				$R['upload'] = str_replace('['.$_val.']','',$R['upload']);
				$d['_pload']['count']--;
			}
			else {
				$d['upload']['data'][] = $U;
			}
			if (!$U['cync'])
			{
				$cyncArr = getArrayString($R['cync']);
				$_CYNC = "cync='[".$m."][".$R['uid']."][uid,down][".$table['s_comment']."][".$R['mbruid']."][".$cyncArr['data'][5].",CMT:".$R['uid']."#CMT]'";
				getDbUpdate($table['s_upload'],$_CYNC,'uid='.$U['uid']);
			}
		}
		if ($R['upload'] != $d['upload']['tmp'])
		{
			getDbUpdate($table['s_comment'],"upload='".$R['upload']."'",'uid='.$R['uid']);
		}
		$d['upload']['count'] = $d['_pload']['count'];
		return $d['upload'];
	}
}
// 텍스트 중 http://~ 로 된 문구 <a></a> 로 감싸주는 함수 
function autolink($str, $attributes=array()) {
    $attrs = '';
    foreach ($attributes as $attribute => $value) {
        $attrs .= " {$attribute}=\"{$value}\"";
    }
    $str = ' ' . $str;
    $str = preg_replace('`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i','$1<a href="$2"'.$attrs.' target="blank">$2</a>',$str );
    $str = substr($str, 1);
    return $str;
}

// 지난 시간 얻기 함수 
function getJNTime($d_regis)
{
	 global $g;
	 //include $g['path_core'].'function/sys.func.php';
   // 최근 이슈 경과 시간 추출
	 $dnowdate=date("Y-m-j G:i:s");// 오늘 날짜 
	 $ddate=getDateFormat($d_regis,'Y-m-j G:i:s');//Last-ay
	 $timediffer=strtotime($dnowdate) - strtotime("$ddate GMT"); // 기준일과 오늘의 시간(초) 차이
	 $dval=$timediffer+32400;
    if((60>$dval && $dval>0)||!$dval){
    	 $JN_time=date('s 초 전',$timediffer);
    }elseif(3600>$dval&& $dval>60){
                   $JN_time=date('i 분 전',$timediffer);
    }elseif(86400>$dval && $dval>3600){
    	 $JN_time=date('G 시간 전',$timediffer);
    }elseif(2592000>$dval && $dval>86400){
                   $JN_time=date('j 일 전',$timediffer);
    }elseif(31104000>$dval && $dval>2592000){
    	 $JN_time=date('n 개월 전',$timediffer);
    }else{
    	$JN_time='-';
    }  
    return $JN_time;
}
// 한 줄 의견 리스트 출력함수  
function getOnelineList($theme,$parent)
{
    global $g,$table,$_HS,$m,$my;
     include $theme.'_var.php';// 설정파일 인클루드 
    $g['img_module_skin']=$theme.'image/';
    $TCD = getDbArray($table['s_oneline'],'parent='.$parent,'*',$d['oneline']['sort'],$d['oneline']['orderby'],0,0);
    while($O=db_fetch_array($TCD))
     {
          if($O['mbruid']){
           $M=getDbData($table['s_mbrdata'],'memberuid='.$O['mbruid'],'*');
           $M1=getUidData($table['s_mbrid'],$O['mbruid']);  
          }
         $compare_time=$O['d_modify']?$O['d_modify']:$O['d_regis'];
         $JN_time=getJNTime($compare_time); // 지난시간 얻기 함수 호출
     	  
          // 아바타 사진 url 세팅
          if($M['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$M['photo'];
          else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';

     	    echo '<li class="media">
                        <div class="media-left">
                            <a href="'.$g['s'].'/profile/'.$M1['id'].'" target="_blank">
                            <img class="media-object" src="'.$avatar_img.'" alt="'.$M[$_HS['nametype']].'님 아바타">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">'.($O[$_HS['nametype']]?$O[$_HS['nametype']]:$O['name']).'</h4>
                              <input type="hidden" name="theme" class="theme" value="'.$theme.'" />
                              <input type="hidden" name="parent" class="parent" value="'.$parent.'" />
                              <input type="hidden" name="o_content" class="o_content" value="'.getContents($O['content'],$O['html'],$keyword).'" />
                            '.getContents($O['content'],$O['html'],$keyword).' 
                            <div class="rb-buttons">
                                <button type="button" class="rb-like btn btn-link" data-toggle="button">좋아요</button>
                                <button type="button" class="btn btn-link">답글달기</button>
                                <a href="#" class="rb-like" data-toggle="tooltip" title="홍길동님이 좋아합니다."><i class="fa fa-thumbs-o-up"></i> 1</a>
                                <a class="rb-time" data-toggle="tooltip" title="'.getDateFormat($compare_time,'Y년 m월 d일  H시 i분').'">'.$JN_time.'</a>
                            </div>
                        </div>
                   </li>'; // media : 반복되는 리스트 
     } // while
}
/*
   댓글 리스트 출력 함수
   $puid : parent uid - 게시글 uid
*/ 
function getCommentList($theme,$parent,$_where,$recnum,$sort,$orderby1,$orderby2,$cp)
{
    global $g,$table,$_HS,$m,$my;
    include $theme.'_var.php';// 설정파일 인클루드 
    $g['img_module_skin']=$theme.'image/';
    $NCD = array();
    $RCD = array();
    $cp = $cp ? $cp : 1;
    $sort= $sort? $sort : 'uid';
    $orderby1 = $orderby1 ? $orderby1 : $d['comment']['orderby1'];
    $orderby2 = $orderby2 ? $orderby2 : $d['comment']['orderby2'];
    $recnum = $recnum ? $recnum : $d['comment']['recnum'];
    $cmentque = " and parent='".str_replace('-', '',$parent)."'";
    if($_where) $cmentque .=" and ".$_where;
	 $PCD = getDbArray($table['s_comment'],'notice=1'.$cmentque,'*',$sort,$orderby1,0,0);
	 $TCD = getDbArray($table['s_comment'],'notice=0'.$cmentque,'*',$sort,$orderby2,$recnum,$cp);
	 $NUM = getDbRows($table['s_comment'],'notice=0'.$cmentque);
	 $TPG = getTotalPage($NUM,$recnum);
	 while($_R = db_fetch_array($PCD)) $NCD[] = $_R;
	 while($_R = db_fetch_array($TCD)) $RCD[] = $_R;
    //echo $cmentque;
    $RCD=$NCD+$RCD;
    $i=1;
    $namegi=$NUM-($cp*$recnum);
    if($namegi>0){
    	$namegi_ment='총 <span class="text-danger namegi">'.$namegi.'</span> 개의 댓글이 더 있습니다.';
    	$btn_more='btn-more';
    	$is_namegi=1;
    }else{
    	$namegi_ment='더이상 댓글이 없습니다.';
      $btn_more='disabled';
      $is_namegi=0;
    }
    // 로그인 사용자 아바타 사진 url 세팅
    if($my['photo']) $my_avatar=$g['url_root'].'/_var/avatar/'.$my['photo'];
    else  $my_avatar=$g['url_root'].'/_var/avatar/0.gif';

    echo '<!-- filter -->
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-sm" data-toggle="buttons">
            <label class="btn btn-default active">
                <input type="radio" name="options" id="option1" autocomplete="off" checked> 광장
            </label>
            <label class="btn btn-default">
                <input type="radio" name="options" id="option2" autocomplete="off"> 핫이슈
            </label>
            <label class="btn btn-default">
                <input type="radio" name="options" id="option3" autocomplete="off"> 내 피드
            </label>
        </div>
    </div>'; 
    foreach($RCD as $C) 
    {
    	   $C['mobile']=isMobileConnect($C['agent']);
	   if($C['mbruid']){
           $M=getDbData($table['s_mbrdata'],'memberuid='.$C['mbruid'],'*');
           $M1=getUidData($table['s_mbrid'],$C['mbruid']);  
          } 
	    $isSECRETCHECK=true;
          $compare_time=$C['d_modify']?$C['d_modify']:$C['d_regis'];
	    $JN_time=getJNTime($compare_time); // 지난시간 얻기 함수 호출
       
          // 아바타 사진 url 세팅
         if($M['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$M['photo'];
         else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';

       echo '		  
        <div class="rb-item panel panel-default" id="'.$C['uid'].'-'.$C['score1'].'-'.$C['oneline'].'">
            <div class="panel-heading clearfix">
                <div class="rb-media media"  id="cuid-'.$C['uid'].'">
                    <input type="hidden" name="is_namegi" value="'.$is_namegi.'"/>
                    <input type="hidden" name="TPG" value="'.$TPG.'"/>
                    <input type="hidden" name="theme" class="theme" value="'.$theme.'" />
                    <input type="hidden" name="parent" class="parent" value="'.$parent.'" />
                    <input type="hidden" name="c_content" class="c_content" value="'.htmlspecialchars(getContents($C['content'],$C['html'],$keyword)).'" />
                    <div class="media-left">
                        <a href="'.$g['s'].'/profile/'.$M1['id'].'"><img class="media-object" src="'.$avatar_img.'" alt="'.$M[$_HS['nametype']].'님 아바타"></a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">'.($C[$_HS['nametype']]?$C[$_HS['nametype']]:$C['name']).'</h4>
                        <div class="rb-meta">
                            <a class="rb-time" data-toggle="tooltip" title="'.getDateFormat($compare_time,'Y년 m월 d일  H시 i분').'">'.$JN_time.'</a>
                            <a class="rb-range" data-toggle="tooltip" title="공유대상 : 전체공개"><i class="fa fa-globe"></i></a>
                        </div>
                    </div>
                </div>

                <div class="rb-actions">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                        </button>';
                         if($my['admin']||$C['mbruid']==$my['uid']){
                         echo ' 
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                            <li><a href="#" class="rb-hidden cmt-acts" id="hidden-'.$C['uid'].'" ><i class="fa fa-eye-slash fa-fw"></i> 게시물 숨기기</a></li>
                            <li><a href="#" class="rb-report cmt-acts" id="report-'.$C['uid'].'"><i class="fa fa-info-circle fa-fw"></i> 게시물 신고</a></li>
                            <li><a href="#" class="rb-linkSave cmt-acts" id="savelink-'.$C['uid'].'"><i class="fa fa-bookmark fa-fw"></i> 링크 저장</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">'.($C[$_HS['nametype']]?$C[$_HS['nametype']]:$C['name']).'님</li>
                            <li><a href="#" class="rb-unfolow cmt-acts" id="'.$C['mbruid'].'"><i class="fa fa-chain-broken fa-fw"></i> 팔로우 취소</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">게시물 관리</li>
                            <li><a href="#" class="rb-modify cmt-acts" id="edit-'.$C['uid'].'"><i class="fa fa-pencil fa-fw"></i> 게시물 수정</a></li>
                            <li><a href="#" class="rb-delete cmt-acts" id="delete-'.$C['uid'].'"><i class="fa fa-trash fa-fw"></i> 게시물 삭제</a></li>
                        </ul>';
                        }
                    echo '
                    </div>
                </div>
            </div> <!-- panel-heading-->

            <div class="panel-body">
                <p class="comment-content" id="comment-content-'.$C['uid'].'">'.autolink(getContents($C['content'],$C['html'],$keyword)).'</p>
                <div class="clearfix cmt-modify-btn-wrap hide" id="btn-cmt-modify-wrap-'.$C['uid'].'">
                    <div class="rb-action pull-right">
                         <button type="button" class="btn btn-default btn-sm cmt-modify-cancel" id="'.$C['uid'].'">취소</button>
                         <button type="button" class="btn btn-primary btn-sm cmt-modify" id="'.$C['uid'].'">수정</button>
                    </div>
                 </div>    
            </div>';
 if($C['is_link']){
  $links_arr=explode('^^',$C['links']);
  $link_title=$links_arr[0];$link_url=$links_arr[1];$link_desc=$links_arr[2];$link_thumb=$links_arr[3];
  echo '<div class="rb-comment-links clearfix">
               <div class="media">
                   <div class="media-left" id="extracted_thumb"><img src="'.$link_thumb.'" width="100" height="100"></div>
                   <div class="media-body">
                       <h4 class="media-heading"><a href="'.$link_url.'" target="_blank">'.$link_title.'</a></h4>
                       <p class="rb-description">'.$link_desc.'</p>
                   </div>
                </div>
             </div>';          
   } // 링크 출력부
   // 포토출력 
   if($C['is_photo'])
   {
       $upArray=getArrayString($C['upload']);
       $upQty=$upArray['count'];
       $_pque='('; 
       foreach ($upArray['data'] as $val) {
            $_pque .='uid='.$val.' or ';
       }
       $_pque=substr($_pque,0,-4).')';
          
       $_UCD=getDbArray($table['s_upload'],$_pque,'*','gid','asc',10,1);
       $data_layout=array('6'=>'23','5'=>'23','4'=>'22','3'=>'12','2'=>'2','1'=>'1'); 

     echo '<div class="rb-attach">';
        echo '<div class="photoset-grid-lightbox" data-layout="'.$data_layout[$upQty].'">'; 
        while($_U=db_fetch_array($_UCD))
           {
                $_tmpArray=explode('.',$_U['tmpname']);
                $_name=$_tmpArray[0]; // 이름
                $_ext=$_tmpArray[1]; // 확장자
                $s_src= str_replace('.', '',$_U['url']).$_U['folder'].'/'.$_name.'_feed.'.$_ext;
                $b_src= str_replace('.', '',$_U['url']).$_U['folder'].'/'.$_name.'_b.'.$_ext;
                $img_alt=$_U['caption']?$_U['caption']:$_U['name'];
                 echo '<img src="'.$s_src.'" alt="'.$img_alt.'"  data-hightres="'.$b_src.'"/>';
          }
        echo '</div>';  
   echo '</div>';        
   } // 포토 출력부  
   // 지도 출력 
   if($C['is_place'])
   {
       $P=getDbData($table['s_place'],'parent='.$C['uid'],'*');
        // Lat, Lng 은 숫자형으로 적용해야 한다. 
   echo '<script>
               $(\'document\').ready(function(){
                  var Lat='.$P['lat'].';var Lng='.$P['lng'].';var name=\''.$P['name'].'\';var addr=\''.$P['addr'].'\';var map_id=\'cmap_'.$C['uid'].'\';
                  PlaceToMap(Lat,Lng,name,addr,map_id);
                });
            </script>
            <div id="cmap_'.$C['uid'].'" style="height:200px;"></div>';

   } // 지도 출력  
            echo '  
            <div class="rb-buttons">
                <button type="button" id="like-issue" class="rb-like btn btn-link" data-toggle="button">
                    <i class="fa fa-thumbs-up fa-fw fa-lg"></i>좋아요
                </button>
                <button type="button" class="rb-comment btn btn-link" id="'.$C['uid'].'"><i class="fa fa-comment fa-fw fa-lg"></i>댓글달기</button>
                <button type="button" class="rb-share btn btn-link" data-toggle="modal"><i class="fa fa-share fa-fw fa-lg"></i>공유하기</button>
            </div>

            <div class="panel-footer">

                <div class="rb-state-like">
                    <span id="likeMe">회원님 외 </span>
                    <a href="#" class="rb-tooltip rb-likeUsers" data-toggle="modal" data-html="true"  data-container=".rb-item"
                        title="홍길동<br>벽돌이<br>홍길동<br>벽돌이<br>홍길동<br>벽돌이<br>홍길동<br>벽돌이<br>홍길동<br>벽돌이<br>20명 더 있음">
                        13명
                    </a>
                    이 좋아합니다.
                </div>

                <!-- 이슈 공유이력이 있을 때만 출력 -->
                <div class="rb-state-share hidden">
                    <a href="#" class="rb-tooltip rb-shareUsers" data-toggle="modal">
                        공유 1회
                    </a>
                </div>

                <ul class="rb-commnets media-list" id="oneline-box-'.$C['uid'].'">';
                    getOnelineList($theme,$C['uid']); // 한줄의견 출력                 
                echo '
                </ul>

                <!-- 댓글작성 -->
                <div class="rb-comment-input media" id="comments-'.$C['uid'].'-reply">
                    <div class="media-left">
                        <a href="'.$g['s'].'/profile/'.$my['id'].'" target="_blank">
                            <img class="media-object" src="'.$my_avatar.'" alt="'.$my[$_HS['nametype']].'">
                        </a>
                    </div>
                    <div class="media-body">
                        <input type="hidden" name="parent" value="'.$C['uid'].'" />
                        <input type="hidden" name="theme" value="'.$theme.'" />
                        <input type="hidden" name="ouid" value="" />
                        <textarea class="rb-textarea form-control" id="oneline-ta-'.$C['uid'].'" name="oneline" rows="1" placeholder="댓글을 입력하세요..."></textarea>
                        <div class="rb-attach"> 
                            <button type="button" class="rb-photo rb-tooltip btn btn-link" title="사진 첨부">
                                <i class="fa fa-camera"></i>
                            </button> 
                            <button type="button" class="rb-smile rb-tooltip btn btn-link" title="스티커 올리기">
                                <i class="fa fa-smile-o"></i>
                            </button> 
                        </div>
                    </div>
                </div>

            </div>
        </div>';
		 $i++;
	} //endforeach
    
}
function getAvatarsrc()
{
   global $g,$my;
    
    if($my['photo']) $avatar_src=$g['url_root'].'/_var/avatar/'.$my['photo'];
    else  $avatar_src=$g['url_root'].'/_var/avatar/0.gif';
   
    return $avatar_src; 

}	   
