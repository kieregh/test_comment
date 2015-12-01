
 <form name="cmtform" id="cmtform"  method="post" action="<?php echo $g['s']?>/" >
    <input type="hidden" name="r" value="<?php echo $r?>" />
    <input type="hidden" name="m" value="comment" />
    <input type="hidden" name="a" value="comment_regis" />        
    <input type="hidden" name="parent" value="<?php echo $d['comment']['parent']?>" />
    <input type="hidden" name="parentmbr" value="<?php echo $my['uid']?>" />
    <input type="hidden" name="uid" value="<?php echo $C['uid']?>" />
    <input type="hidden" name="pcode" value="<?php echo $date['totime']?>" />
    <input type="hidden" name="wcode" value="<?php echo $_SESSION['wcode']?>" />
    <input type="hidden" name="theme" value="<?php echo $g['dir_comment_skin']?>" /><!-- 테마값  -->
    <input type="hidden" name="feed_table" value="<?php echo $d['comment']['feed_table']?>" /><!-- 피드백 받을 테이블명  -->
    <input type="hidden" name="c_sort" value="<?php echo $d['comment']['sort']?>" /><!-- sort  -->
    <input type="hidden" name="c_orderby" value="<?php echo $d['comment']['orderby1']?>" /><!-- sort  -->
    <input type="hidden" name="c_recnum" value="<?php echo $d['comment']['recnum']?>" /><!-- 출력수  -->
    <input type="hidden" name="c_page" value="1" /><!-- 페이지값 -->
    <input type="hidden" name="content" value="<?php echo $C['content']?>" />
    <input type="hidden" name="html" value="TEXT" />
 </form>

<div class="jquery-comments comment-input-box">
    <div class="commenting-field main">
       <img src="<?php echo getAvatarsrc();?>" class="profile-picture img-round by-current-user">
       <div class="textarea-wrapper">
            <span class="close" style="display: none;">
              <span class="left"></span><span class="right"></span>
            </span>
            <div contenteditable="true" class="textarea" name="content" data-placeholder="댓글을 입력해주세요. " style="height: 3.65em;"></div>
            <div class="control-row" style="display: none;">
                <span class="send save highlight-background cmt-post">게시</span>
            </div>
        </div>
     </div>
</div>
