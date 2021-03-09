<?php

$full = elgg_extract('full_view', $vars, FALSE);
$poll = elgg_extract('entity', $vars, FALSE);

if (!$poll) {
   return TRUE;
}

$owner = $poll->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array('href' => $owner->getURL(),'text' => $owner->name,'is_trusted' => true));
$author_text = elgg_echo('byline', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $poll->tags));
$date = elgg_view_friendly_time($poll->time_created);
$metadata = elgg_view_menu('entity', array('entity' => $poll,'handler' => 'poll','sort_by' => 'priority','class' => 'elgg-menu-hz'));
$subtitle = "$author_text $date $comments_link";

//////////////////////////////////////////////////
//Poll information
$pollpost = $poll->getGUID();
$opened=poll_check_status($poll);
$created = $poll->created;
$important = $poll->important;
$num_responses = $poll->countAnnotations('all_votes');

///////////////////////////////////////////////////////////////////
//Links to actions
$owner_guid = $owner->getGUID();
$user_guid = elgg_get_logged_in_user_guid();

$container_guid = $poll->container_guid;
$container = get_entity($container_guid);
if ($container instanceof ElggGroup) {
   $group_owner_guid = $container->owner_guid;
   $operator=false;
   if (($group_owner_guid==$user_guid)||(check_entity_relationship($user_guid,'group_admin',$container_guid))){
      $operator=true;
   }
}

if ($created){		
   if (($poll->canEdit())&&(($owner_guid==$user_guid)||(($container instanceof ElggGroup)&&($operator))||(elgg_is_admin_logged_in()))) {
      //Relaunch
      $url_relaunch = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/relaunch?pollpost=" . $pollpost);
      $word_relaunch = elgg_echo("poll:relaunch");
      //Open and close
      if ($opened){						           
         //Close
         $url_close = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/close?edit=no&pollpost=" . $pollpost);
         $word_close = elgg_echo("poll:close_in_listing");
      }	else {
      	 //Open
         $url_open = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/open?pollpost=" . $pollpost);
         $word_open = elgg_echo("poll:open_in_listing");
      }
   }
   if (elgg_is_admin_logged_in()){
      //Stand_out
      if ($important)
         $poll_stand_out=elgg_echo('poll:no_stand_out');
      else
         $poll_stand_out=elgg_echo('poll:stand_out');
      $url_stand_out = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/standout?pollpost=" . $pollpost);
      $word_stand_out = $poll_stand_out;
   }
} else {
  if (($poll->canEdit())&&(($owner_guid==$user_guid)||(($container instanceof ElggGroup)&&($operator))||(elgg_is_admin_logged_in()))) {
      //Publish
      $url_publish = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/poll/publish?pollpost=" . $pollpost);
      $word_publish = elgg_echo("poll:publish");
   }
}

