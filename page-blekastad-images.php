<?php

/* Template Name: Blekastad - obrázky */

get_header();

?>

<?php require 'partials/blekastad-nav.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center" style="min-height: 75vh;">
        <div class="col">
            <?php if (user_has_role('administrator') || user_has_role('blekastad_editor')) : ?>
                <h1 class="mb-3">Obrazové přílohy</h1>
                <?php require 'partials/blekastad-images.php'; ?>
            <?php else : ?>
                <div class="alert alert-warning">
                    Pro zobrazení nemáte patřičná oprávnění.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer();
