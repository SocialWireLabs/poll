<?php

elgg_load_library('poll');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('polls'));

elgg_register_title_button();

$filter = get_input('filter', 'newest');

$polls_nav = elgg_view('poll/view_sort_everyone_polls', array('filter' => $filter));

$content = $polls_nav;

$offset = get_input('offset');		
if (empty($offset)) {
   $offset = 0;
}		
$limit = 10;

if (strcmp($filter,'pop')==0){
 
   $polls_important_temp = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false, 'order_by'=>'e.time_created desc','metadata_name_value_pairs' => array(array('name' => 'important', 'value' => 1),array('name' => 'created', 'value' => 1)),'metadata_case_sensitive' => false));

   if ($polls_important_temp){
      $num_polls_important = 0;
      foreach($polls_important_temp as $poll){
         $polls_important[$num_polls_important]=$poll;
	 $num_polls_important=$num_polls_important+1;   	 
      }
      if ($num_polls_important>1) {
         $last_index = $num_polls_important-1;
         sort_polls_by_votes($polls_important,0,$last_index);
      }
   }

   $polls_not_important_temp = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'order_by'=>'e.time_created desc','metadata_name_value_pairs' => array(array('name' => 'important', 'value' => 0),array('name' => 'created', 'value' => 1)),'metadata_case_sensitive' => false));

    if ($polls_not_important_temp){
      $num_polls_not_important = 0;
      foreach($polls_not_important_temp as $poll){
         $polls_not_important[$num_polls_not_important]=$poll;
	 $num_polls_not_important=$num_polls_not_important+1;   	 
      }
      if ($num_polls_not_important>1) {
         $last_index = $num_polls_not_important-1;
         sort_polls_by_votes($polls_not_important,0,$last_index);
      }
   }

} else {

   $polls_important = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'order_by'=>'e.time_created desc', 'metadata_name_value_pairs' => array(array('name' => 'important', 'value' => 1),array('name' => 'created', 'value' => 1)),'metadata_case_sensitive' => false));

   $polls_not_important = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'order_by'=>'e.time_created desc','metadata_name_value_pairs' => array(array('name' => 'important', 'value' => 0),array('name' => 'created', 'value' => 1)),'metadata_case_sensitive' => false));

}

if ($polls_important){
   $num_polls_important = count($polls_important);
} else {
   $num_polls_important = 0;
}
if ($polls_not_important){
   $num_polls_not_important = count($polls_not_important);
} else {
   $num_polls_not_important = 0;
}

$k=0;
$item=$offset;
$polls_range=array();
if ($num_polls_important>$offset){
   while (($k<$limit)&&($item<$num_polls_important)){
      $polls_range[$k]=$polls_important[$item];
      $k=$k+1;
      $item=$item+1;
   }
}
$item = $item-$num_polls_important;
while (($k<$limit)&&($item<$num_polls_not_important)){
   $polls_range[$k]=$polls_not_important[$item];
   $k=$k+1;
   $item=$item+1;
}

$num_polls = $num_polls_important + $num_polls_not_important;
   
if ($num_polls>0){
   $vars=array('count'=>$num_polls,'limit'=>$limit,'offset'=>$offset,'full_view'=>false);
   $content .= elgg_view_entity_list($polls_range,$vars);
} else {
   $content .= '<p>' . elgg_echo('poll:none') . '</p>';
}

$title = elgg_echo('poll:all');

$body = elgg_view_layout('content', array('filter_context' => 'all','content' => $content,'title' => $title));

echo elgg_view_page($title, $body);
		
?>