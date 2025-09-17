<?php
get_header(); ?>

<div id="primary" class="sts-content-area">
    <main id="main" class="sts-site-main">

        <?php
        while (have_posts()) : the_post();
            $status = wp_get_object_terms(get_the_ID(), 'ticket_status', array('fields' => 'names'));
            $types = wp_get_object_terms(get_the_ID(), 'ticket_type', array('fields' => 'names'));
            $attachments = get_attached_media('', get_the_ID());
            $ticket_id = get_post_meta(get_the_ID(), '_sts_ticket_id', true);

            $current_status = !empty($status) ? esc_html($status[0]) : __('Sem status', 'simple-ticket-system');
            $status_class = !empty($status) ? sanitize_title($status[0]) : 'no-status';
            $current_type = !empty($types) ? esc_html($types[0]) : __('Não definido', 'simple-ticket-system');
        ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('sts-single-ticket'); ?>>
                <header class="sts-ticket-header">
                    <div class="sts-title-wrapper">
                        <h1 class="sts-ticket-title"><?php the_title(); ?></h1>
                        <span class="sts-single-ticket-id"><?php echo esc_html($ticket_id); ?></span>
                    </div>
                    <div class="sts-ticket-meta">
                        <span class="sts-ticket-status <?php echo $status_class; ?>"><?php echo $current_status; ?></span>
                        <span class="sts-ticket-info"><strong><?php _e('Tipo:', 'simple-ticket-system'); ?></strong> <?php echo $current_type; ?></span>
                        <span class="sts-ticket-info"><strong><?php _e('Criado em:', 'simple-ticket-system'); ?></strong> <?php echo get_the_date('d/m/Y H:i'); ?></span>
                    </div>
                </header>

                <div class="sts-ticket-content">
                    <h3><?php _e('Descrição do Problema', 'simple-ticket-system'); ?></h3>
                    <?php the_content(); ?>
                </div>

                <?php if (!empty($attachments)) : ?>
                    <div class="sts-ticket-attachments">
                        <h3><?php _e('Anexos', 'simple-ticket-system'); ?></h3>
                        <ul>
                            <?php foreach ($attachments as $attachment) : ?>
                                <li><a href="<?php echo wp_get_attachment_url($attachment->ID); ?>" target="_blank"><?php echo esc_html($attachment->post_title); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="sts-ticket-responses">
                    <?php
                    // Se os comentários estiverem abertos ou se houver pelo menos um comentário, carregue o template de comentários.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>

            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php
get_footer();
?>