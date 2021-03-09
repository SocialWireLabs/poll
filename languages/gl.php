<?php

return array(
	
/**
* Menu items and titles
**/
	
'poll' => "Enquisa",
'polls' => "Enquisas",
'poll:newpoll' => "Nova enquisa",
'poll:your' => "As túas enquisas",
'poll:user' => "Enquisas de %s",
'poll:friends' => "Enquisas de contactos",
'poll:user:friends' => "Enquisas de contactos de %s",
'poll:all' => "Todas as enquisas",
'poll:posttitle' => "Enquisas de %s: %s",
'poll:read' => "Enquisa",
'poll:add' => "Crear enquisa",
'poll:addpost' => "Crear enquisa",
'poll:editpost' => "Editar enquisa",
'poll:votepost' => "Votar",
'poll:addquestionpost' => "Engadir unha pregunta",
'poll:editquestionpost' => "Editar pregunta",
'poll:text' => "Texto da enquisa",
'poll:strapline' => "%s",
'poll:enable_group_polls' => 'Habilitar enquisas de grupo',
'poll:group' => 'Enquisas de grupo',
'poll:nogroup' => 'Non hai enquisas neste grupo',
'poll:growup' => 'Enquisas de grupo',
'poll:date' => "%s ás %s",
'poll:by' => "por",
'poll:at' => "ás",
'item:object:poll' => 'Enquisas',
'poll:num_responses' => 'respostas',
'poll:num_response' => 'resposta',
'poll:num_comments' => 'comentarios',
'poll:questions' => "Preguntas",
'poll:question' => "Pregunta",
'poll:title' => "Título",
'poll:description' => "Descripción (opcional)",	
'poll:desc' => "Descripción",
'poll:responses' => "Opcións de Resposta",		
'poll:responses_read' => "Opcións de Resposta",
'poll:results' => "[+] Mostrar os resultados",
'poll:option' => "opción",
'poll:selector_label' => "Votantes visibles",
'poll:not_yet' => "%s aínda non creou enquisas.",
'poll:none' => "Aínda non se creou ningunha enquisa.",
'poll:several_responses' => 'Permítense varias respostas para esta pregunta',
'poll:activate_label' => 'Data de inicio de disponibilidade',
'poll:close_label' => 'Data de peche',
'poll:activate_now' => 'Agora',
'poll:not_close' => 'Ilimitado',
'poll:activate_date' => 'Escoller día e hora:',
'poll:close_date' => 'Escoller día e hora:',
'poll:opendate' => 'Día:',          
'poll:opentime' => 'Hora:',
'poll:closedate' => 'Día:',
'poll:closetime' => 'Hora:',
'poll:timeformat_calendar' => 'd/m/Y H:i',
'poll:timeformat_calendar_2' => '%d/%m/%Y %H:%M',
'poll:draft' => 'borrador',
'poll:save' => 'Gardar',
'poll:publish' => 'Publicar',
'poll:cancel' => 'Cancelar',
'poll:add_question' => 'Engadir pregunta',
'poll:add_response' => 'Engadir resposta',
'poll:add_responses_label' => 'Os votantes poden engadir opcións de resposta',
'poll:vote' => 'Votar',
'poll:not_questions' => 'Non hai preguntas.',
'poll:is_draft' => 'A enquisa non está publicada.',
'poll:close_before_editing' => 'Pechar',
'poll:votes' => 'Número de votos: ',
'poll:close_in_listing' => 'Pechar',
'poll:open_in_listing' => 'Abrir',
'poll:close_for_editing' => 'A enquisa está aberta. ¿Queres pechala para editar?',
'poll:stand_out' => 'Destacar',
'poll:no_stand_out' => 'Non destacar',
'poll:relaunch' => 'Copiar',
'poll:sort_menu:newest' => 'Recentes',
'poll:sort_menu:popular' => 'Populares',
'poll:up' => 'Anterior',
'poll:down' => 'Seguinte',
'poll:top' => 'Arriba',
'poll:bottom' => 'Abaixo',
'poll:edit_question' => 'Editar',
'poll:delete_question' => 'Borrar',
'poll:delete_question_confirm' => '¿Estás seguro de querer borrar esta pregunta?',
'poll:change_vote_label' => 'Permítese cambiar os votos',

/**
* Poll email
**/
'poll:notify:subject' => '%s creou unha nova enquisa: %s',
'poll:notify:body' =>
'%s creou unha nova enquisa: %s.
Para ver a enquisa fai click no seguinte enlace: %s',
'poll:notify:summary' => 'Nova enquisa: %s',

		
/**
* Poll widget
**/	
			
'poll:numbertodisplay' => "¿Cántas enquisas desexas mostrar?",
'poll:morepolls' => 'Máis enquisas',
'poll:widget:description' => 'Lista das túas enquisas',

			
/**
* Poll river
**/

'river:create:object:poll' => '%s creou unha enquisa titulada %s',
'river:update:object:poll' => '%s actualizou a enquisa %s',
'river:standout:object:poll' => '%s destacou a enquisa %s', 
'river:vote:object:poll' => '%s votou a enquisa %s',
'river:comment:object:poll' => "%s comentou a enquisa %s",
		
/**
* Status messages
**/
			
'poll:created' => "A enquisa foi creada con éxito.",
'poll:updated' => "A enquisa foi actualizada con éxito.",
'poll:deleted' => "A enquisa foi borrada con éxito.",
'poll:voted' => "O seu voto foi rexistrado. Gracias por votar.",
'poll:closed' => "A enquisa está pechada.",
'poll:opened'=> "A enquisa está aberta.",
'poll:closed_listing' => "A enquisa foi pechada con éxito.",
'poll:opened_listing' => "A enquisa foi aberta con éxito.",
'poll:emphasized'=> "A enquisa foi destacada con éxito.",	
'poll:relaunched'=> "A enquisa foi relanzada con éxito.",	
				
/**
* Error messages
**/
	
'poll:save:failure' => "Erro: a enquisa non puido ser salvada.",
'poll:title_blank' => "Erro: é necesario introducir o título.",
'poll:question_blank' => "Erro: é necesario introducir a pregunta e as respostas.",
'poll:responses_blank' => "Erro: campo resposta baleiro.",
'poll:response_repetition' => "Erro: as respostas deben ser diferentes.",
'poll:response_only_one_option' => "Erro: é necesario introducir polo menos dúas  respostas.",
'poll:blank_times' => "Erro: é necesario introducir as datas de activación e peche.",
'poll:bad_times' => "Erro: o formato das datas de activación e peche non é correcto.",
'poll:error_times' => "Erro: o momento de peche é menor que o de activación.",
'poll:structure' => "Erro: non podes cambiar a estructura da enquisa.",
'poll:null_vote' => "Erro: é necesario votar todas as preguntas.",
'poll:notfound' => "Erro: non se puido atopar a enquisa especificada.",
'polls:nonefound' => "Non hai enquisas de %s",
'poll:notdeleted' => "Erro: non se puido borrar a enquisa.",
'poll:error_container' => "Erro: non podes publicar aquí.",
'poll:error_save' => "Erro: non puidemos salvar a enquisa.",
'poll:question_error_save' => "Erro: no se puido salvar a pregunta.",
'poll:questionnotdeleted' => "Erro: no se puido borrar unha pregunta.",
	
);