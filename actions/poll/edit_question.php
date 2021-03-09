<?php

gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$container = get_entity($poll->container_guid);
	
$created = $poll->created;
$count_votes=$poll->countAnnotations('all_votes');
$input_question = get_input('question');
$input_several_responses = get_input('several_responses');  
$responses = get_input('responses');
$responses = array_map('trim', $responses);
$input_responses = implode(Chr(26),$responses);
if (!empty($responses))
   $number_responses = count($responses);
else
   $number_responses = 0;
if ((!$created)||($count_votes==0)) {
   if (strcmp($input_several_responses,"on")==0) {
      $several_responses=true;
   } else { 
      $several_responses=false;
   }
}

$index = get_input('index');

$modification=false;

// Make sure we actually have permission to edit
if ($poll->getSubtype() == "poll" && $poll->canEdit()) {

   // Cache to the session
   elgg_make_sticky_form('edit_question_poll');   
	
   // Verify question and responses
   if (strcmp($input_question,"")==0) {	
      register_error(elgg_echo("poll:question_blank"));
      forward("poll/edit_question/$pollpost/$index");		   
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
      forward("poll/edit_question/$pollpost/$index");
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
      forward("poll/edit_question/$pollpost/$index");
   }		
   if ($number_responses<2){
      register_error(elgg_echo("poll:response_only_one_option"));
      forward("poll/edit_question/$pollpost/$index");
   }
	
   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'metadata_name_value_pairs' => array('name' => 'index', 'value' => $index));
   $questions=elgg_get_entities_from_relationship($options);
   $one_question = $questions[0];   

   $previous_responses = $one_question->responses;
   $previous_responses = explode(Chr(26),$previous_responses);
   $previous_responses = array_map('trim', $previous_responses);
   $number_previous_responses=count($previous_responses);
   if ($number_responses<$number_previous_responses){
      $modification=true;
   } 				
		
   if (($created) && ($count_votes>0) && ($modification)){
      register_error(elgg_echo("poll:structure"));
      // Remove the poll post cache        
      elgg_clear_sticky_form('edit_question_poll');
      // Forward
      forward("poll/edit/$pollpost");
   } 
      
   // Edit question
   if (!$one_question->save()){
      register_error(elgg_echo('poll:question_error_save'));
      forward("poll/edit/$pollpost");
   }

   $one_question->question = $input_question;
   $one_question->responses = $input_responses;
   if ((!$created)||($count_votes==0)) {
      $one_question->several_responses = $several_responses;
   }
   $one_question->index = $index;

   // Remove the poll post cache
   elgg_clear_sticky_form('edit_question_poll');
                
   // Add to river
   //if ($created)
   //   add_to_river('river/object/poll/update','update',$user_guid,$pollpost);
   
   // Forward   
   forward("poll/edit/$pollpost");      
}
 
?>
