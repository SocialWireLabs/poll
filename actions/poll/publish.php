<?php

gatekeeper();

//Get input data
$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid);

if ($poll->getSubtype() == "poll" && $poll->canEdit()) {
   //Times
   $poll->created = true;
   system_message(elgg_echo("poll:created"));

   //River           
   elgg_create_river_item(array(
   		'view'=>'river/object/poll/create',
   		'action_type'=>'create',
   		'subject_guid'=>$user_guid,
   		'object_guid'=>$pollpost,
   ));

   //Forward
   forward($_SERVER['HTTP_REFERER']);
}
		
?>
