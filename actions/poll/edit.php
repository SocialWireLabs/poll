<?php

gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$close_poll = get_input('close_poll');

if (strcmp($close_poll,"yes")==0){
   $poll->option_close_value = 'poll_not_close';
   $poll->opened = false;
   forward("poll/edit/$pollpost");
} 

if ($poll->getSubtype() == "poll" && $poll->canEdit()) {

   $user_guid = elgg_get_logged_in_user_guid();

   $title = get_input('title');
   $desc = get_input('desc');
   $option_activate_value = get_input('option_activate_value');
   $option_close_value = get_input('option_close_value');
   if (strcmp($option_activate_value,'poll_activate_date')==0){
      $opendate = get_input('opendate');
      $opentime = get_input('opentime');
   } 
   if (strcmp($option_close_value,'poll_close_date')==0){
      $closedate = get_input('closedate');
      $closetime = get_input('closetime'); 
   } 

   $add_responses = get_input('add_responses');
   $change_vote = get_input('change_vote');
   $selector = get_input('selector');

   $tags = get_input('polltags');
   $access_id = get_input('access_id');
   $selected_action = get_input('submit');

   $container = get_entity($poll->container_guid);      

   $created=$poll->created;

   $poll_publish=elgg_echo('poll:publish');
   $poll_save=elgg_echo('poll:save');
   $poll_add_question=elgg_echo('poll:add_question');

   $now=time();

   // Cache to the session
   elgg_make_sticky_form('edit_poll');
 
   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question','limit'=>0);
   $questions=elgg_get_entities_from_relationship($options);
   if (empty($questions))
      $num_questions = 0;
   else
      $num_questions = count($questions);

   $num_responses = $poll->countAnnotations('all_votes');

   if (((strcmp($selected_action,$poll_save)==0)&&($created)&&($num_questions==0))||((strcmp($selected_action,$poll_publish)==0)&&($num_questions==0))){
      register_error(elgg_echo("poll:not_questions"));
      forward("poll/edit/$pollpost");
   }
        
   if (strcmp($option_activate_value,'poll_activate_date')==0){
      $mask_time="[0-2][0-9]:[0-5][0-9]";
      if (!ereg($mask_time,$opentime,$same)){
         register_error(elgg_echo("poll:bad_times"));
         forward("poll/edit/$pollpost");
      }
   }
   if (strcmp($option_close_value,'poll_close_date')==0){
      $mask_time="[0-2][0-9]:[0-5][0-9]";
      if (!ereg($mask_time,$closetime,$same)){
         register_error(elgg_echo("poll:bad_times"));
         forward("poll/edit/$pollpost");
      }
   }
   if (strcmp($option_activate_value,'poll_activate_now')==0){
      $activate_time=$now;
   } else {
      $opentime_array = explode(':',$opentime);
      $opentime_h = trim($opentime_array[0]);
      $opentime_m = trim($opentime_array[1]);
      $opendate_text = date("Y-m-d",$opendate);
      $opendate = strtotime($opendate_text." ".date_default_timezone_get());
      $opendate_array = explode('-',$opendate_text);
      $opendate_y = trim($opendate_array[0]);
      $opendate_m = trim($opendate_array[1]);
      $opendate_d = trim($opendate_array[2]);
      $activate_date = mktime(0,0,0,$opendate_m,$opendate_d,$opendate_y);
      $activate_time = mktime($opentime_h,$opentime_m,0,$opendate_m,$opendate_d,$opendate_y);
      if ($activate_time < 1){
         register_error(elgg_echo("poll:bad_times"));
	 forward("poll/edit/$pollpost");
      }
   }   
   if (strcmp($option_close_value,'poll_not_close')==0){
      $close_time=$now+1;
   } else {
      $closetime_array = explode(':',$closetime);
      $closetime_h = trim($closetime_array[0]);
      $closetime_m = trim($closetime_array[1]);
      $closedate_text = date("Y-m-d",$closedate);
      $closedate = strtotime($closedate_text." ".date_default_timezone_get());
      $closedate_array = explode('-',$closedate_text);
      $closedate_y = trim($closedate_array[0]);
      $closedate_m = trim($closedate_array[1]);
      $closedate_d = trim($closedate_array[2]);
      $close_date = mktime(0,0,0,$closedate_m,$closedate_d,$closedate_y);
      $close_time = mktime($closetime_h,$closetime_m,0,$closedate_m,$closedate_d,$closedate_y);
      
      if ($close_time < 1){
         register_error(elgg_echo("poll:bad_times"));
         forward("poll/edit/$pollpost");
      }
   }
   if ($activate_time>=$close_time) {
      register_error(elgg_echo("poll:error_times"));
      forward("poll/edit/$pollpost");
   }

   //Set add_responses
   if (strcmp($add_responses,"on")==0) {
      $poll->add_responses = true;
   } else {
      $poll->add_responses = false;
   }

   //Set change_vote 
   if (strcmp($change_vote,"on")==0) {
      $poll->change_vote = true;
   } else {
      $poll->change_vote = false;
   }

   //Set selector
   if (($num_responses < 1) || ($poll->selector)) {
      if (strcmp($selector,"on")==0) {
         $poll->selector = true;
      } else {
         $poll->selector = false;
      }
   }

   // Convert string of tags into a preformatted array
   $tagarray = string_to_tag_array($tags);
			
   // Make sure the title isn't blank
   if (strcmp($title,"")==0) {	
      register_error(elgg_echo("poll:title_blank"));
      forward("poll/edit/$pollpost");			
   } 
      
   // Get owning user
   $owner = get_entity($poll->getOwnerGUID());

   // Set its access	
   $poll->access_id = $access_id;

   // Set its title 
   $poll->title= $title;

   // Set its description
   $poll->desc = $desc;

   // Save the poll post
   if (!$poll->save()) {
      register_error(elgg_echo("poll:error_save"));
      forward("poll/edit/$pollpost");
   }
		
   //Set times
   $poll->option_activate_value = $option_activate_value;
   $poll->option_close_value = $option_close_value;
   if (strcmp($option_activate_value,'poll_activate_now')!=0){
      $poll->activate_date = $activate_date;
      $poll->activate_time = $activate_time;
      $poll->form_activate_date = $activate_date;
      $poll->form_activate_time = $opentime;
   }
   if (strcmp($option_close_value,'poll_not_close')!=0){
      $poll->close_date = $close_date;
      $poll->close_time = $close_time;
      $poll->form_close_date = $close_date;
      $poll->form_close_time = $closetime;
   }
   if ((strcmp($option_activate_value,'poll_activate_date')==0)&&(strcmp($option_close_value,'poll_close_date')==0)){
      if (($now>=$activate_time)&&($now<$close_time)){
         $poll->opened=true;
      } else {
         $poll->opened=false;
      }
   } elseif (strcmp($option_activate_value,'poll_activate_date')==0){
      if ($now>=$activate_time) {
         $poll->opened=true;
      } else {
         $poll->opened=false;
      }
   } elseif (strcmp($option_close_value,'poll_close_date')==0){
      if ($now<$close_time) {
         $poll->opened=true;
      } else {
         $poll->opened=false;
      } 
   } else {
      $poll->opened=true;
   }
	      			
   // Now let's add tags.
   if (is_array($tagarray)) {
      $poll->tags = $tagarray;
   }
           
   // Questions  access
   if (!empty($questions)){
      foreach ($questions as $one_question){
         $one_question->access_id = $poll->access_id;
         if (!$one_question->save()){
            register_error(elgg_echo("poll:question_error_save"));
            forward("poll/edit/$pollpost");
         }
      }
   }

   // Remove the poll post cache        
   elgg_clear_sticky_form('edit_poll');

   // Forward 
   if (strcmp($selected_action,$poll_publish)==0){
      $poll->created = true;
      // Add to river
      elgg_create_river_item(array(
         'view'=>'river/object/poll/create',
         'action_type'=>'create',
         'subject_guid'=>$user_guid,
         'object_guid'=>$pollpost,
      ));
      //System message
      system_message(elgg_echo("poll:created"));
      if ($container instanceof ElggGroup) {
         forward(elgg_get_site_url()  . "poll/group/" . $container->getGUID());
      } else {
         forward(elgg_get_site_url()  . "poll/owner/" . $owner->username);
      }
   } else {
      if (strcmp($selected_action,$poll_save)==0){
	 // Add to river
	 //add_to_river('river/object/poll/update','update',$user_guid,$pollpost);
	 // System message
	 system_message(elgg_echo("poll:updated"));
	 if ($container instanceof ElggGroup) {
            forward(elgg_get_site_url() . "poll/group/" . $container->getGUID());
         } else {
            forward(elgg_get_site_url()  . "poll/owner/" . $owner->username);
         }
      } else {
         $poll->option_close_value = "poll_not_close";
	 $poll->opened = false;
      	 forward("poll/add_question/$pollpost");
      }
   }	
}

?>
