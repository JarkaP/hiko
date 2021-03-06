<?php

/* Template Name: Obrázky */

get_header();

$pods_types = get_hiko_post_types_by_url();
$editor = $pods_types['editor'];
output_current_type_script($pods_types);
require_once get_template_directory() . '/partials/custom-nav.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center" style="min-height: 75vh;">
        <div class="col">
            <?php if (has_user_permission($editor)) : ?>
                <h1 class="mb-3">Obrazové přílohy</h1>
                <?php require_once get_template_directory() . '/partials/images.php'; ?>
            <?php else : ?>
                <div class="alert alert-warning mw-400">
                    Pro zobrazení nemáte patřičná oprávnění.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer();
