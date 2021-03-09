<?php

gatekeeper();
if (is_callable('group_gatekeeper')) 
   group_gatekeeper();

$pollpost = get_input('pollpost');
$poll = get_entity($pollpost);
$container = get_entity($poll->container_guid);	
$user_guid = elgg_get_logged_in_user_guid();

if ($poll->getSubtype() == "poll") {
   $poll_new = new ElggObject();
   $poll_new->subtype = "poll";
   $poll_new->owner_guid = $user_guid;
   $poll_new->container_guid = $poll->container_guid;
   $poll_new->access_id = $poll->access_id;
   $poll_new->title = $poll->title;
   $poll_new->desc = $poll->desc;

   if (!$poll_new->save()) {
      register_error(elgg_echo("poll:error_save"));
      forward($_SERVER['HTTP_REFERER']); 
   }
   $poll_new->created = $poll->created;
   $poll_new->important = $poll->important;

   //Times
   $poll_new->option_activate_value = 'poll_activate_now';
   $poll_new->option_close_value = 'poll_not_close';
   $poll_new->opened = false;
   $now=time();
   $poll_new->activate_time = $poll->activate_time;
   $poll_new->close_time = $poll->close_time;
   $poll->form_activate_date = $poll->form_activate_date;
   $poll->form_activate_time = $poll->form_activate_time;
   $poll->form_close_date = $poll->form_close_date;
   $poll->form_close_time = $poll->form_close_time;


   // Add tags 			
   $tagsarray=array();
   if(is_array($poll->tags)){
      $i=0;
      foreach($poll->tags as $one_tag){
         $tagsarray[$i] = $poll->tags[$i];
         $i=$i+1;
      }
   } else {
      $tagsarray[0]=$poll->tags;
   }	
   $poll_new->tags = array();
   $poll_new->tags = $tagsarray;

   // Add_responses
   $poll_new->add_responses = $poll->add_responses;

   // Change_vote
   $poll_new->change_vote = $poll->change_vote;

   // Selector 
   $poll_new->selector = $poll->selector;
	
   // Add questions

   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question','limit'=>0);
   $questions=elgg_get_entities_from_relationship($options);
   
   $poll_new_guid = $poll_new->getGUID();

   foreach ($questions as $one_question){
      $question = new ElggObject();
      $question->subtype = "poll_question";
      $question->owner_guid = $user_guid;
      $question->container_guid = $new_poll->container_guid;
      $question->access_id = $new_poll->access_id;
      if (!$question->save()){
         register_error(elgg_echo('poll:question_error_save'));
	 forward($_SERVER['HTTP_REFERER']); 
      }
      $question->question = $one_question->question;
      $question->several_responses = $one_question->several_responses;
      $question->responses = $one_question->responses;
      $question->index = $one_question->index;
      add_entity_relationship($poll_new_guid,'poll_question',$question->getGUID());
   }  
     
   // System message 
   system_message(elgg_echo("poll:relaunched"));	
   // Add to river
   elgg_create_river_item(array(
         'view'=>'river/object/poll/create',
         'action_type'=>'create',
         'subject_guid'=>$user_guid,
         'object_guid'=>$poll_new_guid,
   ));
   //Forwad
   forward($_SERVER['HTTP_REFERER']);
}
		
?>
