<?php

$num = $vars['entity']->num_display;

$options = array('type' => 'object','subtype' => 'poll','container_guid' => $vars['entity']->owner_guid,'limit' => $num,'full_view' => FALSE,'pagination' => FALSE);

$content = elgg_list_entities($options);
echo $content;

if ($content) {
   $poll_url = "poll/owner/" . elgg_get_page_owner_entity()->username;
   $more_link = elgg_view('output/url', array('href' => $poll_url,'text' => elgg_echo('poll:morepolls'),'is_trusted' => true));
   echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
   echo elgg_echo('poll:none');
}
