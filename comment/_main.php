<?php
if(!defined('__KIMS__')) exit;
// 댓글 초기 설정값 세팅
function Comment_int($object_id)
{
   global $g,$table;
   $R=getDbData($table['commentobject'],"id='".$object_id."'",'*');
   return $R;
}

