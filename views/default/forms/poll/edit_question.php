<div class="contentWrapper">
<?php	 

if (isset($vars['entity'])) {
   $user_guid = elgg_get_logged_in_user_guid();
   $user = get_entity($user_guid);
   $poll = $vars['entity'];
   $pollpost = $poll->getGUID();
   $action = "poll/edit_question";
   $created = $poll->created;
   $count_votes=$poll->countAnnotations('all_votes');
   $index = $vars['index'];
   
   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'metadata_name_value_pairs' => array('name' => 'index', 'value' => $index));
   $questions=elgg_get_entities_from_relationship($options);
   $one_question=$questions[0];
}

if (!elgg_is_sticky_form('edit_question_poll')) {
   $question = $one_question->question;
   $several_responses = $one_question->several_responses;
   $responses = $one_question->responses;
   $responses=explode(Chr(26),$responses);
   $responses = array_map('trim', $responses);
} else {
   $question = elgg_get_sticky_value('edit_question_poll','question');
   $responses = elgg_get_sticky_value('edit_question_poll','responses');
   if ((!$created)||($count_votes==0)) {
      $several_responses = elgg_get_sticky_value('edit_question_poll','several_responses');
   } else {
      $several_responses = $one_question->several_responses;
   }
} 
 
elgg_clear_sticky_form('edit_question_poll');	        	

if (($created)&&($count_votes>0))
   $disabled = "disabled";
else
   $disabled = "";
      
?>
<form action="<?php echo elgg_get_site_url()."action/".$action?>" name="edit_question_poll" enctype="multipart/form-data" method="post">

    <?php echo elgg_view('input/securitytoken'); ?>

      <p>
      <b><?php echo elgg_echo("poll:question"); ?></b><br>
      <?php
      echo elgg_view("input/longtext", array("name" => "question","value" => $question));
      ?>
      </p>
      <p>
      <b><?php echo elgg_echo("poll:responses_read"); ?></b>
      </p>
      <p>
      <?php
      if(count($responses) > 0) {
         $i=0; 
         foreach ($responses as $response) {
	    ?>
            <p class="clone">	
	    <?php
            echo elgg_view("input/text", array("name" => "responses[]","value" => $response));
	    if ($i>0){	
	       ?>
	       <!-- remove response -->
               <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false"><?php echo elgg_echo("delete"); ?></a>
	       <?php
	    }
	    ?>
            </p>
            <?php
            $i=$i+1;
         }
      } else {
         ?>
         <p class="clone">
         <?php
         echo elgg_view("input/text", array("name" => "responses[]","value" => $responses));
	 ?>
	 </p>
         <?php
      }
      ?>
      <!-- add link to add more responses which triggers a jquery clone function -->
      <a href="#" class="add" rel=".clone"><?php echo elgg_echo("poll:add_response"); ?></a>
      <br /><br />

      <p><?php
      if ($several_responses){
         $selected_several_responses = "checked = \"checked\"";
      } else {
         $selected_several_responses = "";
      }
      echo ("<b><input type=\"checkbox\" name=\"several_responses\" $disabled $selected_several_responses/>" . " " . elgg_echo('poll:several_responses') . "</b>"); 
      ?></p>

<!-- add the add/delete functionality  -->
<script type="text/javascript">
// remove function for the jquery clone plugin
$(function(){
   var removeLink = '<a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false"><?php echo elgg_echo("delete");?></a>';
   $('a.add').relCopy({ append: removeLink});
});
</script>

<?php
$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('poll:save')));
echo $submit_input;
?>

<input type="hidden" name="pollpost" value="<?php echo $pollpost; ?>">
<input type="hidden" name="index" value="<?php echo $index; ?>">

</form>

<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/poll/lib/jquery.MultiFile.js"></script><!-- multi file jquery plugin -->
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/poll/lib/reCopy.js"></script><!-- copy field jquery plugin -->
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/poll/lib/js_functions.js"></script>

</div>

