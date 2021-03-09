<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid);

$container_guid = $poll->container_guid;
$container = get_entity($container_guid);

$page_owner = $container;
if (elgg_instanceof($container, 'object')) {
   $page_owner = $container->getContainerEntity();
}
elgg_set_page_owner_guid($page_owner->getGUID());

if (elgg_instanceof($container, 'group')) {
   elgg_push_breadcrumb($container->name, "poll/group/$container->guid/all");
} else {
   elgg_push_breadcrumb($container->name, "poll/owner/$container->username");
}
elgg_push_breadcrumb($poll->title, $poll->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

if ($poll && $poll->canEdit()){
   $title = elgg_echo('poll:editpost');
   $content = elgg_view('forms/poll/edit', array('entity' => $poll));
} 

$body = elgg_view_layout('content', array('filter' => '','content' => $content,'title' => $title));
echo elgg_view_page($title, $body);
		
?>