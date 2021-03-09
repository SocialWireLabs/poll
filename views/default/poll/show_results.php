<div class="contentWrapper">

<?php

if (isset($vars['entity'])) {

   $poll = $vars['entity'];
   $pollpost = $poll->getGUID();
   $container_guid = $poll->container_guid;
   $container = get_entity($container_guid);
   $owner_guid = $poll->owner_guid;
   $owner = get_entity($owner_guid);

   // Set img src
   $img_src = elgg_get_site_url() . "mod/poll/graphics/poll.gif";

   $users_responses = $poll->getAnnotations(array('annotation_name'=>'vote', 'limit'=>9999, 'offset'=>0, 'reverse_order_by'=>true));
   $num_votes = $poll->countAnnotations('all_votes');
	
   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'limit'=>0, 'order_by_metadata' => array('name'=>'index','direction' => 'asc', 'as' => 'integer'));
   $questions=elgg_get_entities_from_relationship($options);
  
   if (empty($questions))
      $num_questions = 0;
   else
      $num_questions = count($questions);

   if ($num_questions > 0){
      echo "<table class=\"poll_questions_vote_table\">";
      foreach ($questions as $one_question) {
         $index = $one_question->index;
         echo "<tr><td>";
         echo $one_question->question;
         //echo "</td></tr>";
         $responses=$one_question->responses;
         $responses=explode(Chr(26),$responses);
         $responses = array_map('trim', $responses);
         //echo "<tr><td>";
         $j=1;
         foreach($responses as $one_response) {
            $id_vote=$index . "." . $j;
	    $j=$j+1;
	    $response_count = 0;
	    if (is_array($users_responses)) {
               foreach ($users_responses as $item) {
                  if ($item->value == $id_vote){
                     $response_count += 1;
                  }	
               }
	    }
            if($response_count==0) $response_percentage=0;
            else $response_percentage = round(100 / ($num_votes / $response_count));
	    ?>
            <div id="progress_indicator" >
	       <?php echo $one_response . " (" . $response_count . ")"; ?><br>
	       <div id="progressBarContainer" align="left">	
	          <img src="<?php echo $img_src; ?>" width="<?php echo $response_percentage; ?>%" height="14px">
	       </div>	
	    </div>
	    <?php
         } 
         echo "</td></tr>"; 
      }
      echo "</table>";
   }
   ?>	
   <p><?php
   if ($num_questions>0){
      echo "<br>"; 
      echo elgg_echo('poll:votes') . $num_votes; 
   }
   ?></p>
   <?php		
} else {
   register_error(elgg_echo("poll:notfound"));
   if ($container instanceof ElggGroup) {
      forward(elgg_get_site_url() . "poll/group/" . $container->getGUID());
   } else {
      forward(elgg_get_site_url() . "poll/owner/" . $owner->username);
   }
}	
?>
</div>
