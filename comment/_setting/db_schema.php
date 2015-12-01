<?php
if(!defined('__KIMS__')) exit;

//댓글 오브젝트 설정
$_tmp = db_query( "select count(*) from ".$table[$module.'object'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("
CREATE TABLE ".$table[$module.'object']." (
uid         INT             PRIMARY KEY     NOT NULL AUTO_INCREMENT,
id   VARCHAR(30)     DEFAULT ''      NOT NULL,
name        VARCHAR(200)    DEFAULT ''      NOT NULL,
p_theme    VARCHAR(100)    DEFAULT ''      NOT NULL,
m_theme   VARCHAR(100)    DEFAULT ''      NOT NULL,
use_oneline   TINYINT(1)   DEFAULT '0'      NOT NULL,
p_table   VARCHAR(20)    DEFAULT ''      NOT NULL,
c_perm_write   TINYINT(4)   DEFAULT '0'      NOT NULL,
c_table   VARCHAR(20)    DEFAULT ''      NOT NULL,
c_snsconnect   VARCHAR(100)    DEFAULT ''      NOT NULL,
c_recnum   VARCHAR(20)    DEFAULT ''      NOT NULL,
c_sort   VARCHAR(10)    DEFAULT ''      NOT NULL,
c_orderby  VARCHAR(10)    DEFAULT ''      NOT NULL,
c_point   INT    DEFAULT '0'      NOT NULL,
c_onelinedel   TINYINT(1)   DEFAULT '0'      NOT NULL,
o_table   VARCHAR(20)    DEFAULT ''      NOT NULL,
o_orderby  VARCHAR(10)    DEFAULT ''      NOT NULL,
o_point   INT    DEFAULT '0'      NOT NULL,
badword  TEXT   NOT NULL,
badword_action TINYINT(1)   DEFAULT '0'      NOT NULL,
badword_escape VARCHAR(10)    DEFAULT ''      NOT NULL,
KEY id(id),
KEY c_table(c_table)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'object'],$DB_CONNECT); 
}
?>
