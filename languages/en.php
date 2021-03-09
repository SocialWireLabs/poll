<?php

return array(
	
/**
* Menu items and titles
**/
	
'poll' => "Poll",
'polls' => "Polls",
'poll:newpoll' => "New poll",
'poll:your' => "Your polls",
'poll:user' => "%s's polls",
'poll:friends' => "Friends' polls",
'poll:user:friends' => "%s's friends' polls",
'poll:all' => "All site polls",
'poll:posttitle' => "%s's poll: %s",
'poll:read' => "Poll",
'poll:add' => "Create poll",
'poll:addpost' => "Create poll",
'poll:editpost' => "Edit poll",
'poll:votepost' => "Vote",
'poll:addquestionpost' => "Add a question",
'poll:editquestionpost' => "Edit question",
'poll:text' => "Poll Text",
'poll:strapline' => "%s",
'poll:enable_group_polls' => 'Enable group polls',
'poll:group' => 'Group polls',
'poll:nogroup' => 'There are no polls in this group',
'poll:growup' => 'Group polls',
'poll:date' => "%s at %s",
'poll:by' => "by",
'poll:at' => "at", 
'item:object:poll' => 'Polls',
'poll:num_responses' => 'responses',
'poll:num_response' => 'response',
'poll:num_comments' => 'comments',
'poll:questions' => "Questions",
'poll:question' => "Question",
'poll:title' => "Title",
'poll:description' => "Description (optional)",	
'poll:desc' => "Description",
'poll:responses' => "Responses",
'poll:responses_read' => "Responses",
'poll:results' => "[+] Show the results",
'poll:option' => "option",
'poll:selector_label' => "Visible voters",
'poll:not_yet' => "%s hasn't created a poll yet.",
'poll:none' => "No polls have been created yet.",
'poll:several_responses' => 'Several responses for this question',
'poll:activate_label' => 'Activation time',
'poll:close_label' => 'Closing time',
'poll:activate_now' => 'Now',
'poll:not_close' => 'Unlimited',
'poll:activate_date' => 'Choose day and hour:',
'poll:close_date' => 'Choose day and hour:',
'poll:opendate' => 'Day:',          
'poll:opentime' => 'Hour:',
'poll:closedate' => 'Day:',
'poll:closetime' => 'Hour:',
'poll:timeformat_calendar' => 'm/d/Y H:i',
'poll:timeformat_calendar_2' => '%m/%d/%Y %H:%M',
'poll:draft' => 'draft',
'poll:save' => 'Save',
'poll:publish' => 'Publish',
'poll:cancel' => 'Cancel',
'poll:add_question' => 'Add question',
'poll:add_response' => 'Add response',
'poll:add_responses_label' => 'Options of response can be added by voters',
'poll:vote' => 'Vote',
'poll:not_questions' => 'There are not questions.',
'poll:is_draft' => 'The poll is not published.', 
'poll:close_before_editing' => 'Close',
'poll:votes' => 'Number of votes: ',
'poll:stand_out' => 'Stand out',
'poll:no_stand_out' => 'No stand out',
'poll:close_in_listing' => 'Close',
'poll:open_in_listing' => 'Open',
'poll:close_for_editing' => 'The poll is opened. Do you want to close it for editing?',
'poll:relaunch' => 'Copy',
'poll:sort_menu:newest' => 'Newest',
'poll:sort_menu:popular' => 'Popular',
'poll:up' => 'Up',
'poll:down' => 'Down',
'poll:top' => 'Top',
'poll:bottom' => 'Bottom',
'poll:edit_question' => 'Edit',
'poll:delete_question' => 'Delete',
'poll:delete_question_confirm' => 'Are you sure you want to delete this question?',
'poll:change_vote_label' => 'Votes can be changed',

/**
* Poll email
**/
'poll:notify:subject' => '%s has created a new poll: %s',
'poll:notify:body' =>
'%s has created a new poll: %s.
In order to see the poll click the following link: %s',
'poll:notify:summary' => 'New poll: %s',
			 
/**
* Poll widget
**/	

'poll:numbertodisplay' => "How many polls you want to display?",
'poll:morepolls' => 'More polls',
'poll:widget:description' => 'List of your polls',
         	
/**
* Poll river
**/
'river:create:object:poll' => '%s created a poll titled %s',
'river:update:object:poll' => '%s updated the poll %s',
'river:standout:object:poll' => '%s emphasized the poll %s', 
'river:vote:object:poll' => '%s voted the poll %s',
'river:comment:object:poll' => "%s commented the poll %s",
	        
/**
* Status messages
**/
	
'poll:created' => "The poll was successfully created.",
'poll:updated' => "The poll was successfully updated.",
'poll:deleted' => "The poll was successfully deleted.",
'poll:voted' => "Your vote has been recorded. Thank you for voting.",
'poll:closed' => "The poll is closed.",
'poll:opened'=> "The poll is opened.",
'poll:closed_listing' => "The poll was successfully closed.",
'poll:opened_listing' => "The poll was successfully opened.",
'poll:emphasized'=> "The poll was successfully emphasized.",	
'poll:relaunched'=> "The poll was successfully relaunched.",	
		
/**
* Error messages
**/
	
'poll:save:failure' => "Error: the poll could not be saved.",
'poll:title_blank' => "Error: you need to fill the title.",
'poll:question_blank' => "Error: you need to fill the question.",
'poll:responses_blank' => "Error: the response field is empty.",
'poll:response_repetition' => "Error: responses must be different.",
'poll:response_only_one_option' => "Error: the number of responses must be greater than one.",
'poll:blank_times' => "Error: you need to fill the activation and closing dates.",
'poll:bad_times' => "Error: bad activation or closing dates format.",
'poll:error_times' => "Error: closing time less than activation time.",
'poll:structure' => "Error: you are not allowed to change the poll structure.",	
'poll:null_vote' => "Error: you need to vote all questions.",
'poll:notfound' => "Error: the specified poll could not be found.",
'polls:nonefound' => "No polls were found from %s",
'poll:notdeleted' => "Error: the poll could not be deleted.",
'poll:error_container' => "Error: you are not allowed to write in the container.",
'poll:error_save' => "Error: the poll could not be saved.",
'poll:question_error_save' => "Error: the question could not be saved.",
'poll:questionnotdeleted' => "Error: a question could not be deleted.",
);