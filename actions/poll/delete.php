<?php

gatekeeper();

$pollpost = get_input('guid');

$poll = get_entity($pollpost);
$container = get_entity($poll->container_guid);
$owner = get_entity($poll->getOwnerGUID());
	
if ($poll->getSubtype() == "poll" && $poll->canEdit()) {
   //Delete questions
   $questions = elgg_get_entities_from_relationship(array('relationship' => 'poll_question','relationship_guid' => $pollpost,'inverse_relationship' => false,'type' => 'object','subtype' => 'poll_question','limit'=>0));
   foreach($questions as $one_question){
      $deleted=$one_question->delete();
      if (!$deleted){
         register_error(elgg_echo("poll:questionnotdeleted"));
         if ($container instanceof ElggGroup) {
            forward(elgg_get_site_url()  . 'poll/group/' . $container->username);
         } else {
            forward(elgg_get_site_url()  . 'poll/owner/' . $owner->username);
         }
      }
   }

   // Delete it!
   $deleted = $poll->delete();
   if ($deleted > 0) {
      system_message(elgg_echo("poll:deleted"));
   } else {
      register_error(elgg_echo("poll:notdeleted"));
   }
   if ($container instanceof ElggGroup) {
      forward(elgg_get_site_url()  . 'poll/group/' . $container->username);
   } else {
      forward(elgg_get_site_url()  . 'poll/owner/' . $owner->username);
   }
}
	
?>