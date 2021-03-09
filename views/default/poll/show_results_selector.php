<div class="contentWrapper">

<?php

if (isset($vars['entity'])) {

   $poll = $vars['entity'];
   $index = $vars['index'];
   $pollpost = $poll->getGUID();
   $container_guid = $poll->container_guid;
   $container = get_entity($container_guid);
   $owner_guid = $poll->owner_guid;
   $owner = get_entity($owner_guid);

   $img_template = '<img border="0" width="16" height="16" alt="%s" title="%s" src="'.elgg_get_config('wwwroot').'mod/poll/graphics/%s"/>';

   $num_votes = $poll->countAnnotations('all_votes');

   $users_responses = $poll->getAnnotations(array('annotation_name'=>'vote', 'limit'=>99999, 'offset'=>0,'reverse_order_by'=>true));
   $options = array('relationship' => 'poll_question', 'relationship_guid' => $pollpost,'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'poll_question', 'limit'=>0, 'metadata_name_value_pairs' => array('name' => 'index','value' => $index));
   $questions=elgg_get_entities_from_relationship($options);
   $one_question=$questions[0];

   $responses=$one_question->responses;
   $responses=explode(Chr(26),$responses);
   $responses = array_map('trim', $responses);

   $count = array();
   $users = array();
   $choices = array();
   foreach ($users_responses as $choice){
      $user=get_entity($choice->owner_guid)->getGUID();
      if (!in_array($user, $users))
         $users[]=$user;
   }

   foreach ($users as $user){
      $j=1;
      foreach ($responses as $response){
	 $id_vote = $index. "." . $j;
	 $j=$j+1;
         $choices[$user][$id_vote]=' ';
      }
   }

   foreach ($users_responses as $choice){
      $user=get_entity($choice->owner_guid)->getGUID();
      $j=1;
      foreach ($responses as $response){
	 $id_vote = $index. "." . $j;
	 $j=$j+1;
	 if ($choice->value==$id_vote) {
            $choices[$user][$id_vote]='OK';
	    break;
	 }
      }
   }

   echo $one_question->question;

   $tabla='<center><table class="tabla" cellspacing="0"><tbody>';

   $tabla.='<TR class="principal" >';

   $tabla.='<TH>'.'</TH>';

   $j=1;
   foreach ($responses as $response){
      $id_vote = $index. "." . $j;
      $count[$id_vote]=0;

      $text=elgg_echo("poll:option"). " " .$j;
      $text_response = sprintf($img_template,$text,$response,"check.gif") . " " . substr($response, 0, 20) . "...";
      $tabla.='<TH>'.$text_response.'</TH>';
      $j=$j+1;
   }
   $tabla.='</TR>';

   foreach ($users as $user){
      $icon = elgg_view_entity_icon(get_entity($user),'tiny');
      $user_icon = elgg_view_image_block($icon,get_entity($user)->name);
      $tabla.='<TR>'.'<TH>'.$user_icon.'</TH>';
      foreach ($choices[$user] as $key=>$value){
         if (($value)=='OK'){
            $count[$key]++;
            $tabla.='<TD class="green">'.$value.'</TD>';
         } else {
            $tabla.='<TD class=red>'.$value.'</TD>';
         }
      }
   }
   $tabla.='</TR>';
   $tabla.='<TR class="principal" >'.'<TH>'.elgg_echo('poll:votes').'</TH>';

   foreach ($count as $key){
      $tabla.='<TD>'.$key.'</TD>';
   }

   $tabla.='</tbody></TABLE>';
   echo $tabla;

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
