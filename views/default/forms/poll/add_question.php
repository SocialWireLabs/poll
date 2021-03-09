<div class="contentWrapper">

<?php
	 
if (isset($vars['entity'])) {
   $action = "poll/add_question";

   if (!elgg_is_sticky_form('add_question_poll')) {
      $question = "";
      $several_responses = false;
      $responses = array();
   } else {
      $question = elgg_get_sticky_value('add_question_poll','question');
      $several_responses =  elgg_get_sticky_value('add_question_poll','several_response');
      $responses =  elgg_get_sticky_value('add_question_poll','responses');
   }

   elgg_clear_sticky_form('add_question_poll');
	        	
   ?>
   <form action="<?php echo elgg_get_site_url()."action/".$action?>" name="add_question_poll" enctype="multipart/form-data" method="post">

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
	          ?>								                  <!-- remove response -->
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
	 </p>
         
    	 <p><?php
	 if ($several_responses){
	      $selected_several_responses = "checked = \"checked\"";
	 } else {
	      $selected_several_responses = "";
	 }
	 echo ("<b><input type=\"checkbox\" name=\"several_responses\" $selected_several_responses/>" . " " . elgg_echo('poll:several_responses') . "</b>"); 
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

<input type="hidden" name="pollpost" value="<?php echo $vars['entity']->getGUID(); ?>">

</form>

<?php
}

?>

<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/poll/lib/jquery.MultiFile.js"></script><!-- multi file jquery plugin -->
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/poll/lib/reCopy.js"></script><!-- copy field jquery plugin -->
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/poll/lib/js_functions.js"></script>

</div>
