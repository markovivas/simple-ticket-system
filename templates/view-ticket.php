<?php
$current_user = wp_get_current_user();
$args = array(
    'post_type' => 'ticket',
    'author' => $current_user->ID,
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);

$tickets = get_posts($args);

if ($tickets) {
    echo '<h3 class="sts-section-title">' . __('Meus Tickets', 'simple-ticket-system') . '</h3>';
    echo '<div class="sts-ticket-grid">';
    
    foreach ($tickets as $ticket) {
        $status = wp_get_object_terms($ticket->ID, 'ticket_status', array('fields' => 'names'));
        $types = wp_get_object_terms($ticket->ID, 'ticket_type', array('fields' => 'names'));
        $ticket_id = get_post_meta($ticket->ID, '_sts_ticket_id', true);

        $status_class = !empty($status) ? sanitize_title($status[0]) : 'no-status';
        $current_status = !empty($status) ? esc_html($status[0]) : __('Sem status', 'simple-ticket-system');
        $current_type = !empty($types) ? esc_html($types[0]) : __('Não definido', 'simple-ticket-system');
        
        echo '<a href="' . get_permalink($ticket->ID) . '" class="sts-ticket-card ' . $status_class . '">';
        echo '<span class="sts-card-ticket-id">' . esc_html($ticket_id) . '</span>';
        echo '<div class="sts-card-header">';
        echo '<h4 class="sts-card-title">' . esc_html($ticket->post_title) . '</h4>';
        echo '<span class="sts-ticket-status ' . $status_class . '">' . $current_status . '</span>';
        echo '</div>';
        
        echo '<div class="sts-card-body">';
        echo '<p><strong>' . __('Tipo:', 'simple-ticket-system') . '</strong> ' . $current_type . '</p>';
        echo '</div>';

        echo '<div class="sts-card-footer">';
        echo '<span class="sts-ticket-date">' . sprintf(__('Aberto em: %s', 'simple-ticket-system'), date_i18n('d/m/Y', strtotime($ticket->post_date))) . '</span>';
        echo '</div>';

        echo '</a>';
    }
    
    echo '</div>';
} else {
    echo '<p>' . __('Você não possui tickets.', 'simple-ticket-system') . '</p>';
}