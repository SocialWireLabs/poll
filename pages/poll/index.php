<?php

elgg_load_library('poll');

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$owner = elgg_get_page_owner_entity();

if (!$owner) {
   forward('poll/all');
}

$owner_guid = $owner->getGUID();

elgg_push_breadcrumb($owner->name);

elgg_register_title_button('poll','add');

$filter = get_input('filter', 'newest');

if ($owner instanceof ElggGroup)
   $username="group:" . $owner->guid;
else
   $username= $owner->username;

$polls_nav = elgg_view('poll/view_sort_polls', array('filter' => $filter, 'username' => $username));

$content = $polls_nav;

$offset = get_input('offset');
if (empty($offset)) {
   $offset = 0;
}
$limit = 10;

if (strcmp($filter,'pop')==0){

   $polls_important_temp = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'container_guid'=>$owner_guid, 'order_by'=>'e.time_created desc', 'metadata_name_value_pairs' => array('name' => 'important', 'value' => 1),'metadata_case_sensitive' => false));
  
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
   
   $polls_not_important_temp = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'container_guid'=>$owner_guid,'order_by'=>'e.time_created desc','metadata_name_value_pairs' => array('name' => 'important', 'value' => 0), 'metadata_case_sensitive' => false));

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
  
   $polls_important = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'container_guid'=>$owner_guid,'order_by'=>'e.time_created desc', 'metadata_name_value_pairs' => array('name' => 'important', 'value' => 1), 'metadata_case_sensitive' => false));

   $polls_not_important = elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'poll','limit'=>false,'container_guid'=>$owner_guid,'order_by'=>'e.time_created desc','metadata_name_value_pairs' => array('name' => 'important', 'value' => 0), 'metadata_case_sensitive' => false));

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

$num_polls = $num_polls_important + $num_polls_not_important;

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

if ($num_polls>0){	
   $vars=array('count'=>$num_polls,'limit'=>$limit,'offset'=>$offset,'full_view'=>false);
   $content .= elgg_view_entity_list($polls_range,$vars);
} else {
   $content .= '<p>' . elgg_echo('poll:none') . '</p>';
}

$title = elgg_echo('poll:user', array($owner->name));


$filter_context = '';
if ($owner->getGUID() == elgg_get_logged_in_user_guid()) {
   $filter_context = 'mine';
}

$params = array('filter_context' => $filter_context,'content' => $content,'title' => $title,
);

if (elgg_instanceof($owner, 'group')) {
        $params['filter'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
		
?>