<?php
if(!defined('__KIMS__')) exit;

// 위젯에서 지정한 데이타 
$d['comment']['theme']=$comment_theme?$comment_theme:'default'; // 지정 테마 
$d['comment']['parent']=$comment_parent; //  댓글 저장시 해당 부모값을 구분하기 위해서 고유 아이디가 필요하다. (예: blog,bbs,profile)
$d['comment']['feed_table']=$feed_table; // 댓글 수 증감 피드백 받을 테이블명 (본 테이블에는 comment,oneline 필드가 존재해야 한다.) 

$g['dir_comment_module'] = $g['path_module'].'comment/';
$g['url_comment_module'] = $g['s'].'/modules/comment';

$g['dir_comment_skin'] = $g['dir_comment_module'].'theme/'.$d['comment']['theme'].'/';
$g['url_comment_skin'] = $g['url_comment_module'].'/theme/'.$d['comment']['theme'];

if(!$g['dir_module']) $g['dir_module']=$g['dir_comment_module'];

include $g['dir_comment_skin'].'main.php';  

?>
