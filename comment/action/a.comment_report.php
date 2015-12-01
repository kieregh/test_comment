<?php
if(!defined('__KIMS__')) exit;
if (!$my['uid']) echo '[RESULT:로그인을 먼저 해주세요.:RESULT]';
$R = getUidData($table['s_comment'],$uid);
if (!$R['uid']) echo '[RESULT:존재하지 않는 댓글입니다.:RESULT]';

include_once $theme.'/_var.php';
include_once $g['path_module'].'comment/lib/action.func.php';

if ($d['comment']['singo_del'] && $d['comment']['singo_del_num'] <= $R['singo'])
{
	if ($d['comment']['singo_del_act'] == 1)
	{
	
		 // 댓글 삭제시 추가삭제 부분 처리함수 - 한줄의견,첨부파일,장소 삭제 및 피드테이블 적용 
      setCommentDel($R,$d);
   	
		getDbDelete($table['s_comment'],'uid='.$R['uid']);
		getDbUpdate($table['s_numinfo'],'comment=comment-1',"date='".substr($R['d_regis'],0,8)."' and site=".$R['site']);
		if ($R['point']&&$R['mbruid'])
		{
			getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$R['mbruid']."','0','-".$R['point']."','댓글삭제(".getStrCut($R['subject'],15,'').")환원','".$date['totime']."'");
			getDbUpdate($table['s_mbrdata'],'point=point-'.$R['point'],'memberuid='.$R['mbruid']);
		}
		$backUrl = getLinkFilter($g['s'].'/?'.($_HS['usescode']?'r='.$r.'&amp;':'').($c?'c='.$c:'m='.$m),array('skin','iframe','sort','orderby','recnum','where','keyword'));
		getLink($backUrl ,'parent.' , '신고건수 누적으로 삭제처리 되었습니다.' , $history);
	}
	else {
		getDbUpdate($table['s_comment'],'hidden=1','uid='.$R['uid']);
		$backUrl = getLinkFilter($g['s'].'/?'.($_HS['usescode']?'r='.$r.'&amp;':'').($c?'c='.$c:'m='.$m),array('skin','iframe','sort','orderby','recnum','where','keyword'));
		getLink($backUrl ,'parent.' , '신고건수 누적으로 게시제한처리 되었습니다.' , $history);
	}
}
else {
	if (!strstr($_SESSION['module_comment_singo'],'['.$R['uid'].']'))
	{
		getDbUpdate($table['s_comment'],'singo=singo+1','uid='.$R['uid']);
		$_SESSION['module_comment_singo'] .= '['.$R['uid'].']';
		echo '[RESULT:신고처리 되었습니다.:RESULT]';//getLink('','','신고처리 되었습니다.','');
	}
	else {
		echo '[RESULT:이미 신고하신 댓글입니다.:RESULT]'; // getLink('','','이미 신고하신 댓글입니다.','');
	}
}
?>
