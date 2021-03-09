<?php

$filter = $vars['filter'];
$url = elgg_get_site_url() . 'poll/friends/';

?>
<a href="<?php echo $url; ?>&filter=newest"><?php echo elgg_echo('poll:sort_menu:newest'); ?></a>
<?php
echo " ";
?>
<a href="<?php echo $url; ?>&filter=pop"><?php echo elgg_echo('poll:sort_menu:popular'); ?></a>
