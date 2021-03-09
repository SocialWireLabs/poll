<div class="contentWrapper">

<?php

$action = "poll/add";
if (!elgg_is_sticky_form('add_poll')) {
   $title = "";
   $desc = "";
   $add_responses = false;
   $change_vote = false;
   $selector = false;
   $tags = "";
   $access_id = 0;
   $opendate = "";
   $closedate = "";
   $opentime = "00:00";
   $closetime = "00:00";
   $option_activate_value = 'poll_activate_now';
   $option_close_value = 'poll_not_close';
} else {
   $title = elgg_get_sticky_value('add_poll','title');
   $desc = elgg_get_sticky_value('add_poll','desc');
   $add_responses = elgg_get_sticky_value('add_poll','add_responses');
   $change_vote = elgg_get_sticky_value('add_poll','change_vote');
   $selector = elgg_get_sticky_value('add_poll','selector');
   $tags = elgg_get_sticky_value('add_poll','polltags');
   $access_id = elgg_get_sticky_value('add_poll','access_id');
   $opendate = elgg_get_sticky_value('add_poll','opendate');
   $closedate = elgg_get_sticky_value('add_poll','closedate');
   $opentime = elgg_get_sticky_value('add_poll','opentime');
   $closetime = elgg_get_sticky_value('add_poll','closetime');
   $option_activate_value = elgg_get_sticky_value('add_poll','option_activate_value');
   $option_close_value = elgg_get_sticky_value('add_poll','option_close_value');
}

elgg_clear_sticky_form('add_poll');

$options_activate=array();
$options_activate[0]=elgg_echo('poll:activate_now');
$options_activate[1]=elgg_echo('poll:activate_date');
$op_activate=array();
$op_activate[0]='poll_activate_now';
$op_activate[1]='poll_activate_date';
if (strcmp($option_activate_value,$op_activate[0])==0){
   $checked_radio_activate_0 = "checked = \"checked\"";
   $checked_radio_activate_1 = "";
   $style_display_activate = "display:none";
} else {
   $checked_radio_activate_0 = "";
   $checked_radio_activate_1 = "checked = \"checked\"";
   $style_display_activate = "display:block";
}
$options_close=array();
$options_close[0]=elgg_echo('poll:not_close');
$options_close[1]=elgg_echo('poll:close_date');
$op_close=array();
$op_close[0]='poll_not_close';
$op_close[1]='poll_close_date';
if (strcmp($option_close_value,$op_close[0])==0){
   $checked_radio_close_0 = "checked = \"checked\"";
   $checked_radio_close_1 = "";
   $style_display_close = "display:none";
} else {
   $checked_radio_close_0 = "";
   $checked_radio_close_1 = "checked = \"checked\"";
   $style_display_close = "display:block";
}
$opendate_label = elgg_echo('poll:opendate');
$closedate_label = elgg_echo('poll:closedate');
$opentime_label = elgg_echo('poll:opentime');
$closetime_label = elgg_echo('poll:closetime');

$add_responses_label = elgg_echo('poll:add_responses_label');
if ($add_responses){
   $selected_add_responses = "checked = \"checked\"";
   $style_display_add_responses = "display:block";
} else {
   $selected_add_responses = "";
   $style_display_add_responses = "display:none";
}

$change_vote_label = elgg_echo('poll:change_vote_label');
if ($change_vote){
   $selected_change_vote = "checked = \"checked\"";
   $style_display_change_vote = "display:block";
} else {
   $selected_change_vote = "";
   $style_display_change_vote = "display:none";
}

$selector_label = elgg_echo('poll:selector_label');
if ($selector){
   $selected_selector = "checked = \"checked\"";
   $style_display_selector = "display:block";
} else {
   $selected_selector = "";
   $style_display_selector = "display:none";
}

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('name' => 'polltags', 'value' => $tags));
$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));

$submit_input_add_question = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('poll:add_question')));
 
?>
 
<form action="<?php echo elgg_get_site_url()."action/".$action?>" name="add_poll" enctype="multipart/form-data" method="post">

<?php echo elgg_view('input/securitytoken'); ?>

