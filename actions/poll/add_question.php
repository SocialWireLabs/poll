<?php

gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();

// Get input data
$input_question = get_input('question');
$input_several_responses = get_input('several_responses');  
$responses = get_input('responses');
$responses = array_map('trim', $responses);
$input_responses = implode(Chr(26),$responses);
if (!empty($responses))
   $number_responses = count($responses);
else
   $number_responses = 0;
if (strcmp($input_several_responses,"on")==0){
   $several_responses=true;
}else{ 
   $several_responses=false;
}

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$container = get_entity($poll->container_guid);	
$created = $poll->created;

// Make sure we actually have permission to edit
if ($poll->getSubtype() == "poll" && $poll->canEdit()) {
   $count_votes=$poll->countAnnotations('all_votes');
   if (($created)&&($count_votes>0)){   
      register_error(elgg_echo("poll:structure"));
      forward("poll/edit/$pollpost");
   } 

   // Cache to the session
   elgg_make_sticky_form('add_question_poll');

   // Verify question and responses

   if (strcmp($input_question,"")==0) {	  
      register_error(elgg_echo("poll:question_blank"));
      forward("poll/add_question/$pollpost");
   } 

   $blank_response=false;
   $responsesarray=array();
   $i=0;
   foreach($responses as $one_response){
      $responsesarray[$i]=$one_response;
      if (strcmp($one_response,"")==0){
         $blank_response=true;
	 break;
      }
      $i=$i+1;
   }	           
   if ($blank_response){
      register_error(elgg_echo("poll:responses_blank"));
       forward("poll/add_question/$pollpost");
   }
   $same_response=false;
   $i=0;
   while(($i<$number_responses)&&(!$same_response)){
      $j=$i+1;
      while($j<$number_responses){
         if (strcmp($responsesarray[$i],$responsesarray[$j])==0){
            $same_response=true;
	    break;
	 }
	 $j=$j+1;
      }
      $i=$i+1;
   }
   if ($same_response){
      register_error(elgg_echo("poll:response_repetition"));
      forward("poll/add_question/$pollpost");
   }
   if ($number_responses<2){
      register_error(elgg_echo("poll:response_only_one_option"));
      forward("poll/add_question/$pollpost");
   }	

   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question','limit'=>0);
   $questions=elgg_get_entities_from_relationship($options);
   if (empty($questions))
      $index=0;
   else
      $index=count($questions);

   //Create new question
   $question = new ElggObject();
   $question->subtype = "poll_question";
   $question->owner_guid = $user_guid;
   $question->container_guid = $poll->container_guid;
   $question->access_id = $poll->access_id;
   if (!$question->save()){
      register_error(elgg_echo('poll:question_error_save')); 
      forward("poll/edit/$pollpost");
   }
   $question->question = $input_question;
   $question->several_responses = $several_responses;
   $question->responses = $input_responses;
   $question->index = $index;

   add_entity_relationship($pollpost,'poll_question',$question->getGUID());

   // Remove the poll post cache        
   elgg_clear_sticky_form('add_question_poll');
	
   // Add to river
   //if ($created)
   //   add_to_river('river/object/poll/update','update',$user_guid,$pollpost);
	 
   // Forward		   
   forward("poll/edit/$pollpost");
}
  
?>
