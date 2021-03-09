<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$container = get_entity($poll->container_guid);	

if ($poll->getSubtype() == "poll" && $poll->canEdit()) {
   $important=$poll->important;
   if (!$important){
      $poll->important=true;
      // System message 
      system_message(elgg_echo("poll:emphasized"));
   } else {
      $poll->clearMetadata('important');
      $poll->important=false;
   }
   forward($_SERVER['HTTP_REFERER']);
}
	
?>
