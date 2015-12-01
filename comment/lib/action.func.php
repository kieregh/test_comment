<?php
// 댓글 삭제시 일괄적용하는 내용 세팅 함수
function setCommentDel($R,$d){
   global $table;
 
   $sync_arr=explode('|',$R['sync']);
   $feed_table=$sync_arr[0];
   $feed_uid=$sync_arr[1]; 

   // 댓글 수량 동기화
   getDbUpdate($feed_table,'comment=comment-1','uid='.$feed_uid);

   if($R['upload']) DeleteUpfile($R,$d);
   if($R['is_place']) DeletePlace($R,$d);
   if($R['oneline']) DeleteOneline($R,$d,$sync_arr);
}

//한줄의견 삭제 함수
function DeleteOneline($C,$d,$sync_arr)
{
 global $table,$date;

 $_ONELINE = getDbSelect($table['s_oneline'],'parent='.$C['uid'],'*');
 while($_O=db_fetch_array($_ONELINE))
 {
    if ($d['comment']['give_opoint']&&$_O['mbruid'])
    {
        getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_O['mbruid']."','0','-".$d['comment']['give_opoint']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($_O['content'])),15,'').")환원','".$date['totime']."'");
         getDbUpdate($table['s_mbrdata'],'point=point-'.$d['comment']['give_opoint'],'memberuid='.$_O['mbruid']);
    }
 }
 getDbDelete($table['s_oneline'],'parent='.$C['uid']);
 
 // 댓글 수량 동기화
 $sync_arr=explode('|',$R['sync']);
 $feed_table=$sync_arr[0];
 $feed_uid=$sync_arr[1]; 
  getDbUpdate($feed_table,'oneline=oneline-1','uid='.$feed_uid);
}

//첨부파일 삭제 함수
function DeleteUpfile($R,$d)
{
   global $g,$table;

   $UPFILES = getArrayString($R['upload']);

 foreach($UPFILES['data'] as $_val)
 {
    $U = getUidData($table['s_upload'],$_val);
    if ($U['uid'])
    {
         if ($U['url']==$d['comment']['ftp_urlpath'])
         {
              $FTP_CONNECT = ftp_connect($d['comment']['ftp_host'],$d['comment']['ftp_port']); 
              $FTP_CRESULT = ftp_login($FTP_CONNECT,$d['comment']['ftp_user'],$d['comment']['ftp_pass']); 
              if ($d['comment']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
              if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
              if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');

              ftp_delete($FTP_CONNECT,$d['comment']['ftp_folder'].$U['folder'].'/'.$U['tmpname']);
              if($U['type']==2) ftp_delete($FTP_CONNECT,$d['comment']['ftp_folder'].$U['folder'].'/'.$U['thumbname']);
              ftp_close($FTP_CONNECT);
         }
         else {
             unlink($U['url'].$U['folder'].'/'.$U['tmpname']);
             if($U['type']==2) unlink($U['url'].$U['folder'].'/'.$U['thumbname']);
         }
            getDbDelete($table['s_upload'],'uid='.$U['uid']);
        }
   }
}

// 장소 삭제 함수
function DeletePlace($R)
{
   global $table;

   getDbDelete($table['s_place'],'parent='.$R['uid']);
}


?>
