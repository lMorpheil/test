<?php
    /*
     * Шаблон поста
     */
?>

<article class="post">
    <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail('post_thumb'); ?>
    </a>
    <div class="post__info">
        <h2 class="post__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <p class="post__description">
            <?php echo get_the_excerpt() ?>
        </p>
        <div class="post__row">
            <p class="post__author"><span>Автор:</span> <?php the_author(); ?></p>
            <div class="like" data-js="like-wrapper">
                <?php
                $id = get_the_ID();
                
                $likes = $wpdb->get_var( "SELECT sum(likes) - sum(dislikes) FROM wp_post_likes WHERE likes = true AND post_id = $id");
                ?>
                <button class="like__increment" data-btn="like"></button>
                <div class="like__counter" data-js="counter" data-id="<?php the_ID(); ?>"><?php echo $likes ? $likes : 0 ?></div>
                <button class="like__decrement" data-btn="dislike"></button>
            </div>
        </div>
    </div>
</article>