if ($full) {
   elgg_load_library('poll');
   $params = array('entity' => $poll,'title' => $title,'metadata' => $metadata,'subtitle' => $subtitle,'tags' => $tags);
   $params = $params + $vars;
   $summary = elgg_view('object/elements/summary', $params);
   $body = "";

   if ($num_responses==1){
      $body .= "{$num_responses} " . elgg_echo('poll:num_response');
   } else {
      $body .= "{$num_responses} " . elgg_echo('poll:num_responses');
   }
   
   $body .= "<br>";

   //Links to actions
   if ($created){		
      if (($vars['entity']->canEdit())&&(($owner_guid==$user_guid)||(($container instanceof ElggGroup)&&($operator))||(elgg_is_admin_logged_in()))) {	
         if ($opened){						        
	    $body .= "<a href=\"{$url_close}\">{$word_close}</a>" . " " . "<a href=\"{$url_relaunch}\">{$word_relaunch}</a>";
         } else {
      	    $body .= "<a href=\"{$url_open}\">{$word_open}</a>" . " " . "<a href=\"{$url_relaunch}\">{$word_relaunch}</a>";
         }
         $body .= " ";
      }
      if (elgg_is_admin_logged_in()){
         $body .= "<a href=\"{$url_stand_out}\">{$word_stand_out}</a>";
      }
   } else {
     if (($poll->canEdit())&&(($owner_guid==$user_guid)||(is_admin_logged_in()))) 
        $body .= "<a href=\"{$url_publish}\">{$word_publish}</a>";
   }
   $body .= "<br><br>";
   
   $previous_vote = poll_check_previous_vote($poll, $user_guid);
   if ($poll->change_vote) {
      $body .= elgg_view('forms/poll/vote', array('entity' => $poll));	   
   } else {
      if ($previous_vote) {
         $body .= "<p>" . elgg_echo('poll:voted') . "</p>";
      } else {
         $body .= elgg_view('forms/poll/vote', array('entity' => $poll));
      }
   }
   if (!$poll->selector) {
      if (($poll->change_vote)||($previous_vote)||($poll->canEdit())) { 
         $body .= "<div class=\"contentWrapper\">";
         $text_results = elgg_echo('poll:results');
         $body .= "<p align=\"center\"><a onclick=\"poll_show_results();\" style=\"cursor:hand;\">$text_results</a></p>";     
         $body .= "<div id=\"resultsDiv\" style=\"display:none;\">";
         $body .= elgg_view('poll/show_results',array('entity' => $poll)); 
         $body .= "</div>";
	 if (($poll->canEdit())&&(($owner_guid==$user_guid)||(($container instanceof ElggGroup)&&($operator))||(elgg_is_admin_logged_in()))) {  
	    $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'limit'=>0, 'order_by_metadata' => array('name'=>'index','direction' => 'asc', 'as' => 'integer'));
            $questions=elgg_get_entities_from_relationship($options);
            foreach ($questions as $one_question) {
               $index = $one_question->index;
               $name_div = $index;
	       $num_question = $index+1;
	       $text_question = elgg_echo('poll:question') . " " . $num_question;
	       $text_results = elgg_echo('poll:results') . " (".$text_question.")";
	       $body .= "<p align=\"center\"><a onclick=\"poll_show_results_selector($name_div);\" style=\"cursor:hand;\">$text_results</a></p>"; 
               $body .= "<div id=\"". $name_div . "\" style=\"display:none;\">";
               $body .= elgg_view('poll/show_results_by_user',array('entity' => $poll,'index'=>$index)); 
               $body .= "</div>";
            }
	 }
         $body .= "</div>";   
      }
   } else {
      $body .= "<div class=\"contentWrapper\">";
      $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'limit'=>0, 'order_by_metadata' => array('name'=>'index','direction' => 'asc', 'as' => 'integer'));
      $questions=elgg_get_entities_from_relationship($options);
      foreach ($questions as $one_question) {
         $index = $one_question->index;
         $name_div = $index;
	 $num_question = $index+1;
	 $text_question = elgg_echo('poll:question') . " " . $num_question;
	 $text_results = elgg_echo('poll:results') . " (".$text_question.")";
	 $body .= "<p align=\"center\"><a onclick=\"poll_show_results_selector($name_div);\" style=\"cursor:hand;\">$text_results</a></p>"; 
         $body .= "<div id=\"". $name_div . "\" style=\"display:none;\">";
         $body .= elgg_view('poll/show_results_selector',array('entity' => $poll,'index'=>$index)); 
         $body .= "</div>";

      }
      $body .= "</div>";
   }
   echo elgg_view('object/elements/full', array('summary' => $summary,'icon' => $owner_icon,'body' => $body));

} else {
   $params = array('entity' => $poll,'title' => $title, 'metadata' => $metadata,'subtitle' => $subtitle,'tags' => $tags);
   $params = $params + $vars;
   $list_body = elgg_view('object/elements/summary', $params);

   $body = "";   

   if ($num_responses==1){
      $body .= "{$num_responses} " . elgg_echo('poll:num_response');
   } else {
      $body .= "{$num_responses} " . elgg_echo('poll:num_responses');
   }
   
   $body .= "<br>";

   //Links to actions
   if ($created){		
      if (($vars['entity']->canEdit())&&(($owner_guid==$user_guid)||(($container instanceof ElggGroup)&&($operator))||(elgg_is_admin_logged_in()))) {	
         if ($opened){						       
	    $body .= "<a href=\"{$url_close}\">{$word_close}</a>" . " " . "<a href=\"{$url_relaunch}\">{$word_relaunch}</a>";
         } else {
      	    $body .= "<a href=\"{$url_open}\">{$word_open}</a>" . " " . "<a href=\"{$url_relaunch}\">{$word_relaunch}</a>";
         }
         $body .= " ";
      }
      if (elgg_is_admin_logged_in()){
         $body .= "<a href=\"{$url_stand_out}\">{$word_stand_out}</a>";
      }
   } else {
     if (($poll->canEdit())&&(($owner_guid==$user_guid)||(($container instanceof ElggGroup)&&($operator))||(elgg_is_admin_logged_in()))) 
        $body .= "<a href=\"{$url_publish}\">{$word_publish}</a>";
   }
   $body .= "<br><br>";
   
   $list_body .= $body;

   echo elgg_view_image_block($owner_icon, $list_body);
}

?>

<script type="text/javascript">
   function poll_show_results(){
      var resultsDiv = document.getElementById('resultsDiv');
      if (resultsDiv.style.display == 'none') {
         resultsDiv.style.display = 'block';
      } else {	
	 resultsDiv.style.display = 'none';
      }
   }	

   function poll_show_results_selector(name_div){
       var resultsDiv_selector = document.getElementById(name_div);
       if (resultsDiv_selector.style.display == 'none'){
          resultsDiv_selector.style.display = 'block';
       } else {       
          resultsDiv_selector.style.display = 'none';
       }
    }   

</script>
