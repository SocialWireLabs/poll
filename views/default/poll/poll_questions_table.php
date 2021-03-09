<?php

$poll = $vars['entity'];
$pollpost = $poll->getGUID();

$options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'limit'=>0, 'type' => 'object', 'subtype' => 'poll_question','order_by_metadata' => array('name'=>'index','direction' => 'asc', 'as' => 'integer'));

$questions=elgg_get_entities_from_relationship($options);

if (empty($questions)){
   $num_questions = 0;
}else{
   $num_questions = count($questions);
}

$edit_msg = elgg_echo('poll:edit_question');
$delete_msg = elgg_echo('poll:delete_question');
$delete_confirm_msg = elgg_echo('poll:delete_question_confirm');
$moveup_msg = elgg_echo('poll:up');
$movedown_msg = elgg_echo('poll:down');
$movetop_msg = elgg_echo('poll:top');
$movebottom_msg = elgg_echo('poll:bottom');
	
$img_template = '<img border="0" width="16" height="16" alt="%s" title="%s" src="'.elgg_get_config('wwwroot').'mod/poll/graphics/%s" />';
$edit_img = sprintf($img_template,$edit_msg,$edit_msg,"edit_question.jpeg");
$delete_img = sprintf($img_template,$delete_msg,$delete_msg,"delete_question.gif");
	
$question_txt = elgg_echo('poll:questions');
$edit_txt = elgg_echo('poll:edit_question');
$up_txt = elgg_echo('poll:up');
$down_txt = elgg_echo('poll:down');
$top_txt = elgg_echo('poll:top');
$bottom_txt = elgg_echo('poll:bottom');
$delete_txt = elgg_echo('poll:delete_question');
	

	$body .= <<<EOF
			<SCRIPT>
				function call_mouse_over_function(object){
					$(object).css("background","#E3F1FF");
				}
				function call_mouse_out_function(object){
					$(object).css("background","");
				}
			</SCRIPT>
			<table class="poll_questions_list_table">
				
EOF;

foreach($questions as $question){
        $index=$question->index;
        $question_guid = $question->getGUID();
    	$class = $class == "poll_questions_list_table_odd" ? "poll_questions_list_table_even" : "poll_questions_list_table_odd";
        $moveup_img = sprintf($img_template,$moveup_msg,$moveup_msg,"up.png");
	$movedown_img = sprintf($img_template,$movedown_msg,$movedown_msg,"down.png");
	$movetop_img = sprintf($img_template,$movetop_msg,$movetop_msg,"top.png");
	$movebottom_img = sprintf($img_template,$movebottom_msg,$movebottom_msg,"bottom.png");
	$up_script = "";
    	$top_script = "";
    	$down_script = "";
    	$bottom_script = "";

	$url_delete=elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/delete_question?pollpost=" . $pollpost . "&index=" . $index);
	$url_edit = elgg_add_action_tokens_to_url(elgg_get_site_url() . "poll/edit_question/" . $pollpost . "/" . $index);
	$url_up = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/move_question?pollpost=" . $pollpost . "&act=up" . "&index=" . $index);
	$url_down = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/move_question?pollpost=" . $pollpost . "&act=down" . "&index=" . $index);
	$url_top = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/move_question?pollpost=" . $pollpost . "&act=top" . "&index=" . $index);
	$url_bottom = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/move_question?pollpost=" . $pollpost . "&act=bottom" . "&index=" . $index);

	$text_question = elgg_get_excerpt($question->question,45);

    	if ($num_questions == 1) {
    		$up_script = 'Onclick="javascript:return false;"';
        	$top_script = 'Onclick="javascript:return false;"';
        	$down_script = 'Onclick="javascript:return false;"';
        	$bottom_script = 'Onclick="javascript:return false;"';
        	$moveup_img = sprintf($img_template,$moveup_msg,$moveup_msg,"up_dis.png");
        	$movetop_img = sprintf($img_template,$movetop_msg,$movetop_msg,"top_dis.png");
        	$movedown_img = sprintf($img_template,$movedown_msg,$movedown_msg,"down_dis.png");
        	$movebottom_img = sprintf($img_template,$movebottom_msg,$movebottom_msg,"bottom_dis.png");
        	
	} elseif ($num_questions == 2) {
        	$top_script = 'Onclick="javascript:return false;"';
        	$bottom_script = 'Onclick="javascript:return false;"';
        	$movetop_img = sprintf($img_template,$movetop_msg,$movetop_msg,"top_dis.png");
        	$movebottom_img = sprintf($img_template,$movebottom_msg,$movebottom_msg,"bottom_dis.png");
		if ($index==0){
		   $up_script = 'Onclick="javascript:return false;"';
		   $moveup_img = sprintf($img_template,$moveup_msg,$moveup_msg,"up_dis.png");
		} else {
		   $up_script = "";
		}
		if ($index+1 == $num_questions){
		   $down_script = 'Onclick="javascript:return false;"';
		   $movedown_img = sprintf($img_template,$movedown_msg,$movedown_msg,"down_dis.png");
		} else {
		   $down_script = "";
		}
    	} elseif ($index == 0) {
        	$up_script = 'Onclick="javascript:return false;"';
        	$top_script = 'Onclick="javascript:return false;"';
        	$down_script = "";
        	$bottom_script = "";
        	$moveup_img = sprintf($img_template,$moveup_msg,$moveup_msg,"up_dis.png");
        	$movetop_img = sprintf($img_template,$movetop_msg,$movetop_msg,"top_dis.png");
        } elseif ($index+1 == $num_questions) {
        	$up_script = "";
        	$top_script = "";
        	$down_script = 'Onclick="javascript:return false;"';
        	$bottom_script = 'Onclick="javascript:return false;"';
        	$movedown_img = sprintf($img_template,$movedown_msg,$movedown_msg,"down_dis.png");
        	$movebottom_img = sprintf($img_template,$movebottom_msg,$movebottom_msg,"bottom_dis.png");
        }
        
        $field_template = <<<END
        	<tr class="%s" onmouseover="call_mouse_over_function(this)" onmouseout="call_mouse_out_function(this)">
				<td style="width:345px;">%s</td>
				<td style="text-align:center"><a href="{$url_edit}">$edit_img</a></td>
				<td style="text-align:center"><a href="{$url_up}" %s>$moveup_img</a></td>
				<td style="text-align:center"><a href="{$url_down}" %s>$movedown_img</a></td>
				<td style="text-align:center"><a href="{$url_top}" %s >$movetop_img</a></td>
				<td style="text-align:center"><a href="{$url_bottom} %s">$movebottom_img</a></td>
				<td style="text-align:center"><a onclick="return confirm('$delete_confirm_msg')" href="{$url_delete}">$delete_img</a></td>
			</tr>
END;
        
$body .= sprintf($field_template,$class,$text_question,$up_script,$down_script,$top_script,$bottom_script);
	   
}
   
$body .= "</table>";
echo $body;


?>