<p>
<b><?php echo elgg_echo("poll:title"); ?></b><br>
<?php echo elgg_view("input/text", array('name' => 'title', 'value' => $title)); ?>
</p>

<p>
<b> <?php echo elgg_echo("poll:description"); ?> </b> 
<?php echo elgg_view("input/longtext" ,array('name' => 'desc', 'value' => $desc)); ?>
</p>

<table class="poll_dates_table">
<tr>
<td>
<p>
<b><?php echo elgg_echo('poll:activate_label'); ?></b><br>
<?php echo "<input type=\"radio\" name=\"option_activate_value\" value=$op_activate[0] $checked_radio_activate_0 onChange=\"poll_show_activate_time()\">$options_activate[0]";?><br> 
<?php echo "<input type=\"radio\" name=\"option_activate_value\" value=$op_activate[1] $checked_radio_activate_1 onChange=\"poll_show_activate_time()\">$options_activate[1]";?><br> 
<div id="resultsDiv_activate" style="<?php echo $style_display_activate;?>;">
   <?php echo $opendate_label; ?><br> 
   <?php echo elgg_view('input/date',array('timestamp'=>TRUE, 'autocomplete'=>'off','class'=>'poll-compressed-date','name'=>'opendate','value'=>$opendate)); ?>
   <?php echo "<br>" . $opentime_label; ?> <br> 
   <?php echo "<input type = \"text\" name = \"opentime\" value = $opentime>"; ?>  
</div>
</p><br>
</td>
<td>
<p>
<b><?php echo elgg_echo('poll:close_label'); ?></b><br>
<?php echo "<input type=\"radio\" name=\"option_close_value\" value=$op_close[0] $checked_radio_close_0 onChange=\"poll_show_close_time()\">$options_close[0]";?><br> 
<?php echo "<input type=\"radio\" name=\"option_close_value\" value=$op_close[1] $checked_radio_close_1 onChange=\"poll_show_close_time()\">$options_close[1]";?><br>
<div id="resultsDiv_close" style="<?php echo $style_display_close;?>;">
   <?php echo $closedate_label; ?><br> 
   <?php echo elgg_view('input/date',array('timestamp'=>TRUE, 'autocomplete'=>'off','class'=>'poll-compressed-date','name'=>'closedate','value'=>$closedate)); ?>
   <?php echo "<br>" . $closetime_label; ?> <br> 
   <?php echo "<input type = \"text\" name = \"closetime\" value = $closetime>"; ?>  
</div>
</p><br>
</td>
</tr>
</table>

<p>
<b>
<?php echo "<input type = \"checkbox\" name = \"add_responses\" $selected_add_responses> $add_responses_label"; ?>
</b>      
</p><br>

<p>
<b>
<?php echo "<input type = \"checkbox\" name = \"change_vote\" $selected_change_vote> $change_vote_label"; ?>
</b>      
</p><br>

<p>
<b>
<?php echo "<input type = \"checkbox\" name = \"selector\" $selected_selector> $selector_label"; ?>
</b>      
</p><br>

<p>
<b>
<?php echo $tag_label; ?></b><br>
<?php echo $tag_input; ?></p><br>
<p>
<b><?php echo $access_label; ?></b><br>
<?php echo $access_input; ?>
</p><br>

<?php
echo $submit_input_add_question;
?>

<input type="hidden" name="container_guid" value="<?php echo $vars['container_guid']; ?>">

</form>

<script language="javascript">
   function poll_show_activate_time(){
      var resultsDiv_activate = document.getElementById('resultsDiv_activate');
      if (resultsDiv_activate.style.display == 'none'){
         resultsDiv_activate.style.display = 'block';
      } else {       
         resultsDiv_activate.style.display = 'none';
      }
   }   
   function poll_show_close_time(){
      var resultsDiv_close = document.getElementById('resultsDiv_close');
      if (resultsDiv_close.style.display == 'none'){
            resultsDiv_close.style.display = 'block';
      } else {       
         resultsDiv_close.style.display = 'none';
      }
   }  
</script>
</div>

