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
     	  $M=getDbData($table['s_mbrdata'],'memberuid='.$O['mbruid'],'*');
     	   // 아바타 사진 url 세팅
           if($M['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$M['photo'];
           else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';

     	    echo '<div class="media one-list">
		                  <a target="_blank" title="트위터 프로필 새창 열림" data-toggle="tooltip" href="" class="pull-left hidden-xs rb-avatar">
								     <img src="'.$avatar_img.'" class="media-object img-rounded" alt="32x32" data-src="holder.js/32x32" style="width: 32px; height: 32px;">
								</a>									 
								 <div class="media-body">
								      <h6 class="media-heading">
				  		                <a class="a-muted" data-toggle="popover" href="">'.($O[$_HS['nametype']]?$O[$_HS['nametype']]:$O['name']).'</a>
				  		                <span class="text-muted small time-wrap"><time title="'.getDateFormat($O['d_regis'],'Y년 m월 d일  H시 i분').'" data-toggle="tooltip" class="timeago live-tooltip">'.getJNTime($O['d_regis']).'</time></span>
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

   // 한줄의견 쓰는(현재 로그인한) 사용자 아바타 사진 url 세팅
   if($my['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$my['photo'];
   else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';
   
    foreach($RCD as $C) 
    {
    	 $C['mobile']=isMobileConnect($C['agent']);
	    if($C['mbruid']) $M=getDbData($table['s_mbrdata'],'memberuid='.$C['mbruid'],'*');else $M=array();
	    $isSECRETCHECK=true;
		$JN_time=getJNTime($C['d_regis']); // 지난시간 얻기 함수 호출
       
        // 댓글 작성자 아바타 사진 url 세팅
       if($M['photo']) $avatar_img=$g['url_root'].'/_var/avatar/'.$M['photo'];
       else  $avatar_img=$g['url_root'].'/_var/avatar/0.gif';

       
		 echo '
         <section id="pinBoot" class="rb-pinterest-grid">
		   <article class="panel panel-default panel-google-plus comment-list" id="'.$C['uid'].'-'.$C['score1'].'-'.$C['oneline'].'">
             <div  id="cuid-'.$C['uid'].'">
                 <input type="hidden" name="is_namegi" value="'.$is_namegi.'"/>
                 <input type="hidden" name="TPG" value="'.$TPG.'"/>
                 <input type="hidden" name="theme" class="theme" value="'.$theme.'" />
                 <input type="hidden" name="parent" class="parent" value="'.$parent.'" />
                 <input type="hidden" name="c_content" class="c_content" value="'.htmlspecialchars(getContents($C['content'],$C['html'],$keyword)).'" />
                 <div class="dropdown">
                     <span class="dropdown-toggle" type="button" data-toggle="dropdown">
                         <span class="[ glyphicon glyphicon-chevron-down ]"></span>
                     </span>
                     <ul class="dropdown-menu" role="menu">
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                         <li role="presentation" class="divider"></li>
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                     </ul>
                 </div>
                 <div class="panel-google-plus-tags">
                     <ul>
                         <li>#자동차</li>
                         <li>#귀성길</li>
                     </ul>
                 </div>
                 <div class="panel-heading">
                     <img class="img-circle pull-left" src="/_core/opensrc/thumb/image.php?width=46&amp;height:46&amp;cropratio=1:1&amp;image='.$avatar_img.'" alt="'.$M[$_HS['nametype']].' 아바타 " />
                     <h3>소비자와 함께</h3>
                     <h5><span>제한적으로 공유함</span> - <span>'.getDateFormat($C['d_regis'],'Y. m. d').'</span> </h5>
                 </div>
                 <div class="panel-body">
                     <p>'.getContents($C['content'],$C['html'],$keyword).'</p>
                 </div>
                 <div class="panel-footer">
                     <button type="button" class="btn btn-default">+1</button>
                     <button type="button" class="btn btn-default">
                         <span class="glyphicon glyphicon-share-alt"></span>
                     </button>
                     <div class="input-placeholder">댓글 추가...</div>
                 </div>
                 <div class="panel-google-plus-comment" id="comments-'.$C['uid'].'-reply">
                     <input type="hidden" name="parent" value="'.$C['uid'].'" />
                     <input type="hidden" name="theme" value="'.$theme.'" />
                     <input type="hidden" name="ouid" value="" />
                     <img class="img-circle pull-left" src="/_core/opensrc/thumb/image.php?width=46&amp;height:46&amp;cropratio=1:1&amp;image='.$avatar_img.'" alt="'.$my[$_HS['nametype']].' 아바타 " />
                     <div class="panel-google-plus-textarea">
                         <textarea rows="4"></textarea>
                         <button type="button" class="btn btn-success disabled" id="one-regis-'.$C['uid'].'">댓글 올리기</button>
                         <button type="reset" class="btn btn-default">취소</button>
                     </div>
                     <div class="clearfix"></div>
                 </div>';
                   echo '<div id="oneline-box-'.$C['uid'].'" class="one-list-wrap">';
                      // 한줄 의견 리스트 출력함수 호출 
                            getOnelineList($theme,$C['uid']);
                   echo  '</div>'; // 한 줄 의견 출력 박스   
             echo '</div>   
          </article>
          </section>';
		 $i++;
		} //endforeach
	   $R=array(); 
	    // 더보기 버튼 설정시 
	   if($d['comment']['show_more'])
	   {
	   	 echo '<button class="text-center btn btn-default btn-block '.$btn_more.'"><i class="fa fa-sort-desc fa-lg"></i><span class="text-muted small"> 더 보기 ( '.$namegi_ment.' ) </span></button>';
	   }	
    
}	   
