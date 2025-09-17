<?php
// Notificar sobre novo ticket
function sts_notify_new_ticket($post_id) {
    $post = get_post($post_id);
    $author = get_userdata($post->post_author);
    $admin_email = get_option('admin_email');
    
    $subject = __('Novo Ticket Criado', 'simple-ticket-system') . ': ' . $post->post_title;
    
    $message = __("Um novo ticket foi criado no sistema.\n\n", 'simple-ticket-system');
    $message .= __('Título: ', 'simple-ticket-system') . $post->post_title . "\n";
    $message .= __('Autor: ', 'simple-ticket-system') . $author->display_name . "\n";
    $message .= __('Email: ', 'simple-ticket-system') . $author->user_email . "\n";
    $message .= __('Conteúdo: ', 'simple-ticket-system') . "\n" . $post->post_content . "\n\n";
    $message .= __('Para visualizar e responder ao ticket, acesse: ', 'simple-ticket-system') . admin_url('post.php?post=' . $post_id . '&action=edit') . "\n";
    
    wp_mail($admin_email, $subject, $message);
}

// Notificar sobre resposta ao ticket
function sts_notify_ticket_response($post_id, $comment_id) {
    $post = get_post($post_id);
    $comment = get_comment($comment_id);
    $author = get_userdata($post->post_author);
    
    // Notificar o autor do ticket
    $subject = __('Nova Resposta no Seu Ticket', 'simple-ticket-system') . ': ' . $post->post_title;
    
    $message = __("Seu ticket recebeu uma nova resposta.\n\n", 'simple-ticket-system');
    $message .= __('Ticket: ', 'simple-ticket-system') . $post->post_title . "\n";
    $message .= __('Resposta de: ', 'simple-ticket-system') . $comment->comment_author . "\n";
    $message .= __('Resposta: ', 'simple-ticket-system') . "\n" . $comment->comment_content . "\n\n";
    $message .= __('Para visualizar o ticket completo, acesse: ', 'simple-ticket-system') . get_permalink($post_id) . "\n";
    
    wp_mail($author->user_email, $subject, $message);
    
    // Se a resposta não foi do admin, notificar o admin
    if (!user_can($comment->user_id, 'manage_options')) {
        $admin_email = get_option('admin_email');
        $subject = __('Nova Resposta no Ticket', 'simple-ticket-system') . ': ' . $post->post_title;
        
        $message = __("Um ticket recebeu uma nova resposta.\n\n", 'simple-ticket-system');
        $message .= __('Ticket: ', 'simple-ticket-system') . $post->post_title . "\n";
        $message .= __('Autor do Ticket: ', 'simple-ticket-system') . $author->display_name . "\n";
        $message .= __('Resposta de: ', 'simple-ticket-system') . $comment->comment_author . "\n";
        $message .= __('Resposta: ', 'simple-ticket-system') . "\n" . $comment->comment_content . "\n\n";
        $message .= __('Para visualizar e responder ao ticket, acesse: ', 'simple-ticket-system') . admin_url('post.php?post=' . $post_id . '&action=edit') . "\n";
        
        wp_mail($admin_email, $subject, $message);
    }
}