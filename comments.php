<?php
/**
 * Template untuk menampilkan area komentar.
 * Menggunakan callback kustom untuk menata setiap komentar.
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area btn-custom">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf( esc_html__( 'Satu Komentar', 'mediman' ) );
            } else {
                // Teks "X Komentar"
                printf(
                    esc_html( '%1$s Komentar' ),
                    number_format_i18n( $comment_count )
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            // PERINTAH UTAMA: Gunakan fungsi 'mediman_custom_comment_template' untuk menampilkan setiap komentar
            wp_list_comments( [
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size'=> 60,
                'callback'   => 'mediman_custom_comment_template', // <-- INI KUNCINYA
            ] );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

        <?php if ( ! comments_open() ) : ?>
            <p class="no-comments"><?php esc_html_e( 'Komentar untuk artikel ini sudah ditutup.', 'mediman' ); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php comment_form(); // Memanggil form komentar yang sudah kita terjemahkan ?>

</div>