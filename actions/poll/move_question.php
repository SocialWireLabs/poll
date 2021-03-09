<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

// Get input data
$pollpost = get_input('pollpost');
$index = get_input('index');	
$action = get_input('act');

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

   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question','limit'=>0);
   $questions=elgg_get_entities_from_relationship($options);
   $num_questions=count($questions);
   $end=0;
   
   if (strcmp($action,"up")==0){
      foreach ($questions as $one_question){
         if ($one_question->index==$index){
            $one_question->index=$index-1;
            $end=$end+1;
         } else if ($one_question->index==($index-1)){
            $one_question->index=$index;
            $end=$end+1;
         }
         if ($end==2)
            break;  
      }
   }
   if (strcmp($action,"down")==0){
      foreach ($questions as $one_question){
         if ($one_question->index==$index){
            $one_question->index=$index+1;
            $end=$end+1;
         } else if ($one_question->index==($index+1)){
            $one_question->index=$index;
            $end=$end+1;
         }
         if ($end==2)
            break;  
      }
   }
   if (strcmp($action,"top")==0){
      foreach ($questions as $one_question){
         if ($one_question->index==$index){
            $one_question->index=0;
         } else {
	    if ($one_question->index < $index)
               $one_question->index=$one_question->index + 1;
         }
      }
   }
   if (strcmp($action,"bottom")==0){
      foreach ($questions as $one_question){
         if ($one_question->index==$index){
            $one_question->index=$num_questions-1;
         } else {
	    if ($one_question->index > $index)
               $one_question->index=$one_question->index - 1;
         }
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