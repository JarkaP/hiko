<?php

function list_places_simple()
{
    $type = test_input($_GET['type']);

    wp_die(json_encode(
        get_pods_name_and_id($type),
        JSON_UNESCAPED_UNICODE
    ));
}
add_action('wp_ajax_list_places_simple', 'list_places_simple');



function list_place_single()
{
    $results = [];

    if (!array_key_exists('pods_id', $_GET)) {
        wp_send_json_error('Not found', 404);
    }

    $id = test_input($_GET['pods_id']);
    $type = test_input($_GET['type']);

    $pod = pods($type, $id);

    if (!$pod->exists()) {
        wp_send_json_error('Not found', 404);
    }

    $results['country'] = $pod->field('country');
    $results['id'] = $pod->display('id');
    $results['latitude'] = $pod->display('latitude');
    $results['longitude'] = $pod->display('longitude');
    $results['name'] = $pod->field('name');
    $results['note'] = $pod->display('note');

    wp_die(json_encode(
        $results,
        JSON_UNESCAPED_UNICODE
    ));
}
add_action('wp_ajax_list_place_single', 'list_place_single');
