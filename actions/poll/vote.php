<?php
	 
gatekeeper();

elgg_load_library('poll');

$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid);

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);

if ($poll->getSubtype() == "poll") {

   if (poll_check_status($poll)){

      $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question','limit'=>0);
      $questions=elgg_get_entities_from_relationship($options);

      if (empty($questions))
         $num_questions = 0;
      else
         $num_questions = count($questions);

      // Cache to the session
      elgg_make_sticky_form('vote_poll');

      $response = array();
      $add_response = array();
      $responses = array();
      $several_responses = array();

      foreach($questions as $one_question){
         $index = $one_question->index;
         $name_response="response".$index;
         $name_add_response="add_response_".$index;
         $responses[$index] = $one_question->responses; 
         $responses[$index] = explode(Chr(26),$responses[$index]);
         $responses[$index] = array_map('trim', $responses[$index]);
	 $several_responses[$index] = $one_question->several_responses;
	 if ($several_responses[$index]){
	    $num_check = 0;
	    $j = 1;
	    $comp_response = "";
	    foreach($responses[$index] as $one_response){
	       $name_response_item = $name_response . "_" . $j;
	       $check_response_item = get_input($name_response_item);
	       if (strcmp($check_response_item,"on")==0) {
	          if ($num_check == 0)
		     $comp_response = $one_response;
		  else
		     $comp_response .= Chr(26) . $one_response;
		  $num_check = $num_check +1;
	       }
	       $j = $j+1;
	    }
	    $check_response_item = get_input($name_response);
	    if (strcmp($check_response_item,"on")==0) {
	       $add_response[$index] = get_input($name_add_response);
	       if (strcmp($add_response[$index],"")==0){
	          register_error(elgg_echo("poll:responses_blank" . $index));
	          forward($_SERVER['HTTP_REFERER']); 
	       }
	       foreach($responses[$index] as $one_response){
	          if (strcmp($one_response,$add_response[$index])==0){
	             register_error(elgg_echo("poll:response_repetition"));
	             forward($_SERVER['HTTP_REFERER']); 
	          }
	       }
	       if ($num_check == 0)
		  $comp_response = $add_response[$index];
	       else
		  $comp_response .= Chr(26) . $add_response[$index];
	       $num_check = $num_check +1;
	    }
	    if($num_check == 0) {
	       register_error(elgg_echo("poll:null_vote"));
	       forward($_SERVER['HTTP_REFERER']); 
	    } 
	    $response[$index] = explode(Chr(26),$comp_response);
            $response[$index] = array_map('trim', $response[$index]);

	 } else {
            $response[$index]=get_input($name_response);
	    if(empty($response[$index])) {
	       register_error(elgg_echo("poll:null_vote"));
	       forward($_SERVER['HTTP_REFERER']); 
	    } 
            if (strcmp($response[$index],"add_response")==0){
               $add_response[$index] = get_input($name_add_response);
	       if (strcmp($add_response[$index],"")==0){
	          register_error(elgg_echo("poll:responses_blank"));
	          forward($_SERVER['HTTP_REFERER']); 
	       }
	       foreach($responses[$index] as $one_response){
	          if (strcmp($one_response,$add_response[$index])==0){
	             register_error(elgg_echo("poll:response_repetition"));
	             forward($_SERVER['HTTP_REFERER']); 
	          }
	       }
	       $response[$index] = $add_response[$index];
	    }
         }
      } 
      
      //Add responses
      $access = elgg_set_ignore_access(true);
      foreach($questions as $one_question){
         $index = $one_question->index;
	 if (strcmp($add_response[$index],"")!=0){
            $one_question_responses = $one_question->responses;
	    $one_question_responses .= Chr(26) . $add_response[$index];
            $one_question->responses = $one_question_responses;
	 }
      }
      elgg_set_ignore_access($access);
      
      //Add vote as an annotation
      $id_vote=array();
      foreach ($questions as $one_question){
	 $index = $one_question->index;
	 $responses[$index] = $one_question->responses; 
         $responses[$index] = explode(Chr(26),$responses[$index]);
         $responses[$index] = array_map('trim', $responses[$index]);
	 $id_vote[$index]="";
         if (is_array($response[$index])){
	    $first=true;
	    foreach ($response[$index] as $one_selected_response){
	       if (strcmp($one_selected_response,"add_response")==0)
	          $one_selected_response = $add_response[$index];
	       $j=1;
	       foreach ($responses[$index] as $one_response){
		  if(strcmp($one_selected_response,$one_response)==0) 
		     break;
		  $j=$j+1;
	       }
               if ($first){
	          $id_vote[$index].=$index . "." . $j;
		  $first=false;
               } else {
	          $id_vote[$index].=Chr(26) . $index . "." . $j;
	       }	
	    }
	 } else {
	    $j=1;
	    foreach ($responses[$index] as $one_response){
	       if(strcmp($response[$index],$one_response)==0) 
	          break;
	       $j=$j+1;
	    }
	    $id_vote[$index].=$index . "." . $j;
	 }
      }

      if ($poll->change_vote) {
         $previous_vote = poll_check_previous_vote($poll, $user_guid);
         if ($previous_vote) {
            $user->deleteOwnedAnnotations('vote');
	    $user->deleteOwnedAnnotations('all_votes');
         }
      } 

      $index=0;
      while ($index<$num_questions){
	 $id_vote_array = explode(Chr(26),$id_vote[$index]);
	 $id_vote_array = array_map('trim', $id_vote_array);
	 foreach($id_vote_array as $one_id_vote){
	    $poll->annotate('vote', $one_id_vote, $poll->access_id);
	 }
	 $index=$index+1;
      }
      $poll->annotate('all_votes', "$num_questions", $poll->access_id);    
   
      // Remove the poll post cache
      elgg_clear_sticky_form('vote_poll');
      
      // Success message
      system_message(elgg_echo("poll:voted"));
      // Forward
      forward($_SERVER['HTTP_REFERER']); 
   } else {
      system_message(elgg_echo("poll:closed"));
      forward($_SERVER['HTTP_REFERER']); 
   }
}

?>