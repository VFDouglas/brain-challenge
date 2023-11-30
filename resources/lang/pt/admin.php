<?php

return [
    'general'       => [
        'select_choose' => 'Escolha',
    ],
    'events'        => [
        'page_title'                    => 'Eventos',
        'no_event_found'                => 'Nenhum evento encontrado',
        'cannot_delete_parent_user'     => 'O evento está associado a outra instância do sistema.
            Remova a instância primeiro.',
        'edit_event_modal_title'        => 'Editar Evento',
        'error_get_event'               => 'Erro ao buscar evento',
        'error_delete_event'            => 'Erro ao excluir o evento',
        'delete_event_tooltip'          => 'Clique para excluir o evento',
        'error_get_event_title'         => 'Evento',
        'error_save_event'              => 'Erro ao salvar o evento',
        'event_name_text'               => 'Nome',
        'event_location_text'           => 'Location',
        'event_starts_at_text'          => 'Inicia Em',
        'event_ends_at_text'            => 'Termina Em',
        'event_status_text'             => 'Status',
        'event_close_modal_button_text' => 'Fechar',
        'event_save_modal_button_text'  => 'Salvar',
        'create_event_button_text'      => 'Criar Evento',
    ],
    'users'         => [
        'page_title'                   => 'Usuários',
        'no_user_found'                => 'Nenhum usuário encontrado',
        'edit_user_modal_title'        => 'Editar Usuário',
        'create_user_modal_title'      => 'Criar Usuário',
        'error_get_user'               => 'Erro ao buscar usuário',
        'error_get_user_title'         => 'Usuários',
        'error_get_user_description'   => 'Nenhum usuário encontrado',
        'error_save_user'              => 'Erro ao salvar o usuário',
        'user_name_text'               => 'Nome',
        'user_email_text'              => 'Email',
        'user_role_text'               => 'Cargo',
        'user_role_A'                  => 'Administrador',
        'user_role_P'                  => 'Professor',
        'user_role_S'                  => 'Estudante',
        'user_status_text'             => 'Status',
        'user_close_modal_button_text' => 'Fechar',
        'user_save_modal_button_text'  => 'Salvar',
        'create_user_button_text'      => 'Criar Usuário',
        'user_already_exists'          => 'Já existe um usuário com este email',
        'user_not_found'               => 'Usuário não encontrado',
        'cannot_delete_parent_user'    => 'O usuário está associado a um evento ou outra entidade do sistema.
            Para excluí-lo, remova todos os relacionamentos antes.',
        'duplicate_entry'              => 'Um usuário já existe com este email',
    ],
    'presentations' => [
        'page_title'                           => 'Apresentações',
        'create_presentation_modal_title'      => 'Criar Apresentação',
        'no_presentation_found'                => 'Nenhuma apresentação encontrada',
        'event_not_found'                      => 'Evento não encontrado',
        'edit_presentation_modal_title'        => 'Editar Apresentação',
        'error_get_presentation'               => 'Erro ao buscar apresentação',
        'error_get_presentation_title'         => 'Apresentação',
        'error_get_presentation_description'   => 'Nenhuma apresentação encontrada',
        'error_save_presentation'              => 'Erro ao salvar a apresentação',
        'presentation_name_text'               => 'Nome',
        'presentation_username_text'           => 'Usuário',
        'presentation_starts_at_text'          => 'Inicia Em',
        'presentation_ends_at_text'            => 'Termina Em',
        'presentation_status_text'             => 'Status',
        'presentation_description_text'        => 'Descrição',
        'presentation_close_modal_button_text' => 'Fechar',
        'presentation_save_modal_button_text'  => 'Salvar',
        'create_presentation_button_text'      => 'Criar Apresentação',
        'presentation_user_text'               => 'Usuários',
        'presentation_event_text'              => 'Evento',
        'presentation_professor_text'          => 'Professor',
        'no_user_create_presentations'         => 'Nenhum evento ou professor encontrado. Crie um para poder criar uma
            apresentação.',
        'presentation_already_exists'          => 'Uma apresentação já existe para este professor.',
    ],
    'schedules'     => [
        'page_title'                       => 'Programação',
        'no_schedule_found'                => 'Nenhuma programação encontrada',
        'create_schedule'                  => 'Criar Programação',
        'edit_schedule'                    => 'Editar Programação',
        'error_get_schedule'               => 'Erro ao buscar programação',
        'error_get_schedule_title'         => 'Programação',
        'error_get_schedule_description'   => 'Nenhuma programação encontrada',
        'error_save_schedule'              => 'Erro ao salvar a programação',
        'schedule_title_text'              => 'Título',
        'schedule_description_text'        => 'Descrição',
        'schedule_username_text'           => 'Usuário',
        'schedule_starts_at_text'          => 'Inicia Em',
        'schedule_ends_at_text'            => 'Termina Em',
        'schedule_status_text'             => 'Status',
        'schedule_close_modal_button_text' => 'Fechar',
        'schedule_save_modal_button_text'  => 'Salvar',
        'create_schedule_button_text'      => 'Criar Programação',
        'schedule_event_text'              => 'Evento',
        'no_user_create_schedules'         => 'Nenhum evento encontrado. Crie um para poder criar uma programação.',
        'schedule_already_exists'          => 'Uma programação com esse título já existe.'
    ],
    'awards'        => [
        'page_title'                    => 'Premios',
        'no_award_found'                => 'Nenhum premio encontrado',
        'create_award_modal_title'      => 'Criar Premio',
        'edit_award'                    => 'Editar Premio',
        'error_get_award'               => 'Erro ao buscar premio',
        'error_get_award_title'         => 'Premio',
        'error_get_award_description'   => 'Nenhum premio encontrado',
        'error_save_award'              => 'Erro ao salvar o premio',
        'award_presentation_text'       => 'Apresentação',
        'award_description_text'        => 'Descrição',
        'award_username_text'           => 'Estudante',
        'award_close_modal_button_text' => 'Fechar',
        'award_save_modal_button_text'  => 'Salvar',
        'create_award_button_text'      => 'Criar Premio',
        'award_event_text'              => 'Evento',
        'no_user_create_awards'         => 'Nenhum evento encontrado. Crie um para poder criar um premio.',
        'award_already_exists'          => 'Um premio já existe para este estudante nessa apresentação.'
    ],
    'pages'         => [
        'page_title'                    => 'Página',
        'page_name_text'                => 'Nome',
        'page_url_text'                 => 'URL',
        'page_status_text'              => 'Status',
        'create_page_tooltip'           => 'Como o programador tem que configurar cada página, não faz sentido haver um
            botão para criar ou excluir uma.',
        'edit_page_tooltip'             => 'Associar estudantes',
        'modal_associate_student_title' => 'Associar Estudante',
        'error_get_page_title'          => 'Página',
        'error_get_page'                => 'Erro ao buscar página',
        'edit_page_user_name'           => 'Usuário',
        'edit_page_save_button'         => 'Salvar',
        'edit_page_cancel_button'       => 'Cancelar'
    ],
    'notifications' => [
        'page_title'                           => 'Notificações',
        'no_notification_found'                => 'Nenhuma notificação encontrada',
        'create_notification_modal_title'      => 'Criar Notificação',
        'edit_notification_modal_title'        => 'Editar Notificação',
        'error_get_notification'               => 'Erro ao buscar notificação',
        'error_get_notification_title'         => 'Notificação',
        'error_get_notification_description'   => 'Nenhuma notificação encontrada',
        'error_save_notification'              => 'Erro ao salvar a notificação',
        'notification_title_text'              => 'Título',
        'notification_description_text'        => 'Descrição',
        'notification_status_text'             => 'Status',
        'notification_close_modal_button_text' => 'Fechar',
        'notification_save_modal_button_text'  => 'Salvar',
        'create_notification_button_text'      => 'Criar Notificação',
        'notification_already_exists'          => 'Uma notificação com esse título já existe.'
    ];
