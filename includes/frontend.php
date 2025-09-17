<?php
// Shortcode para formulário de abertura de ticket
function sts_ticket_form_shortcode() {
    ob_start();
    
    if (is_user_logged_in()) {
        include STS_PATH . 'templates/form-ticket.php';
    } else {
        echo '<p>' . __('Você precisa estar logado para abrir um ticket.', 'simple-ticket-system') . '</p>';
        echo wp_login_form(array('echo' => false));
    }
    
    return ob_get_clean();
}
add_shortcode('ticket_form', 'sts_ticket_form_shortcode');

// Shortcode para visualização de tickets do usuário
function sts_my_tickets_shortcode() {
    ob_start();
    
    if (is_user_logged_in()) {
        include STS_PATH . 'templates/view-ticket.php';
    } else {
        echo '<p>' . __('Você precisa estar logado para visualizar seus tickets.', 'simple-ticket-system') . '</p>';
        echo wp_login_form(array('echo' => false));
    }
    
    return ob_get_clean();
}
add_shortcode('my_tickets', 'sts_my_tickets_shortcode');

// Processar envio do formulário de ticket
function sts_process_ticket_form() {
    if (isset($_POST['sts_submit_ticket']) && wp_verify_nonce($_POST['sts_ticket_form_nonce'], 'sts_ticket_form')) {
        $current_user = wp_get_current_user();
        $title = sanitize_text_field($_POST['sts_ticket_title']);
        $content = wp_kses_post($_POST['sts_ticket_content']);
        $type_id = isset($_POST['sts_ticket_type']) ? (int) $_POST['sts_ticket_type'] : 0;
        
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'ticket',
            'post_author' => $current_user->ID
        );
        
        $post_id = wp_insert_post($post_data);
        
        if (!is_wp_error($post_id)) {
            // Gerar e salvar o ID personalizado
            $last_id = get_option('sts_last_ticket_id', 0);
            $new_id = $last_id + 1;
            $ticket_id_formatted = 'DTI-' . sprintf('%03d', $new_id);

            update_post_meta($post_id, '_sts_ticket_id', $ticket_id_formatted);
            update_option('sts_last_ticket_id', $new_id);

            // Definir status padrão
            wp_set_object_terms($post_id, 'Aberto', 'ticket_status');
            
            // Definir tipo de solicitação
            if ($type_id > 0) {
                wp_set_object_terms($post_id, $type_id, 'ticket_type');
            }

            // Lidar com o anexo
            if (!empty($_FILES['sts_ticket_attachment']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $attachment_id = media_handle_upload('sts_ticket_attachment', $post_id);
            }

            // Enviar notificação
            sts_notify_new_ticket($post_id);
            
            // Redireciona para a página do shortcode, não para o permalink do novo post.
            wp_redirect(add_query_arg('ticket_submitted', 'success', wp_get_referer()));
            exit;
        } else {
            wp_redirect(add_query_arg('ticket_submitted', 'error', wp_get_referer()));
            exit;
        }
    }
}
add_action('init', 'sts_process_ticket_form');

// Carregar o template para a página de um único ticket
function sts_single_ticket_template($single_template) {
    global $post;

    if ($post->post_type == 'ticket') {
        $single_template = STS_PATH . 'templates/single-ticket.php';
    }
    return $single_template;
}
add_filter('single_template', 'sts_single_ticket_template');