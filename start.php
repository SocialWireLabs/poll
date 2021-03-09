<?php

function poll_init() {

   $item = new ElggMenuItem('poll', elgg_echo('polls'), 'poll/all');
   elgg_register_menu_item('site', $item);
  
   // Extend system CSS with our own styles, which are defined in the poll/css view
   elgg_extend_view('css/elgg','poll/css');
								
   // Register a page handler, so we can have nice URLs
   elgg_register_page_handler('poll','poll_page_handler');
				
   // Register entity type
   elgg_register_entity_type('object','poll');

   // Register a URL handler for poll posts
   elgg_register_plugin_hook_handler('entity:url', 'object', 'poll_url');

   // Show polls in groups
   add_group_tool_option('poll',elgg_echo('poll:enable_group_polls'),false);
   elgg_extend_view('groups/tool_latest', 'poll/group_module');

   // Add a menu item to the user ownerblock
   elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'poll_owner_block_menu');

// Advanced permissions
   elgg_register_plugin_hook_handler('permissions_check', 'object', 'poll_permissions_check');

   // Register granular notification for this type
   elgg_register_notification_event('object', 'poll', array('create'));
   elgg_register_plugin_hook_handler('prepare','notification:publish:object:poll', 'poll_prepare_notification');

   // Register library
   elgg_register_library('poll', elgg_get_plugins_path() . 'poll/lib/poll_lib.php');

   // Add a widget
   elgg_register_widget_type('poll', elgg_echo('polls'), elgg_echo('poll:widget:description'));

}

function poll_permissions_check($hook, $type, $return, $params) {
   if (($params['entity']->getSubtype() == 'poll')||($params['entity']->getSubtype() == 'poll_question')) {
      $user_guid = elgg_get_logged_in_user_guid();
      $group_guid = $params['entity']->container_guid;
      $group = get_entity($group_guid);
      if ($group instanceof ElggGroup) {
         $group_owner_guid = $group->owner_guid;
         $operator=false;
         if (($group_owner_guid==$user_guid)||(check_entity_relationship($user_guid,'group_admin',$group_guid))){
            $operator=true;
         }
         if ($operator){
            return true;
	 }   
      }
   }	
}

/**
 * Add a menu item to the user ownerblock
*/
function poll_owner_block_menu($hook, $type, $return, $params) {
   if (elgg_instanceof($params['entity'], 'user')) {
      $url = "poll/owner/{$params['entity']->username}";
      $item = new ElggMenuItem('poll', elgg_echo('polls'), $url);
      $return[] = $item;
   } else {
      if ($params['entity']->poll_enable != "no") {
         $url = "poll/group/{$params['entity']->guid}/all";
         $item = new ElggMenuItem('poll', elgg_echo('poll:group'), $url);
         $return[] = $item;
      }
   }
   return $return;
}

/**
 * Prepare a notification message about a new poll
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function poll_prepare_notification($hook, $type, $return, $params) {
   $entity = $params['entity']->getObject();
   $owner = $params['event']->getActor();
   $recipient = $params['recipient'];
   $language = $params['language'];
   $method = $params['method'];

   // Title for the notification
   $notification->subject = elgg_echo('poll:notify:subject', array($owner->name, $entity->title), $language);

   // Message body for the notification
   $notification->body = elgg_echo('poll:notify:body', array(
      $owner->name,
      $entity->title,
      $entity->getURL()
   ), $language);

   // The summary text is used e.g. by the site_notifications plugin
   $notification->summary = elgg_echo('poll:notify:summary', array($entity->title), $language);

   return $notification;
}

/**
* Poll page handler; allows the use of fancy URLs
*
* @param array $page From the page_handler function
* @return true|false Depending on success
*/
function poll_page_handler($page) {
   if (isset($page[0])) {
      elgg_push_breadcrumb(elgg_echo('polls'));
      $base_dir = elgg_get_plugins_path() . 'poll/pages/poll';
      switch ($page[0]) {
         case "view":
            set_input('pollpost', $page[1]);
            include "$base_dir/read.php";
            break;
         case "owner":
            set_input('username', $page[1]);
            include "$base_dir/index.php";
            break;
         case "group":
            set_input('container_guid', $page[1]);
            include "$base_dir/index.php";
            break;
         case "friends":
            include "$base_dir/friends.php";
            break;
         case "all":
            include "$base_dir/everyone.php";
            break;
         case "add":
            set_input('container_guid', $page[1]);
            include "$base_dir/add.php";
            break;
         case "edit":
            set_input('pollpost', $page[1]);
            include "$base_dir/edit.php";
            break;
	 case "add_question":
            set_input('pollpost', $page[1]);
            include "$base_dir/add_question.php";
            break;
	 case "edit_question":
            set_input('pollpost', $page[1]);
	    set_input('index',$page[2]);
            include "$base_dir/edit_question.php";
            break;
         default:
            return false;
      }
   } else {
      forward();
   }
   return true;
}

/**
 * Returns the URL from a poll entity
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    The current URL
 * @param array  $params Hook parameters
 * @return string
 */
function poll_url($hook, $type, $url, $params) {
   $poll = $params['entity'];
   // Check that the entity is a poll object
   if ($poll->getSubtype() !== 'poll') {
        // This is not a poll object, so there's no need to go further
        return;
   }
   $title = elgg_get_friendly_title($poll->title);
   return $url . "poll/view/" . $poll->getGUID() . "/" . $title;
}

// Poll opened or closed?
function poll_check_status($poll) {
   if (strcmp($poll->option_close_value,'poll_close_date')==0){
      $now=time();
      if (($now>=$poll->activate_time)&&($now<$poll->close_time)){
         return true;
      } else {
         if ($poll->action == true){
            $poll->option_close_value ='';
            $poll->action = false;
            $poll->opened = true;
            return true;
         }
         return false;
      }
   } else {
      $poll->action = false;
      return $poll->opened;
   } 
}


// Make sure the poll initialisation function is called on initialisation
elgg_register_event_handler('init','system','poll_init');
		
// Register actions
$action_base = elgg_get_plugins_path() . 'poll/actions/poll';
elgg_register_action("poll/add","$action_base/add.php");
elgg_register_action("poll/edit","$action_base/edit.php");
elgg_register_action("poll/delete","$action_base/delete.php");
elgg_register_action("poll/open","$action_base/open.php");
elgg_register_action("poll/close","$action_base/close.php");
elgg_register_action("poll/publish","$action_base/publish.php");
elgg_register_action("poll/relaunch","$action_base/relaunch.php");
elgg_register_action("poll/standout","$action_base/standout.php");
elgg_register_action("poll/add_question","$action_base/add_question.php");
elgg_register_action("poll/edit_question","$action_base/edit_question.php");
elgg_register_action("poll/move_question","$action_base/move_question.php");
elgg_register_action("poll/delete_question","$action_base/delete_question.php");
elgg_register_action("poll/vote","$action_base/vote.php");
?>