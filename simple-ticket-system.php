<?php
/**
 * Plugin Name: Simple Ticket System
 * Plugin URI: https://exemplo.com
 * Description: Um sistema simples de tickets para WordPress
 * Version: 1.0.0
 * Author: Seu Nome
 * License: GPL v2 or later
 */

// Evitar acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('STS_PATH', plugin_dir_path(__FILE__));
define('STS_URL', plugin_dir_url(__FILE__));
define('STS_VERSION', '1.0.0');

// Incluir arquivos necessários
require_once STS_PATH . 'includes/post-types.php';
require_once STS_PATH . 'includes/admin-ui.php';
require_once STS_PATH . 'includes/frontend.php';
require_once STS_PATH . 'includes/notifications.php';
require_once STS_PATH . 'includes/assets.php';

// Ativar o plugin
register_activation_hook(__FILE__, 'sts_activate_plugin');
function sts_activate_plugin() {
    // Criar tipos de post personalizados
    sts_register_ticket_post_type();
    
    // Recarregar regras de permalink
    flush_rewrite_rules();
}

// Desativar o plugin
register_deactivation_hook(__FILE__, 'sts_deactivate_plugin');
function sts_deactivate_plugin() {
    flush_rewrite_rules();
}

// Inicializar o plugin
add_action('plugins_loaded', 'sts_init_plugin');
function sts_init_plugin() {
    // Carregar traduções se necessário
    load_plugin_textdomain('simple-ticket-system', false, dirname(plugin_basename(__FILE__)) . '/languages');
}