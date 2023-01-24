<?php
get_header();
?>

<main class="main">
    <div class="row">
        <div class="posts">
            <h1 class="posts_title"><?php the_archive_title(); ?></h1>
            <div class="posts__grid">
                <?php if (have_posts()) : while (have_posts()) : the_post() ?>
                    <?php get_template_part('template-parts/content'); ?>
                <?php endwhile; endif; ?>
                <?php
                the_posts_pagination(
                        array(
                                'prev_next' => true,
                                'prev_text' => 'Назад',
                                'next_text' => 'Вперед'
                        )
                );
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();
