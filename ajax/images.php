<?php

add_action('wp_ajax_handle_img_uploads', function () {
    $f = $_FILES['files'];
    $valid = verify_upload_img($f);

    if ($valid !== true) {
        wp_send_json_error($valid, 400);
    }

    if (!array_key_exists('l_type', $_GET) || !array_key_exists('letter', $_GET)) {
        wp_send_json_error('Not found', 404);
    }

    $id = sanitize_text_field($_GET['letter']);
    $type = sanitize_text_field($_GET['l_type']);
    $pod = pods($type, $id);

    if (!$pod->exists()) {
        wp_send_json_error('Not found', 404);
    }

    if (!in_array($type, array_keys(get_types_by_letter()))) {
        wp_send_json_error('Not found', 404);
    }

    $upload_dir = wp_upload_dir();
    $new_file_dir = $upload_dir['basedir'] . '/' . $type . '/' . $id;
    $file_path = hiko_sanitize_file_name($f['name'][0]);

    $filename = $new_file_dir . '/' . $file_path;

    if (file_exists($upload_dir['basedir'] . '/' . $type . '/' . $id . '/' . basename($filename))) {
        wp_send_json_error('File already exists', 403);
    }

    $attachment = [
        'guid' => $upload_dir['url'] . '/' . $type . '/' . $id . '/' . basename($filename),
        'post_mime_type' => wp_check_filetype(basename($filename), null)['type'],
        'post_title' => sanitize_title(preg_replace('/\.[^.]+$/', '', basename($filename))),
        'post_content' => '',
        'post_status' => 'private'
    ];

    if (!is_dir($new_file_dir)) {
        $nf = mkdir($new_file_dir, 0777, true);
        if (!$nf) {
            wp_send_json_error(error_get_last()['message'], 501);
        }
    }

    if (!$file_path) {
        wp_send_json_error('error', 500);
    }

    $u = move_uploaded_file($f['tmp_name'][0], $filename);

    if (!$u) {
        wp_send_json_error(error_get_last()['message'], 501);
        return;
    }

    $insert = wp_insert_attachment($attachment, $filename, 0);

    if (is_wp_error($insert)) {
        wp_send_json_error('error', 500);
        return;
    }

    wp_update_attachment_metadata($insert, wp_generate_attachment_metadata($insert, $filename));
    $pod->add_to('images', $insert);
    $pod->save;
    wp_send_json_success();
});


add_action('wp_ajax_list_images', function () {
    if (!array_key_exists('l_type', $_GET) || !array_key_exists('letter', $_GET)) {
        wp_send_json_error('Not found', 404);
    }

    $id = sanitize_text_field($_GET['letter']);
    $type = sanitize_text_field($_GET['l_type']);

    $pod = pods($type, $id);

    if (!$pod->exists()) {
        wp_send_json_error('Not found', 404);
    }

    wp_send_json_success([
        'images' =>  get_sorted_images($pod->field('images')),
        'name' => $pod->field('name'),
        'url' => home_url('/' . get_types_by_letter()[$type]['handle'] .'/letters-add/?edit=' . $id),
    ]);
});


add_action('wp_ajax_delete_hiko_image', function () {
    $data = decode_php_input();

    $pod = pods(
        sanitize_text_field($data->l_type),
        sanitize_text_field($data->letter)
    );

    if (!$pod->exists()) {
        wp_send_json_error('Not found', 404);
    }

    $img_id = sanitize_text_field($data->img);

    $pod->remove_from('images', $img_id);
    $pod->save;
    $delete = wp_delete_attachment($img_id, true);

    if ($delete == 1) {
        wp_send_json_success();
    }

    wp_send_json_error('Error', 500);
});


add_action('wp_ajax_change_metadata', function () {
    $data = decode_php_input();

    if (!property_exists($data, 'img_id') || !property_exists($data, 'img_status') || !property_exists($data, 'img_description')) {
        wp_send_json_error('Not found', 404);
    }

    $update = wp_update_post([
        'ID' => sanitize_text_field($data->img_id),
        'post_content' => sanitize_text_field(html_entity_decode($data->img_description)),
        'post_status' => sanitize_text_field($data->img_status),
    ]);

    if (is_wp_error($update)) {
        wp_send_json_error(error_get_last()['message'], 500);
    }

    wp_send_json_success('saved');

});


add_action('wp_ajax_change_image_order', function () {
    $data = json_decode(key($_POST));

    if (!property_exists($data, 'img_id') || !property_exists($data, 'img_order')) {
        wp_send_json_error('Not found', 404);
    }

    $update = update_post_meta(
        sanitize_text_field($data->img_id),
        'order',
        sanitize_text_field($data->img_order)
    );

    if (is_wp_error($update)) {
        wp_send_json_error(error_get_last()['message'], 500);
        return;
    }

    wp_send_json_success('saved');
});



function get_sorted_images($unsorted_images)
{
    if (!$unsorted_images) {
        return [];
    }

    $images = [];

    foreach ($unsorted_images as $img) {
        if (!is_user_logged_in() && $img['post_status'] === 'private') {
            continue;
        }

        $images[] = [
            'id' => $img['ID'],
            'img' => [
                'large' => $img['guid'],
                'thumb' => wp_get_attachment_image_src($img['ID'], 'thumbnail')[0]
            ],
            'description' => get_post_field('post_content', $img['ID']),
            'order' => intval(get_post_meta($img['ID'], 'order', true)),
            'status' => $img['post_status']
        ];
    }

    usort($images, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $images;
}
