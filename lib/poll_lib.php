<?php
		
function poll_check_previous_vote($poll, $user_guid){
   $votes = $poll->getAnnotations(array('annotation_name'=>'all_votes', 'limit'=>99999, 'offset'=>0,'reverse_order_by'=>true));
   if(count($votes) > 0) {
      foreach($votes as $vote){
         if($vote->owner_guid == $user_guid) return true;
      }
      return false;
   }
   return false;
}

function sort_polls_by_votes(&$polls_sorted_by_votes,$a,$b){
   $from = $a;
   $to = $b;
   $temp=$polls_sorted_by_votes[($from+$to)/2];
   $pivot=$temp->countAnnotations('all_votes');
   do {
      $temp=$polls_sorted_by_votes[$from];
      $votes=$temp->countAnnotations('all_votes');
      while($votes>$pivot){
         $from=$from+1;
	 $temp=$polls_sorted_by_votes[$from];
	 $votes=$temp->countAnnotations('all_votes');
      }
      $temp=$polls_sorted_by_votes[$to];
      $votes=$temp->countAnnotations('all_votes');
      while($votes<$pivot){
         $to=$to-1;
         $temp=$polls_sorted_by_votes[$to];
	 $votes=$temp->countAnnotations('all_votes');
      }
      if ($from<=$to){
         $poll_aux=$polls_sorted_by_votes[$from];
	 $polls_sorted_by_votes[$from]=$polls_sorted_by_votes[$to];
	 $polls_sorted_by_votes[$to]=$poll_aux;
	 $from=$from+1;
	 $to=$to-1;
      }
   }while($from<=$to);
   if ($a<$to)
      sort_polls_by_votes($polls_sorted_by_votes,$a,$to);
   if ($from<$b)
      sort_polls_by_votes($polls_sorted_by_votes,$from,$b);	
   return;
}

function poll_my_sort($original,$field,$descending = false){
   if (!$original) {
      return $original;
   }
   $sortArr = array();
   foreach ($original as $key => $item){
      $sortArr[$key] = $item->$field;
   }
   if ($descending){
      arsort($sortArr);
   } else {
      asort($sortArr);
   }
   $resultArr = array();
   foreach ($sortArr as $key => $value){
      $resultArr[$key] = $original[$key];
   }	   
   return $resultArr;
}  

?>