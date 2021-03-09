<?php

$group = elgg_get_page_owner_entity();
$group_guid = $group->getGUID();
if ($group->poll_enable == "no") {
	return true;
}

elgg_push_context('widgets');

$limit = 6; 

$polls = elgg_get_entities(array('type'=>'object','subtype'=>'poll','limit'=>false,'container_guid'=>$group_guid,'order_by'=>'e.time_created desc'));
$polls_sorted_by_stand_out_and_time=array();
$num_polls=0;
foreach($polls as $poll){
   if ($poll->important){
      $polls_sorted_by_stand_out_and_time[$num_polls]=$poll;
      $num_polls=$num_polls+1;   
   }
}
                
foreach($polls as $poll){
   if (!$poll->important){
      $polls_sorted_by_stand_out_and_time[$num_polls]=$poll;
      $num_polls=$num_polls+1;   
   }
}

$i=0;
foreach($polls_sorted_by_stand_out_and_time as $poll) {
   if ($i==$limit)
      break;
   $content .= elgg_view('object/poll', array('full_view' => false, 'entity' => $poll));
   $i=$i+1;
}

elgg_pop_context();

if (!$content) {
   $content = '<p>' . elgg_echo('poll:none') . '</p>';
}

$all_link = elgg_view('output/url', array('href' => "poll/group/$group_guid/",'text' => elgg_echo('link:view:all'),'is_trusted' => true));

$new_link = elgg_view('output/url', array('href' => "poll/add/$group_guid",'text' => elgg_echo('poll:add'),'is_trusted' => true));

echo elgg_view('groups/profile/module', array('title' => elgg_echo('poll:group'),'content' => $content,'all_link' => $all_link,'add_link' => $new_link));

?>
