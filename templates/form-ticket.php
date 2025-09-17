<?php
if (isset($_GET['ticket_submitted'])) {
    if ($_GET['ticket_submitted'] === 'success') {
        echo '<div class="sts-alert sts-alert-success">' . __('Ticket enviado com sucesso!', 'simple-ticket-system') . '</div>';
    } else {
        echo '<div class="sts-alert sts-alert-error">' . __('Ocorreu um erro ao enviar o ticket.', 'simple-ticket-system') . '</div>';
    }
}
?>

<form action="" method="post" class="sts-ticket-form" enctype="multipart/form-data">
    <?php wp_nonce_field('sts_ticket_form', 'sts_ticket_form_nonce'); ?>

    <div class="form-grupo">
        <label for="sts_ticket_title"><?php _e('Descreva resumidamente seu problema', 'simple-ticket-system'); ?></label>
        <input type="text" id="sts_ticket_title" name="sts_ticket_title" required>
    </div>

    <div class="form-grupo">
        <label for="sts_ticket_content"><?php _e('Forneça detalhes', 'simple-ticket-system'); ?></label>
        <textarea id="sts_ticket_content" name="sts_ticket_content" rows="8" required></textarea>
    </div>

    <div class="form-grupo">
        <label for="sts_ticket_type"><?php _e('Tipo de solicitação', 'simple-ticket-system'); ?></label>
        <select id="sts_ticket_type" name="sts_ticket_type" required>
            <?php
            $types = get_terms(array('taxonomy' => 'ticket_type', 'hide_empty' => false));
            foreach ($types as $type) {
                echo '<option value="' . esc_attr($type->term_id) . '">' . esc_html($type->name) . '</option>'; 
            }
            ?>
        </select>
    </div>

    <div class="form-grupo">
        <label for="sts_ticket_attachment"><?php _e('Anexo', 'simple-ticket-system'); ?></label>
        <div class="sts-file-input-wrapper">
            <span class="sts-file-input-button"><?php _e('Escolher arquivo', 'simple-ticket-system'); ?></span>
            <input type="file" id="sts_ticket_attachment" name="sts_ticket_attachment">
        </div>
        <span id="sts-file-name" class="sts-file-name"></span>
        <small><?php _e('(captura de tela, documento ou qualquer outro arquivo)', 'simple-ticket-system'); ?></small>
    </div>

    <div class="form-grupo">
        <input type="submit" name="sts_submit_ticket" value="<?php _e('Enviar Ticket', 'simple-ticket-system'); ?>">
    </div>
</form>