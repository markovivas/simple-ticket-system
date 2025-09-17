<?php

// Evitar acesso direto
if (!defined('ABSPATH')) {
    exit;
}

function sts_enqueue_frontend_assets() {
    // Enfileira o CSS do frontend
    wp_enqueue_style('sts-frontend-style', STS_URL . 'assets/css/frontend.css', array(), STS_VERSION);

    // Enfileira o JS do frontend
    wp_enqueue_script('sts-frontend-script', STS_URL . 'assets/js/frontend.js', array(), STS_VERSION, true);
}
add_action('wp_enqueue_scripts', 'sts_enqueue_frontend_assets');

function sts_enqueue_admin_assets($hook) {
    $screen = get_current_screen();

    // Carrega o CSS apenas nas páginas de listagem e edição do post type 'ticket'
    if (isset($screen->post_type) && $screen->post_type == 'ticket') {
        wp_enqueue_style('sts-admin-style', STS_URL . 'assets/css/admin.css', array(), STS_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'sts_enqueue_admin_assets');