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
          $compare_time=$O['d_modify']?$O['d_modify']:$O['d_regis'];
          $JN_time=getJNTime($compare_time); // 지난시간 얻기 함수 호출
     	  $M=getDbData($table['s_mbrdata'],'memberuid='.$O['mbruid'],'*');
     	   // 아바타 사진 url 세팅
           if($M['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$M['photo'];
           else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';

     	    echo '<div class="media one-list">
		                  <a target="_blank" title="프로필 새창 열림" data-toggle="tooltip" href="" class="pull-left hidden-xs rb-avatar">
								     <img src="'.$avatar_img.'" class="media-object img-rounded" alt="32x32" data-src="holder.js/32x32" style="width: 32px; height: 32px;">
								</a>									 
								 <div class="media-body">
								      <h6 class="media-heading">
				  		                <a class="a-muted" data-toggle="popover" href="">'.($O[$_HS['nametype']]?$O[$_HS['nametype']]:$O['name']).'</a>
				  		                <span class="text-muted small time-wrap"><time title="'.getDateFormat($compare_time,'Y년 m월 d일  H시 i분').'" data-toggle="tooltip" class="timeago live-tooltip">'.$JN_time.'</time></span>
				  	                   <span class="pull-right">';
				  	                      echo '<input type="hidden" name="theme" class="theme" value="'.$theme.'" />';
				  	                      echo '<input type="hidden" name="parent" class="parent" value="'.$parent.'" />';
				  	                      echo '<input type="hidden" name="o_content" class="o_content" value="'.getContents($O['content'],$O['html'],$keyword).'" />';
				  	                     if($my['admin']||$O['mbruid']==$my['uid'])
                                     {
                                      	  echo '<span class="text-muted"><a href="#" id="delete-'.$O['uid'].'" class="live-tooltip one-tools" title="삭제" ><i class="fa fa-trash-o"></i></a></span>';
                                      	  echo '<span class="text-muted"><a href="#" id="edit-'.$O['uid'].'" class="live-tooltip one-tools" title="수정" ><i class="fa fa-edit"></i></a></span>';
                                      }else{
                                          echo '<span class="text-muted"><a href="#" id="report-'.$O['uid'].'" class="live-tooltip one-tools" title="신고" ><i class="fa fa-frown-o"></i></a></span>';      
                                     }
				  	              echo '</span>
				  	               </h6>
										<article class="small">'.getContents($O['content'],$O['html'],$keyword).'</article>
						       </div>';
				echo '</div>'; // media : 반복되는 리스트 
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
	    if($C['mbruid']) $M=getDbData($table['s_mbrdata'],'memberuid='.$C['mbruid'],'*');else $M=array();
	    $isSECRETCHECK=true;
        $compare_time=$C['d_modify']?$C['d_modify']:$C['d_regis'];
		$JN_time=getJNTime($compare_time); // 지난시간 얻기 함수 호출
       
        // 아바타 사진 url 세팅
       if($M['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$M['photo'];
       else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';

       
		 echo '
		   <a name="one-write2-'.$C['uid'].'"></a>
	   	<li id="'.$C['uid'].'-'.$C['score1'].'-'.$C['oneline'].'" class="list-group-item comment-list">
				<div class="media" id="cuid-'.$C['uid'].'">
				   <input type="hidden" name="is_namegi" value="'.$is_namegi.'"/>
				   <input type="hidden" name="TPG" value="'.$TPG.'"/>
				   <input type="hidden" name="theme" class="theme" value="'.$theme.'" />
	  	         <input type="hidden" name="parent" class="parent" value="'.$parent.'" />
	  	         <input type="hidden" name="c_content" class="c_content" value="'.htmlspecialchars(getContents($C['content'],$C['html'],$keyword)).'" />
                  <div class="media-left">
					   <a target="_blank" title="프로필 새창 열림" data-toggle="tooltip" href="#" class="pull-left hidden-xs rb-avatar">
						   <img src="'.$avatar_img.'" class="media-object img-rounded" alt="48x48" data-src="holder.js/48x48" style="width: 48px; height: 48px;">
					   </a>
                  </div>
					<div class="media-body">
					  	<h5 class="media-heading">
					  		 <a class="a-muted" data-toggle="popover" href="">'.($C[$_HS['nametype']]?$C[$_HS['nametype']]:$C['name']).'</a>
					  		 <span class="text-muted small time-wrap"><time title="'.getDateFormat($compare_time,'Y년 m월 d일  H시 i분').'" data-toggle="tooltip" class="timeago live-tooltip">'.$JN_time.'</time></span>
                           <span></span>
					  	    <span class="pull-right top-tools">';
	  	                      if($my['admin']||$C['mbruid']==$my['uid'])
                              {
                               	 echo '<span class="text-muted"><a href="#" id="delete-'.$C['uid'].'" class="live-tooltip cmt-tools" title="삭제" ><i class="fa fa-trash-o"></i></a></span>';
                                   echo '<span class="text-muted"><a href="#" id="edit-'.$C['uid'].'" class="live-tooltip cmt-tools" title="수정" ><i class="fa fa-edit"></i></a></span>';
                               }else{
                                   echo '<span class="text-muted"><a href="#" id="report-'.$C['uid'].'" class="live-tooltip cmt-tools" title="신고" ><i class="fa fa-frown-o"></i></a></span>';      
                               }
				  	           echo '</span>
					  	</h5>';
  		echo '<hr class="cmt-hr">
    	  	         <div class="panel-body nopd-left comment-content" id="comment-content-'.$C['uid'].'">';
           	    	if(!$C['hidden'] || $my['admin'] || ($my['id']&&$my['id']==$C['id']) || strstr($_SESSION['module_'.$m.'_view'],'['.$C['uid'].']')){
    	   	         echo autolink(getContents($C['content'],$C['html'],$keyword));
    	    	}else{
                              $isSECRETCHECK=false;
    		         echo '<i class="fa fa-lock-o"></i> <a href="'.$g['cment_modify'].$C['uid'].'" onclick="return cmentHidden(\''.$C['id'].'\',\''.$C['uid'].'\',event);">비공개 댓글입니다.</a>';
    	  	    }
            echo '</div>';
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
         $_pque='('; 
         foreach ($upArray['data'] as $val) {
            $_pque .='uid='.$val.' or ';
         }
        $_pque=substr($_pque,0,-4).')';
          
         $_UCD=getDbArray($table['s_upload'],$_pque,'*','gid','asc',10,1);
         echo '<ul class="rb-img-wrapper list-inline">'; 
            while($_U=db_fetch_array($_UCD))
               {
                    $img= str_replace('.', '',$_U['url']).$_U['folder'].'/'.$_U['tmpname'];
                    $img_src='/_core/opensrc/thumb/thumb.php?width=100&amp;cropratio=2:1.5&amp;image='.$img;
                    $img_alt=$_U['caption']?$_U['caption']:$_U['name'];
                     echo '<li><a href="#"><img src="'.$img.'" class="img-responsive" alt="'.$img_alt.'" width="100px" height="100px" /></a></li>';
              }
              echo '</ul>';        
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

		    echo '<div class="clearfix">
							<div class="pull-left" style="margin-top:4px;">
								<a class="small live-tooltip" href="#comments-'.$C['uid'].'-reply" data-parent="#comments-'.$C['uid'].'" data-toggle="collapse">답글 <span id="one-num-'.$C['uid'].'">'.$C['oneline'].'</span> <i class="fa fa-sort-desc"></i></a>
                              <span class="thumb-wrap" style="margin-left:15px;">
                                  <a class="live-tooltip cmt-tools" title="공감" data-toggle="tooltip" id="good-'.$C['uid'].'"><i class="fa fa-thumbs-up fa-sm"></i> <span id="good-num-'.$C['uid'].'">'.$C['score1'].'</span></a>
                                  <a class="live-tooltip cmt-tools" title="비공감" data-toggle="tooltip" id="bad-'.$C['uid'].'" style="margin-left:7px;"><i class="fa fa-thumbs-down fa-sm"></i> <span id="bad-num-'.$C['uid'].'">'.$C['score2'].'</span></a>
                              </span>
							</div>
							<div class="pull-right">
					            <div class="clearfix cmt-modify-btn-wrap" style="display:none;margin:5px 0" id="btn-cmt-modify-wrap-'.$C['uid'].'">
                                    <button class="btn btn-default btn-sm cmt-modify-cancel" id="'.$C['uid'].'" >취소</button>
                                    <button class="btn btn-primary btn-sm cmt-modify" id="'.$C['uid'].'" >수정</button>
                               </div> 
							</div>
						</div>
						<!-- nested -->
						<div class="rb-comment-reply collapse" id="comments-'.$C['uid'].'-reply">
							<!-- 의견쓰기 -->
							<div class="media">								
								<div class="media-left" style="width:90%;">
								    <input type="hidden" name="parent" value="'.$C['uid'].'" />
								     <input type="hidden" name="theme" value="'.$theme.'" />
								     <input type="hidden" name="ouid" value="" />
                                                     <textarea placeholder="답글을 입력하세요..." name="oneline"  rows="1" class="form-control rb-commnet-reply"></textarea>
			                              </div>
                                                <div class="media-body">
                                                       <button class="btn btn-primary btn-md one-regis" id="one-regis-'.$C['uid'].'">등록</button>
							       </div>								
							</div>';
						 	echo '<div id="oneline-box-'.$C['uid'].'" class="one-list-wrap">';
						          // 한줄 의견 리스트 출력함수 호출 
						          getOnelineList($theme,$C['uid']);
						   echo  '</div>'; // 한 줄 의견 출력 박스 	 															 	
			echo '</div>
					</div>
				</div>
			</li>';
		 $i++;
		} //endforeach
	   // 더보기 버튼 설정시 
	   if($NUM && $d['comment']['show_more'])
	   {
	   	 echo '<button class="text-center btn btn-default btn-block '.$btn_more.'"><i class="fa fa-sort-desc fa-lg"></i><span class="text-muted small"> 더 보기 ( '.$namegi_ment.' ) </span></button>';
	   }	
      //    if(!$NUM){
      // echo '<div class="rb-timeline">
      //               <div class="rb-nopost">
      //                   <i class="fa fa-exclamation-circle"></i>
      //                   등록된 소식이 없습니다.
      //               </div>
      //           </div>';
      //    }
    
}
function getAvatarsrc()
{
   global $g,$my;
    
    if($my['photo']) $avatar_src=$g['url_root'].'/_var/avatar/'.$my['photo'];
    else  $avatar_src=$g['url_root'].'/_var/avatar/0.gif';
   
    return $avatar_src; 

}	   
