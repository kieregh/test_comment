<?php
if(!defined('__KIMS__')) exit;

// foreach ($_POST as $key => $val) {
//       echo $key .' : '.$val.'<br />';
// }
// exit;
 // 전송 함수 



if (!$_SESSION['wcode']){
	echo '정상적인 접근이 아닙니다.';exit;
}else{
	include $theme.'/_var.php';
	include $theme.'/function.php';
	$mbruid		= $my['uid'];
	$id			= $my['id'];
	$name		= $my['uid'] ? $my['name'] : trim($name);
	$nic		= $my['uid'] ? $my['nic'] : $name;
	$pw			= $pw ? md5($pw) : ''; 
	$subject	= $my['admin'] ? trim($subject) : htmlspecialchars(trim($subject));
	$content	= trim($content);
	$subject	= $subject ? $subject : getStrCut(str_replace('&amp;',' ',strip_tags($content)),35,'..');
	$html		= $html ? $html : 'TEXT';
	$d_regis	= $date['totime'];
	$d_modify	= '';
	$d_oneline	= '';
	$ip			= $_SERVER['REMOTE_ADDR'];
	$agent		= $_SERVER['HTTP_USER_AGENT'];
	//$upload		= $upfiles; // upfiles 값을 배열로 받아서 풀어서 upload 에 저장한다.  아래 참조 
	$adddata	= trim($adddata);
	$hit		= 0;
	$down		= 0;
	$oneline	= 0;
	$score1		= 0;
	$score2		= 0;
	$report		= 0;
	$point		= $d['comment']['give_point'];
	$hidden		= $hidden ? intval($hidden) : 0;
	$notice		= $notice ? intval($notice) : 0;
	$display	= $hidepost || $hidden ? 0 : 1;

	// 포토, 장소, 링크 존재여부 
	$is_photo=0;
	$is_link=0;
	$is_place=0;

	if ($d['comment']['perm_write'] > $my['level'])
	{
		echo '댓글등록 권한이 없습니다.';exit;
	}
	if ($d['comment']['badword_action'])
	{
		$badwordarr = explode(',' , $d['comment']['badword']);
		$badwordlen = count($badwordarr);
		for($i = 0; $i < $badwordlen; $i++)
		{
			if(!$badwordarr[$i]) continue;
			if(strstr($subject,$badwordarr[$i]) || strstr($content,$badwordarr[$i]))
			{
				if ($d['comment']['badword_action'] == 1)
				{
					echo '등록이 제한된 단어를 사용하셨습니다.';exit;
				}
				else {
					$badescape = strCopy($badwordarr[$i],$d['comment']['badword_escape']);
					$content = str_replace($badwordarr[$i],$badescape,$content);
					$subject = str_replace($badwordarr[$i],$badescape,$subject);
				}
			}
		}
	}
  // 업로드 파일 세팅 
	if($upfiles)
	{	
		$upload='';
		foreach ($upfiles as $file) {
	      $upload .=$file;   
		}
		$upload=trim($upload);
		$is_photo=1;
   }
   if($links!='') $is_link=1;
   if($lat!='' && $lng!='') $is_place=1;
   
	if ($uid)
	{
		$R = getUidData($table['s_comment'],$uid);
		if (!$R['uid']){
			echo '존재하지 않는 댓글입니다.';exit;
		} 

		if (!$my['id'] || ($my['id'] != $R['id'] && !$my['admin']))
		{
			if (!$pw)
			{
				echo '수정모드 : 정상적인 접근이 아닙니다.';exit;
			}
			else {
				if($pw != $R['pw'])
				{
					echo '수정모드 : 정상적인 접근이 아닙니다.';exit;
				}
			}
		}
     
		$QVAL = "display='$display',hidden='$hidden',notice='$notice',subject='$subject',content='$content',html='$html',";
		$QVAL .="d_modify='$d_regis',upload='$upload',adddata='$adddata',links='$links',is_link='$is_link',is_photo='$is_photo',is_place='$is_place'";
		getDbUpdate($table['s_comment'],$QVAL,'uid='.$R['uid']);

		 // 장소정보 수정 
		 $_PAL="lat='$lat',lng='$lng',address='$address'";
		 getDbUpdate($table['s_place'],$_PAL,'parent='.$uid);

	}
	else 
	{
		// $parent_set  가공 
		$parent_arr=explode('-',$parent);
		$parent_uid=$parent_arr[1];
		$parent_set=str_replace('-','', $parent);
	    
		$R = getUidData($feed_table,$parent_uid);
		getDbUpdate($feed_table,"comment=comment+1,d_comment='".$date['totime']."'",'uid='.$R['uid']);
		$sync = $feed_table.'|'.$parent_uid.'|'.$parentmbr; 
		$minuid = getDbCnt($table['s_comment'],'min(uid)','');
		$uid = $minuid ? $minuid-1 : 1000000000;
		
		$QKEY = "uid,site,parent,parentmbr,display,hidden,notice,name,nic,mbruid,id,pw,subject,content,html,";
		$QKEY.= "hit,down,oneline,score1,score2,report,point,d_regis,d_modify,d_oneline,upload,ip,agent,sync,sns,adddata,links,is_link,is_photo,is_place";
		$QVAL = "'$uid','$s','".$parent_set."','$parentmbr','$display','$hidden','$notice','$name','$nic','$mbruid','$id','$pw','$subject','$content','$html',";
		$QVAL.= "'$hit','$down','$oneline','$score1','$score2','$report','$point','$d_regis','$d_modify','$d_oneline','$upload','$ip','$agent','$sync','','$adddata','$links','$is_link','$is_photo','$is_place'";
		getDbInsert($table['s_comment'],$QKEY,$QVAL);
		getDbUpdate($table['s_numinfo'],'comment=comment+1',"date='".$date['today']."' and site=".$s);
		if ($point&&$my['uid'])
		{
			getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','".$point."','댓글(".getStrCut($subject,15,'').")포인트','".$date['totime']."'");
			getDbUpdate($table['s_mbrdata'],'point=point+'.$point,'memberuid='.$my['uid']);
		}
		$_SESSION['xW'] = $uid;
		if ($snsCallBack && is_file($g['path_module'].$snsCallBack))
		{
			$xcync = $cync.',CMT:'.$uid;
			$orignSubject = strip_tags($subject);
			$orignContent = getStrCut($orignSubject,60,'..');
			$orignUrl = 'http://'.$_SERVER['SERVER_NAME'].str_replace('./','/',getCyncUrl($xcync)).'#CMT';
			include $g['path_module'].$snsCallBack;
			if ($snsSendResult)
			{
				getDbUpdate($table['s_comment'],"sns='".$snsSendResult."'",'uid='.$uid);
			}
		}// sns 동시 등록 	

		// // 페북 등록 
	 //   $orignSubject = strip_tags($subject);
		// $orignContent = getStrCut($orignSubject,60,'..');
		// $orignUrl = 'http://'.$_SERVER['SERVER_NAME'].'/feed/'.$uid;

  //  //    function SnsCurlPost($param_arr,$id)
		// 	// {
		// 	// 	global $r;

		// 	// 	// 전송 url 
		// 	// 	 $url='graph.facebook.com/'.$id.'/feed?';
		// 	//     $fields=http_build_query($param_arr);
			    
		// 	//     $ch = curl_init();
		// 	// 	 curl_setopt($ch,CURLOPT_URL,$url);
		// 	// 	 curl_setopt($ch,CURLOPT_POST,1);
		// 	// 	 curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
		// 	// 	 curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30); 
		// 	// 	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		// 	// 	 curl_setopt($ch, CURLOPT_REFERER, $referer );
		// 	// 	 curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 
		// 	// 	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    
		// 	// 	$result = curl_exec($ch);
		// 	// 	curl_close($ch);
				    
		// 	// 	return $result;	    
		// 	// }
			// $_mysnsdat=explode(',',$g['mysns'][1]);
			// // $id=$_mysnsdat[4];
			// // $ac_token=$_mysnsdat[2];

			// // $param_arr=array('message' =>$originSubject,'access_token'=>$ac_token);
	  // //     $curl_send=SnsCurlPost($param_arr,$id);
	  // //     $FBRESULT=json_encode($curl_send,true);
	  //      require_once $g['path_module'].'social/oauth/facebook/src/facebook.php';
		 //   $FBCONN = new Facebook(array('appId'=>$d['social']['key_f'],'secret'=>$d['social']['secret_f']));    
			// $FBRESULT = $FBCONN->api('/'. $_mysnsdat[4].'/feed?access_token='.$_mysnsdat[2],'POST',array('access_toten'=>$_mysnsdat[2],'message' => $orignSubject.'   '.$orignUrl));
			// if($FBRESULT['id'])
			// {
			// 	$FBPARAM = explode('_',$FBRESULT['id']);
			// 	$FBPAURL = 'http://facebook.com/permalink.php?story_fbid='.$FBPARAM[1].'&id='.$_mysnsdat[4];
			// 	$QVAL = "'$snsgid','f','".$_mysnsdat[4]."','$subject','$name','$nic','$my[uid]','$my[id]','$FBPAURL','$xcync','$date[totime]'";
			// 	getDbInsert($table['socialdata'],$QKEY,$QVAL);
			// 	$snsSendResult .= getDbCnt($table['socialdata'],'max(uid)','').',';
			// 	$snsgid--;
			// }

      // place 정보 등록
      if($lat!='' && $lng!='')
      {	
         $_PKEY="parent,mbruid,name,address,lat,lng,type,date";
         $_PVAL="'$uid','$mbruid','$place_name','$location','$lat','$lng','$place_type','".$date['today']."'";
          getDbInsert($table['s_place'],$_PKEY,$_PVAL);
       }
       // 업로드 파일에 대한 parent 값 세팅 
       if($upload)
       { 
       	 $up_arr=getArrayString($upload);
       	 for($i=0;$i<count($up_arr['data']);$i++) {
       	    getDbUpdate($table['s_upload'],"parent='".$m.$uid."'",'uid='.$up_arr['data'][$i]);
       	 }
       }
	} 
	// 신규등록 
} 

$response=getCommentList($theme,$parent,$_where,$c_recnum,$c_sort,$c_orderby,$orderby2,$c_page);
echo $response;

exit;
?>
