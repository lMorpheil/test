<?php
    if (! is_active_sidebar('sidebar')) {
        return false;
    }
?>

<aside>
    <?php dynamic_sidebar('sidebar'); ?>
</aside>
