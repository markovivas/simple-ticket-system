<?php
// Adicionar meta boxes para tickets
function sts_add_ticket_meta_boxes() {
    add_meta_box(
        'sts_ticket_details',
        __('Detalhes do Ticket', 'simple-ticket-system'),
        'sts_ticket_details_callback',
        'ticket',
        'side',
        'high'
    );

    add_meta_box(
        'sts_ticket_responses',
        __('Respostas', 'simple-ticket-system'),
        'sts_ticket_responses_callback',
        'ticket',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sts_add_ticket_meta_boxes');

// Callback para detalhes do ticket
function sts_ticket_details_callback($post) {
    wp_nonce_field('sts_save_ticket_data', 'sts_ticket_nonce');
    
    $status = wp_get_object_terms($post->ID, 'ticket_status', array('fields' => 'names'));
    $current_status = !empty($status) ? $status[0] : 'Aberto';

    $type = wp_get_object_terms($post->ID, 'ticket_type', array('fields' => 'names'));
    $current_type = !empty($type) ? $type[0] : __('Nenhum', 'simple-ticket-system');
    
    $author_id = $post->post_author;
    $author = get_userdata($author_id);
    $attachments = get_attached_media('', $post->ID);
    $ticket_id = get_post_meta($post->ID, '_sts_ticket_id', true);

    echo '<p><strong>' . __('ID do Ticket:', 'simple-ticket-system') . '</strong> ' . esc_html($ticket_id) . '</p>';
    
    echo '<p><strong>' . __('Criado por:', 'simple-ticket-system') . '</strong> ' . $author->display_name . '</p>';
    echo '<p><strong>' . __('Email:', 'simple-ticket-system') . '</strong> ' . $author->user_email . '</p>';
    echo '<p><strong>' . __('Data:', 'simple-ticket-system') . '</strong> ' . get_the_date('d/m/Y H:i', $post->ID) . '</p>';
    
    echo '<label for="ticket_status"><strong>' . __('Status:', 'simple-ticket-system') . '</strong></label>';
    echo '<select name="ticket_status" id="ticket_status" style="width:100%">';
    
    $statuses = get_terms(array(
        'taxonomy' => 'ticket_status',
        'hide_empty' => false
    ));
    
    foreach ($statuses as $status) {
        echo '<option value="' . $status->name . '" ' . selected($current_status, $status->name, false) . '>' . $status->name . '</option>';
    }
    
    echo '</select>';

    echo '<p style="margin-top:10px;"><strong>' . __('Tipo de Solicitação:', 'simple-ticket-system') . '</strong> ' . esc_html($current_type) . '</p>';

    if (!empty($attachments)) {
        echo '<p style="margin-top:10px;"><strong>' . __('Anexos:', 'simple-ticket-system') . '</strong></p>';
        echo '<ul>';
        foreach ($attachments as $attachment) {
            echo '<li><a href="' . wp_get_attachment_url($attachment->ID) . '" target="_blank">' . esc_html($attachment->post_title) . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="margin-top:10px;"><strong>' . __('Anexos:', 'simple-ticket-system') . '</strong> ' . __('Nenhum anexo.', 'simple-ticket-system') . '</p>';
    }

}

// Callback para respostas do ticket
function sts_ticket_responses_callback($post) {
    $responses = get_comments(array(
        'post_id' => $post->ID,
        'order' => 'ASC'
    ));
    
    echo '<div class="sts-responses">';
    
    if ($responses) {
        foreach ($responses as $response) {
            echo '<div class="sts-response">';
            echo '<p><strong>' . $response->comment_author . '</strong> <em>' . date('d/m/Y H:i', strtotime($response->comment_date)) . '</em></p>';
            echo '<div>' . wpautop($response->comment_content) . '</div>';
            echo '</div><hr>';
        }
    } else {
        echo '<p>' . __('Nenhuma resposta ainda.', 'simple-ticket-system') . '</p>';
    }
    
    echo '</div>';
    
    // Formulário para nova resposta
    echo '<h3>' . __('Adicionar Resposta', 'simple-ticket-system') . '</h3>';
    echo '<textarea name="sts_response_content" style="width:100%; height:100px;"></textarea>';
    echo '<p class="description">' . __('Digite sua resposta acima e atualize o ticket.', 'simple-ticket-system') . '</p>';
}

// Salvar dados do ticket
function sts_save_ticket_data($post_id) {
    if (!isset($_POST['sts_ticket_nonce']) || !wp_verify_nonce($_POST['sts_ticket_nonce'], 'sts_save_ticket_data')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Salvar status
    if (isset($_POST['ticket_status'])) {
        $status_name = sanitize_text_field($_POST['ticket_status']);
        $term = get_term_by('name', $status_name, 'ticket_status');
        if ($term && !is_wp_error($term)) {
            wp_set_object_terms($post_id, $term->term_id, 'ticket_status');
        } else {
            wp_set_object_terms($post_id, $status_name, 'ticket_status');
        }
    }
    
    // Salvar resposta se houver
    if (!empty($_POST['sts_response_content'])) {
        $user = wp_get_current_user();
        
        $comment_data = array(
            'comment_post_ID' => $post_id,
            'comment_author' => $user->display_name,
            'comment_author_email' => $user->user_email,
            'comment_content' => wp_kses_post($_POST['sts_response_content']),
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user->ID,
            'comment_approved' => 1,
        );
        
        $comment_id = wp_insert_comment($comment_data);
        
        if ($comment_id) {
            // Enviar notificação por email
            sts_notify_ticket_response($post_id, $comment_id);
        }
    }
}
add_action('save_post_ticket', 'sts_save_ticket_data', 10, 1);

// Personalizar colunas na lista de tickets
function sts_custom_ticket_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'ticket_id' => __('ID', 'simple-ticket-system'),
        'title' => $columns['title'],
        'author' => __('Autor', 'simple-ticket-system'),
        'ticket_type' => __('Tipo', 'simple-ticket-system'),
        'status' => __('Status', 'simple-ticket-system'),
        'date' => $columns['date']
    );
    
    return $new_columns;
}
add_filter('manage_ticket_posts_columns', 'sts_custom_ticket_columns');

function sts_custom_ticket_column_data($column, $post_id) {
    switch ($column) {
        case 'status':
            $status = wp_get_object_terms($post_id, 'ticket_status', array('fields' => 'names'));
            if (!empty($status)) {
                $status_class = sanitize_title($status[0]);
                echo '<span class="sts-admin-status status-' . esc_attr($status_class) . '">' . esc_html($status[0]) . '</span>';
            } else {
                echo __('Nenhum status definido', 'simple-ticket-system');
            }
            break;
        case 'ticket_id':
            $ticket_id = get_post_meta($post_id, '_sts_ticket_id', true);
            echo $ticket_id ? '<strong>' . esc_html($ticket_id) . '</strong>' : '';
            break;
    }
}
add_action('manage_ticket_posts_custom_column', 'sts_custom_ticket_column_data', 10, 2);