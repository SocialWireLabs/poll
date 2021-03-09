<?php

return array(
	
/**
* Menu items and titles
**/
	
'poll' => "Encuesta",
'polls' => "Encuestas",
'poll:newpoll' => "Nueva encuesta",
'poll:your' => "Tus encuestas",
'poll:user' => "Encuestas de %s",
'poll:friends' => "Encuestas de contactos",
'poll:user:friends' => "Encuestas de contactos de %s",
'poll:all' => "Todas las encuestas",
'poll:posttitle' => "Encuestas de %s: %s",
'poll:read' => "Encuesta",
'poll:add' => "Crear encuesta",
'poll:addpost' => "Crear encuesta",
'poll:editpost' => "Editar encuesta",
'poll:votepost' => "Votar",
'poll:addquestionpost' => "Añadir una pregunta",
'poll:editquestionpost' => "Editar pregunta",
'poll:text' => "Texto de la encuesta",
'poll:strapline' => "%s",
'poll:enable_group_polls' => 'Habilitar encuestas de grupo',
'poll:group' => 'Encuestas de grupo',
'poll:nogroup' => 'No hay encuestas en este grupo',
'poll:growup' => 'Encuestas de grupo',
'poll:date' => "%s a las %s",
'poll:by' => "por",
'poll:at' => "a las", 
'item:object:poll' => 'Encuestas',
'poll:num_responses' => 'respuestas',
'poll:num_response' => 'respuesta',
'poll:num_comments' => 'comentarios',
'poll:questions' => "Preguntas",
'poll:question' => "Pregunta",
'poll:title' => "Título",
'poll:description' => "Descripción (opcional)",
'poll:desc' => "Descripción",	
'poll:responses' => "Opciones de respuesta",
'poll:responses_read' => "Opciones de respuesta",
'poll:results' => "[+] Mostrar los resultados",
'poll:option' => "opción",
'poll:selector_label' => "Mostrar las elecciones de cada votante. Si no se marca, la votación es anónima y se muestran sólo los votos totales por opción",
'poll:not_yet' => "%s no ha creado encuestas todavía.",
'poll:none' => "No se han creado encuestas todavía.",
'poll:several_responses' => 'Se permite votar por varias opciones en esta pregunta',
'poll:activate_label' => 'Fecha de inicio de disponibilidad',
'poll:close_label' => 'Fecha de cierre',
'poll:activate_now' => 'Ahora',
'poll:not_close' => 'Ilimitado',
'poll:activate_date' => 'Escoger día y hora:',
'poll:close_date' => 'Escoger día y hora:',
'poll:opendate' => 'Día:',          
'poll:opentime' => 'Hora:',
'poll:closedate' => 'Día:',
'poll:closetime' => 'Hora:',
'poll:timeformat_calendar' => 'd/m/Y H:i',
'poll:timeformat_calendar_2' => '%d/%m/%Y %H:%M',
'poll:draft' => 'borrador',
'poll:save' => 'Guardar',
'poll:publish' => 'Publicar',
'poll:cancel' => 'Cancelar',
'poll:add_question' => 'Añadir pregunta',
'poll:add_response' => 'Añadir respuesta',
'poll:add_responses_label' => 'Los votantes pueden añadir nuevas opciones de respuesta',
'poll:vote' => 'Votar',
'poll:not_questions' => 'No hay preguntas.',
'poll:is_draft' => 'La encuesta no está publicada.',
'poll:close_before_editing' => 'Cerrar',
'poll:votes' => 'Número de votos: ',
'poll:close_in_listing' => 'Cerrar',
'poll:open_in_listing' => 'Abrir',
'poll:close_for_editing' => 'La encuesta está abierta. ¿Quieres cerrarla para editar?',
'poll:stand_out' => 'Destacar',
'poll:no_stand_out' => 'No destacar',
'poll:relaunch' => 'Copiar',
'poll:sort_menu:newest' => 'Recientes',
'poll:sort_menu:popular' => 'Populares',
'poll:up' => 'Anterior',
'poll:down' => 'Siguiente',
'poll:top' => 'Arriba',
'poll:bottom' => 'Abajo',
'poll:edit_question' => 'Editar',
'poll:delete_question' => 'Borrar',
'poll:delete_question_confirm' => '¿Estás seguro de querer borrar esta pregunta?',
'poll:change_vote_label' => 'Se permiten cambiar las elecciones mientras esté abierta la encuesta ',

/**
* Poll email
**/
'poll:notify:subject' => '%s ha creado una nueva encuesta: %s',
'poll:notify:body' =>
'%s ha creado una nueva encuesta: %s.
Para ver la encuesta haz click en el siguiente enlace: %s',
'poll:notify:summary' => 'Nueva encuesta: %s',
		
/**
* Poll widget
**/	
			
'poll:numbertodisplay' => "¿Cuántas encuestas deseas mostrar?",
'poll:morepolls' => 'Más encuestas',
'poll:widget:description' => 'Lista de tus encuestas',
			
/**
* Poll river
**/

'river:create:object:poll' => '%s creó una encuesta titulada %s',
'river:update:object:poll' => '%s actualizó la encuesta %s',
'river:standout:object:poll' => '%s destacó la encuesta %s', 
'river:vote:object:poll' => '%s votó la encuesta %s',
'river:comment:object:poll' => "%s comentó la encuesta %s",
		
/**
* Status messages
**/
			
'poll:created' => "La encuesta fue creada con éxito.",
'poll:updated' => "La encuesta fue actualizada con éxito.",
'poll:deleted' => "La encuesta fue borrada con éxito.",
'poll:voted' => "Su voto fue registrado. Gracias por votar.",
'poll:closed_listing' => "La encuesta fue cerrada con éxito.",
'poll:opened_listing' => "La encuesta fue abierta con éxito.",
'poll:closed' => "La encuesta está cerrada.",
'poll:opened'=> "La encuesta está abierta. Debes cerrarla para editarla",
'poll:emphasized'=> "La encuesta fue destacada con éxito.",	
'poll:relaunched'=> "La encuesta fue relanzada con éxito.",	
				
/**
* Error messages
**/
	
'poll:save:failure' => "Error: la encuesta no pudo ser salvada.",
'poll:title_blank' => "Error: es necesario introducir el título.",
'poll:question_blank' => "Error: es necesario introducir la pregunta.",
'poll:responses_blank' => "Error: campo respuesta vacío.",
'poll:response_repetition' => "Error: las respuestas deben ser diferentes.",
'poll:response_only_one_option' => "Error: es necesario introducir al menos dos respuestas.",
'poll:blank_times' => "Error: es necesario introducir las fechas de activación y cierre.",
'poll:bad_times' => "Error: el formato de las fechas de activación o cierre no es correcto.",
'poll:error_times' => "Error: el momento de cierre es anterior que el de activación.",
'poll:structure' => "Error: no se puede cambiar la estructura del cuestionario.", 
'poll:null_vote' => "Error: es necesario votar todas las preguntas.",
'poll:notfound' => "Error: no se pudo encontrar la encuesta especificada.",
'polls:nonefound' => "No hay encuestas de %s",
'poll:notdeleted' => "Error: no se pudo borrar la encuesta.",
'poll:error_container' => "Error: no puedes publicar aquí.",
'poll:error_save' => "Error: no se pudo salvar la encuesta.",
'poll:question_error_save' => "Error: no se pudo salvar la pregunta.",
'poll:questionnotdeleted' => "Error: no se pudo borrar una pregunta.",
);