<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

// Get input data
$pollpost = get_input('pollpost');
$index = get_input('index');	

$user_guid = elgg_get_logged_in_user_guid();
	
$poll = get_entity($pollpost);
$container = get_entity($poll->container_guid);

// Make sure we actually have permission to edit
if ($poll->getSubtype() == "poll" && $poll->canEdit()) {
 
   $created = $poll->created;
   $count_votes=$poll->countAnnotations('all_votes');

   if (($created)&&($count_votes>0)){   
      register_error(elgg_echo("poll:structure"));
      forward("poll/edit/$pollpost");   
   } 
      
   $options = array('relationship' => 'poll_question', 'relationship_guid' =>$pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'limit'=>0, 'metadata_name_value_pairs' => array('name' => 'index', 'value' => $index, 'operand' => '>='));
   $questions=elgg_get_entities_from_relationship($options);

   $already_deleted=false;
   foreach ($questions as $one_question){
      if ($one_question->index==$index){
         if(!$already_deleted){
            $already_deleted=true;
	    $deleted=$one_question->delete();
            if (!$deleted){
               register_error(elgg_echo("poll:questionnotdeleted"));
               forward("poll/edit/$pollpost");   
            }
	 }
      } else {
         $previous_index = $one_question->index;
         $one_question->index = $previous_index-1;
      }
   }

   //System message
   system_message(elgg_echo("poll:updated"));
   
   // Add to river
   //if ($created)
   //   add_to_river('river/object/poll/update','update',$user_guid,$pollpost);
   //Forward
   forward("poll/edit/$pollpost"); 	
}

?>