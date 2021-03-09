<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$index = get_input('index');

if ($poll && $poll->canEdit()){
   elgg_set_page_owner_guid($poll->getContainerGUID());
   $container = elgg_get_page_owner_entity();

   if (elgg_instanceof($container, 'group')) {
      elgg_push_breadcrumb($container->name, "poll/group/$container->guid/all");
   } else {
      elgg_push_breadcrumb($container->name, "poll/owner/$container->username");
   }
   elgg_push_breadcrumb($poll->title,$poll->getURL());
   
   $title = elgg_echo('poll:editquestionpost');
   $content = elgg_view('forms/poll/edit_question', array('entity' => $poll,'index'=>$index));
   $body = elgg_view_layout('content', array('filter' => '','content' => $content,'title' => $title));
}
echo elgg_view_page($title, $body);
		
?>