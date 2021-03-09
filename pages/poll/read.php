<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);

if ($poll) {
   elgg_set_page_owner_guid($poll->getContainerGUID());
   $container = elgg_get_page_owner_entity();

   if (elgg_instanceof($container, 'group')) {
      elgg_push_breadcrumb($container->name, "poll/group/$container->guid/all");
   } else {
      elgg_push_breadcrumb($container->name, "poll/owner/$container->username");
   }
   elgg_push_breadcrumb($poll->title);

   $title = elgg_echo('poll:votepost');		

   $content = elgg_view('object/poll',array('full_view' => true, 'entity' => $poll));
   $content .= '<div id="comments">' . elgg_view_comments ($poll) . '</div>';

   $body = elgg_view_layout('content', array('filter' => '','content' => $content,'title' => $title));
   
   echo elgg_view_page($title, $body);
} else {
   register_error( elgg_echo('poll:notfound'));
   forward();
}

		
?>