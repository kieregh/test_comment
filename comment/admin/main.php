<?php
include $g['path_module'].$module.'/var/var.php';
$bbs_time=$d['bbs']['time']; // 아래 $d 배열과 충돌을 피하기 위해서 별도로 지정
$sort	= $sort ? $sort : 'uid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 30;
$bbsque	= 'uid';
if ($where && $keyw)
{
	if (strstr('[id]',$where)) $bbsque .= " and ".$where."='".$keyw."'";
	else $bbsque .= getSearchSql($where,$keyw,$ikeyword,'or');	
}
$RCD = getDbArray($table[$module.'object'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'object'],$bbsque);
$TPG = getTotalPage($NUM,$recnum);
$_LEVELNAME = array('l0'=>'전체허용');
$_LEVELDATA=getDbArray($table['s_mbrlevel'],'','*','uid','asc',0,1);
while($_L=db_fetch_array($_LEVELDATA)) $_LEVELNAME['l'.$_L['uid']] = $_L['name'].' 이상';
?>
<div class="page-header">
 <h4>오브젝트 전체현황 
       <a href="<?php echo $g['adm_href']?>&amp;front=settings"  class="pull-right btn btn-link"><i class="fa fa-plus"></i> 새 오브젝트 만들기</a>
 </h4>
</div>
<div class="rb-heading well well-sm">
	<form name="procForm" action="<?php echo $g['s']?>/" method="get" class="form-horizontal">
		 <input type="hidden" name="r" value="<?php echo $r?>" />
		 <input type="hidden" name="m" value="<?php echo $m?>" />
		 <input type="hidden" name="module" value="<?php echo $module?>" />
		 <input type="hidden" name="front" value="<?php echo $front?>" />

       <div class="form-group" style="padding-top:19px">
			 <label class="col-sm-1 control-label">펄터</label>
			 <div class="col-sm-11">
			 	<div class="col-sm-10">
					 <div class="input-group input-group-sm">
						<span class="input-group-btn hidden-xs" style="width:165px">
							<select name="where" class="form-control btn btn-default">
								<option value="name"<?php if($where=='name'):?> selected="selected"<?php endif?>>오브젝트명 </option>
	                     <option value="id"<?php if($where=='id'):?> selected="selected"<?php endif?>>아이디</option>
							</select>
						</span>
						<input type="text" name="keyw" value="<?php echo stripslashes($keyw)?>" class="form-control">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit">검색</button>
						</span>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick="location.href='<?php echo $g['adm_href']?>';">리셋</button>
						</span>
					 </div>
				</div>
				<div class="col-sm-2">
					<select name="recnum" onchange="this.form.submit();" class="form-control input-sm">
						<option value="20"<?php if($recnum==10):?> selected="selected"<?php endif?>>10 개</option>
						<option value="35"<?php if($recnum==20):?> selected="selected"<?php endif?>>20 개</option>
						<option value="50"<?php if($recnum==30):?> selected="selected"<?php endif?>>30 개</option>
					</select>
				</div>
			</div>
	    </div> <!-- .form-group -->
		 

	</form>    
</div>  <!-- .rb-heading well well-sm : 검색영역 회색 박스  -->
<!-- 리스트 시작  -->
<div class="page-header">
	<h4>
		<small>개 ( <?php echo $p?>/<?php echo $TPG.($TPG>1?' pages':' page')?> )</small>
	</h4>
