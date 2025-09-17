<?php
// Registrar Custom Post Type para tickets
function sts_register_ticket_post_type() {
    $labels = array(
        'name' => __('Tickets', 'simple-ticket-system'),
        'singular_name' => __('Ticket', 'simple-ticket-system'),
        'menu_name' => __('Tickets', 'simple-ticket-system'),
        'name_admin_bar' => __('Ticket', 'simple-ticket-system'),
        'add_new' => __('Novo Ticket', 'simple-ticket-system'),
        'add_new_item' => __('Adicionar Novo Ticket', 'simple-ticket-system'),
        'new_item' => __('Novo Ticket', 'simple-ticket-system'),
        'edit_item' => __('Editar Ticket', 'simple-ticket-system'),
        'view_item' => __('Ver Ticket', 'simple-ticket-system'),
        'all_items' => __('Todos os Tickets', 'simple-ticket-system'),
        'search_items' => __('Procurar Tickets', 'simple-ticket-system'),
        'not_found' => __('Nenhum ticket encontrado.', 'simple-ticket-system'),
        'not_found_in_trash' => __('Nenhum ticket na lixeira.', 'simple-ticket-system')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'ticket'),
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false, // Remover capacidade de criar diretamente
        ),
        'map_meta_cap' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author'),
        'menu_icon' => 'dashicons-tickets'
    );

    register_post_type('ticket', $args);

    // Registrar taxonomia para status
    $status_labels = array(
        'name' => __('Status', 'simple-ticket-system'),
        'singular_name' => __('Status', 'simple-ticket-system'),
        'search_items' => __('Procurar Status', 'simple-ticket-system'),
        'all_items' => __('Todos os Status', 'simple-ticket-system'),
        'edit_item' => __('Editar Status', 'simple-ticket-system'),
        'update_item' => __('Atualizar Status', 'simple-ticket-system'),
        'add_new_item' => __('Adicionar Novo Status', 'simple-ticket-system'),
        'new_item_name' => __('Novo Nome de Status', 'simple-ticket-system'),
        'menu_name' => __('Status', 'simple-ticket-system'),
    );

    $status_args = array(
        'hierarchical' => true,
        'labels' => $status_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'ticket-status'),
    );

    register_taxonomy('ticket_status', 'ticket', $status_args);

    // Registrar taxonomia para Tipo de Solicitação
    $type_labels = array(
        'name' => __('Tipos de Solicitação', 'simple-ticket-system'),
        'singular_name' => __('Tipo de Solicitação', 'simple-ticket-system'),
        'search_items' => __('Procurar Tipos', 'simple-ticket-system'),
        'all_items' => __('Todos os Tipos', 'simple-ticket-system'),
        'edit_item' => __('Editar Tipo', 'simple-ticket-system'),
        'update_item' => __('Atualizar Tipo', 'simple-ticket-system'),
        'add_new_item' => __('Adicionar Novo Tipo', 'simple-ticket-system'),
        'new_item_name' => __('Novo Nome de Tipo', 'simple-ticket-system'),
        'menu_name' => __('Tipos de Solicitação', 'simple-ticket-system'),
    );

    $type_args = array(
        'hierarchical' => true,
        'labels' => $type_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'ticket-type'),
    );

    register_taxonomy('ticket_type', 'ticket', $type_args);

    // Adicionar status padrão
    $default_statuses = array('Aberto', 'Em Andamento', 'Resolvido');
    
    foreach ($default_statuses as $status) {
        if (!term_exists($status, 'ticket_status')) {
            wp_insert_term($status, 'ticket_status');
        }
    }

    // Adicionar tipos padrão
    $default_types = array('Dúvida', 'Problema Técnico', 'Sugestão');
    foreach ($default_types as $type) {
        if (!term_exists($type, 'ticket_type')) {
            wp_insert_term($type, 'ticket_type');
        }
    }
}
add_action('init', 'sts_register_ticket_post_type');