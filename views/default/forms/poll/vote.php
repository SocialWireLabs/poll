<?php

$poll = $vars['entity'];
$pollpost = $poll->getGUID();
$action = "poll/vote";

$now=time();
if (poll_check_status($poll)){
   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question','limit'=>0);
   $questions=elgg_get_entities_from_relationship($options);

   if (empty($questions))
      $num_questions = 0;
   else
      $num_questions = count($questions);

   $desc = $poll->desc;

   $question_text = array();
   $response_inputs = array();
   $add_response_label = elgg_echo("poll:add_response");
   foreach($questions as $one_question){
      $index = $one_question->index;
      $question_text[$index] = $one_question->question;
      $response_inputs[$index] = "";
      $name_response="response".$index;
      $name_add_response="add_response_".$index;
      $name_div_add_response = "div_add_response_".$index;
      $responses = $one_question->responses;
      $responses = explode(Chr(26),$responses);
      $responses = array_map('trim', $responses);
      $several_responses = $one_question->several_responses;
      $j=1;
      foreach ($responses as $one_response){
         if ($several_responses){
	    $name_response_item = $name_response . "_" . $j;
	    if (elgg_is_sticky_form('vote_poll')) {
               $value_response = elgg_get_sticky_value('vote_poll',$name_response_item);
            }
	    if (strcmp($value_response,"on")==0) {
               $checked = "checked = \"checked\"";
	    } else {
	       $checked = "";
	    }
            $response_inputs[$index] .= "<input type=\"checkbox\" name=$name_response_item $checked>$one_response" . "<br>"; 
         } else {
	    if (elgg_is_sticky_form('vote_poll')) {
               $value_response = elgg_get_sticky_value('vote_poll',$name_response);
            }
	    if (strcmp($value_response,$one_response)==0){
	       $checked = "checked = \"checked\"";
	    } else {
	       $checked = "";
	    }
            $response_inputs[$index] .= "<input type=\"radio\" name=$name_response value='$one_response' $checked onChange=\"poll_add_response_radio('$name_div_add_response','b')\">$one_response" . "<br>";
         }
	 $j=$j+1;      
      }
      
      if ($poll->add_responses) {

         if ($several_responses){
            if (elgg_is_sticky_form('vote_poll')) {
               $value_response = elgg_get_sticky_value('vote_poll',$name_response);
               $value_add_response = elgg_get_sticky_value('vote_poll',$name_add_response);
            }
            if (strcmp($value_response,"on")==0) {
               $checked = "checked = \"checked\"";
	       $style_display = "display:block";
            } else {
	       $checked = "";
	       $style_display = "display:none";
            }
            $response_inputs[$index] .= "<input type=\"checkbox\" name=$name_response $checked onChange=\"poll_add_response_checkbox('$name_div_add_response')\">$add_response_label" . "<br>"; 
	    $response_inputs[$index] .= "<div id=\"$name_div_add_response\" style=\"" . $style_display . "\">";
	    $response_inputs[$index] .= elgg_view("input/text", array('name' => $name_add_response, 'value' => $value_add_response));
            $response_inputs[$index] .= "</div>";
         } else {
            if (elgg_is_sticky_form('vote_poll')) {
               $value_add_response = elgg_get_sticky_value('vote_poll',$name_add_response);
            }
            if (strcmp($value_response,"add_response")==0) {
               $checked = "checked = \"checked\"";
	       $style_display = "display:block";
            } else {
	       $checked = "";
	       $style_display = "display:none";
            }
            $response_inputs[$index] .= "<input type=\"radio\" name=$name_response value=\"add_response\" $checked onChange=\"poll_add_response_radio('$name_div_add_response','a')\">$add_response_label" . "<br>";
	    $response_inputs[$index] .= "<div id=\"$name_div_add_response\" style=\"" . $style_display . "\">";
	    $response_inputs[$index] .= elgg_view("input/text", array('name' => $name_add_response, 'value' => $value_add_response));
            $response_inputs[$index] .= "</div>";
         }
      }
   }
 
   elgg_clear_sticky_form('vote_poll');

   $submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('poll:vote')));
   $entity_hidden = elgg_view('input/hidden', array('name' => 'pollpost', 'value' => $pollpost));

   ?>
   <form action="<?php echo elgg_get_site_url()."action/".$action?>" name="vote_poll" enctype="multipart/form-data" method="post">

   <?php echo elgg_view('input/securitytoken'); 

   $form_body = "";

   if (strcmp($desc,"")!=0) {
      $form_body = "<p><b>" . elgg_echo("poll:desc") . "</b></p>";
      $form_body .= $desc;
   }

   if ($num_questions>0) {
      $form_body .= "<table class=\"poll_questions_vote_table\">";
      $index=0;
      while ($index < $num_questions){
         $form_body .= "<tr><td>";
    	 $form_body .= $question_text[$index] . $response_inputs[$index];
	 $form_body .= "</td></tr>"; 
         $index = $index+1;
      }
      $form_body .= "</table><br>";
      if ($poll->created){
         $form_body .= "<p>" . $submit_input . $entity_hidden . "</p>";
	 $form_body .= "</form>";
         echo elgg_echo($form_body);
      } else {
         $form_body .= "<p>" . elgg_echo('poll:is_draft') . "</p>";
         echo elgg_echo($form_body);
      }
   } else {
      $form_body .= "<p>" . elgg_echo('poll:not_questions') . "</p>";
      echo elgg_echo($form_body);
   }
} else {
   $form_body = "";
   $form_body .= "<p>" . elgg_echo('poll:closed') . "</p>";
   echo elgg_echo($form_body);
}

?>

<script type="text/javascript">

    function poll_add_response_checkbox(name_div){
       var resultsDiv_add_response = document.getElementById(name_div);
       if (resultsDiv_add_response.style.display == 'none'){
          resultsDiv_add_response.style.display = 'block';
       } else {       
          resultsDiv_add_response.style.display = 'none';
       }
    }   
    function poll_add_response_radio(name_div,option){
       var resultsDiv_add_response = document.getElementById(name_div);
       if (resultsDiv_add_response.style.display == 'none'){
          if (option == 'a'){
             resultsDiv_add_response.style.display = 'block';
	  }
       } else {
          if (option == 'b'){       
             resultsDiv_add_response.style.display = 'none';
	  }
       }
    }   

</script>  