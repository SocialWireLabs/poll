<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$edit = get_input('edit');

if ($poll->getSubtype() == "poll" && $poll->canEdit()) {

   //Times
   $poll->option_close_value = 'poll_not_close';   
   $poll->opened = false;
   $poll->action = true;

   // System message 
   system_message(elgg_echo("poll:closed_listing"));
   if (strcmp($edit,'no')==0)
      forward($_SERVER['HTTP_REFERER']);
   else
      forward("poll/edit/$pollpost");	
}
	
?>
