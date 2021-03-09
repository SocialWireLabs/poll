<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);

if ($poll->getSubtype() == "poll" && $poll->canEdit()) {
 
   //Times
   $poll->option_activate_value = 'poll_activate_now';
   $poll->opened = true;
   $poll->action = true;

   // System message 
   system_message(elgg_echo("poll:opened_listing"));
   forward($_SERVER['HTTP_REFERER']);
}
		
?>