</div>
<form name="listForm" action="<?php echo $g['s']?>/" method="post">
		<input type="hidden" name="r" value="<?php echo $r?>">
		<input type="hidden" name="m" value="<?php echo $module?>">
		<input type="hidden" name="a" value="">
		<div class="table-responsive">
			<table class="table table-striped">
				<tr>
					<th><label data-tooltip="tooltip" title="선택"><input type="checkbox" class="checkAll-data-user"></label></th>
					<th>번호</th>
					<th>아이디</th>
					<th>오브젝트명</th>
					<th>대표 테마</th>
					<th>댓글 테이블</th>
					<th>댓글 쓰기 권한</th>
					<th>한줄의견 테이블</th>
					<th>관리</th>
				</tr>
				<?php while($R=db_fetch_array($RCD)):?>
				<?php $L=getOverTime($date['totime'],$R['d_last'])?>
				<?php 
				 $tooltip='<h6>'; // 요약 툴팁
				 $tooltip .='댓글 출력수 : '.number_format($R['c_recnum']).'개 출력<br />';
				 $tooltip .='댓글 포인트 : '.number_format($R['c_point']).'P 지급<br />';
				 $tooltip .='한줄의견 포인트 : '.number_format($R['o_point']).'P 지급 <br /><i></i>';
			    $tooltip .='</h6>'; 
				?>
				<tr>
					<td><input type="checkbox" name="object_members[]" value="<?php echo $R['uid']?>" class="rb-data-user" onclick="checkboxCheck();"/></td>
					<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
					<td><?php echo $R['id']?></td>
					<td><input type="text" name="name_<?php echo $R['uid']?>" value="<?php echo $R['name']?>" data-tooltip="tooltip" title="<?php echo $tooltip?>"/></td>
					<td><?php echo $R['p_theme']?></td>
					<td><?php echo $R['c_table']?></td>
					<td><?php echo $R['c_perm_write']?> 레벨 이상</td>
					<td><?php echo $R['o_table']?></td>
					<td>
							<a href="<?php echo $g['adm_href']?>&amp;front=settings&amp;uid=<?php echo $R['uid']?>">설정</a>
					</td>
		   	</tr>
				<?php endwhile?>
			</table>
		</div>
		<?php if(!$NUM):?>
		<div class="rb-none" style="padding-bottom:20px;">데이타가 존재하지 않습니다. </div>
		<?php endif?>
		<div class="rb-footer clearfix">
			<div class="pull-right">
				<ul class="pagination">
				<script>getPageLink(5,<?php echo $p?>,<?php echo $TPG?>,'');</script>
				<?php //echo getPageLink(5,$p,$TPG,'')?>
				</ul>
			</div>	
			<div>
				<button type="button" onclick="chkFlag('object_members[]');checkboxCheck();" class="btn btn-default btn-sm">선택/해제</button>
				<button type="button" onclick="actCheck('multi_modify');" class="btn btn-default btn-sm rb-action-btn" disabled>이름 수정</button>
				<button type="button" onclick="actCheck('multi_delete');" class="btn btn-default btn-sm rb-action-btn" disabled>삭제</button>

			</div>
		</div> <!-- .rb-footer --> 
</form>
<!-- basic -->
<script>
$(".checkAll-data-user").click(function(){
	$(".rb-data-user").prop("checked",$(".checkAll-data-user").prop("checked"));
	checkboxCheck();
});
function checkboxCheck()
{
	var f = document.listForm;
    var l = document.getElementsByName('object_members[]');
    var n = l.length;
    var i;
	var j=0;
	for	(i = 0; i < n; i++)
	{
		if (l[i].checked == true) j++;
	}
	if (j) $('.rb-action-btn').prop("disabled",false);
	else $('.rb-action-btn').prop("disabled",true);
}
function dropDate(date1,date2)
{
	var f = document.procForm;
	f.d_start.value = date1;
	f.d_finish.value = date2;
	f.submit();
}
function actCheck(act)
{
	var f = document.listForm;
    var l = document.getElementsByName('object_members[]');
    var n = l.length;
	var j = 0;
    var i;
    for (i = 0; i < n; i++)
	{
		if(l[i].checked == true)
		{
			j++;
		}
	}
	if (!j)
	{
		alert('선택된 오브젝트가 없습니다.     ');
		return false;
	}
	
	if (act == 'multi_modify')
	{
		if (confirm('정말로 수정하시겠습니까?       '))
		{
			getIframeForAction(f);
			f.a.value = act;
			f.submit();
		}
	}
	if (act == 'multi_delete')
	{
		if (confirm('삭제하시면 해당 댓글이 출력되지 않습니다. 정말로 수정하시겠습니까?       '))
		{
			getIframeForAction(f);
			f.a.value = act;
			f.submit();
		}
	}

	return false;
}
</script>
