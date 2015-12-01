<?php
include_once $g['path_module'].$module.'/var/var.php';
if($uid) $R=getUidData($table[$module.'object'],$uid);
?>
<style>
.checkbox, .checkbox-inline, .radio-inline {margin: 0px !important ;}
.radio-inline input {position:relative !important;left:0 !important;}
</style>
<form class="form-horizontal rb-form" role="form" name="procForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>" onsubmit="return saveCheck(this);">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $module?>" />
	<input type="hidden" name="uid" value="<?php echo $uid?>" />	
	<input type="hidden" name="a" value="object_setting" />
    <div class="alert alert-info">
       <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       <?php if($uid):?>
         <strong>[수정 모드]</strong> 오브젝트 설정내용을 수정합니다.
       <?php else:?>
          <strong>[생성 모드]</strong> 오브젝트를 신규 생성합니다.  
       <?php endif?>
   </div> 
	<div class="page-header">
		<h4>댓글 설정</h4>
	</div>
    <div class="form-group">
			<label class="col-sm-2 control-label">오브젝트 이름 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#object_name-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-5">
						<input class="form-control" placeholder="" type="text" name="name" value="<?php echo $R['name']?>"<?php if(!$R['uid'] && !$g['device']):?> autofocus<?php endif?>>
					 </div>
		       </div>
		       <p class="help-block collapse" id="object_name-guide">
					 <small class="text-danger"> 
					 	오브젝트를 표현하는 명칭으로 한글,영문등 자유롭게 등록할 수 있습니다. <br />
					 	예를 들어, 블로그에 사용될 댓글인 경우 '블로그 댓글' 과 같은 식으로 하시면 됩니다.
					 </small>
			    </p>
			</div>				
	 </div>
	 <div class="form-group">
			<label class="col-sm-2 control-label">오브젝트 아이디 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#object_id-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
			<div class="col-sm-10">
				<div class="row">
					 <div class="col-sm-5">
							<input class="form-control" placeholder="" type="text" name="id" value="<?php echo $R['id']?>" <?php if($R['uid']):?>disabled<?php endif?>>
							<?php if($R['uid']):?>
							<input type="hidden" name="id" value="<?php echo $R['id']?>" />
							<?php endif?>
			         </div>
		       </div>
		        <p class="help-block collapse" id="object_id-guide">
					 <small class="text-danger"> 중복되지 않는 고유한 영문 대소문자+숫자+_ 조합으로 만듭니다.</small>
				 </p>
			</div>				
	 </div>
	<div class="form-group">
  	  <label class="col-sm-2 control-label">대표테마 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#p_theme-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label> 
     <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
			  		    <select name="p_theme" class="form-control">
							<option value="">+ 선택하세요</option>
							<option value="">--------------------------------</option>
							<?php $tdir = $g['path_module'].$module.'/theme/_pc/'?>
							<?php $dirs = opendir($tdir)?>
							<?php while(false !== ($skin = readdir($dirs))):?>
							<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
							<option value="_pc/<?php echo $skin?>" title="<?php echo $skin?>"<?php if($R['p_theme']=='_pc/'.$skin):?> selected="selected"<?php endif?>><?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
							<?php endwhile?>
							<?php closedir($dirs)?>
						</select>						
			    </div> <!-- .col-sm-3  -->
			</div> <!-- .row  -->
			<p class="help-block collapse" id="p_theme-guide">
				<small class="text-danger">
			    지정된 대표테마는 댓글 출력시 사용되는 테마입니다. 
			   </small>
			</p>
		</div> <!-- .col-sm-10  -->
	</div> <!-- .form-group  -->
	<div class="form-group">
  	  <label class="col-sm-2 control-label">모바일 테마 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#m_theme-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
     <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
			  		    <select name="m_theme" class="form-control">
							<option value="">+ PC모드와 동일</option>
							<option value="">--------------------------------</option>
							<?php $tdir = $g['path_module'].$module.'/theme/_mobile/'?>
							<?php $dirs = opendir($tdir)?>
							<?php while(false !== ($skin = readdir($dirs))):?>
							<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
							<option value="_mobile/<?php echo $skin?>" title="<?php echo $skin?>"<?php if($R['m_theme']=='_mobile/'.$skin):?> selected="selected"<?php endif?>><?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
							<?php endwhile?>
							<?php closedir($dirs)?>
						</select>
			    </div> <!-- .col-sm-3  -->
			</div> <!-- .row  -->
			<p class="help-block collapse" id="m_theme-guide">
				 <small class="text-danger">선택하지 않으면 데스크탑 대표테마로 설정됩니다.</small>
			</p>
		</div> <!-- .col-sm-10  -->
	</div> <!-- .form-group  --> 
	<div class="form-group">
  	    <label class="col-sm-2 control-label">부모 데이타 테이블명 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#p_table-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
       <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
		  		   <select name="p_table" class="form-control">
						<option value="">+ 테이블 선택</option>
						<option value="">--------------------------------</option>
					   <?php $STATUS=db_query('SHOW TABLE STATUS',$DB_CONNECT);?>
						<?php while($T=db_fetch_assoc($STATUS)):?>
						     <option value="<?php echo $T['Name']?>"<?php if($T['Name']==$R['p_table']):?> selected="selected"<?php endif?>><?php echo $T['Name']?></option>
						<?php endwhile?>
					</select>
			    </div> <!-- .col-sm-3  -->
			</div> <!-- .row  -->
			<p class="help-block collapse" id="p_table-guide">
				 <small class="text-danger">
				 	 본 댓글을 생성하는 부모계층의 오브젝트 데이타가 저장되는 테이블명을 선택해주세요. <br/>
				 </small>
			</p>
		 </div> <!-- .col-sm-10  -->
	</div> <!-- .form-group  -->
    <div class="form-group">
  	    <label class="col-sm-2 control-label">댓글 테이블명 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#c_table-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
       <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
		  		   <select name="c_table" class="form-control">
						<option value="">+ 테이블 선택</option>
						<option value="">--------------------------------</option>
					   <?php $STATUS=db_query('SHOW TABLE STATUS',$DB_CONNECT);?>
						<?php while($T=db_fetch_assoc($STATUS)):?>
						     <option value="<?php echo $T['Name']?>"<?php if($T['Name']==$R['c_table']):?> selected="selected"<?php endif?>><?php echo $T['Name']?></option>
						<?php endwhile?>
					</select>
			    </div> <!-- .col-sm-3  -->
			</div> <!-- .row  -->
			<p class="help-block collapse" id="c_table-guide">
				 <small class="text-danger">
				 	생성된 댓글 오브젝트를 통해서 입력된 댓글이 저장되는 테이블명을 선택해주세요. <br/>
				 </small>
			</p>
		 </div> <!-- .col-sm-10  -->
	</div> <!-- .form-group  -->
   <div class="form-group">
  	  <label class="col-sm-2 control-label">댓글권한</label>
     <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
			  		    <select name="c_perm_write" class="form-control">
							<option value="0">+ 전체허용</option>
							<option value="0">--------------------------------</option>
						   <?php $_LEVEL=getDbArray($table['s_mbrlevel'],'','*','uid','asc',0,1)?>
							<?php while($_L=db_fetch_array($_LEVEL)):?>
							<option value="<?php echo $_L['uid']?>"<?php if($_L['uid']==$R['c_perm_write']):?> selected="selected"<?php endif?>><?php echo $_L['name']?>(<?php echo number_format($_L['num'])?>) 이상</option>
							<?php if($_L['gid'])break; endwhile?>
						</select>
			    </div> <!-- .col-sm-3  -->
			</div> <!-- .row  -->
		</div> <!-- .col-sm-10  -->
	 </div> <!-- .form-group  -->
	 <div class="form-group">
  	   <label class="col-sm-2 control-label">소셜연동 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#c_snsconnect-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
       <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
			  		    <select name="c_snsconnect" class="form-control">
							<option value="">+ 연동안함</option>
							<option value="">--------------------------------</option>
						 	<?php $tdir = $g['path_module'].'social/inc/'?>
							<?php if(is_dir($tdir)):?>
							<?php $dirs = opendir($tdir)?>
							<?php while(false !== ($skin = readdir($dirs))):?>
							<?php if($skin=='.' || $skin == '..')continue?>
							<option value="social/inc/<?php echo $skin?>"<?php if($R['c_snsconnect']=='social/inc/'.$skin):?> selected="selected"<?php endif?>><?php echo str_replace('.php','',$skin)?></option>
							<?php endwhile?>
							<?php closedir($dirs)?>
							<?php endif?>
						</select>
			    </div> <!-- .col-sm-3  -->
			</div> <!-- .row  -->
			<p class="help-block collapse" id="c_snsconnect-guide">
				 <small class="text-danger">
				 	소셜모듈을 설치 후 사용가능합니다. <br/>
				 </small>
			</p>
		</div> <!-- .col-sm-10  -->
	 </div> <!-- .form-group  -->
	 <div class="form-group">
			<label class="col-sm-2 control-label">댓글출력수</label>
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" name="c_recnum" value="<?php echo $R['c_recnum']?$R['c_recnum']:$d['comment']['c_recnum']?>" class="form-control">
							<span class="input-group-addon">개</span>
						</div>
					</div>
				</div>
			</div>
	 </div>
	 <div class="form-group">
	 	   <label class="col-sm-2 control-label">댓글정렬 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#c_sort_orderby-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
	 	   <div class="col-sm-10"> 
	 	   	   <div class="row">
	 	   	   	    <div class="col-sm-3">
					   	<select name="c_sort" class="form-control">
							<option value="">+ 정렬기준</option>
							<option value="">--------------------------</option>
						 	<option value="uid"<?php if($R['c_sort']=='uid'):?> selected="selected"<?php endif?>>등록순(uid)</option>
						 	<option value="oneline"<?php if($R['c_sort']=='oneline'):?> selected="selected"<?php endif?>>한줄의견순(oneline)</option>
						 	<option value="score1"<?php if($R['c_sort']=='score1'):?> selected="selected"<?php endif?>>좋아요순(score1)</option>
						 	<option value="score2"<?php if($R['c_sort']=='score2'):?> selected="selected"<?php endif?>>나빠요순(score2)</option>
						 </select>
					   </div>
					   <div class="col-sm-3">
					   	    <div class="btn-group btn-group-sm" data-toggle="buttons">
								<label class="btn btn-default<?php if(!$R['c_orderby']||$R['c_orderby']=='asc'):?> active<?php endif?>" onclick="checkRadio('#radio-asc');">
									<input type="radio" value="asc" name="c_orderby"<?php if(!$R['c_orderby']||$R['c_orderby']=='asc'):?> checked<?php endif?> id="radio-asc"> <i class="fa fa-sort-amount-asc"></i> 정순
								</label>
								<label class="btn btn-default<?php if($R['c_orderby']=='desc'):?> active<?php endif?>" onclick="checkRadio('#radio-desc');">
									<input type="radio" value="desc" name="c_orderby"<?php if($R['c_orderby']=='desc'):?> checked<?php endif?> id="radio-desc"> <i class="fa fa-sort-amount-desc"></i> 역순
								</label>
					       </div>
					   </div>
				 </div>	
				 <p class="help-block collapse" id="c_sort_orderby-guide">
				     <small class="text-danger">
				     	  1. 정렬기준 <br />
				     	       해당 uid, hit,oneline,score1,score2 필드가 존재해야 적용됩니다. <br/>
				 	      2. 정순/역순 <br />
				 	          정순 - 해당 숫자가 작은 것부터 출력 ( 예: 정렬기준이 'uid' 인 경우 1,2,3...즉, 오래된 글부터 출력) <br/>
				 	          역순 - 해당 숫자가 큰 것부터 출력 ( 예: 정렬기준이 'score1' 인 경우 30, 25, 20...즉, '좋아요' 가  많은 것부터 출력)
				     </small>
		 	    </p>
			</div>
	  </div>
	  <div class="form-group">
			<label class="col-sm-2 control-label">삭제 제한</label>
			<div class="col-sm-10">
				<div class="checkbox">
					<label>
						<input  type="checkbox" name="c_onelinedel" value="1"  <?php if($R['c_onelinedel']):?> checked<?php endif?>  class="form-control">
						<i></i>한줄의견이 있는 댓글의 삭제를 제한합니다.		
					</label>
				</div>
			</div>
	 </div>		
	 <div class="form-group">
			<label class="col-sm-2 control-label">댓글포인트 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#c_point-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" name="c_point" value="<?php echo $R['c_point']?$R['c_point']:$d['comment']['c_point']?>" class="form-control">
							<span class="input-group-addon">포인트 지급</span>
						</div>
					</div>
				</div>
				<p class="help-block collapse" id="c_point-guide">
				     <small class="text-danger">등록한 댓글을 삭제시 환원됩니다</small>
		 	    </p>
			</div>
	 </div>
	  <div class="page-header" style="padding-top:20px;">
		<h4>한줄의견 설정</h4>
	  </div>
	  <div class="form-group">
			<label class="col-sm-2 control-label">한줄의견 사용여부</label>
			<div class="col-sm-10">
				<div class="checkbox">
					<label>
						<input  type="checkbox" name="use_oneline" value="1"  <?php if($R['use_oneline']):?> checked<?php endif?>  class="form-control">
						<i></i>한줄의견을 사용합니다.		
					</label>
				</div>
			</div>
	  </div>	
	  <div class="form-group">
  	    <label class="col-sm-2 control-label">한줄의견 테이블명</label>
       <div class="col-sm-10">
  		   <div class="row">
  		   	 <div class="col-sm-5">
		  		   <select name="o_table" class="form-control">
						<option value="">+ 테이블 선택</option>
						<option value="">--------------------------------</option>
					   <?php $STATUS=db_query('SHOW TABLE STATUS',$DB_CONNECT);?>
						<?php while($T=db_fetch_assoc($STATUS)):?>
						     <option value="<?php echo $T['Name']?>"<?php if($T['Name']==$R['o_table']):?> selected="selected"<?php endif?>><?php echo $T['Name']?></option>
						<?php endwhile?>
					</select>
			    </div> <!-- .col-sm-3  -->
		 	 </div> <!-- .row  -->
		  </div> <!-- .col-sm-10  -->
	   </div> <!-- .form-group  -->	
	  <div class="form-group">
	 	   <label class="col-sm-2 control-label">한줄의견정렬</label>
	 	   <div class="col-sm-10"> 
	 	   	    <div class="row"> 
	 	    	    <div class="col-sm-5">
	 	    	    	  <select name="o_orderby" class="form-control">
								<option value="">+ 선택</option>
								<option value="">-------------------------</option>
								<option value="desc"<?php if($R['o_orderby']=='desc'):?> selected="selected"<?php endif?>>최근한줄의견이 위로 정렬</option>
								<option value="asc"<?php if(!$R['o_orderby']||$R['o_orderby']=='asc'):?> selected="selected"<?php endif?>>최근한줄의견이 아래로 정렬</option>
						  </select>
					</div>					
			    </div>
			</div>
	  </div>
	 <div class="form-group">
			<label class="col-sm-2 control-label">한줄의견포인트 <small class="text-muted"><a data-toggle="collapse" data-tooltip="tooltip" title="도움말" href="#o_point-guide"><i class="fa fa-question-circle fa-fw"></i></a></small></label>
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-3">
						<div class="input-group">
							<input type="text" name="o_opoint" value="<?php echo $R['o_opoint']?$R['o_point']:$d['comment']['o_point']?>" class="form-control">
							<span class="input-group-addon">포인트 지급</span>
						</div>
					</div>
				</div>
				<p class="help-block collapse" id="o_point-guide">
				     <small class="text-danger">등록한 한줄의견을 삭제시 환원됩니다</small>
		 	    </p>
			</div>
	 </div>
	 <div class="page-header" style="padding-top:20px;">
		<h4>공통사항 설정</h4>
	 </div>
	<div class="form-group">
       <label class="col-sm-2 control-label">댓글제한단어</label>
	     <div class="col-sm-8">
             <p>
						<textarea name="badword" rows="5" class="form-control" onfocus="this.style.color='#000000';" onblur="this.style.color='#ffffff';" style="color:#fff" ><?php echo $R['badword']?$R['badword']:$d['comment']['badword']?></textarea>
 				 </p>
          </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">제한단어 처리</label>
	  	  <div class="col-sm-10">
	  	  	   <p>
	  	  	   	     <label>
				          <input type="radio" name="badword_action" value="0" <?php if($R['badword_action']==0):?> checked<?php endif?> /> 제한단어 체크하지 않음
					   </label>
              </p>
              <p> 	
               	  <label>
					     <input type="radio" name="badword_action" value="1"<?php if($R['badword_action']==1):?> checked<?php endif?> /> 등록을 차단함
                    </label> 
               </p>
               <p>
				   	 <label>
					      <input type="radio" name="badword_action" value="2"<?php if($R['badword_action']==2):?> checked<?php endif?> /> 제한단어를 다음의 문자로 치환하여 등록함
				     </label>
				       <input type="text" name="badword_escape" value="<?php echo $R['badword_escape']?$R['badword_escape']:$d['comment']['badword_escape']?>" maxlength="1"  style="width:20px;" />
               </p>
		   </div><!-- .col-sm-10 -->
	</div>
	
   <div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<button type="submit" class="btn btn-primary btn-lg"><?php echo $uid?'수정':'신규 생성'?>하기</button>
			</div>
	</div>
