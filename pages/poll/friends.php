<?php

elgg_load_library('poll');

$owner = elgg_get_page_owner_entity();
if (!$owner) {
   forward('poll/all');
}

elgg_push_breadcrumb($owner->name, "poll/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$filter = get_input('filter', 'newest');

$polls_nav = elgg_view('poll/view_sort_friends_polls', array('filter' => $filter));

$content = $polls_nav;

$offset = get_input('offset');		
if (empty($offset)) {
   $offset = 0;
}
$limit = 10;
		
$polls = elgg_get_entities_from_relationship(array(
   'type'=>'object',
   'subtype'=>'poll',
   'limit'=>false,
   'offset'=>0,
   'relationship'=>'friend',
   'relationship_guid'=>$owner->getGUID(),
   'relationship_join_on'=>'container_guid'
));

$polls_important = array();
$num_polls_important = 0;
$polls_not_important = array();
$num_polls_not_important = 0;
if ($polls){
   foreach($polls as $poll){
      if ($poll->created){
         if ($poll->important){
	    $polls_important[$num_polls_important]=$poll;
	    $num_polls_important=$num_polls_important+1;   
	 } else {
            $polls_not_important[$num_polls_not_important]=$poll;
	    $num_polls_not_important=$num_polls_not_important+1;   
	 }
      }
   }
}	
if (strcmp($filter,'pop')==0){
   if ($num_polls_important>1) {
      $last_index = $num_polls_important-1;
      sort_polls_by_votes($polls_important,0,$last_index);
   }
   if ($num_polls_not_important>1) {
      $last_index = $num_polls_not_important-1;
      sort_polls_by_votes($polls_not_important,0,$last_index);
   }
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

$title = elgg_echo('poll:user:friends',array($owner->name));

$params = array('filter_context' => 'friends','content' => $content,'title' => $title);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

?>