</form>
<script type="text/javascript">
//<![CDATA[

// radio 버튼 체크 함수
function checkRadio(id)
{
	$(id).prop("checked",true);
}
function saveCheck(f)
{
    if (f.name.value == '')
	{
		alert('오프젝트 이름을 입력해 주세요.     ');
		f.name.focus();
		return false;
	}
	<?php if(!$uid):?>
		if (f.id.value == '')
		{
			alert('오프젝트 아이디를 입력해 주세요.      ');
			 f.id.focus();
	      return false;
		}
		if (!chkFnameValue(f.id.value))
		{
			alert('오프젝트 아이디는 영문 대소문자/숫자/_ 만 사용가능합니다.      ');
			f.id.value = '';
			f.id.focus();
			return false;
		}
	<?php endif?>
	if (f.pc_theme.value == '')
	{
		alert('대표테마를 선택해 주세요.       ');
		f.pc_theme.focus();
		return false;
	}

	if (f.c_table.value == '')
	{
		alert('댓글 저장 테이블을 선택해주세요.       ');
		f.c_table.focus();
		return false;
	}
   if(f.use_oneline.checked==true)
   {
       if(f.o_table.value=='')
       {
           alert('한줄의견 저장 테이블을 선택해주세요.       ');
		    f.o_table.focus();
		    return false;   	 
       }	
   }

  if (confirm('정말로 실행하시겠습니까?         '))
   {
		getIframeForAction(f);
			f.submit();
	}
}
//]]>
</script>
